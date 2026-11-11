<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiUserBooksTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_only_see_their_own_books(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Book::factory()->create(['user_id' => $userA->id, 'title' => 'Book Of A']);
        Book::factory()->create(['user_id' => $userB->id, 'title' => 'Book Of B']);

        $this->actingAs($userA)->get('/books')->assertSee('Book Of A')->assertDontSee('Book Of B');
        $this->actingAs($userB)->get('/books')->assertSee('Book Of B')->assertDontSee('Book Of A');
    }

    public function test_user_gets_404_on_another_users_book(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $userA->id]);

        $this->actingAs($userB)->get("/books/{$book->id}")->assertNotFound();
        $this->actingAs($userB)->get("/books/{$book->id}/edit")->assertNotFound();
        $this->actingAs($userB)->put("/books/{$book->id}", [
            'title' => 'Hijacked',
            'language' => 'English',
            'status' => 'to_read',
            'ownership' => 'owned',
        ])->assertNotFound();
        $this->actingAs($userB)->delete("/books/{$book->id}")->assertNotFound();

        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => $book->title]);
    }

    public function test_owner_can_still_view_and_update_own_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get("/books/{$book->id}")->assertOk();
        $this->actingAs($user)->put("/books/{$book->id}", [
            'title' => 'Updated Title',
            'language' => 'English',
            'status' => 'reading',
            'ownership' => 'owned',
            'condition' => 'new',
        ])->assertRedirect(route('books.index'));

        $this->assertSame('Updated Title', $book->fresh()->title);
    }

    public function test_new_books_are_assigned_to_current_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/books', [
            'title' => 'My New Book',
            'language' => 'Indonesian',
            'status' => 'to_read',
            'ownership' => 'owned',
            'condition' => 'new',
        ])->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', ['title' => 'My New Book', 'user_id' => $user->id]);
    }

    public function test_csv_export_contains_only_own_books(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Book::factory()->create(['user_id' => $userA->id, 'title' => 'Export Title A']);
        Book::factory()->create(['user_id' => $userB->id, 'title' => 'Export Title B']);

        $response = $this->actingAs($userA)->get('/books/export');

        $response->assertOk();
        $content = $response->streamedContent();
        $this->assertStringContainsString('Export Title A', $content);
        $this->assertStringNotContainsString('Export Title B', $content);
    }

    public function test_guests_are_redirected_from_books_and_dashboard(): void
    {
        $this->get('/books')->assertRedirect(route('login'));
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/books/export')->assertRedirect(route('login'));
    }

    public function test_dashboard_renders_for_admin_with_books(): void
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        Book::factory()->count(3)->create(['user_id' => $admin->id]);

        $this->actingAs($admin)->get('/dashboard')->assertOk();
    }
}
