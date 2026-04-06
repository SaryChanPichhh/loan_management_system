<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Repayment Schedule - {{ $loan->loan_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .no-print {
            text-align: right;
            margin-bottom: 20px;
        }
        .btn-print {
            padding: 10px 20px;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .header-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .header-section h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header-section h2 {
            margin: 5px 0 0;
            font-size: 18px;
            color: #555;
            text-transform: uppercase;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
            border: 1px solid #eee;
        }
        .info-col p {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 130px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .table-footer {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .summary-box {
            float: right;
            width: 350px;
            border: 1px solid #333;
            padding: 15px;
            margin-bottom: 40px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .summary-total {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
            padding-top: 8px;
            margin-top: 8px;
        }
        .signature-section {
            clear: both;
            display: table;
            width: 100%;
            margin-top: 80px;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-line {
            width: 250px;
            border-bottom: 1px solid #333;
            margin: 0 auto;
            margin-bottom: 10px;
        }
        .date-line {
            width: 250px;
            border-bottom: 1px solid #333;
            margin: 0 auto;
            margin-top: 30px;
            margin-bottom: 10px;
        }
        /* Clearfix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>

<div class="container clearfix">
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Print Schedule</button>
    </div>

    <div class="header-section">
        <h1>{{ config('app.name', 'SetecLoan') }}</h1>
        <h2>Loan Repayment Schedule</h2>
    </div>

    <div class="info-grid">
        <div class="info-col" style="border-right: none;">
            <p><span class="info-label">Loan Code:</span> {{ $loan->loan_code }}</p>
            <p><span class="info-label">Customer Name:</span> {{ $loan->customer->full_name ?? $loan->customer->name ?? 'N/A' }}</p>
            <p><span class="info-label">Phone:</span> {{ $loan->customer->phone ?? 'N/A' }}</p>
            <p><span class="info-label">Address:</span> {{ $loan->customer->address ?? 'N/A' }}</p>
        </div>
        <div class="info-col">
            <p><span class="info-label">Product Name:</span> {{ $loan->product->name ?? 'N/A' }}</p>
            <p><span class="info-label">Principal Amount:</span> ${{ number_format($loan->principal_amount, 2) }}</p>
            <p><span class="info-label">Interest Rate:</span> {{ $loan->interest_rate }}%</p>
            <p><span class="info-label">Duration:</span> {{ $loan->duration_months }} Months</p>
            <p><span class="info-label">Start Date:</span> {{ \Carbon\Carbon::parse($loan->start_date)->format('d-M-Y') }}</p>
            <p><span class="info-label">End Date:</span> {{ \Carbon\Carbon::parse($loan->end_date)->format('d-M-Y') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Due Date</th>
                <th>Principal</th>
                <th>Interest</th>
                <th>Late Fee</th>
                <th>Total Due</th>
                <th>Balance After Payment</th>
            </tr>
        </thead>
        <tbody>
            @php
                $runningBalance = $loan->principal_amount;
                $sumPrincipal = 0;
                $sumInterest = 0;
                $sumLateFee = 0;
                $sumTotalDue = 0;
            @endphp
            @foreach($loan->schedules->sortBy('installment_number') as $schedule)
                @php
                    $runningBalance -= $schedule->principal_due;
                    if ($runningBalance < 0.01) $runningBalance = 0; // Prevent -0.00 rendering
                    
                    $sumPrincipal += $schedule->principal_due;
                    $sumInterest += $schedule->interest_due;
                    $sumLateFee += $schedule->late_fee_due;
                    $sumTotalDue += $schedule->amount_due;
                @endphp
                <tr>
                    <td>{{ $schedule->installment_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->due_date)->format('d-M-Y') }}</td>
                    <td>${{ number_format($schedule->principal_due, 2) }}</td>
                    <td>${{ number_format($schedule->interest_due, 2) }}</td>
                    <td>${{ number_format($schedule->late_fee_due, 2) }}</td>
                    <td>${{ number_format($schedule->amount_due, 2) }}</td>
                    <td>${{ number_format($runningBalance, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-footer">
                <td colspan="2" style="text-align: right; padding-right: 10px;">Totals</td>
                <td>${{ number_format($sumPrincipal, 2) }}</td>
                <td>${{ number_format($sumInterest, 2) }}</td>
                <td>${{ number_format($sumLateFee, 2) }}</td>
                <td>${{ number_format($sumTotalDue, 2) }}</td>
                <td>-</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <div class="summary-row">
            <span>Total Loan Amount:</span>
            <span>${{ number_format($loan->principal_amount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Total Interest Payable:</span>
            <span>${{ number_format($sumInterest, 2) }}</span>
        </div>
        <div class="summary-row summary-total">
            <span>Grand Total Repayable:</span>
            <span>${{ number_format($sumTotalDue, 2) }}</span>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>Customer Signature</strong>
            <p style="font-size: 13px; color: #555; margin-top: 5px;">{{ $loan->customer->full_name ?? $loan->customer->name ?? 'Name: __________' }}</p>
            
            <div class="date-line"></div>
            <strong>Date</strong>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>Loan Officer Signature</strong>
            <p style="font-size: 13px; color: #555; margin-top: 5px;">{{ $loan->createdBy->name ?? 'Name: __________' }}</p>
            
            <div class="date-line"></div>
            <strong>Date</strong>
        </div>
    </div>
</div>

</body>
</html>
