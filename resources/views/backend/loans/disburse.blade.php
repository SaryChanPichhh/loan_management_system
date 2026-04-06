@extends('backend.layout.master')

@section('title', 'Disburse Loan Allocation')

@section('contents')
<div class="page-wrapper">
    {{-- ── BREADCRUMB ─────────────────────────────────────────────────── --}}
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    Loan Disbursement
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.show', $loan->id) }}">{{ $loan->loan_code }}</a></li>
                        <li class="breadcrumb-item active">Disburse</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                {{-- Banner/Header --}}
                <div class="card shadow-sm border-0 mb-4 p-4" style="background: linear-gradient(135deg, #1e2433 0%, #1a1f2c 100%); border-radius: 12px;">
                    <div class="d-flex align-items-center justify-content-between mb-0">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-hand-holding-usd text-warning mr-2"></i> Disbursement Details
                        </h5>
                        <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-arrow-left"></i> Back to Loan
                        </a>
                    </div>
                </div>

                <div class="card border-0 mb-4" style="border-radius: 12px; border-left: 5px solid #d4af37 !important;">
                    <div class="card-body bg-light">
                        <h6 class="text-muted"><i class="fas fa-file-invoice-dollar mr-2"></i> Loan Summary</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Customer Name:</strong> <br>
                                <span class="text-dark">{{ $loan->customer->name }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Principal Amount:</strong> <br>
                                <span class="text-primary font-weight-bold">${{ number_format($loan->principal_amount, 2) }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Interest Rate:</strong> <br>
                                <span class="text-dark">{{ number_format($loan->interest_rate, 2) }}% / month</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Duration:</strong> <br>
                                <span class="text-dark">{{ $loan->duration_months }} Months</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($requiresCollateral && !$hasCollateral)
                    <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Action Required:</strong> Collateral is required for loans above $5,000. 
                        Please <a href="{{ route('loans.collaterals.index', $loan->id) }}" class="font-weight-bold text-dark text-decoration-underline">add collateral</a> first before disbursing.
                    </div>
                @endif

                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h6 class="mb-4"><i class="fas fa-money-check-alt text-success mr-2"></i> Payment Information</h6>
                        
                        <form action="{{ route('loans.disburse', $loan->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="amount" class="font-weight-bold">Disbursement Amount ($) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount', $loan->principal_amount) }}" required>
                                    @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="disbursed_at" class="font-weight-bold">Disbursement Date <span class="text-danger">*</span></label>
                                    <input type="date" name="disbursed_at" id="disbursed_at" class="form-control" value="{{ old('disbursed_at', now()->format('Y-m-d')) }}" required>
                                    @error('disbursed_at') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="method" class="font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                                    <select name="method" id="method" class="form-control" required>
                                        <option value="CASH" {{ old('method') == 'CASH' ? 'selected' : '' }}>Cash</option>
                                        <option value="BANK_TRANSFER" {{ old('method') == 'BANK_TRANSFER' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="MOBILE_MONEY" {{ old('method') == 'MOBILE_MONEY' ? 'selected' : '' }}>Mobile Money (Bakong, ABA PAY, etc)</option>
                                        <option value="CHEQUE" {{ old('method') == 'CHEQUE' ? 'selected' : '' }}>Cheque</option>
                                    </select>
                                    @error('method') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row bg-light rounded p-3 mb-3 mx-0">
                                <div class="col-md-6 form-group mb-md-0">
                                    <label for="bank_name">Bank/Provider Name (Optional)</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name') }}" placeholder="e.g. ABA Bank">
                                    @error('bank_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6 form-group mb-0">
                                    <label for="account_number">Bank Account / Wallet Number (Optional)</label>
                                    <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number') }}" placeholder="e.g. 000 000 000">
                                    @error('account_number') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="reference_number">Transaction Reference Number (Optional)</label>
                                <input type="text" name="reference_number" id="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="Leave blank to auto-generate">
                                @error('reference_number') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="notes">Disbursement Notes (Optional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Enter any extra details or memos...">{{ old('notes') }}</textarea>
                                @error('notes') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <hr>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning px-4" style="font-weight: 600;" {{ ($requiresCollateral && !$hasCollateral) ? 'disabled' : '' }}>
                                    <i class="fas fa-check-circle mr-2"></i> Confirm Disbursement
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>{{-- /container-fluid --}}
</div>{{-- /page-wrapper --}}
@endsection
