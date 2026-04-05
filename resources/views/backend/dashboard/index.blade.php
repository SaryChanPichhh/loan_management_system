@extends('backend.layout.master') @section('contents')
<div class="page-wrapper pb-5">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3
                    class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2"
                >
                    សួស្តី {{ Auth::user()->name }}! (Dashboard)
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item text-muted">
                                ទិដ្ឋភាពទូទៅនៃហិរញ្ញវត្ថុរបស់អ្នក
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Row 1: Loan & Application Status -->
        <div class="row">
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-primary" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">កម្ចីសកម្ម (Active Loans)</h6>
                                <h2 class="text-dark mb-0 font-weight-bold">{{ number_format($activeLoansCount) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-primary"><i data-feather="file-text" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-info" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">បានអនុម័ត (Approved Apps)</h6>
                                <h2 class="text-dark mb-0 font-weight-bold">{{ number_format($approvedLoansCount) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-info"><i data-feather="check-square" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-warning" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">កំពុងរង់ចាំ (Pending Apps)</h6>
                                <h2 class="text-dark mb-0 font-weight-bold">{{ number_format($pendingLoansCount) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-warning"><i data-feather="clock" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Portfolio Totals -->
        <div class="row">
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-success" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">សរុបប្រាក់បញ្ចេញ</h6>
                                <h2 class="text-dark mb-0 font-weight-bold"><sup class="set-doller">$</sup>{{ number_format($totalDisbursed, 2) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-success"><i data-feather="dollar-sign" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-info" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">សរុបប្រាក់ប្រមូលបាន</h6>
                                <h2 class="text-dark mb-0 font-weight-bold"><sup class="set-doller">$</sup>{{ number_format($totalCollected, 2) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-info"><i data-feather="download" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-primary" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">អតិថិជនសរុប</h6>
                                <h2 class="text-dark mb-0 font-weight-bold">{{ number_format($totalCustomers) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-primary"><i data-feather="users" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Daily & Risk Summary -->
        <div class="row">
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-secondary" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">អតិថិជនដែលត្រូវបង់ថ្ងៃនេះ</h6>
                                <h2 class="text-dark mb-0 font-weight-bold">{{ number_format($customersDueTodayCount) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-secondary"><i data-feather="calendar" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-success" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">ប្រាក់ប្រមូលបានថ្ងៃនេះ</h6>
                                <h2 class="text-dark mb-0 font-weight-bold"><sup class="set-doller">$</sup>{{ number_format($amountPaidToday, 2) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-success"><i data-feather="trending-up" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 rounded-lg border-0 border-left border-danger" style="border-left-width: 5px !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-normal mb-2 w-100 text-truncate p-2">កម្ចីហួសកំណត់ (Overdue)</h6>
                                <h2 class="text-dark mb-0 font-weight-bold">{{ number_format($overdueCount) }}</h2>
                            </div>
                            <div class="ml-auto">
                                <span class="opacity-7 text-danger"><i data-feather="alert-circle" style="width: 28px; height: 28px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-xl">
                    <div class="card-body">
                        <h4 class="card-title font-weight-bold text-dark mb-4">
                            ការបញ្ចេញកម្ចី ទល់នឹង ការប្រមូលប្រាក់ (Disbursement
                            vs Collection)
                        </h4>
                        <div style="height: 350px">
                            <canvas id="disbursementCollectionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-xl">
                    <div class="card-body">
                        <h4 class="card-title font-weight-bold text-dark mb-4">
                            ហានិភ័យកម្ចីហួសកំណត់ (PAR Distribution)
                        </h4>
                        <div style="height: 350px">
                            <canvas id="parDistributionChart"></canvas>
                        </div>
                        <div class="mt-4 text-center">
                            <small class="text-muted"
                                >ចែកតាមចំនួនថ្ងៃដែលលើសកំណត់</small
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Payments Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-xl">
                        <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-striped no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="text-muted">
                                        <th class="font-weight-medium border-0">លេខកម្ចី</th>
                                        <th class="font-weight-medium border-0">អតិថិជន</th>
                                        <th class="font-weight-medium border-0">ចំនួនទឹកប្រាក់</th>
                                        <th class="font-weight-medium border-0">វិធីទូទាត់</th>
                                        <th class="font-weight-medium border-0">លេខយោង</th>
                                        <th class="font-weight-medium border-0">ម៉ោង</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todayRepayments as $repayment)
                                        <tr>
                                            <td class="px-2 py-3 border-top-0">
                                                <span class="font-weight-bold text-primary">{{ $repayment->loan->loan_code ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-2 py-3 border-top-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="m-r-10">
                                                        <a class="btn btn-circle btn-info text-white">{{ substr($repayment->loan->customer->name ?? 'U', 0, 1) }}</a>
                                                    </div>
                                                    <div class="">
                                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">{{ $repayment->loan->customer->name ?? 'Unknown' }}</h5>
                                                        <small class="text-muted">{{ $repayment->loan->customer->code ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-2 py-3 border-top-0">
                                                <span class="font-weight-bold text-success">${{ number_format($repayment->amount, 2) }}</span>
                                            </td>
                                            <td class="px-2 py-3 border-top-0">
                                                <span class="badge badge-light-secondary text-secondary font-weight-medium">{{ $repayment->payment_method }}</span>
                                            </td>
                                            <td class="px-2 py-3 border-top-0 text-muted">
                                                {{ $repayment->reference_number ?? '-' }}
                                            </td>
                                            <td class="px-2 py-3 border-top-0">
                                                <small class="text-muted"><i data-feather="clock" class="feather-sm mr-1"></i> {{ $repayment->created_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i data-feather="inbox" class="mb-2" style="width: 40px; height: 40px;"></i>
                                                <p class="mb-0">មិនមានការទូទាត់សម្រាប់ថ្ងៃនេះទេ</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @push('scripts')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        "use strict";

        // 1. Disbursement vs Collection Chart
        const ctxDC = document.getElementById('disbursementCollectionChart').getContext('2d');
        new Chart(ctxDC, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'ការបញ្ចេញកម្ចី (Disbursement)',
                        data: {!! json_encode($disbursements) !!},
                        backgroundColor: 'rgba(95, 118, 232, 0.7)',
                        borderColor: 'rgb(95, 118, 232)',
                        borderWidth: 1,
                        borderRadius: 5,
                    },
                    {
                        label: 'ការប្រមូលប្រាក់ (Collection)',
                        data: {!! json_encode($collections) !!},
                        backgroundColor: 'rgba(1, 202, 241, 0.7)',
                        borderColor: 'rgb(1, 202, 241)',
                        borderWidth: 1,
                        borderRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: (value) => '$' + value.toLocaleString() }
                    }
                }
            }
        });

        // 2. PAR Distribution Chart (Portfolio at Risk)
        const ctxPAR = document.getElementById('parDistributionChart').getContext('2d');
        new Chart(ctxPAR, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($parData)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($parData)) !!},
                    backgroundColor: [
                        '#f6c23e', // 1-30: Yellow
                        '#fd7e14', // 31-60: Orange
                        '#e74a3b', // 61-90: Red
                        '#851414'  // 91+: Dark Red
                    ],
                    hoverOffset: 15,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endpush @push('styles')
<style>
    .rounded-xl {
        border-radius: 1.25rem !important;
    }
    .rounded-lg {
        border-radius: 0.75rem !important;
    }
</style>
@endpush
