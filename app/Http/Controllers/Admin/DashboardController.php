<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $statusCounts = Rental::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $depositsHeld = (int) Rental::whereIn('status', ['approved', 'on_rent'])->sum('deposit');

        $pendingRefunds = Rental::where('deposit_refund_status', 'pending')
            ->get()
            ->sum(fn ($rental) => $rental->deposit - $rental->deposit_deduction);

        $pendingVerification = Rental::with(['user', 'libraryBook'])
            ->where('status', 'pending_verification')
            ->latest()
            ->limit(5)
            ->get();

        $overdue = Rental::with(['user', 'libraryBook'])
            ->where('status', 'on_rent')
            ->whereDate('end_date', '<', now()->toDateString())
            ->orderBy('end_date')
            ->get();

        return view('admin.dashboard', compact(
            'statusCounts',
            'depositsHeld',
            'pendingRefunds',
            'pendingVerification',
            'overdue'
        ));
    }
}
