@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold">Dashboard</h1>
            <p class="text-muted">Overview of your book collection and reading progress</p>
        </div>
        <a href="{{ route('books.export') }}" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download me-1" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-2.5a.5.5 0 0 1 .5-.5zm0-3A1.5 1.5 0 0 3 5v5a1.5 1.5 0 0 1-3 0v-5zM.5 11A1.5 1.5 0 0 0 2 12.5v-5a1.5 1.5 0 0 0-3 0v5A1.5 1.5 0 0 0 .5 11z"/>
            </svg>
            Export CSV
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted mb-2">Total Books</h6>
                <h2 class="card-text mb-0">{{ $totalBooks }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted mb-2">Total Spent</h6>
                <h2 class="card-text mb-0">{{ formatRupiah($totalSpent) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted mb-2">Books Finished</h6>
                <h2 class="card-text mb-0">{{ $finishedBooks }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted mb-2">Completion Rate</h6>
                <h2 class="card-text mb-0">{{ $completionRatio }}%</h2>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Monthly Expenses vs Books Completed</h5>
    </div>
    <div class="card-body">
        @if(empty($chartData))
            <div class="text-center text-muted py-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-bar-chart-line mb-3" style="color: #444;" viewBox="0 0 16 16">
                    <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3zM1 14v-3h3v3H1zm4 0v-3h3v3H5zm4 0v-3h3v3H9zm4 0v-3h3v3h-3z"/>
                </svg>
                <p class="mb-0">No data available. Add books with purchase dates and finish dates to see the chart.</p>
            </div>
        @else
            <canvas id="dashboardChart" style="max-height: 400px;"></canvas>
        @endif
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        const chartData = @json($chartData);
        
        console.log('Chart Data:', chartData);
        
        if (chartData.length > 0) {
            new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(d => d.month),
                datasets: [
                    {
                        label: 'Expenses (Rp)',
                        data: chartData.map(d => d.expenses),
                        backgroundColor: 'rgba(13, 110, 253, 0.5)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Books Completed',
                        data: chartData.map(d => d.books_completed),
                        backgroundColor: 'rgba(25, 135, 84, 0.5)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#e0e0e0'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#e0e0e0'
                        },
                        grid: {
                            color: '#333'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Expenses (Rp)',
                            color: '#e0e0e0'
                        },
                        ticks: {
                            color: '#e0e0e0'
                        },
                        grid: {
                            color: '#333'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Books Completed',
                            color: '#e0e0e0'
                        },
                        ticks: {
                            color: '#e0e0e0'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
        }
    </script>
@endsection
