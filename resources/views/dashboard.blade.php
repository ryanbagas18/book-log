@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold">Dashboard</h1>
            <p class="text-muted mb-0">Overview of your book collection and reading progress</p>
        </div>
        <a href="{{ route('books.export') }}" class="btn btn-success">Export CSV</a>
    </div>

    <!-- Top stats -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Books</h6>
                    <h2 class="mb-0">{{ $totalBooks }}</h2>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Spent</h6>
                    <h2 class="mb-0">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Books Finished</h6>
                    <h2 class="mb-0">{{ $finishedBooks }}</h2>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Completion Rate</h6>
                    <h2 class="mb-0">{{ $completionRatio }}%</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Range selector -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Trends</h5>
        <div class="btn-group btn-group-sm" role="group">
            @foreach (['12' => 'Last 12 months', '24' => 'Last 24 months', 'all' => 'All time'] as $value => $label)
                <a href="{{ route('dashboard.index', ['range' => $value]) }}"
                    class="btn btn-outline-secondary {{ $range === (string) $value ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Spending</h5>
                </div>
                <div class="card-body">
                    @if (array_sum($chart['spending']) === 0)
                        <p class="text-center text-muted py-5 mb-0">No purchases in this period.</p>
                    @else
                        <canvas id="spendingChart" style="max-height: 320px;"></canvas>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Books Bought vs Finished</h5>
                </div>
                <div class="card-body">
                    @if (array_sum($chart['bought']) + array_sum($chart['finished']) === 0)
                        <p class="text-center text-muted py-5 mb-0">No books bought or finished in this period.</p>
                    @else
                        <canvas id="volumeChart" style="max-height: 320px;"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reading insights -->
    <h5 class="mb-3">Reading Insights</h5>
    <div class="row g-4 mb-5">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Unread Asset Value</h6>
                    <div class="progress mb-3" style="height: 22px;">
                        <div class="progress-bar bg-success" style="width: {{ $readPct }}%">
                            {{ $readPct > 8 ? $readPct . '%' : '' }}</div>
                        <div class="progress-bar bg-warning text-dark" style="width: {{ $unreadPct }}%">
                            {{ $unreadPct > 8 ? $unreadPct . '%' : '' }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-success">Read</span>
                            <div class="fs-5 fw-semibold">Rp {{ number_format($readValue, 0, ',', '.') }}</div>
                            <small class="text-muted">{{ $readPct }}% of collection value</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning text-dark">Unread</span>
                            <div class="fs-5 fw-semibold">Rp {{ number_format($unreadValue, 0, ',', '.') }}</div>
                            <small class="text-muted">{{ $unreadPct }}% of collection value</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-2">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Backlog</h6>
                    <h2 class="mb-0">{{ $backlog }}</h2>
                    <small class="text-muted">owned, not finished</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-2">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Avg Cost / Book</h6>
                    <h4 class="mb-0">{{ $avgCost !== null ? 'Rp ' . number_format($avgCost, 0, ',', '.') : '-' }}</h4>
                    <small class="text-muted">free books included</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-2">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Reading Pace</h6>
                    @if ($pace['average'] !== null)
                        <h4 class="mb-0">{{ $pace['average'] }} days/book</h4>
                        <small class="text-muted d-block">median {{ $pace['median'] }} days</small>
                        <small class="text-muted">based on {{ $pace['count'] }}
                            {{ \Illuminate\Support\Str::plural('book', $pace['count']) }}</small>
                    @else
                        <h4 class="mb-0">-</h4>
                        <small class="text-muted">needs start & finish dates</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chart = @json($chart);
            const text = '#e0e0e0';
            const grid = '#333';

            const formatShort = v => v >= 1000000 ? (v / 1000000).toFixed(1) + 'M' :
                v >= 1000 ? (v / 1000).toFixed(0) + 'k' : String(v);
            const formatFull = v => 'Rp ' + Number(v).toLocaleString('id-ID');

            const baseOptions = (yExtra = {}) => ({
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: text
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: text
                        },
                        grid: {
                            color: grid
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: text
                        },
                        grid: {
                            color: grid
                        },
                        ...yExtra
                    }
                }
            });

            const spendingCanvas = document.getElementById('spendingChart');
            if (spendingCanvas) {
                const opts = baseOptions({
                    ticks: {
                        color: text,
                        callback: v => formatShort(v)
                    },
                    title: {
                        display: true,
                        text: 'Spent (Rp)',
                        color: text
                    }
                });
                opts.plugins.legend.display = false;
                opts.plugins.tooltip = {
                    callbacks: {
                        label: c => formatFull(c.parsed.y)
                    }
                };

                new Chart(spendingCanvas, {
                    type: 'bar',
                    data: {
                        labels: chart.labels,
                        datasets: [{
                            label: 'Spent (Rp)',
                            data: chart.spending.map(Number),
                            backgroundColor: 'rgba(13, 110, 253, 0.5)',
                            borderColor: 'rgba(13, 110, 253, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: opts
                });
            }

            const volumeCanvas = document.getElementById('volumeChart');
            if (volumeCanvas) {
                new Chart(volumeCanvas, {
                    type: 'bar',
                    data: {
                        labels: chart.labels,
                        datasets: [{
                                label: 'Bought',
                                data: chart.bought.map(Number),
                                backgroundColor: 'rgba(255, 193, 7, 0.5)',
                                borderColor: 'rgba(255, 193, 7, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Finished',
                                data: chart.finished.map(Number),
                                backgroundColor: 'rgba(25, 135, 84, 0.5)',
                                borderColor: 'rgba(25, 135, 84, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: baseOptions({
                        ticks: {
                            color: text,
                            stepSize: 1,
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Books',
                            color: text
                        }
                    })
                });
            }
        });
    </script>
@endpush
