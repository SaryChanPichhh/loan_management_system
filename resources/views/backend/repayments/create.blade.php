@extends('backend.layout.master')

@push('styles')
<style>
    .repay-card { border-radius: 1rem; border: none; }
    .breakdown-box { background: #f8f9fa; border-radius: .75rem; padding: 1.25rem; }
    .breakdown-row { display: flex; justify-content: space-between; padding: .35rem 0; border-bottom: 1px dashed #dee2e6; }
    .breakdown-row:last-child { border-bottom: none; }
    .late-badge { background: #fff3cd; color: #856404; border: 1px solid #ffc107; border-radius: .5rem; padding: .5rem 1rem; }
    .form-control:focus { border-color: #007bff; box-shadow: 0 0 0 .2rem rgba(0,123,255,.15); }
    .input-group-text { background: #f8f9fa; border-right: none; }
    .input-group .form-control { border-left: none; }
</style>
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    កត់ត្រាការបង់ប្រាក់ (Record Repayment)
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.payments', $loan->id) }}">{{ $loan->loan_code }}</a></li>
                        <li class="breadcrumb-item active">បង់ប្រាក់</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>សូមពិនិត្យ:</strong>
                <ul class="mb-0 mt-1 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">

            {{-- LEFT: Payment Form --}}
            <div class="col-lg-7">
                <div class="card repay-card shadow-sm">
                    <div class="card-header py-3">
                        <h5 class="mb-0">
                            <i data-feather="dollar-sign" class="mr-2"></i>
                            ការបង់ប្រាក់ — {{ $loan->loan_code }}
                            <span class="badge badge-success ml-2">Active</span>
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        {{-- Late Fee Warning --}}
                        @if($isLate)
                            <div class="late-badge mb-4 d-flex align-items-center">
                                <i data-feather="alert-triangle" class="mr-2 text-warning"></i>
                                <div>
                                    <strong>ការបង់ប្រាក់ហួសកំណត់!</strong>
                                    Late Fee នឹងត្រូវបានបន្ថែម:
                                    <strong class="text-danger">${{ number_format($lateFeePreview, 2) }}</strong>
                                    (1.5% នៃ Outstanding Balance)
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('repayments.store', $loan->id) }}" method="POST" id="repayment-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-medium">ទឹកប្រាក់បង់ ($) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-success font-weight-bold">$</span>
                                            </div>
                                            <input type="number" id="amount" name="amount"
                                                   class="form-control font-weight-bold text-success"
                                                   step="0.01" min="0.01"
                                                   value="{{ old('amount', $nextSchedule ? $nextSchedule->amount_due : '') }}"
                                                   placeholder="0.00" required>
                                        </div>
                                        @if($nextSchedule)
                                            <small class="text-muted">
                                                ចំនួនបង់ប្រចាំខែ: <strong>${{ number_format($nextSchedule->amount_due, 2) }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-medium">ថ្ងៃបង់ប្រាក់ <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i data-feather="calendar" style="width:15px"></i></span>
                                            </div>
                                            <input type="date" name="payment_date"
                                                   class="form-control"
                                                   value="{{ old('payment_date', date('Y-m-d')) }}"
                                                   max="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-medium">វិធីបង់ <span class="text-danger">*</span></label>
                                        <select name="payment_method" class="form-control custom-select" required>
                                            <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>ជ្រើសរើស...</option>
                                            @foreach(['Cash' => 'សាច់ប្រាក់ (Cash)', 'ABA Bank' => 'ABA Bank Transfer', 'Acleda' => 'អេស៊ីលីដា (Acleda)', 'Wing' => 'វីង (Wing Money)', 'Other' => 'ផ្សេងៗ (Other)'] as $val => $label)
                                                <option value="{{ $val }}" {{ old('payment_method') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-medium">Reference / Transaction No.</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i data-feather="hash" style="width:15px"></i></span>
                                            </div>
                                            <input type="text" name="reference_number"
                                                   class="form-control"
                                                   value="{{ old('reference_number') }}"
                                                   placeholder="ប្រសិនបើមាន...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-medium">កំណត់ចំណាំ</label>
                                <textarea name="notes" class="form-control" rows="2"
                                          placeholder="ព័ត៌មានបន្ថែម...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <a href="{{ route('loans.payments', $loan->id) }}" class="btn btn-light border">
                                    <i data-feather="arrow-left" style="width:15px"></i> បោះបង់
                                </a>
                                <button type="submit" class="btn btn-primary px-5"
                                        onclick="return confirm('បញ្ជាក់ការបង់ប្រាក់ $' + document.getElementById('amount').value + '?')">
                                    <i data-feather="check-circle" style="width:15px"></i> បញ្ជាក់ការបង់ប្រាក់
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            {{-- RIGHT: Loan Summary + Next Installment --}}
            <div class="col-lg-5">

                {{-- Loan summary card --}}
                <div class="card repay-card shadow-sm mb-3">
                    <div class="card-header py-3">
                        <h6 class="mb-0"><i data-feather="info" class="mr-2"></i>ព័ត៌មានកម្ចី</h6>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted">អ្នកខ្ចី</td>
                                <td class="font-weight-bold">{{ $loan->customer->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Principal</td>
                                <td>${{ number_format($loan->principal_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Outstanding</td>
                                <td class="text-danger font-weight-bold">
                                    ${{ number_format($loan->account->outstanding_balance ?? 0, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">ការប្រាក់</td>
                                <td>{{ $loan->interest_rate }}%</td>
                            </tr>
                            <tr>
                                <td class="text-muted">រយៈពេល</td>
                                <td>{{ $loan->duration_months }} ខែ</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Next installment card --}}
                @if($nextSchedule)
                <div class="card repay-card shadow-sm">
                    <div class="card-header py-3">
                        <h6 class="mb-0">
                            <i data-feather="calendar" class="mr-2"></i>
                            ការបង់ប្រាក់ខែ #{{ $nextSchedule->installment_number }}
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="breakdown-box">
                            <div class="breakdown-row">
                                <span class="text-muted">ថ្ងៃគ្រប់កំណត់</span>
                                <strong>{{ $nextSchedule->due_date }}</strong>
                            </div>
                            <div class="breakdown-row">
                                <span class="text-muted">Grace Period End</span>
                                <strong>{{ $nextSchedule->grace_period_end_date }}</strong>
                            </div>
                            <div class="breakdown-row">
                                <span class="text-muted">Principal Due</span>
                                <span>${{ number_format($nextSchedule->principal_due, 2) }}</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="text-muted">Interest Due</span>
                                <span>${{ number_format($nextSchedule->interest_due, 2) }}</span>
                            </div>
                            @if($isLate)
                            <div class="breakdown-row">
                                <span class="text-danger font-weight-bold">Late Fee (1.5%)</span>
                                <span class="text-danger font-weight-bold">${{ number_format($lateFeePreview, 2) }}</span>
                            </div>
                            @endif
                            <div class="breakdown-row" style="font-size:1.05rem;">
                                <span class="font-weight-bold">សរុបត្រូវបង់</span>
                                <strong class="text-primary">
                                    ${{ number_format($nextSchedule->amount_due + ($isLate ? $lateFeePreview : 0), 2) }}
                                </strong>
                            </div>
                            @if($nextSchedule->amount_paid > 0)
                            <div class="breakdown-row">
                                <span class="text-muted">បានបង់រួច</span>
                                <span class="text-success">${{ number_format($nextSchedule->amount_paid, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        <span class="badge {{ $nextSchedule->status === 'partial' ? 'badge-warning' : 'badge-secondary' }} mt-2">
                            {{ $nextSchedule->status }}
                        </span>
                        @if($isLate)
                            <span class="badge badge-danger mt-2">ហួសកំណត់ — {{ \Carbon\Carbon::parse($nextSchedule->grace_period_end_date)->diffInDays(now()) }} ថ្ងៃ</span>
                        @endif
                    </div>
                </div>
                @else
                    <div class="alert alert-success">
                        <i data-feather="check-circle"></i> <strong>លុប! គ្មានការបង់ប្រាក់ដែលនៅជំពាក់!</strong>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof feather !== 'undefined') feather.replace();
    });
</script>
@endpush
