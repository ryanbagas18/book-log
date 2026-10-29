<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalSpent = Book::where('ownership', 'owned')->sum('price');
        $finishedBooks = Book::where('status', 'finished')->count();
        $totalBooksCount = Book::where('ownership', 'owned')->count();
        
        $completionRatio = $totalBooksCount > 0 
            ? round(($finishedBooks / $totalBooksCount) * 100, 1) 
            : 0;

        // Monthly data for chart
        $monthlyData = Book::where('ownership', 'owned')
            ->selectRaw('DATE_FORMAT(purchase_date, "%Y-%m") as month, SUM(price) as total_spent, COUNT(*) as books_added')
            ->whereNotNull('purchase_date')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyFinished = Book::where('status', 'finished')
            ->selectRaw('DATE_FORMAT(finished_at, "%Y-%m") as month, COUNT(*) as books_finished')
            ->whereNotNull('finished_at')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Combine data - get all unique months from both datasets
        $allMonths = [];

        foreach ($monthlyData as $data) {
            $allMonths[$data->month] = [
                'expenses' => $data->total_spent,
                'books_completed' => 0
            ];
        }

        foreach ($monthlyFinished as $data) {
            if (isset($allMonths[$data->month])) {
                $allMonths[$data->month]['books_completed'] = $data->books_finished;
            } else {
                $allMonths[$data->month] = [
                    'expenses' => 0,
                    'books_completed' => $data->books_finished
                ];
            }
        }

        $chartData = collect($allMonths)->map(function($data, $month) {
            return [
                'month' => $month,
                'expenses' => $data['expenses'],
                'books_completed' => $data['books_completed'],
            ];
        })->sortBy('month')->values()->all();

        return view('dashboard', compact(
            'totalBooks',
            'totalSpent',
            'finishedBooks',
            'completionRatio',
            'chartData'
        ));
    }
}
