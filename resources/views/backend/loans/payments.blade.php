@extends('backend.layout.master')

@push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<style>
    .stat-card { border-radius: .75rem; border: none; }
    .stat-card .card-body { padding: 1.25rem 1.5rem; }
    .stat-value { font-size: 1.6rem; font-weight: 700; margin-bottom: 0; }
    tr.overdue-row { background-color: #fff5f5 !important; }
    tr.paid-row { background-color: #f0fff4 !important; }
</style>
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    ការបង់ប្រាក់កម្ចី
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.show', $loan->id) }}">{{ $loan->loan_code }}</a></li>
                        <li class="breadcrumb-item active">ការបង់ប្រាក់</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center text-right">
                @if($loan->status === 'active')
                    <a href="{{ route('repayments.create', $loan->id) }}" class="btn btn-primary btn-sm">
                        <i data-feather="plus-circle" style="width:15px"></i> បង់ប្រាក់ថ្មី
                    </a>
                @endif
                <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-light btn-sm border ml-1">
                    <i data-feather="arrow-left" style="width:15px"></i> ត្រឡប់
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Summary Stats --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Principal</p>
                        <p class="stat-value text-dark">${{ number_format($loan->principal_amount, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Outstanding Balance</p>
                        <p class="stat-value text-danger">${{ number_format($loan->account->outstanding_balance ?? $remainingBalance, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">សរុបបានទូទាត់</p>
                        <p class="stat-value text-success">${{ number_format($totalPaid, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">ហួសកំណត់ (Overdue)</p>
                        <p class="stat-value {{ $overdueCount > 0 ? 'text-danger' : 'text-success' }}">{{ $overdueCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Schedule Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm" style="border-radius:.75rem;border:none;">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i data-feather="list" class="mr-2"></i>
                            កាលវិភាគទូទាត់ ({{ $loan->schedules->count() }} ខែ)
                        </h5>
                        @if($loan->status === 'active')
                            <a href="{{ route('repayments.create', $loan->id) }}" class="btn btn-primary btn-sm">
                                <i data-feather="plus" style="width:14px"></i> បង់ប្រាក់
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="payments_table" class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th width="50">ខែ</th>
                                    <th>ថ្ងៃត្រូវបង់</th>
                                    <th>Grace End</th>
                                    <th>Principal</th>
                                    <th>Interest</th>
                                    <th>សរុប Due</th>
                                    <th>បានបង់</th>
                                    <th>ស្ថានភាព</th>
                                    <th width="90">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($loan->schedules->sortBy('installment_number') as $schedule)
                                    @php
                                        // Usually overdue calculation uses due_date
                                        $dpdBaseDate = $schedule->due_date ?? $schedule->grace_period_end_date;
                                        $isOverdue = in_array($schedule->status, ['overdue']) || 
                                           (!in_array($schedule->status, ['paid', 'waived']) && \Carbon\Carbon::parse($dpdBaseDate)->lt(\Carbon\Carbon::today()));
                                        
                                        $daysPastDue = $isOverdue ? \Carbon\Carbon::parse($dpdBaseDate)->diffInDays(\Carbon\Carbon::today(), false) : 0;
                                        // Default to exactly 0 if negative
                                        if ($daysPastDue < 0) $daysPastDue = 0;

                                        $rowClass = in_array($schedule->status, ['paid', 'waived']) ? 'table-success opacity-75' : ($isOverdue && $daysPastDue > 0 ? 'table-danger' : '');
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td class="text-center font-weight-bold">
                                            {{ $schedule->installment_number ?? ($loop->index + 1) }}
                                        </td>
                                        <td>{{ $schedule->due_date }}</td>
                                        <td>{{ $schedule->grace_period_end_date ?? '—' }}</td>
                                        <td>${{ number_format($schedule->principal_due ?? 0, 2) }}</td>
                                        <td>${{ number_format($schedule->interest_due ?? 0, 2) }}</td>
                                        <td class="font-weight-bold">${{ number_format($schedule->amount_due, 2) }}</td>
                                        <td class="text-success">${{ number_format($schedule->amount_paid, 2) }}</td>
                                        <td>
                                            @if($schedule->status === 'paid')
                                                <span class="badge badge-success">បានបង់ ✓</span>
                                            @elseif($schedule->status === 'partial')
                                                <span class="badge badge-warning">Partial</span>
                                            @elseif($schedule->status === 'waived')
                                                <span class="badge badge-success">បានបង់ (Early) ✓</span>
                                            @elseif($isOverdue && $daysPastDue > 0)
                                                <span class="badge badge-danger">Overdue <br>({{ $daysPastDue }} DPD)</span>
                                            @else
                                                <span class="badge badge-secondary">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($schedule->status !== 'paid' && $loan->status === 'active')
                                                <a href="{{ route('repayments.create', $loan->id) }}"
                                                   class="btn btn-xs btn-{{ $isOverdue ? 'danger' : 'primary' }} btn-sm py-0 px-2"
                                                   title="Record Payment">
                                                    <i data-feather="dollar-sign" style="width:13px"></i>
                                                </a>
                                            @elseif($schedule->status === 'paid')
                                                <span class="text-success"><i data-feather="check" style="width:14px"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot class="thead-light">
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold">សរុប</td>
                                    <td class="font-weight-bold">${{ number_format($totalPayable, 2) }}</td>
                                    <td class="font-weight-bold text-success">${{ number_format($totalPaid, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Repayment History --}}
        @if($loan->repayments->count() > 0)
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm" style="border-radius:.75rem;border:none;">
                    <div class="card-header py-3">
                        <h5 class="mb-0">
                            <i data-feather="clock" class="mr-2"></i>
                            ប្រវត្តិការបង់ប្រាក់ ({{ $loan->repayments->count() }} ដង)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>ថ្ងៃ</th>
                                    <th>ចំនួន</th>
                                    <th>Principal</th>
                                    <th>Interest</th>
                                    <th>Late Fee</th>
                                    <th>វិធី</th>
                                    <th>Ref</th>
                                    <th>ទទួលដោយ</th>
                                    <th>សកម្មភាព</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($loan->repayments->sortByDesc('payment_date') as $rep)
                                    <tr>
                                        <td>{{ $rep->payment_date }}</td>
                                        <td class="font-weight-bold">${{ number_format($rep->amount, 2) }}</td>
                                        <td>${{ number_format($rep->principal_paid, 2) }}</td>
                                        <td>${{ number_format($rep->interest_paid, 2) }}</td>
                                        <td>
                                            @if($rep->late_fee_applied)
                                                <span class="text-danger">${{ number_format($rep->late_fee_paid, 2) }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $rep->payment_method }}</td>
                                        <td><small>{{ $rep->reference_number ?? '—' }}</small></td>
                                        <td>{{ $rep->receivedBy->name ?? '—' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('repayments.receipt', $rep->id) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" title="Print Receipt">
                                                <i data-feather="printer" style="width:13px"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            "use strict";
            $(".preloader").fadeOut();
            if ($("#payments_table").length) {
                try {
                    $("#payments_table").DataTable({
                        pageLength: 25,
                        order: [[0, "asc"]],
                        responsive: true,
                        language: {
                            search: "ស្វែងរក៖",
                            lengthMenu: "បង្ហាញ _MENU_ បញ្ជី",
                            info: "បង្ហាញ _START_–_END_ / _TOTAL_",
                            paginate: { next: "បន្ទាប់", previous: "មុន" }
                        }
                    });
                } catch (e) { console.error(e); }
            }
            if (typeof feather !== "undefined") feather.replace();
        });
    </script>
@endpush
