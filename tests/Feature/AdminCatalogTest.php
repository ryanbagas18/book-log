<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\LibraryBook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCatalogTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        return $admin;
    }

    public function test_non_admin_gets_403_on_all_admin_catalog_routes(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();
        $book = LibraryBook::factory()->create();

        $this->actingAs($user)->get('/admin/genres')->assertForbidden();
        $this->actingAs($user)->get('/admin/genres/create')->assertForbidden();
        $this->actingAs($user)->post('/admin/genres', ['name' => 'X'])->assertForbidden();
        $this->actingAs($user)->get("/admin/genres/{$genre->id}/edit")->assertForbidden();
        $this->actingAs($user)->put("/admin/genres/{$genre->id}", ['name' => 'Y'])->assertForbidden();
        $this->actingAs($user)->delete("/admin/genres/{$genre->id}")->assertForbidden();

        $this->actingAs($user)->get('/admin/library-books')->assertForbidden();
        $this->actingAs($user)->get('/admin/library-books/create')->assertForbidden();
        $this->actingAs($user)->get("/admin/library-books/{$book->id}/edit")->assertForbidden();
        $this->actingAs($user)->delete("/admin/library-books/{$book->id}")->assertForbidden();
    }

    public function test_admin_can_crud_genres(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/genres', ['name' => 'Mystery'])
            ->assertRedirect(route('admin.genres.index'));
        $genre = Genre::where('name', 'Mystery')->firstOrFail();
        $this->assertSame('mystery', $genre->slug);

        $this->actingAs($admin)->put("/admin/genres/{$genre->id}", ['name' => 'Thriller'])
            ->assertRedirect(route('admin.genres.index'));
        $this->assertSame('Thriller', $genre->fresh()->name);

        $this->actingAs($admin)->delete("/admin/genres/{$genre->id}")
            ->assertRedirect(route('admin.genres.index'));
        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);
    }

    public function test_genre_with_books_cannot_be_deleted(): void
    {
        $admin = $this->admin();
        $genre = Genre::factory()->create();
        LibraryBook::factory()->create(['genre_id' => $genre->id]);

        $response = $this->actingAs($admin)->delete("/admin/genres/{$genre->id}");

        $response->assertRedirect(route('admin.genres.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('genres', ['id' => $genre->id]);
    }

    public function test_admin_can_create_and_update_library_book(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $genre = Genre::factory()->create();

        $payload = [
            'genre_id' => $genre->id,
            'title' => 'Test Rental Book',
            'author' => 'Test Author',
            'language' => 'Indonesian',
            'daily_price' => 5000,
            'deposit_price' => 75000,
            'stock' => 2,
            'is_active' => '1',
            'goodreads_url' => 'https://www.goodreads.com/book/show/1',
        ];

        $this->actingAs($admin)->post('/admin/library-books', $payload)
            ->assertRedirect(route('admin.library-books.index'));

        $book = LibraryBook::where('title', 'Test Rental Book')->firstOrFail();
        $this->assertSame(5000, $book->daily_price);
        $this->assertSame(75000, $book->deposit_price);
        $this->assertSame(2, $book->stock);
        $this->assertTrue($book->is_active);

        $this->actingAs($admin)->put("/admin/library-books/{$book->id}", [
            ...$payload,
            'title' => 'Renamed Book',
            'daily_price' => 7000,
            'stock' => 0,
        ])->assertRedirect(route('admin.library-books.index'));

        $book->refresh();
        $this->assertSame('Renamed Book', $book->title);
        $this->assertSame(7000, $book->daily_price);
        $this->assertSame(0, $book->stock);

        $this->actingAs($admin)->delete("/admin/library-books/{$book->id}")
            ->assertRedirect(route('admin.library-books.index'));
        $this->assertSoftDeleted('library_books', ['id' => $book->id]);
    }

    public function test_library_book_cover_upload_and_replacement(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $genre = Genre::factory()->create();

        $payload = [
            'genre_id' => $genre->id,
            'title' => 'Cover Book',
            'language' => 'Indonesian',
            'daily_price' => 3000,
            'stock' => 1,
        ];

        $this->actingAs($admin)->post('/admin/library-books', $payload + [
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 100, 100),
        ]);

        $book = LibraryBook::where('title', 'Cover Book')->firstOrFail();
        $oldPath = $book->cover_image;
        Storage::disk('public')->assertExists($oldPath);
        $this->assertStringStartsWith('library-covers/', $oldPath);

        $this->actingAs($admin)->put("/admin/library-books/{$book->id}", $payload + [
            'title' => 'Cover Book',
            'daily_price' => 3000,
            'stock' => 1,
            'cover_image' => UploadedFile::fake()->image('cover2.png', 100, 100),
        ]);

        $book->refresh();
        $this->assertNotSame($oldPath, $book->cover_image);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($book->cover_image);
    }

    public function test_library_book_validation_rules(): void
    {
        $admin = $this->admin();
        $genre = Genre::factory()->create();

        $base = [
            'genre_id' => $genre->id,
            'title' => 'Validation Book',
            'language' => 'Indonesian',
        ];

        $this->actingAs($admin)->post('/admin/library-books', $base + [
            'daily_price' => 0,
        ])->assertSessionHasErrors('daily_price');

        $this->actingAs($admin)->post('/admin/library-books', $base + [
            'daily_price' => 5000,
            'deposit_price' => -100,
        ])->assertSessionHasErrors('deposit_price');

        $this->actingAs($admin)->post('/admin/library-books', $base + [
            'daily_price' => 5000,
            'goodreads_url' => 'not-a-url',
        ])->assertSessionHasErrors('goodreads_url');

        $this->actingAs($admin)->post('/admin/library-books', $base + [
            'daily_price' => 5000,
            'cover_image' => UploadedFile::fake()->create('doc.pdf', 100),
        ])->assertSessionHasErrors('cover_image');
    }

    public function test_genre_slug_is_unique_and_name_required(): void
    {
        $admin = $this->admin();
        Genre::factory()->create(['name' => 'Duplicate']);

        $this->actingAs($admin)->post('/admin/genres', ['name' => 'Duplicate'])
            ->assertSessionHasErrors('name');

        $this->actingAs($admin)->post('/admin/genres', [])
            ->assertSessionHasErrors('name');
    }
}
