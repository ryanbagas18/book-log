<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Eloquent query menggunakan scopeOwned() dari model Book
        $owned = Book::owned()
            ->get(['id', 'status', 'price', 'purchase_date', 'started_at', 'finished_at']);

        $range = in_array($request->query('range'), ['12', '24', 'all'], true)
            ? $request->query('range')
            : '12';

        // Metrik Statistik Utama
        $totalBooks       = $owned->count();
        $totalSpent       = (int) $owned->sum('price');
        $finishedBooks    = $owned->where('status', 'finished')->count();
        $backlog          = $totalBooks - $finishedBooks;
        $completionRatio  = $totalBooks > 0 ? round($finishedBooks / $totalBooks * 100, 1) : 0;

        // Metrik Aset Belum Dibaca (Tsundoku Value)
        $readValue   = (int) $owned->where('status', 'finished')->sum('price');
        $unreadValue = $totalSpent - $readValue;
        $readPct     = $totalSpent > 0 ? round($readValue / $totalSpent * 100, 1) : 0;
        $unreadPct   = $totalSpent > 0 ? round(100 - $readPct, 1) : 0;

        // Rata-rata Biaya per Buku
        $avgCost = $totalBooks > 0 ? (int) round($totalSpent / $totalBooks) : null;

        // Kecepatan Membaca (Reading Pace)
        $days = $owned
            ->filter(fn($b) => $b->started_at && $b->finished_at)
            ->map(function ($b) {
                $start = Carbon::parse($b->started_at)->startOfDay();
                $end   = Carbon::parse($b->finished_at)->startOfDay();
                return $end->gte($start) ? (int) $start->diffInDays($end) : null;
            })
            ->filter(fn($d) => $d !== null)
            ->values();

        $pace = [
            'count'   => $days->count(),
            'average' => $days->isNotEmpty() ? round($days->avg(), 1) : null,
            'median'  => $days->isNotEmpty() ? $days->median() : null,
        ];

        $chart = $this->buildChartData($owned, $range);

        return view('dashboard', compact(
            'totalBooks',
            'totalSpent',
            'finishedBooks',
            'completionRatio',
            'backlog',
            'readValue',
            'unreadValue',
            'readPct',
            'unreadPct',
            'avgCost',
            'pace',
            'chart',
            'range'
        ));
    }

    /**
     * @param Collection<\App\Models\Book> $owned
     * @param string $range
     * @return array
     */
    private function buildChartData(Collection $owned, string $range): array
    {
        $end = now()->startOfMonth();

        if ($range === 'all') {
            $dates = $owned->pluck('purchase_date')
                ->merge($owned->pluck('finished_at'))
                ->filter()
                ->map(fn($d) => Carbon::parse($d));

            $start = $dates->isEmpty()
                ? $end->copy()->subMonths(11)
                : $dates->min()->copy()->startOfMonth();
        } else {
            $start = $end->copy()->subMonths((int) $range - 1);
        }

        $buckets = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $buckets[$cursor->format('Y-m')] = [
                'label' => $cursor->format('M Y'),
                'spent' => 0,
                'bought' => 0,
                'finished' => 0,
            ];
            $cursor = $cursor->copy()->addMonth();
        }

        foreach ($owned as $book) {
            if ($book->purchase_date) {
                $key = Carbon::parse($book->purchase_date)->format('Y-m');
                if (isset($buckets[$key])) {
                    $buckets[$key]['spent']  += (int) $book->price;
                    $buckets[$key]['bought'] += 1;
                }
            }
            if ($book->status === 'finished' && $book->finished_at) {
                $key = Carbon::parse($book->finished_at)->format('Y-m');
                if (isset($buckets[$key])) {
                    $buckets[$key]['finished'] += 1;
                }
            }
        }

        $rows = collect($buckets)->values();

        return [
            'labels'   => $rows->pluck('label')->toArray(),
            'spending' => $rows->pluck('spent')->toArray(),
            'bought'   => $rows->pluck('bought')->toArray(),
            'finished' => $rows->pluck('finished')->toArray(),
        ];
    }
}
