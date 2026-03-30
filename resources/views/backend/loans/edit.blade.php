@extends('backend.layout.master')

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">កែសម្រួលកម្ចី</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.show', $loan->id) }}">{{ $loan->loan_code }}</a></li>
                        <li class="breadcrumb-item active">កែសម្រួល</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-warning">
            <i data-feather="alert-circle"></i>
            អាចកែសម្រួលបានតែនៅពេលស្ថានភាព <strong>Pending</strong> ប៉ុណ្ណោះ។
            អត្រាការប្រាក់ (Interest Rate) ត្រូវបាន Snapshot ពី Product និងមិនអាចផ្លាស់ប្តូរ។
        </div>

        <div class="row">
            <div class="col-lg-8">
                <form id="editLoanForm" action="{{ route('loans.update', $loan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Customer & Product (read-only) --}}
                    <div class="card">
                        <div class="card-header bg-secondary text-white py-2">
                            <h5 class="mb-0">ព័ត៌មានអតិថិជន និង Product (មិនអាចផ្លាស់ប្ដូរ)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>អ្នកខ្ចី</label>
                                        <input type="text" class="form-control bg-light" readonly
                                               value="{{ $loan->customer->name ?? 'N/A' }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loan Product</label>
                                        <input type="text" class="form-control bg-light" readonly
                                               value="{{ $loan->product->name ?? '—' }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>ការប្រាក់ (Snapshot)</label>
                                    <input type="text" class="form-control bg-light" readonly value="{{ $loan->interest_rate }}%" />
                                </div>
                                <div class="col-md-4">
                                    <label>Loan Code</label>
                                    <input type="text" class="form-control bg-light" readonly value="{{ $loan->loan_code }}" />
                                </div>
                                <div class="col-md-4">
                                    <label>សំណើ</label>
                                    <input type="text" class="form-control bg-light" readonly
                                           value="{{ $loan->application->application_code ?? '—' }}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Editable Loan Terms --}}
                    <div class="card">
                        <div class="card-header bg-primary text-white py-2">
                            <h5 class="mb-0">លក្ខខណ្ឌកម្ចី</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_principal_amount">ចំនួនទឹកប្រាក់កម្ចី ($) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="edit_principal_amount"
                                               name="principal_amount" min="0" step="0.01"
                                               value="{{ old('principal_amount', $loan->principal_amount) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_disbursed_amount">ចំនួនបានចាញ់ ($)</label>
                                        <input type="number" class="form-control" id="edit_disbursed_amount"
                                               name="disbursed_amount" min="0" step="0.01"
                                               value="{{ old('disbursed_amount', $loan->disbursed_amount) }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_duration_months">រយៈពេល (ខែ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_duration_months"
                                       name="duration_months" min="1"
                                       value="{{ old('duration_months', $loan->duration_months) }}" required />
                                @if($loan->product)
                                    <small class="text-muted">អតិបរមា: {{ $loan->product->max_term_months }} ខែ</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="edit_purpose">គោលបំណងកម្ចី</label>
                                <input type="text" class="form-control" id="edit_purpose" name="purpose"
                                       value="{{ old('purpose', $loan->purpose) }}" maxlength="500" />
                            </div>
                            <div class="form-group">
                                <label for="edit_note">ចំណាំ</label>
                                <textarea class="form-control" id="edit_note" name="note" rows="2">{{ old('note', $loan->note) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="card">
                        <div class="card-header bg-warning text-dark py-2">
                            <h5 class="mb-0">ថ្ងៃខែ</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_start_date">ថ្ងៃចាប់ផ្តើម <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="edit_start_date" name="start_date"
                                               value="{{ old('start_date', optional($loan->start_date)->toDateString()) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ថ្ងៃបញ្ចប់ (Auto)</label>
                                        <input type="date" class="form-control bg-light" id="edit_end_date"
                                               readonly value="{{ optional($loan->end_date)->toDateString() }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Grace Period End (Auto)</label>
                                        <input type="date" class="form-control bg-light" id="edit_grace_end"
                                               readonly value="{{ optional($loan->grace_period_end_date)->toDateString() }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_first_payment_date">ថ្ងៃទូទាត់ដំបូង <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="edit_first_payment_date"
                                               name="first_payment_date"
                                               value="{{ old('first_payment_date', optional($loan->first_payment_date)->toDateString()) }}" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> ធ្វើបច្ចុប្បន្នភាព
                            </button>
                            <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-secondary ml-2">
                                <i data-feather="x"></i> បោះបង់
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Right: Live Calculator --}}
            <div class="col-lg-4">
                <div class="card sticky-top" style="top:80px;">
                    <div class="card-header py-2">
                        <h5 class="mb-0">ការគណនា</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td>ប្រាក់បង់ប្រចាំខែ</td>
                                <td class="text-right"><strong class="text-primary" id="edit_monthly">$0.00</strong></td>
                            </tr>
                            <tr>
                                <td>ការប្រាក់សរុប</td>
                                <td class="text-right"><strong class="text-info" id="edit_interest">$0.00</strong></td>
                            </tr>
                            <tr>
                                <td>ទឹកប្រាក់សរុបត្រូវសង</td>
                                <td class="text-right"><strong class="text-success" id="edit_total">$0.00</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    "use strict";
    $(".preloader").fadeOut();

    const GRACE_DAYS = {{ $loan->product?->grace_period_days ?? 3 }};
    const RATE       = {{ $loan->interest_rate }};

    function recalc() {
        const P = parseFloat($("#edit_principal_amount").val()) || 0;
        const r = RATE / 100 / 12;
        const n = parseInt($("#edit_duration_months").val()) || 0;
        if (!P || !n) return;
        let monthly = r === 0 ? P/n : (P * r * Math.pow(1+r,n)) / (Math.pow(1+r,n) - 1);
        const total = monthly * n;
        $("#edit_monthly").text("$" + monthly.toFixed(2));
        $("#edit_interest").text("$" + (total - P).toFixed(2));
        $("#edit_total").text("$" + total.toFixed(2));
    }

    function updateDates() {
        const start = $("#edit_start_date").val();
        const n = parseInt($("#edit_duration_months").val()) || 0;
        if (!start) return;
        const d = new Date(start);
        d.setMonth(d.getMonth() + n);
        $("#edit_end_date").val(d.toISOString().split('T')[0]);

        const g = new Date(start);
        g.setDate(g.getDate() + GRACE_DAYS);
        $("#edit_grace_end").val(g.toISOString().split('T')[0]);
    }

    $("#edit_principal_amount, #edit_duration_months").on("input", function() { recalc(); updateDates(); });
    $("#edit_start_date").on("change", updateDates);

    recalc();
    if (typeof feather !== "undefined") feather.replace();
});
</script>
@endpush
