<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ str_pad($repayment->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
            position: relative;
        }
        .no-print {
            text-align: right;
            margin-bottom: 20px;
            position: absolute;
            top: 30px;
            right: 30px;
        }
        .btn-print {
            padding: 8px 16px;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .header-section {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header-section h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-section h2 {
            margin: 10px 0 5px;
            font-size: 20px;
            color: #555;
            text-transform: uppercase;
        }
        .receipt-no {
            font-size: 16px;
            font-weight: bold;
            color: #d9534f;
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
            padding: 10px 0;
            vertical-align: top;
        }
        .info-col p {
            margin: 6px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 140px;
            color: #555;
        }

        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 15px;
        }
        .breakdown-table th, .breakdown-table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
        }
        .breakdown-table th {
            text-align: left;
            background-color: #f9f9f9;
            color: #333;
            width: 70%;
        }
        .breakdown-table td {
            text-align: right;
        }
        .total-row th, .total-row td {
            font-size: 18px;
            font-weight: bold;
            background-color: #f2f2f2;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
        }

        .footer-info {
            background-color: #fdfdfd;
            border: 1px solid #eee;
            padding: 15px;
            margin-bottom: 40px;
            border-radius: 4px;
        }
        .footer-info p {
            margin: 8px 0;
            font-size: 15px;
        }
        
        .thank-you {
            text-align: center;
            font-style: italic;
            font-size: 16px;
            margin-bottom: 50px;
            color: #666;
        }

        .signature-section {
            margin-top: 60px;
            text-align: left;
        }
        .signature-box {
            display: inline-block;
            width: 300px;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 10px;
            height: 40px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                background-color: white;
            }
            .container {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Print</button>
    </div>

    <div class="header-section">
        <h1>{{ config('app.name', 'SetecLoan') }}</h1>
        <h2>Official Payment Receipt</h2>
        <div class="receipt-no">Receipt No: #{{ str_pad($repayment->id, 6, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="info-grid">
        <!-- Left Column -->
        <div class="info-col">
            <p><span class="info-label">Customer Name:</span> {{ $repayment->loan->customer->full_name ?? $repayment->loan->customer->name ?? 'N/A' }}</p>
            <p><span class="info-label">Phone:</span> {{ $repayment->loan->customer->phone ?? 'N/A' }}</p>
            <p><span class="info-label">Loan Code:</span> {{ $repayment->loan->loan_code }}</p>
            <p><span class="info-label">Payment Date:</span> {{ \Carbon\Carbon::parse($repayment->payment_date)->format('d F Y') }}</p>
            @if($repayment->schedule)
                <p><span class="info-label">Installment:</span> Month #{{ $repayment->schedule->installment_number }}</p>
            @endif
        </div>
        
        <!-- Right Column -->
        <div class="info-col">
            <p><span class="info-label">Payment Method:</span> {{ $repayment->payment_method }}</p>
            <p><span class="info-label">Reference Number:</span> {{ $repayment->reference_number ?? 'N/A' }}</p>
            <p><span class="info-label">Status:</span> <span style="text-transform: capitalize;">{{ $repayment->status }}</span></p>
            <p><span class="info-label">Received By:</span> {{ $repayment->receivedBy->name ?? 'System' }}</p>
        </div>
    </div>

    <!-- Payment Breakdown -->
    <table class="breakdown-table">
        <tbody>
            <tr>
                <th>Principal Paid</th>
                <td>${{ number_format($repayment->principal_paid, 2) }}</td>
            </tr>
            <tr>
                <th>Interest Paid</th>
                <td>${{ number_format($repayment->interest_paid, 2) }}</td>
            </tr>
            @if($repayment->late_fee_paid > 0 || $repayment->late_fee_applied)
            <tr>
                <th>Late Fee Paid</th>
                <td>${{ number_format($repayment->late_fee_paid, 2) }}</td>
            </tr>
            @endif
            @if($repayment->penalty_paid > 0)
            <tr>
                <th>Penalty / Overpayment</th>
                <td>${{ number_format($repayment->penalty_paid, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <th>Total Paid</th>
                <td>${{ number_format($repayment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer Area -->
    @php
        $nextPending = null;
        if ($repayment->loan && $repayment->loan->schedules) {
            $nextPending = $repayment->loan->schedules
                ->whereIn('status', ['pending', 'partial'])
                ->sortBy('installment_number')
                ->first();
        }
        $nextDueDateStr = $nextPending 
            ? \Carbon\Carbon::parse($nextPending->due_date)->format('d F Y') 
            : 'N/A (Fully Paid)';
    @endphp

    <div class="footer-info">
        <p><strong>Outstanding Balance Remaining:</strong> <span style="color: #d9534f;">${{ number_format($repayment->loan->account->outstanding_balance ?? 0, 2) }}</span></p>
        <p><strong>Next Due Date:</strong> {{ $nextDueDateStr }}</p>
    </div>

    <div class="thank-you">
        "Thank you for your payment"
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>Loan Officer Signature</strong><br>
            <span style="color: #555; font-size: 14px;">{{ $repayment->receivedBy->name ?? 'Loan Officer' }}</span>
        </div>
    </div>

</div>

</body>
</html>
