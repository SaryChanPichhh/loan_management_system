@extends('backend.layout.master')
@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">អរុណសួស្តី Jason!</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">ផ្ទាំងគ្រប់គ្រង</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                            <option selected>សីហា ១៩</option>
                            <option value="1">កក្កដា ១៩</option>
                            <option value="2">មិថុនា ១៩</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            {{-- ═══════════════════════════════════════════════════════════════
                 LIVE SUMMARY STAT CARDS
            ════════════════════════════════════════════════════════════════ --}}
            <div class="row mb-4">

                {{-- Active Loans (blue) --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left border-primary shadow-sm h-100">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Active Loans
                                    </p>
                                    <h2 class="mb-0 font-weight-bold text-dark">
                                        {{ number_format($totalActiveLoans) }}
                                    </h2>
                                    <small class="text-muted">Currently disbursed</small>
                                </div>
                                <div class="ml-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:52px;height:52px;background:rgba(95,118,232,.12);">
                                        <i data-feather="credit-card" style="color:#5f76e8;width:24px;height:24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Outstanding Balance (orange) --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left border-warning shadow-sm h-100">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Outstanding Balance
                                    </p>
                                    <h2 class="mb-0 font-weight-bold text-dark">
                                        ${{ number_format($totalOutstandingBalance, 2) }}
                                    </h2>
                                    <small class="text-muted">Total receivable</small>
                                </div>
                                <div class="ml-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:52px;height:52px;background:rgba(255,193,7,.12);">
                                        <i data-feather="dollar-sign" style="color:#ffc107;width:24px;height:24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Collected This Month (green) --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left border-success shadow-sm h-100">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Collected This Month
                                    </p>
                                    <h2 class="mb-0 font-weight-bold text-dark">
                                        ${{ number_format($totalCollectedThisMonth, 2) }}
                                    </h2>
                                    <small class="text-muted">Completed repayments</small>
                                </div>
                                <div class="ml-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:52px;height:52px;background:rgba(40,167,69,.12);">
                                        <i data-feather="trending-up" style="color:#28a745;width:24px;height:24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Overdue Loans (red) — links to loans index filtered by overdue --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ route('loans.index', ['status' => 'overdue']) }}"
                       class="text-decoration-none">
                        <div class="card border-left border-danger shadow-sm h-100">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Overdue Loans
                                        </p>
                                        <h2 class="mb-0 font-weight-bold text-dark">
                                            <i data-feather="alert-triangle"
                                               style="color:#dc3545;width:20px;height:20px;vertical-align:middle;margin-right:4px;"></i>
                                            {{ number_format($overdueCount) }}
                                        </h2>
                                        <small class="text-muted">Days past due &gt; 0</small>
                                    </div>
                                    <div class="ml-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:52px;height:52px;background:rgba(220,53,69,.12);">
                                            <i data-feather="alert-circle" style="color:#dc3545;width:24px;height:24px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>{{-- /stat cards row --}}

            {{-- ═══════════════════════════════════════════════════════════════
                 RECENT REPAYMENTS  &  DUE IN 7 DAYS — side-by-side
            ════════════════════════════════════════════════════════════════ --}}
            <div class="row">

                {{-- Recent Repayments --}}
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex align-items-center justify-content-between py-3"
                             style="background:#fff;border-bottom:1px solid #eef0f2;">
                            <h5 class="mb-0 font-weight-semibold text-dark">
                                <i data-feather="refresh-cw" style="width:16px;height:16px;margin-right:6px;color:#5f76e8;"></i>
                                Recent Repayments
                            </h5>
                            <span class="badge badge-pill badge-light text-muted" style="font-size:.75rem;">
                                Last 5
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size:.85rem;">
                                    <thead style="background:#f8f9fa;">
                                        <tr>
                                            <th class="border-0 py-2 pl-3">Customer</th>
                                            <th class="border-0 py-2 text-right">Amount</th>
                                            <th class="border-0 py-2">Date</th>
                                            <th class="border-0 py-2">Method</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentRepayments as $repayment)
                                            <tr>
                                                <td class="py-2 pl-3">
                                                    <span class="font-weight-medium text-dark">
                                                        {{ $repayment->loan->customer->name ?? '—' }}
                                                    </span>
                                                </td>
                                                <td class="py-2 text-right font-weight-medium text-success">
                                                    ${{ number_format($repayment->amount, 2) }}
                                                </td>
                                                <td class="py-2 text-muted">
                                                    {{ $repayment->payment_date
                                                        ? $repayment->payment_date->format('d M Y')
                                                        : '—' }}
                                                </td>
                                                <td class="py-2">
                                                    <span class="badge badge-pill badge-light text-muted">
                                                        {{ ucfirst($repayment->payment_method ?? '—') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <i data-feather="inbox" style="width:20px;height:20px;"></i>
                                                    No repayments recorded yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Due in 7 Days --}}
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex align-items-center justify-content-between py-3"
                             style="background:#fff;border-bottom:1px solid #eef0f2;">
                            <h5 class="mb-0 font-weight-semibold text-dark">
                                <i data-feather="calendar" style="width:16px;height:16px;margin-right:6px;color:#ff6b35;"></i>
                                Due in Next 7 Days
                            </h5>
                            <span class="badge badge-pill badge-warning text-white" style="font-size:.75rem;">
                                Pending
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size:.85rem;">
                                    <thead style="background:#f8f9fa;">
                                        <tr>
                                            <th class="border-0 py-2 pl-3">Customer</th>
                                            <th class="border-0 py-2">Loan Code</th>
                                            <th class="border-0 py-2">Due Date</th>
                                            <th class="border-0 py-2 text-right">Amount Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($upcomingDue as $schedule)
                                            <tr>
                                                <td class="py-2 pl-3">
                                                    <span class="font-weight-medium text-dark">
                                                        {{ $schedule->loan->customer->name ?? '—' }}
                                                    </span>
                                                </td>
                                                <td class="py-2">
                                                    <a href="{{ route('loans.show', $schedule->loan_id) }}"
                                                       class="text-primary font-weight-medium">
                                                        {{ $schedule->loan->loan_code ?? '—' }}
                                                    </a>
                                                </td>
                                                <td class="py-2">
                                                    @php
                                                        $daysLeft = now()->startOfDay()->diffInDays(
                                                            \Carbon\Carbon::parse($schedule->due_date)->startOfDay(),
                                                            false
                                                        );
                                                    @endphp
                                                    <span class="{{ $daysLeft <= 2 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                                        {{ \Carbon\Carbon::parse($schedule->due_date)->format('d M Y') }}
                                                    </span>
                                                    @if($daysLeft === 0)
                                                        <span class="badge badge-danger badge-pill ml-1" style="font-size:.7rem;">Today</span>
                                                    @elseif($daysLeft === 1)
                                                        <span class="badge badge-warning badge-pill ml-1" style="font-size:.7rem;">Tomorrow</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 text-right font-weight-medium text-warning">
                                                    ${{ number_format($schedule->amount_due, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <i data-feather="check-circle" style="width:20px;height:20px;color:#28a745;"></i>
                                                    No payments due in the next 7 days.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /tables row --}}

            {{-- Charts (kept below) --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ការបញ្ចេញកម្ចីប្រចាំខែ</h4>
                            <div id="morris-bar-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ស្ថានភាពសងត្រឡប់</h4>
                            <ul class="list-inline text-right">
                                <li class="list-inline-item">
                                    <h5><i class="fa fa-circle mr-1 text-success"></i>បង់រួច</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5><i class="fa fa-circle mr-1 text-warning"></i>មិនទាន់បង់</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5><i class="fa fa-circle mr-1 text-danger"></i>ហួសកំណត់</h5>
                                </li>
                            </ul>
                            <div id="morris-repayment-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            "use strict";

            // Monthly Loan Disbursement Bar Chart
            Morris.Bar({
                element: 'morris-bar-chart',
                data: [
                    {y: 'មករា', disbursed: 125000, target: 150000},
                    {y: 'កុម្ភៈ', disbursed: 180000, target: 150000},
                    {y: 'មីនា', disbursed: 145000, target: 150000},
                    {y: 'មេសា', disbursed: 220000, target: 150000},
                    {y: 'ឧសភា', disbursed: 195000, target: 150000},
                    {y: 'មិថុនា', disbursed: 250000, target: 150000},
                    {y: 'កក្កដា', disbursed: 210000, target: 150000},
                    {y: 'សីហា', disbursed: 185000, target: 150000},
                    {y: 'កញ្ញា', disbursed: 230000, target: 150000},
                    {y: 'តុលា', disbursed: 200000, target: 150000},
                    {y: 'វិច្ឆិកា', disbursed: 175000, target: 150000},
                    {y: 'ធ្នូ', disbursed: 240000, target: 150000}
                ],
                xkey: 'y',
                ykeys: ['disbursed', 'target'],
                labels: ['បានបញ្ចេញ', 'គោលដៅ'],
                barColors: ['#5f76e8', '#01caf1'],
                hideHover: 'auto',
                gridLineColor: '#eef0f2',
                resize: true,
                barSizeRatio: 0.5,
                barGap: 3
            });

            // Repayment Status Area Chart
            Morris.Area({
                element: 'morris-repayment-chart',
                data: [
                    {period: 'មករា', paid: 85, unpaid: 25, overdue: 5},
                    {period: 'កុម្ភៈ', paid: 92, unpaid: 18, overdue: 3},
                    {period: 'មីនា', paid: 88, unpaid: 22, overdue: 4},
                    {period: 'មេសា', paid: 95, unpaid: 15, overdue: 2},
                    {period: 'ឧសភា', paid: 90, unpaid: 20, overdue: 3},
                    {period: 'មិថុនា', paid: 98, unpaid: 12, overdue: 1},
                    {period: 'កក្កដា', paid: 93, unpaid: 17, overdue: 2},
                    {period: 'សីហា', paid: 89, unpaid: 21, overdue: 4},
                    {period: 'កញ្ញា', paid: 96, unpaid: 14, overdue: 2},
                    {period: 'តុលា', paid: 91, unpaid: 19, overdue: 3},
                    {period: 'វិច្ឆិកា', paid: 94, unpaid: 16, overdue: 2},
                    {period: 'ធ្នូ', paid: 97, unpaid: 13, overdue: 1}
                ],
                xkey: 'period',
                ykeys: ['paid', 'unpaid', 'overdue'],
                labels: ['បង់រួច', 'មិនទាន់បង់', 'ហួសកំណត់'],
                pointSize: 3,
                fillOpacity: 0.6,
                pointStrokeColors: ['#28a745', '#ffc107', '#dc3545'],
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                lineWidth: 2,
                hideHover: 'auto',
                lineColors: ['#28a745', '#ffc107', '#dc3545'],
                resize: true
            });
        });
    </script>
@endpush
