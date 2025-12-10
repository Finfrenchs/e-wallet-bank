@extends('base')

@section('title', 'Dashboard')

@section('header_title', 'Dashboard')

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalUsers) }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Total Transactions Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalTransactions) }}</h3>
                    <p>Total Transactions</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <!-- Pending Transactions Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($pendingTransactions) }}</h3>
                    <p>Pending Transactions</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Transaction by Status Chart -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Transactions by Status
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Transaction by Type Chart -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Transactions by Type
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Monthly Revenue (Last 6 Months)
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>
                        Recent Transactions
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.transaction.index') }}" class="btn btn-sm btn-primary">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>
                                        <i class="fas fa-user-circle mr-1"></i>
                                        {{ $transaction->user->name }}
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $transaction->transactionType->code }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>{{ $transaction->paymentMethod->name }}</td>
                                    <td>
                                        @if ($transaction->status == 'success')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> Success
                                            </span>
                                        @elseif ($transaction->status == 'pending')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle"></i> Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $transaction->created_at->format('d M Y, H:i') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No transactions yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('AdminLTE/plugins/chart.js/Chart.min.js') }}"></script>
<script>
    // Transaction by Status Chart
    var statusCtx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($transactionsByStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($transactionsByStatus->pluck('total')) !!},
                backgroundColor: [
                    '#28a745', // success - green
                    '#ffc107', // pending - yellow
                    '#dc3545', // failed - red
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
            }
        }
    });

    // Transaction by Type Chart
    var typeCtx = document.getElementById('typeChart').getContext('2d');
    var typeChart = new Chart(typeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($transactionsByType->pluck('code')) !!},
            datasets: [{
                label: 'Transactions',
                data: {!! json_encode($transactionsByType->pluck('total')) !!},
                backgroundColor: '#007bff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    // Monthly Revenue Chart
    var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderColor: '#007bff',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return 'Revenue: Rp ' + tooltipItem.yLabel.toLocaleString('id-ID');
                    }
                }
            }
        }
    });
</script>
@endsection
