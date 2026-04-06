<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SetecLoan — Print Report</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #222;
            background: #fff;
            padding: 20px 30px;
        }

        /* ── Header ─────────────────────────────────────── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #1a3a6b;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .report-header .company-name {
            font-size: 22px;
            font-weight: 700;
            color: #1a3a6b;
            letter-spacing: 1px;
        }
        .report-header .company-name span {
            color: #d4a843;
        }
        .report-header .meta {
            text-align: right;
            color: #555;
            font-size: 11px;
            line-height: 1.6;
        }

        /* ── Section title ──────────────────────────────── */
        .section-title {
            background: #1a3a6b;
            color: #fff;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            margin: 20px 0 8px;
            border-radius: 3px;
        }

        /* ── Tables ─────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 10px;
        }
        th {
            background: #e8edf6;
            color: #1a3a6b;
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #c5cfe0;
        }
        th.text-right, td.text-right { text-align: right; }
        th.text-center, td.text-center { text-align: center; }

        td {
            padding: 5px 8px;
            border: 1px solid #dde3ef;
        }
        tr:nth-child(even) td { background: #f7f9fd; }

        tfoot td {
            background: #dce6f5;
            font-weight: 700;
            border: 1px solid #c5cfe0;
        }

        /* ── Badges / tags ──────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-danger    { background: #f8d7da; color: #721c24; }
        .badge-warning   { background: #fff3cd; color: #856404; }
        .badge-info      { background: #d1ecf1; color: #0c5460; }

        /* ── Summary cards (portfolio) ──────────────────── */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 10px;
        }
        .portfolio-card {
            border: 1px solid #c5cfe0;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
        .portfolio-card .label { font-size: 10px; color: #555; margin-bottom: 4px; }
        .portfolio-card .count { font-size: 20px; font-weight: 700; color: #1a3a6b; }
        .portfolio-card .total { font-size: 10px; color: #777; }

        /* ── Progress bar ───────────────────────────────── */
        .prog-bar-track {
            height: 10px;
            background: #e0e7f0;
            border-radius: 5px;
            overflow: hidden;
        }
        .prog-bar-fill {
            height: 100%;
            background: #1a3a6b;
            border-radius: 5px;
        }

        /* ── Footer ─────────────────────────────────────── */
        .report-footer {
            margin-top: 28px;
            border-top: 1px solid #c5cfe0;
            padding-top: 8px;
            font-size: 10px;
            color: #888;
            display: flex;
            justify-content: space-between;
        }

        /* ── Print controls (screen only) ───────────────── */
        .print-controls {
            text-align: right;
            margin-bottom: 14px;
        }
        .print-controls button {
            background: #1a3a6b;
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 8px;
        }
        .print-controls button.close-btn {
            background: #6c757d;
        }

        @media print {
            .print-controls { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    {{-- Print controls (hidden on print) --}}
    <div class="print-controls">
        <button class="close-btn" onclick="window.close()">✕ បិទ</button>
        <button onclick="window.print()">🖨 បោះពុម្ព / PDF</button>
    </div>

    {{-- Header --}}
    <div class="report-header">
        <div>
            <div class="company-name">Setec<span>Loan</span></div>
            <div style="font-size:11px; color:#555; margin-top:2px;">
                ប្រព័ន្ធគ្រប់គ្រងឥណទាន
            </div>
        </div>
        <div class="meta">
            <strong>REPORT DATE:</strong> {{ now()->format('d/m/Y H:i') }}<br>
            @if($dateFrom || $dateTo)
                <strong>FILTER:</strong> {{ $dateFrom ?? '—' }} → {{ $dateTo ?? now()->format('Y-m-d') }}<br>
            @endif
            <strong>GENERATED BY:</strong> {{ auth()->user()->name ?? 'System' }}
        </div>
    </div>

    {{-- ═════════════════════════════════════════════ --}}
    {{-- 1. Repayment Collection Report               --}}
    {{-- ═════════════════════════════════════════════ --}}
    <div class="section-title">
        💰 របាយការណ៍ប្រមូលទឹកប្រាក់សង (Repayment Collection)
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ឈ្មោះអតិថិជន</th>
                <th>លេខកម្ចី</th>
                <th>ថ្ងៃបង់</th>
                <th class="text-right">ចំនួន</th>
                <th class="text-right">ដើម</th>
                <th class="text-right">ការប្រាក់</th>
                <th class="text-right">ការផាក</th>
                <th>វិធីបង់</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($repayments as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->customer_name }}</td>
                    <td>{{ $r->loan_code }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->payment_date)->format('d/m/Y') }}</td>
                    <td class="text-right">${{ number_format($r->amount, 2) }}</td>
                    <td class="text-right">${{ number_format($r->principal_paid, 2) }}</td>
                    <td class="text-right">${{ number_format($r->interest_paid, 2) }}</td>
                    <td class="text-right">${{ number_format($r->late_fee_paid, 2) }}</td>
                    <td>{{ $r->payment_method ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center" style="color:#888; padding:10px;">គ្មានទិន្នន័យ</td></tr>
            @endforelse
        </tbody>
        @if ($repayments->count())
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">សរុប</td>
                <td class="text-right">${{ number_format($repaymentTotals['amount'], 2) }}</td>
                <td class="text-right">${{ number_format($repaymentTotals['principal_paid'], 2) }}</td>
                <td class="text-right">${{ number_format($repaymentTotals['interest_paid'], 2) }}</td>
                <td class="text-right">${{ number_format($repaymentTotals['late_fee_paid'], 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- ═════════════════════════════════════════════ --}}
    {{-- 2. Overdue Loans                             --}}
    {{-- ═════════════════════════════════════════════ --}}
    <div class="section-title" style="background:#c0392b;">
        ⚠️ របាយការណ៍កម្ចីហួសកំណត់ (Overdue Loans)
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>លេខកម្ចី</th>
                <th>ឈ្មោះអតិថិជន</th>
                <th class="text-right">សមតុល្យ</th>
                <th class="text-right">ប្រាក់ហួស</th>
                <th class="text-center">ថ្ងៃហួស</th>
                <th>អ្នកធានា</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($overdueLoans as $i => $ol)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $ol->loan_code }}</td>
                    <td>{{ $ol->customer_name }}</td>
                    <td class="text-right">${{ number_format($ol->outstanding_balance, 2) }}</td>
                    <td class="text-right" style="color:#c0392b; font-weight:bold;">
                        ${{ number_format($ol->overdue_amount, 2) }}
                    </td>
                    <td class="text-center">
                        @php
                            $dpd = $ol->days_past_due;
                            $cls = $dpd > 30 ? 'badge-danger' : ($dpd > 15 ? 'badge-warning' : 'badge-info');
                        @endphp
                        <span class="badge {{ $cls }}">{{ $dpd }} ថ្ងៃ</span>
                    </td>
                    <td>{{ $ol->guarantor_name ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center" style="color:#888; padding:10px;">គ្មានកម្ចីហួសកំណត់</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ═════════════════════════════════════════════ --}}
    {{-- 3. Loan Portfolio Summary                    --}}
    {{-- ═════════════════════════════════════════════ --}}
    <div class="section-title" style="background:#117a8b;">
        📊 សង្ខេបផលប័ត្រកម្ចី (Loan Portfolio Summary)
    </div>

    @php
        $labelMap = [
            'pending'    => 'កំពុងរង់ចាំ',
            'active'     => 'កំពុងដំណើរការ',
            'completed'  => 'បានបញ្ចប់',
            'defaulted'  => 'មិនបានសង',
            'written_off'=> 'ចាត់ទុកជាខាត',
        ];
        $totalLoans = collect($portfolio)->sum('loan_count');
    @endphp

    <div class="portfolio-grid">
        @foreach ($portfolio as $status => $row)
            <div class="portfolio-card">
                <div class="label">{{ $labelMap[$status] }}</div>
                <div class="count">{{ number_format($row->loan_count) }}</div>
                <div class="total">${{ number_format($row->total_principal, 2) }}</div>
            </div>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th>ស្ថានភាព</th>
                <th class="text-center">ចំនួន</th>
                <th class="text-right">ដើមសរុប (USD)</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($portfolio as $status => $row)
                @php
                    $pct = $totalLoans > 0 ? round(($row->loan_count / $totalLoans) * 100, 1) : 0;
                @endphp
                <tr>
                    <td>{{ $labelMap[$status] }}</td>
                    <td class="text-center">{{ number_format($row->loan_count) }}</td>
                    <td class="text-right">${{ number_format($row->total_principal, 2) }}</td>
                    <td>
                        <div class="prog-bar-track">
                            <div class="prog-bar-fill" style="width:{{ $pct }}%;"></div>
                        </div>
                        <small>{{ $pct }}%</small>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>សរុប</td>
                <td class="text-center">{{ number_format($totalLoans) }}</td>
                <td class="text-right">${{ number_format(collect($portfolio)->sum('total_principal'), 2) }}</td>
                <td>100%</td>
            </tr>
        </tfoot>
    </table>

    {{-- Footer --}}
    <div class="report-footer">
        <span>SetecLoan — ប្រព័ន្ធគ្រប់គ្រងឥណទាន</span>
        <span>បោះពុម្ពថ្ងៃ {{ now()->format('d/m/Y') }}</span>
    </div>

</body>
</html>
