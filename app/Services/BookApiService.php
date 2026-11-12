<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookApiService
{
    public function search(string $query, string $type = 'title'): array
    {
        $results = [];

        if ($type === 'isbn') {
            $results = $this->searchByIsbn($query);
        } else {
            $results = $this->searchByTitleOrAuthor($query, $type);
        }

        return $results;
    }

    protected function searchByIsbn(string $isbn): array
    {
        $books = $this->searchGoogleBooks($isbn, 'isbn');

        if (empty($books)) {
            $books = $this->searchOpenLibrary($isbn, 'isbn');
        }

        return $books;
    }

    protected function searchByTitleOrAuthor(string $query, string $type): array
    {
        $books = $this->searchGoogleBooks($query, $type);

        if (empty($books)) {
            $books = $this->searchOpenLibrary($query, $type);
        }

        return $books;
    }

    protected function searchGoogleBooks(string $query, string $type): array
    {
        try {
            $searchParam = $type === 'isbn' ? 'isbn:' : ($type === 'author' ? 'inauthor:' : 'intitle:');
            $url = "https://www.googleapis.com/books/v1/volumes?q={$searchParam}" . urlencode($query) . "&maxResults=10";

            $response = Http::timeout(10)->withoutVerifying()->get($url);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();

            if (!isset($data['items'])) {
                return [];
            }

            return collect($data['items'])->map(function ($item) {
                $volumeInfo = $item['volumeInfo'] ?? [];
                $industryIdentifiers = $volumeInfo['industryIdentifiers'] ?? [];

                $isbn = null;
                foreach ($industryIdentifiers as $identifier) {
                    if (in_array($identifier['type'] ?? '', ['ISBN_10', 'ISBN_13'])) {
                        $isbn = $identifier['identifier'];
                        break;
                    }
                }

                return [
                    'title' => $volumeInfo['title'] ?? '',
                    'authors' => $volumeInfo['authors'] ?? [],
                    'publisher' => $volumeInfo['publisher'] ?? '',
                    'published_date' => $volumeInfo['publishedDate'] ?? '',
                    'language' => $volumeInfo['language'] ?? '',
                    'page_count' => $volumeInfo['pageCount'] ?? null,
                    'isbn' => $isbn,
                    'cover_url' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
                    'source' => 'Google Books',
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Google Books API error: ' . $e->getMessage());
            return [];
        }
    }

    protected function searchOpenLibrary(string $query, string $type): array
    {
        try {
            if ($type === 'isbn') {
                $url = "https://openlibrary.org/api/books?bibkeys=ISBN:" . urlencode($query) . "&format=json&jscmd=data";
            } else {
                $searchParam = $type === 'author' ? 'author:' : 'title:';
                $url = "https://openlibrary.org/search.json?q={$searchParam}" . urlencode($query) . "&limit=10";
            }

            $response = Http::timeout(10)->withoutVerifying()->get($url);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();

            if ($type === 'isbn') {
                $key = "ISBN:" . $query;
                if (!isset($data[$key])) {
                    return [];
                }

                $book = $data[$key];
                return [[
                    'title' => $book['title'] ?? '',
                    'authors' => collect($book['authors'] ?? [])->pluck('name')->toArray(),
                    'publisher' => $book['publishers'][0]['name'] ?? '',
                    'published_date' => $book['publish_date'] ?? '',
                    'language' => '',
                    'page_count' => $book['number_of_pages'] ?? null,
                    'isbn' => $query,
                    'cover_url' => $book['cover']['medium'] ?? $book['cover']['large'] ?? null,
                    'source' => 'Open Library',
                ]];
            } else {
                if (!isset($data['docs'])) {
                    return [];
                }

                return collect($data['docs'])->take(10)->map(function ($doc, $i) {
                    $isbn = $this->pickIsbn($doc['isbn'] ?? []);
                    $pages = $doc['number_of_pages'] ?? null;

                    if ($i < 5 && (! $isbn || ! $pages)) {
                        $edition = $this->fetchEditionDetails($doc['key'] ?? null);
                        $isbn = $isbn ?: ($edition['isbn_13'] ?? $edition['isbn_10'] ?? null);
                        $pages = $pages ?: ($edition['pages'] ?? null);
                    }

                    return [
                        'title' => $doc['title'] ?? '',
                        'authors' => $doc['author_name'] ?? [],
                        'publisher' => $doc['publisher'][0] ?? '',
                        'published_date' => $doc['first_publish_year'] ?? '',
                        'language' => $doc['language'][0] ?? '',
                        'page_count' => $pages,
                        'isbn' => $isbn,
                        'cover_url' => isset($doc['cover_i']) ? "https://covers.openlibrary.org/b/id/{$doc['cover_i']}-M.jpg" : null,
                        'source' => 'Open Library',
                    ];
                })->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Open Library API error: ' . $e->getMessage());
            return [];
        }
    }

    protected function pickIsbn(array $isbns): ?string
    {
        foreach ($isbns as $isbn) {
            if (strlen((string) $isbn) === 13) {
                return (string) $isbn;
            }
        }

        return $isbns[0] ?? null;
    }

    // Search docs often lack isbn/number_of_pages; editions carry them per printing.
    protected function fetchEditionDetails(?string $workKey): array
    {
        if (! $workKey) {
            return [];
        }

        try {
            $response = Http::timeout(5)->withoutVerifying()
                ->get('https://openlibrary.org' . $workKey . '/editions.json', ['limit' => 1]);

            if (! $response->successful()) {
                return [];
            }

            $entry = $response->json('entries.0') ?? [];

            return [
                'pages' => $entry['number_of_pages'] ?? null,
                'isbn_13' => $entry['isbn_13'][0] ?? null,
                'isbn_10' => $entry['isbn_10'][0] ?? null,
            ];
        } catch (\Exception $e) {
            return [];
        }
    }
}
