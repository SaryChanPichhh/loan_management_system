@extends('backend.layout.master')

@push('styles')
<style>
/* ── Report page variables ──────────────────────────────────── */
:root {
    --rp-blue:    #4361ee;
    --rp-green:   #2ec4b6;
    --rp-orange:  #f77f00;
    --rp-gray:    #6c757d;
    --rp-yellow:  #f3c623;
    --rp-red:     #e63946;
    --rp-navy:    #1a3a6b;
    --rp-accent:  #d4a843;
    --rp-surface: #f0f3ff;
    --rp-border:  #dde3ef;
}

/* ── Tab nav ────────────────────────────────────────────────── */
.rp-tab-bar {
    display: flex;
    gap: 0;
    border-bottom: 2px solid var(--rp-border);
    margin-bottom: 0;
    background: #fff;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(26,58,107,.07);
}
.rp-tab-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 26px;
    font-size: 13.5px;
    font-weight: 600;
    color: #6b7a99;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: all .2s;
    white-space: nowrap;
    position: relative;
    bottom: -2px;
}
.rp-tab-link:hover {
    color: var(--rp-navy);
    background: var(--rp-surface);
    text-decoration: none;
}
.rp-tab-link.active {
    color: var(--rp-navy);
    border-bottom-color: var(--rp-navy);
    background: #fff;
}
.rp-tab-link .tab-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}
.rp-tab-link.active .tab-icon-collection { background: #e8edff; color: var(--rp-blue); }
.rp-tab-link.active .tab-icon-overdue    { background: #fde8ea; color: var(--rp-red);  }
.rp-tab-link.active .tab-icon-portfolio  { background: #e8f7f5; color: var(--rp-green);}
.rp-tab-link:not(.active) .tab-icon { background: #f0f2f5; color: #9aa0b0; }

/* ── Tab panel ──────────────────────────────────────────────── */
.rp-tab-panel {
    background: #fff;
    border: 1px solid var(--rp-border);
    border-top: none;
    border-radius: 0 0 10px 10px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(26,58,107,.06);
}

/* ── Filter bar ─────────────────────────────────────────────── */
.rp-filter-bar {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 20px;
    background: var(--rp-surface);
    border-radius: 8px;
    margin-bottom: 22px;
    border: 1px solid #dce6f5;
}
.rp-filter-bar .filter-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.rp-filter-bar label {
    font-size: 11px;
    font-weight: 700;
    color: #6b7a99;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin: 0;
}
.rp-filter-bar .form-control {
    height: 38px;
    font-size: 13px;
    border-color: #c9d5eb;
    border-radius: 6px;
    min-width: 150px;
}
.rp-filter-bar .form-control:focus {
    border-color: var(--rp-navy);
    box-shadow: 0 0 0 3px rgba(26,58,107,.12);
}
.btn-filter {
    height: 38px;
    padding: 0 20px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 6px;
    background: var(--rp-navy);
    border-color: var(--rp-navy);
    color: #fff;
    transition: all .2s;
}
.btn-filter:hover { background: #0f2a55; border-color: #0f2a55; color: #fff; }
.btn-export-csv {
    height: 38px;
    padding: 0 18px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 6px;
    border: 1.5px solid #1e7e34;
    color: #1e7e34;
    background: #fff;
    transition: all .2s;
}
.btn-export-csv:hover { background: #1e7e34; color: #fff; text-decoration: none; }
.btn-export-pdf {
    height: 38px;
    padding: 0 18px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 6px;
    border: 1.5px solid #c0392b;
    color: #c0392b;
    background: #fff;
    transition: all .2s;
}
.btn-export-pdf:hover { background: #c0392b; color: #fff; text-decoration: none; }

/* ── Table ──────────────────────────────────────────────────── */
.rp-table-wrap {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--rp-border);
}
.rp-table {
    width: 100%;
    margin: 0;
    font-size: 13px;
}
.rp-table thead th {
    background: var(--rp-navy);
    color: #fff;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 11px 14px;
    border: none;
    white-space: nowrap;
}
.rp-table tbody tr { transition: background .15s; }
.rp-table tbody tr:hover td { background: #f0f4ff; }
.rp-table tbody td {
    padding: 10px 14px;
    vertical-align: middle;
    border-color: var(--rp-border);
    font-size: 13px;
}
.rp-table tbody tr:nth-child(even) td { background: #fafbff; }
.rp-table tfoot td {
    padding: 11px 14px;
    background: #e8edff;
    font-weight: 700;
    font-size: 13px;
    border-top: 2px solid #c5cfe0;
    color: var(--rp-navy);
}

/* Row highlight for overdue > 30 days */
.rp-table tbody tr.overdue-critical td {
    background: #fff0f1 !important;
    color: #922b21;
}

/* ── Amount helpers ──────────────────────────────────────────── */
.amount-cell { text-align: right; font-variant-numeric: tabular-nums; font-family: 'Courier New', monospace; font-size: 12.5px; }
.amount-total { color: var(--rp-navy); }
.amount-danger { color: var(--rp-red); font-weight: 700; }

/* ── Badges ─────────────────────────────────────────────────── */
.rp-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .2px;
}
.rp-badge-info      { background: #d1ecf1; color: #0c5460; }
.rp-badge-warning   { background: #fff3cd; color: #856404; }
.rp-badge-danger    { background: #f8d7da; color: #7b141c; }
.badge-method       { background: #e8edff; color: var(--rp-navy); border-radius: 4px; font-size: 11px; padding: 2px 8px; }

/* ── Loan code style ─────────────────────────────────────────── */
.loan-code {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    background: #f0f3ff;
    color: var(--rp-navy);
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: 700;
}

/* ── Portfolio Status Cards ──────────────────────────────────── */
.rp-portfolio-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-bottom: 30px;
}
@media (max-width: 1200px) { .rp-portfolio-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .rp-portfolio-grid { grid-template-columns: repeat(2, 1fr); } }

.rp-status-card {
    border-radius: 12px;
    padding: 22px 20px 18px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
    transition: transform .2s, box-shadow .2s;
}
.rp-status-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 22px rgba(0,0,0,.14);
}
.rp-status-card .sc-label {
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    opacity: .85;
    margin-bottom: 8px;
}
.rp-status-card .sc-count {
    font-size: 36px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 4px;
}
.rp-status-card .sc-principal {
    font-size: 13px;
    font-weight: 600;
    opacity: .8;
}
.rp-status-card .sc-icon {
    position: absolute;
    right: 14px;
    top: 14px;
    font-size: 32px;
    opacity: .15;
}
.rp-status-card .sc-bar {
    height: 4px;
    border-radius: 2px;
    margin-top: 12px;
    background: rgba(255,255,255,.35);
    overflow: hidden;
}
.rp-status-card .sc-bar-fill {
    height: 100%;
    border-radius: 2px;
    background: rgba(255,255,255,.7);
    transition: width 1s ease;
}

/* Status card colours */
.sc-active     { background: linear-gradient(135deg, #4361ee, #3a86ff); color: #fff; }
.sc-completed  { background: linear-gradient(135deg, #11998e, #2ec4b6); color: #fff; }
.sc-defaulted  { background: linear-gradient(135deg, #f77f00, #fcbf49); color: #fff; }
.sc-written-off{ background: linear-gradient(135deg, #6c757d, #adb5bd); color: #fff; }
.sc-pending    { background: linear-gradient(135deg, #f3c623, #ffe066); color: #555; }

/* ── Portfolio detail table ──────────────────────────────────── */
.rp-progress {
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    overflow: hidden;
    min-width: 80px;
}
.rp-progress-bar { height: 100%; border-radius: 5px; transition: width 1s ease; }

/* ── Empty state ─────────────────────────────────────────────── */
.rp-empty {
    text-align: center;
    padding: 50px 20px;
    color: #9aa0b0;
}
.rp-empty i { font-size: 48px; display: block; margin-bottom: 12px; opacity: .4; }
.rp-empty p { font-size: 14px; margin: 0; }
</style>
@endpush

@section('contents')
<div class="page-wrapper">

    {{-- ── Breadcrumb ──────────────────────────────────────────────── --}}
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    របាយការណ៍ និងការវិភាគ
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a>
                            </li>
                            <li class="breadcrumb-item active">របាយការណ៍</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 text-right align-self-center">
                <a href="{{ route('report.index', array_merge(request()->query(), ['export' => 'pdf'])) }}"
                   target="_blank"
                   class="btn btn-sm btn-danger mr-1" id="btn-global-pdf">
                    <i class="mdi mdi-printer mr-1"></i> Print All
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        @php
            $activeTab = request('tab', 'collection');
            $tabBase   = route('report.index');

            $tabs = [
                'collection' => [
                    'label' => 'Collection Report',
                    'icon'  => 'mdi-cash-multiple',
                    'class' => 'tab-icon-collection',
                    'badge' => $repayments->count(),
                ],
                'overdue' => [
                    'label' => 'Overdue Loans',
                    'icon'  => 'mdi-alert-circle-outline',
                    'class' => 'tab-icon-overdue',
                    'badge' => $overdueLoans->count(),
                ],
                'portfolio' => [
                    'label' => 'Portfolio Summary',
                    'icon'  => 'mdi-chart-donut',
                    'class' => 'tab-icon-portfolio',
                    'badge' => null,
                ],
            ];

            $dateFrom = request('date_from', '');
            $dateTo   = request('date_to',   '');
        @endphp

        {{-- ── Tab Bar ─────────────────────────────────────────────── --}}
        <div class="rp-tab-bar">
            @foreach ($tabs as $key => $tab)
                @php
                    $isActive = $activeTab === $key;
                    $queryParams = ['tab' => $key];
                    if ($key === 'collection') {
                        if ($dateFrom) $queryParams['date_from'] = $dateFrom;
                        if ($dateTo)   $queryParams['date_to']   = $dateTo;
                    }
                @endphp
                <a href="{{ route('report.index', $queryParams) }}"
                   class="rp-tab-link {{ $isActive ? 'active' : '' }}"
                   id="tab-btn-{{ $key }}">
                    <span class="tab-icon {{ $tab['class'] }}">
                        <i class="mdi {{ $tab['icon'] }}"></i>
                    </span>
                    {{ $tab['label'] }}
                    @if ($tab['badge'] !== null)
                        <span class="badge badge-{{ $isActive ? 'primary' : 'secondary' }} badge-pill ml-1"
                              style="font-size:10px;">
                            {{ $tab['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- TAB 1 — Collection Report                                  --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        @if ($activeTab === 'collection')
        <div class="rp-tab-panel" id="panel-collection">

            {{-- Filter bar --}}
            <form method="GET" action="{{ route('report.index') }}" id="form-collection">
                <input type="hidden" name="tab" value="collection">
                <div class="rp-filter-bar">
                    <div class="filter-group">
                        <label for="date_from">ចាប់ពីថ្ងៃ (Date From)</label>
                        <input type="date" id="date_from" name="date_from"
                               class="form-control" value="{{ $dateFrom }}">
                    </div>
                    <div class="filter-group">
                        <label for="date_to">ដល់ថ្ងៃ (Date To)</label>
                        <input type="date" id="date_to" name="date_to"
                               class="form-control" value="{{ $dateTo }}">
                    </div>
                    <div class="filter-group" style="justify-content:flex-end;">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn-filter" id="btn-filter-collection">
                            <i class="mdi mdi-magnify"></i> Filter
                        </button>
                    </div>
                    <div class="filter-group ml-auto" style="justify-content:flex-end;">
                        <label>&nbsp;</label>
                        <div class="d-flex gap-2" style="gap:8px;">
                            <a href="{{ route('report.index', array_merge(['tab'=>'collection','export'=>'csv'], $dateFrom ? ['date_from'=>$dateFrom] : [], $dateTo ? ['date_to'=>$dateTo] : [])) }}"
                               class="btn-export-csv" id="btn-csv-collection">
                                <i class="mdi mdi-file-delimited-outline"></i> Export CSV
                            </a>
                            <a href="{{ route('report.index', array_merge(['tab'=>'collection','export'=>'pdf'], $dateFrom ? ['date_from'=>$dateFrom] : [], $dateTo ? ['date_to'=>$dateTo] : [])) }}"
                               target="_blank"
                               class="btn-export-pdf" id="btn-pdf-collection">
                                <i class="mdi mdi-file-pdf-box"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Active filter chip --}}
            @if ($dateFrom || $dateTo)
            <div class="mb-3 d-flex align-items-center" style="gap:8px;">
                <small class="text-muted">Filtered:</small>
                <span class="badge badge-info" style="font-size:12px; padding:5px 12px;">
                    {{ $dateFrom ?: '…' }} → {{ $dateTo ?: 'Today' }}
                </span>
                <a href="{{ route('report.index', ['tab'=>'collection']) }}" class="text-danger" style="font-size:12px;">
                    <i class="mdi mdi-close-circle"></i> Clear
                </a>
            </div>
            @endif

            {{-- Table --}}
            <div class="rp-table-wrap">
                <table class="table rp-table" id="tbl-collection">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Customer</th>
                            <th>Loan Code</th>
                            <th>Date</th>
                            <th class="amount-cell">Amount</th>
                            <th class="amount-cell">Principal</th>
                            <th class="amount-cell">Interest</th>
                            <th class="amount-cell">Late Fee</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($repayments as $i => $r)
                        <tr>
                            <td class="text-muted" style="font-size:12px;">{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $r->customer_name }}</strong>
                            </td>
                            <td><span class="loan-code">{{ $r->loan_code }}</span></td>
                            <td style="white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($r->payment_date)->format('d M Y') }}
                            </td>
                            <td class="amount-cell">
                                <strong>${{ number_format($r->amount, 2) }}</strong>
                            </td>
                            <td class="amount-cell">${{ number_format($r->principal_paid, 2) }}</td>
                            <td class="amount-cell">${{ number_format($r->interest_paid, 2) }}</td>
                            <td class="amount-cell">
                                @if ($r->late_fee_paid > 0)
                                    <span class="amount-danger">${{ number_format($r->late_fee_paid, 2) }}</span>
                                @else
                                    <span class="text-muted">$0.00</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-method">
                                    {{ $r->payment_method ?? '—' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="rp-empty">
                                    <i class="mdi mdi-cash-remove"></i>
                                    <p>No repayments found for the selected period.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if ($repayments->count() > 0)
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right" style="color:#6b7a99;">
                                <i class="mdi mdi-sigma mr-1"></i> TOTALS
                                <small class="ml-1">({{ $repayments->count() }} records)</small>
                            </td>
                            <td class="amount-cell amount-total">${{ number_format($repaymentTotals['amount'], 2) }}</td>
                            <td class="amount-cell amount-total">${{ number_format($repaymentTotals['principal_paid'], 2) }}</td>
                            <td class="amount-cell amount-total">${{ number_format($repaymentTotals['interest_paid'], 2) }}</td>
                            <td class="amount-cell amount-danger">${{ number_format($repaymentTotals['late_fee_paid'], 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

        </div>{{-- /panel-collection --}}
        @endif

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- TAB 2 — Overdue Loans                                      --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        @if ($activeTab === 'overdue')
        <div class="rp-tab-panel" id="panel-overdue">

            {{-- Export bar --}}
            <div class="rp-filter-bar mb-4">
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <span style="font-size:13px; color:#6b7a99; font-weight:600;">
                        <i class="mdi mdi-alert-circle text-danger mr-1"></i>
                        Showing all loans with <code>days_past_due &gt; 0</code>
                    </span>
                </div>
                <div class="filter-group ml-auto" style="justify-content:flex-end;">
                    <label>&nbsp;</label>
                    <a href="{{ route('report.index', ['tab'=>'overdue','export'=>'csv']) }}"
                       class="btn-export-csv" id="btn-csv-overdue">
                        <i class="mdi mdi-file-delimited-outline"></i> Export CSV
                    </a>
                </div>
            </div>

            {{-- Legend --}}
            <div class="d-flex align-items-center mb-3" style="gap:14px; font-size:12px;">
                <span class="text-muted font-weight-bold">DPD Legend:</span>
                <span><span class="rp-badge rp-badge-info">1–15 days</span> Warning</span>
                <span><span class="rp-badge rp-badge-warning">16–30 days</span> High Risk</span>
                <span><span class="rp-badge rp-badge-danger">&gt;30 days</span> Critical</span>
                <span style="margin-left:4px;">
                    <span style="display:inline-block;width:12px;height:12px;background:#fff0f1;border:1px solid #f8d7da;border-radius:2px;"></span>
                    Red row = Critical (&gt;30 days)
                </span>
            </div>

            {{-- Table --}}
            <div class="rp-table-wrap">
                <table class="table rp-table" id="tbl-overdue">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Loan Code</th>
                            <th>Customer</th>
                            <th class="amount-cell">Outstanding</th>
                            <th class="amount-cell">Overdue Amount</th>
                            <th class="text-center">Days Past Due</th>
                            <th>Guarantor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($overdueLoans as $i => $ol)
                            @php $critical = $ol->days_past_due > 30; @endphp
                            <tr class="{{ $critical ? 'overdue-critical' : '' }}">
                                <td class="text-muted" style="font-size:12px;">{{ $i + 1 }}</td>
                                <td><span class="loan-code">{{ $ol->loan_code }}</span></td>
                                <td>
                                    <strong>{{ $ol->customer_name }}</strong>
                                    @if ($critical)
                                        <i class="mdi mdi-alert text-danger ml-1" title="Critical overdue"></i>
                                    @endif
                                </td>
                                <td class="amount-cell">
                                    ${{ number_format($ol->outstanding_balance, 2) }}
                                </td>
                                <td class="amount-cell amount-danger">
                                    ${{ number_format($ol->overdue_amount, 2) }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $dpd = $ol->days_past_due;
                                        $cls = $dpd > 30 ? 'rp-badge-danger'
                                             : ($dpd > 15 ? 'rp-badge-warning'
                                             : 'rp-badge-info');
                                    @endphp
                                    <span class="rp-badge {{ $cls }}">{{ $dpd }}d</span>
                                </td>
                                <td>
                                    @if ($ol->guarantor_name)
                                        <i class="mdi mdi-account-check text-success mr-1"></i>
                                        {{ $ol->guarantor_name }}
                                    @else
                                        <span class="text-muted">
                                            <i class="mdi mdi-account-off-outline mr-1"></i>None
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="rp-empty">
                                    <i class="mdi mdi-check-circle text-success" style="opacity:1; color:#2ec4b6 !important;"></i>
                                    <p>No overdue loans — portfolio is healthy!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>{{-- /panel-overdue --}}
        @endif

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- TAB 3 — Portfolio Summary                                  --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        @if ($activeTab === 'portfolio')
        <div class="rp-tab-panel" id="panel-portfolio">

            @php
                $totalLoans    = collect($portfolio)->sum('loan_count');
                $totalPrincipal= collect($portfolio)->sum('total_principal');

                $statusConfig = [
                    'active'     => ['label' => 'Active',      'card' => 'sc-active',      'bar' => '#4361ee', 'icon' => 'mdi-check-circle-outline'],
                    'completed'  => ['label' => 'Completed',   'card' => 'sc-completed',   'bar' => '#2ec4b6', 'icon' => 'mdi-flag-checkered'],
                    'defaulted'  => ['label' => 'Defaulted',   'card' => 'sc-defaulted',   'bar' => '#f77f00', 'icon' => 'mdi-alert-circle-outline'],
                    'written_off'=> ['label' => 'Written Off',  'card' => 'sc-written-off', 'bar' => '#6c757d', 'icon' => 'mdi-close-circle-outline'],
                    'pending'    => ['label' => 'Pending',     'card' => 'sc-pending',     'bar' => '#f3c623', 'icon' => 'mdi-clock-outline'],
                ];
            @endphp

            {{-- Status Cards --}}
            <div class="rp-portfolio-grid" id="portfolio-cards">
                @foreach ($statusConfig as $status => $cfg)
                    @php
                        $row  = $portfolio[$status] ?? (object)['loan_count'=>0,'total_principal'=>0];
                        $pct  = $totalLoans > 0 ? round(($row->loan_count / $totalLoans) * 100, 1) : 0;
                    @endphp
                    <div class="rp-status-card {{ $cfg['card'] }}" id="card-{{ $status }}">
                        <div class="sc-icon">
                            <i class="mdi {{ $cfg['icon'] }}"></i>
                        </div>
                        <div class="sc-label">{{ $cfg['label'] }}</div>
                        <div class="sc-count">{{ number_format($row->loan_count) }}</div>
                        <div class="sc-principal">
                            ${{ number_format($row->total_principal, 2) }}
                        </div>
                        <div class="sc-bar">
                            <div class="sc-bar-fill" style="width: {{ $pct }}%;" data-width="{{ $pct }}"></div>
                        </div>
                        <div style="font-size:11px; margin-top:4px; opacity:.75;">
                            {{ $pct }}% of total
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Total summary strip --}}
            <div class="d-flex align-items-center justify-content-between mb-4 px-2">
                <div>
                    <span class="text-muted" style="font-size:13px;">Total Portfolio:</span>
                    <strong class="ml-2" style="font-size:18px; color:var(--rp-navy);">
                        {{ number_format($totalLoans) }} Loans
                    </strong>
                    <span class="mx-2 text-muted">|</span>
                    <strong style="font-size:18px; color:var(--rp-navy);">
                        ${{ number_format($totalPrincipal, 2) }}
                    </strong>
                    <span class="text-muted ml-1" style="font-size:12px;">principal disbursed</span>
                </div>
            </div>

            {{-- Detail breakdown table --}}
            <div class="rp-table-wrap">
                <table class="table rp-table" id="tbl-portfolio">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th class="text-center">Loan Count</th>
                            <th class="amount-cell">Total Principal</th>
                            <th class="text-center">% of Count</th>
                            <th style="width:200px;">Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statusConfig as $status => $cfg)
                            @php
                                $row = $portfolio[$status] ?? (object)['loan_count'=>0,'total_principal'=>0];
                                $pct = $totalLoans > 0 ? round(($row->loan_count / $totalLoans) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <span style="display:inline-flex; align-items:center; gap:7px;">
                                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $cfg['bar'] }};display:inline-block;"></span>
                                        <strong>{{ $cfg['label'] }}</strong>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <strong>{{ number_format($row->loan_count) }}</strong>
                                </td>
                                <td class="amount-cell">
                                    ${{ number_format($row->total_principal, 2) }}
                                </td>
                                <td class="text-center">
                                    <span class="rp-badge"
                                          style="background:{{ $cfg['bar'] }}22; color:{{ $cfg['bar'] }};">
                                        {{ $pct }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="rp-progress">
                                        <div class="rp-progress-bar"
                                             style="width:{{ $pct }}%; background:{{ $cfg['bar'] }};"
                                             data-width="{{ $pct }}">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>TOTAL</strong></td>
                            <td class="text-center">{{ number_format($totalLoans) }}</td>
                            <td class="amount-cell">${{ number_format($totalPrincipal, 2) }}</td>
                            <td class="text-center">100%</td>
                            <td>
                                <div class="rp-progress">
                                    <div class="rp-progress-bar"
                                         style="width:100%; background:var(--rp-navy);">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>{{-- /panel-portfolio --}}
        @endif

    </div>{{-- /container-fluid --}}
</div>{{-- /page-wrapper --}}
@endsection

@push('scripts')
<script>
$(function () {
    "use strict";

    // ── Animate progress bars on load ───────────────────────────
    $('[data-width]').each(function () {
        var $el  = $(this);
        var w    = $el.data('width');
        $el.css('width', 0);
        setTimeout(function () { $el.css('width', w + '%'); }, 150);
    });

    // ── DataTables for collection and overdue ────────────────────
    var dtOpts = {
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [],
        language: {
            search:         "ស្វែងរក:",
            lengthMenu:     "បង្ហាញ _MENU_ ជួរ",
            info:           "បង្ហាញ _START_ - _END_ នៃ _TOTAL_",
            infoEmpty:      "គ្មានទិន្នន័យ",
            zeroRecords:    "គ្មានទិន្នន័យ",
            paginate: {
                first:    "ដំបូង",
                last:     "ចុងក្រោយ",
                next:     "បន្ទាប់",
                previous: "មុន",
            }
        }
    };

    if ($('#tbl-collection').length) {
        $('#tbl-collection').DataTable($.extend({}, dtOpts, {
            // disable sorting on last column (method)
            columnDefs: [{ orderable: false, targets: [8] }]
        }));
    }

    if ($('#tbl-overdue').length) {
        $('#tbl-overdue').DataTable($.extend({}, dtOpts, {
            order: [[5, 'desc']], // sort by DPD descending
            columnDefs: [{ orderable: false, targets: [6] }]
        }));
    }
});
</script>
@endpush
