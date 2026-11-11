<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\LibraryBook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_lists_only_active_books(): void
    {
        $active = LibraryBook::factory()->create(['title' => 'Visible Book', 'is_active' => true]);
        LibraryBook::factory()->create(['title' => 'Hidden Book', 'is_active' => false]);
        $deleted = LibraryBook::factory()->create(['title' => 'Deleted Book']);
        $deleted->delete();

        $this->get('/library')
            ->assertOk()
            ->assertSee('Visible Book')
            ->assertDontSee('Hidden Book')
            ->assertDontSee('Deleted Book');
    }

    public function test_search_and_genre_filter_preserve_each_other(): void
    {
        $genreA = Genre::factory()->create(['name' => 'Alpha Genre', 'slug' => 'alpha-genre']);
        $genreB = Genre::factory()->create(['name' => 'Beta Genre', 'slug' => 'beta-genre']);

        LibraryBook::factory()->create(['title' => 'Alpha Searchable', 'genre_id' => $genreA->id]);
        LibraryBook::factory()->create(['title' => 'Alpha Other', 'genre_id' => $genreB->id]);
        LibraryBook::factory()->create(['title' => 'Beta Book', 'genre_id' => $genreB->id]);

        $response = $this->get('/library?genre=alpha-genre&q=Searchable');

        $response->assertOk()->assertSee('Alpha Searchable')->assertDontSee('Beta Book');
        $response->assertSee('name="genre" value="alpha-genre"', false);

        $this->get('/library?genre=beta-genre&q=Searchable')
            ->assertOk()
            ->assertDontSee('Alpha Searchable')
            ->assertDontSee('Beta Book');
    }

    public function test_detail_page_of_inactive_book_returns_404(): void
    {
        $book = LibraryBook::factory()->create(['is_active' => false]);

        $this->get("/library/{$book->id}")->assertNotFound();
    }

    public function test_detail_page_of_deleted_book_returns_404(): void
    {
        $book = LibraryBook::factory()->create();
        $book->delete();

        $this->get("/library/{$book->id}")->assertNotFound();
    }

    public function test_detail_page_shows_rent_link_states(): void
    {
        $inStock = LibraryBook::factory()->create(['stock' => 2]);
        $outOfStock = LibraryBook::factory()->create(['stock' => 0]);

        $this->get("/library/{$inStock->id}")->assertOk()->assertSee('Log in to Rent');

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user)->get("/library/{$inStock->id}")->assertOk()->assertSee('Rent Now');
        $this->actingAs($user)->get("/library/{$outOfStock->id}")->assertOk()->assertSee('Out of stock');
    }

    public function test_guests_can_browse_catalog_and_terms(): void
    {
        $this->get('/')->assertRedirect(route('library.index'));
        $this->get('/library')->assertOk();
        $this->get('/terms')->assertOk();
    }
}
