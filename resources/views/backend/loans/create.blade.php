@extends('backend.layout.master')

@push('styles')
<style>
    .eligibility-panel { transition: all 0.3s ease; }
    .check-item { padding: 4px 0; font-size: 0.85rem; }
    .check-item .dot { width:10px; height:10px; border-radius:50%; display:inline-block; margin-right:6px; }
    .dot-ok  { background:#28a745; }
    .dot-err { background:#dc3545; }
    .dot-na  { background:#adb5bd; }
    .product-info-box { background:#f8f9fa; border-radius:6px; padding:10px 14px; font-size:0.85rem; }
    .threshold-warn { color:#e67e22; font-weight:600; }
</style>
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row"> 
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">បង្កើតកម្ចីថ្មី</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item active">បង្កើតថ្មី</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        {{-- Business Rule Validation Errors --}}
        @if(session('validation_errors'))
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i data-feather="alert-circle" class="mr-1"></i> ការផ្ទៀងផ្ទាត់ច្បាប់អាជីវកម្មបរាជ័យ</strong>
                <ul class="mb-0 mt-2">
                    @foreach(session('validation_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Pre-fill banner from approved application --}}
        @if($application ?? null)
            <div class="alert alert-info">
                <i data-feather="link" class="mr-1"></i>
                កម្ចីនេះត្រូវបង្កើតពីពាក្យស្នើសុំ
                <strong>{{ $application->application_code }}</strong>
                — អ្នកខ្ចី: <strong>{{ $application->customer->name ?? '—' }}</strong>
            </div>
        @endif

        <div class="row">
            {{-- Main Form (Left) --}}
            <div class="col-lg-8">
                <form id="loanForm" action="{{ route('loans.store') }}" method="POST">
                    @csrf
                    @if($application ?? null)
                        <input type="hidden" name="application_id" value="{{ $application->id }}">
                    @endif

                    {{-- SECTION 1: Customer & Product --}}
                    <div class="card">
                        <div class="card-header bg-primary text-white py-2">
                            <h5 class="mb-0"><i data-feather="users" class="mr-1"></i> ១. ព័ត៌មានអតិថិជន និង Product</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="customer_id">អ្នកខ្ចី <span class="text-danger">*</span></label>
                                <select class="form-control" id="customer_id" name="customer_id" required>
                                    <option value="">— ជ្រើសរើសអ្នកខ្ចី —</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ (old('customer_id', $application->customer_id ?? '') == $customer->id) ? 'selected' : '' }}>
                                            {{ $customer->code }} — {{ $customer->name }}
                                            @if(!$customer->age_verified) ⚠ @endif
                                            @if($customer->has_existing_loan) 🔴 @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Eligibility Panel (AJAX populated) --}}
                            <div id="eligibility_panel" class="eligibility-panel" style="display:none;">
                                <div class="product-info-box mb-3" id="eligibility_body"></div>
                            </div>

                            <div class="form-group">
                                <label for="product_id">Loan Product <span class="text-danger">*</span></label>
                                <select class="form-control" id="product_id" name="product_id" required>
                                    <option value="">— ជ្រើសរើស Product —</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                            data-min="{{ $product->min_amount }}"
                                            data-max="{{ $product->max_amount }}"
                                            data-rate="{{ $product->interest_rate }}"
                                            data-max-term="{{ $product->max_term_months }}"
                                            data-grace="{{ $product->grace_period_days ?? 3 }}"
                                            data-guarantor-above="{{ $product->requires_guarantor_above ?? 500 }}"
                                            data-guarantor-multiplier="{{ $product->guarantor_income_multiplier ?? 1.5 }}"
                                            data-collateral-above="{{ $product->requires_collateral_above ?? 5000 }}"
                                            data-interest-type="{{ $product->interest_type }}"
                                            {{ (old('product_id', $application->product_id ?? '') == $product->id) ? 'selected' : '' }}>
                                            {{ $product->product_code }} — {{ $product->name }}
                                            ({{ $product->interest_type }}, {{ $product->interest_rate }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Product info box --}}
                            <div id="product_info" class="product-info-box" style="display:none;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">ចំនួន</small>
                                        <div>$<span id="prd_min">—</span> – $<span id="prd_max">—</span></div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">ការប្រាក់</small>
                                        <div><span id="prd_rate">—</span>% (<span id="prd_type">—</span>)</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">រយៈពេអតិបរមា</small>
                                        <div><span id="prd_max_term">—</span> ខែ</div>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">Grace</small>
                                        <div><span id="prd_grace">—</span> ថ្ងៃ</div>
                                    </div>
                                </div>
                                <div class="row mt-2" id="threshold_row">
                                    <div class="col-md-6">
                                        <small class="threshold-warn">⚠ Guarantor income multiplier: <span id="prd_guarantor_multiplier">—</span>x</small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="threshold-warn">⚠ Collateral required above: $<span id="prd_collateral_above">—</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Loan Terms --}}
                    <div class="card">
                        <div class="card-header bg-info text-white py-2">
                            <h5 class="mb-0"><i data-feather="dollar-sign" class="mr-1"></i> ២. លក្ខខណ្ឌកម្ចី</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="principal_amount">ចំនួនទឹកប្រាក់កម្ចី ($) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="principal_amount" name="principal_amount"
                                               placeholder="0.00" min="0" step="0.01"
                                               value="{{ old('principal_amount', $application->requested_amount ?? '') }}" required />
                                        <small id="amount_warning" class="text-danger" style="display:none;"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="duration_months">រយៈពេល (ខែ) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="duration_months" name="duration_months"
                                               placeholder="12" min="1"
                                               value="{{ old('duration_months', $application->requested_months ?? '') }}" required />
                                        <small id="term_warning" class="text-danger" style="display:none;"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="interest_rate">អត្រាការប្រាក់ (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control bg-light" id="interest_rate"
                                               name="interest_rate" readonly
                                               placeholder="ស្វ័យប្រវត្តិពី Product"
                                               value="{{ old('interest_rate') }}" />
                                        <small class="text-muted">* snapshot ពី Product នៅព្រឹត្តការណ៍អនុម័ត</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="purpose">គោលបំណងកម្ចី</label>
                                <input type="text" class="form-control" id="purpose" name="purpose"
                                       placeholder="ឧ. ទិញឧបករណ៍ / ទុនបង្វិល"
                                       value="{{ old('purpose', $application->purpose ?? '') }}" maxlength="500" />
                            </div>

                            <div class="form-group">
                                <label for="note">ចំណាំ</label>
                                <textarea class="form-control" id="note" name="note" rows="2">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: Dates --}}
                    <div class="card">
                        <div class="card-header bg-warning text-dark py-2">
                            <h5 class="mb-0"><i data-feather="calendar" class="mr-1"></i> ៣. ថ្ងៃខែ</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">ថ្ងៃចាប់ផ្តើម <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                               min="{{ date('Y-m-d') }}"
                                               value="{{ old('start_date', date('Y-m-d')) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">ថ្ងៃបញ្ចប់</label>
                                        <input type="date" class="form-control bg-light" id="end_date"
                                               name="end_date" readonly
                                               value="{{ old('end_date') }}" />
                                        <small class="text-muted">* គណនាដោយស្វ័យប្រវត្តិ</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="grace_days">ចំនួនថ្ងៃ Grace</label>
                                        <input type="number" class="form-control bg-light" id="grace_days"
                                               name="grace_days" readonly
                                               value="{{ old('grace_days') }}" />
                                        <small class="text-muted">* Auto from Product</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_payment_date">ថ្ងៃទូទាត់ដំបូង <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="first_payment_date"
                                               name="first_payment_date"
                                               value="{{ old('first_payment_date') }}" required />
                                        <small class="text-muted">* ត្រូវតែក្រោយ Grace Period</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Guarantor & Collateral Warning --}}
                    <div id="guarantor_warning" class="alert alert-warning" style="display:none;">
                        <i data-feather="user-check"></i>
                        <span id="guarantor_warning_text"></span>
                    </div>
                    <div id="collateral_warning" class="alert alert-warning" style="display:none;">
                        <i data-feather="shield"></i>
                        <span id="collateral_warning_text"></span>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('loans.index') }}" class="btn btn-secondary">
                                <i data-feather="arrow-left"></i> ត្រលប់
                            </a>
                            <button type="submit" class="btn btn-primary ml-2">
                                <i data-feather="save"></i> បង្កើតកម្ចី
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            {{-- Right Panel: Calculator & Summary --}}
            <div class="col-lg-4">
                <div class="card sticky-top" style="top:80px;">
                    <div class="card-header py-2">
                        <h5 class="mb-0"><i data-feather="bar-chart-2" class="mr-1"></i> ការគណនា</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td>ប្រាក់បង់ប្រចាំខែ</td>
                                <td class="text-right"><strong class="text-primary" id="calc_monthly">$0.00</strong></td>
                            </tr>
                            <tr>
                                <td>ការប្រាក់សរុប</td>
                                <td class="text-right"><strong class="text-info" id="calc_interest">$0.00</strong></td>
                            </tr>
                            <tr>
                                <td>ទឹកប្រាក់សរុបត្រូវសង</td>
                                <td class="text-right"><strong class="text-success" id="calc_total">$0.00</strong></td>
                            </tr>
                        </table>
                        <small class="text-muted">* ផ្អែកលើ Reducing Balance Method</small>

                        <hr/>
                        <div id="threshold_summary" style="display:none;">
                            <h6 class="text-muted">Threshold Summary</h6>
                            <div id="guarantor_flag" class="check-item"></div>
                            <div id="collateral_flag" class="check-item"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    "use strict";
    $(".preloader").fadeOut();

    const ELIGIBILITY_URL = "{{ route('loans.customer_eligibility') }}";

    // ── Customer select → AJAX eligibility ───────────────────────────
    $('#customer_id').on('change', function () {
        const cid = $(this).val();
        if (!cid) { $('#eligibility_panel').hide(); return; }

        $.get(ELIGIBILITY_URL, { customer_id: cid }, function(data) {
            let html = '';
            const color = data.eligible ? '#28a745' : '#dc3545';
            const label = data.eligible ? '✔ អតិថិជនឆ្លងកាត់ការផ្ទៀងផ្ទាត់' : '✘ អតិថិជនមិនទាន់ឆ្លងកាត់ការផ្ទៀងផ្ទាត់';

            html += `<strong style="color:${color}">${label}</strong>`;
            if (data.errors && data.errors.length) {
                html += '<ul class="mb-1 mt-1">';
                data.errors.forEach(e => { html += `<li class="text-danger" style="font-size:0.8rem;">${e}</li>`; });
                html += '</ul>';
            }
            if (data.occupation) html += `<div class="check-item"><span class="dot dot-ok"></span>មុខរបរ: ${data.occupation}</div>`;
            if (data.monthly_income) {
                html += `<div class="check-item"><span class="dot dot-ok"></span>ចំណូល: $${parseFloat(data.monthly_income).toFixed(2)}/ខែ</div>`;
                window.currentCustomerIncome = parseFloat(data.monthly_income);
            } else {
                window.currentCustomerIncome = 0;
            }
            if (data.credit_score)   html += `<div class="check-item"><span class="dot dot-ok"></span>Credit Score: ${data.credit_score}</div>`;
            html += `<div class="check-item"><span class="dot ${data.has_document ? 'dot-ok' : 'dot-err'}"></span>ឯកសារ KYC: ${data.has_document ? 'មាន' : 'មិនមាន'}</div>`;

            if (data.guarantors && data.guarantors.length > 0) {
                html += `<div class="mt-2 text-info"><i data-feather="users" class="mr-1 mt-n1"></i>អ្នកធានាសកម្ម:</div>`;
                html += '<ul class="list-unstyled mb-0 ml-3">';
                data.guarantors.forEach(g => {
                    html += `<li>- <strong>${g.full_name}</strong> (ចំណូល: $${parseFloat(g.income || 0).toFixed(2)} | ទំនង: ${g.relationship || 'N/A'})</li>`;
                });
                html += '</ul>';
                window.currentGuarantors = data.guarantors;
            } else {
                html += `<div class="check-item mt-2"><span class="dot dot-na"></span>អ្នកធានា Active: 0 នាក់</div>`;
                window.currentGuarantors = [];
            }
            html += `<div class="mt-2 text-right"><a href="/admin/v1/customers/${cid}?tab=guarantors" target="_blank" class="btn btn-sm btn-outline-primary"><i data-feather="plus" class="mr-1"></i>បន្ថែមអ្នកធានា</a></div>`;

            $('#eligibility_body').html(html);
            $('#eligibility_panel').show();
            if (typeof feather !== 'undefined') feather.replace();
            checkThresholds(); // recompute requirements
        });
    });

    // ── Product select → populate product info & interest_rate ──────
    $('#product_id').on('change', function () {
        const opt = $(this).find(':selected');
        if (!opt.val()) {
            $('#product_info').hide();
            $('#interest_rate').val('');
            return;
        }
        $('#prd_min').text(parseFloat(opt.data('min')).toLocaleString());
        $('#prd_max').text(parseFloat(opt.data('max')).toLocaleString());
        $('#prd_rate').text(opt.data('rate'));
        $('#prd_type').text(opt.data('interest-type'));
        $('#prd_max_term').text(opt.data('max-term'));
        $('#prd_grace').text(opt.data('grace'));
        $('#prd_guarantor_multiplier').text(opt.data('guarantor-multiplier'));
        $('#prd_collateral_above').text(parseFloat(opt.data('collateral-above')).toLocaleString());
        $('#product_info').show();

        $('#interest_rate').val(opt.data('rate'));
        recalc();
        updateGraceDates();
        checkThresholds();
    });

    // ── Amount / duration change → recalculate ───────────────────────
    $('#principal_amount, #duration_months').on('input', function () {
        recalc();
        updateEndDate();
        checkThresholds();
        validateAgainstProduct();
    });

    // ── Start date change → update computed dates ─────────────────────
    $('#start_date').on('change', function () {
        updateGraceDates();
        updateEndDate();
    });

    function recalc() {
        const P = parseFloat($('#principal_amount').val()) || 0;
        const r = (parseFloat($('#interest_rate').val()) || 0) / 100 / 12;
        const n = parseInt($('#duration_months').val()) || 0;
        if (!P || !n) { resetCalc(); return; }
        let monthly;
        if (r === 0) { monthly = P / n; }
        else { monthly = (P * r * Math.pow(1+r,n)) / (Math.pow(1+r,n) - 1); }
        const total    = monthly * n;
        const interest = total - P;
        $('#calc_monthly').text('$' + monthly.toFixed(2));
        $('#calc_interest').text('$' + interest.toFixed(2));
        $('#calc_total').text('$' + total.toFixed(2));
    }

    function resetCalc() {
        $('#calc_monthly, #calc_interest, #calc_total').text('$0.00');
    }

    function updateEndDate() {
        const start = $('#start_date').val();
        const months = parseInt($('#duration_months').val()) || 0;
        if (!start || !months) return;
        const d = new Date(start);
        d.setMonth(d.getMonth() + months);
        $('#end_date').val(d.toISOString().split('T')[0]);
    }

    function updateGraceDates() {
        const start = $('#start_date').val();
        const grace = parseInt($('#product_id').find(':selected').data('grace')) || 3;
        if (!start) return;
        const d = new Date(start);
        $('#grace_days').val(grace);

        // Auto-suggest first payment as day after grace
        const fp = new Date(d);
        fp.setDate(fp.getDate() + 1);
        if (!$('#first_payment_date').val()) {
            $('#first_payment_date').val(fp.toISOString().split('T')[0]);
        }
    }

    function checkThresholds() {
        const opt    = $('#product_id').find(':selected');
        if (!opt.val()) { $('#threshold_summary').hide(); return; }
        const amount = parseFloat($('#principal_amount').val()) || 0;
        const months = parseInt($('#duration_months').val()) || 0;
        const cAbove = parseFloat(opt.data('collateral-above')) || 0;
        const gAbove = parseFloat(opt.data('guarantor-above')) || 0;
        const multiplier = parseFloat(opt.data('guarantor-multiplier')) || 0;
        const rate = (parseFloat(opt.data('rate')) || 0) / 100 / 12;

        $('#threshold_summary').show();
        $('#guarantor_warning, #collateral_warning').hide();

        // Estimate monthly payment
        let monthlyPayment = rate === 0 ? (amount / months) : (amount * rate * Math.pow(1 + rate, months)) / (Math.pow(1 + rate, months) - 1);
        if (isNaN(monthlyPayment) || !isFinite(monthlyPayment)) monthlyPayment = 0;

        let customerPassesIncomeRule = true;
        if (amount >= 500) {
            const reqCustomerIncome = monthlyPayment * 1.5;
            if ((window.currentCustomerIncome || 0) < reqCustomerIncome) {
                customerPassesIncomeRule = false;
                $('#guarantor_flag').html(`<span class="dot dot-err"></span><span class="text-danger">ចំណូលអតិថិជនមិនគ្រប់គ្រាន់</span> (ត្រូវការ $${reqCustomerIncome.toFixed(2)} /ខែ)`);
            } else {
                $('#guarantor_flag').html(`<span class="dot dot-ok"></span><span class="text-success">ចំណូលអតិថិជនគ្រប់គ្រាន់</span>`);
            }

            if (amount <= 5000 && multiplier > 0) {
                const reqIncome = monthlyPayment * multiplier;
                
                // check if has guarantor with enough income
                let qualifiedGuarantor = false;
                if (window.currentGuarantors && window.currentGuarantors.length > 0) {
                    for (let g of window.currentGuarantors) {
                        if (parseFloat(g.income || 0) >= reqIncome) {
                            qualifiedGuarantor = true;
                            break;
                        }
                    }
                }
                
                if (qualifiedGuarantor) {
                     $('#guarantor_flag').append(`<br/><span class="dot dot-ok mt-2"></span><span class="text-success">មានអ្នកធានាគ្រប់គ្រាន់</span> (ចំណូល > $${reqIncome.toFixed(2)})`);
                } else {
                     $('#guarantor_warning').show();
                     $('#guarantor_warning_text').html(`<strong>ត្រូវការអ្នកធានា:</strong> ចំណូលយ៉ាងហោចណាស់ $${reqIncome.toFixed(2)} /ខែ (${multiplier} ដងនៃប្រាក់សងប្រចាំខែ)`);
                     $('#guarantor_flag').append(`<br/><span class="dot dot-err mt-2"></span><span class="text-danger">អ្នកធានាបច្ចុប្បន្នមិនគ្រប់គ្រាន់</span>`);
                }
            } else if (multiplier === 0 || gAbove === 0) {
                $('#guarantor_flag').append(`<br/><span class="dot dot-ok mt-2"></span>Guarantor: មិនតម្រូវ`);
            }
        } else {
            $('#guarantor_flag').html('<span class="dot dot-ok"></span>Guarantor: មិនតម្រូវ (កម្ចីក្រោម $500)');
        }

        $('#collateral_flag').html('<span class="dot dot-ok"></span>Collateral: មិនតម្រូវ');
    }


    function validateAgainstProduct() {
        const opt = $('#product_id').find(':selected');
        if (!opt.val()) return;
        const amount = parseFloat($('#principal_amount').val()) || 0;
        const months = parseInt($('#duration_months').val()) || 0;
        const min = parseFloat(opt.data('min'));
        const max = parseFloat(opt.data('max'));
        const maxTerm = parseInt(opt.data('max-term'));

        const amtWarn = $('#amount_warning');
        const termWarn = $('#term_warning');

        if (amount && (amount < min || amount > max)) {
            amtWarn.text('ចំនួនត្រូវតែស្ថិតក្នុង $' + min.toLocaleString() + ' – $' + max.toLocaleString()).show();
        } else { amtWarn.hide(); }

        if (months && months > maxTerm) {
            termWarn.text('រយៈពេលត្រូវតែ ≤ ' + maxTerm + ' ខែ').show();
        } else { termWarn.hide(); }
    }

    // ── Trigger defaults on page load ────────────────────────────────
    if ($('#product_id').val()) $('#product_id').trigger('change');
    if ($('#customer_id').val()) $('#customer_id').trigger('change');
    updateEndDate();
    updateGraceDates();
    recalc();

    if (typeof feather !== 'undefined') feather.replace();
});
</script>
@endpush
