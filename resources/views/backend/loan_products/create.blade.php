@extends('backend.layout.master')

@push('styles')
<style>
    .form-section { border: 1px solid #e9ecef; border-radius: .4rem; padding: 1.25rem; margin-bottom: 1.25rem; }
    .form-section-title { font-size: .8rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05rem; color: #6c757d; margin-bottom: 1rem; }
    .required-star { color: #dc3545; }
    .input-hint { font-size: .78rem; color: #6c757d; }
    .radio-card { border: 2px solid #dee2e6; border-radius: .4rem; padding: .75rem 1rem;
        cursor: pointer; transition: all .2s; }
    .radio-card:hover { border-color: #007bff; }
    .radio-card input:checked + .radio-card-body .radio-card { border-color: #007bff; background:#e7f3ff; }
    .interest-option { margin-bottom: .5rem; }
    .interest-option input[type=radio]:checked ~ label .radio-card { border-color: #007bff; background:#e7f3ff; }
</style>
@endpush

@section('contents')
<div class="page-wrapper">
    {{-- Breadcrumb --}}
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    បង្កើតផលិតផលកម្ចីថ្មី
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('loan_products.index') }}">ផលិតផលកម្ចី</a></li>
                            <li class="breadcrumb-item active" aria-current="page">បង្កើតថ្មី</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="{{ route('loan_products.store') }}" method="POST">
            @csrf

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong><i data-feather="alert-circle" style="width:16px;height:16px;"></i> មានកំហុស!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            <div class="row">
                {{-- Left Column --}}
                <div class="col-lg-8">
                    {{-- Basic Info --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="info" style="width:16px;height:16px;"></i>
                                ព័ត៌មានមូលដ្ឋាន
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>លេខកូដផលិតផល <span class="required-star">*</span></label>
                                        <input type="text" name="product_code"
                                               class="form-control @error('product_code') is-invalid @enderror"
                                               value="{{ old('product_code') }}"
                                               placeholder="ឧ: LP-001"
                                               maxlength="30">
                                        @error('product_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="input-hint">អក្សរ និងលេខ មិនលើស 30 តួ</small>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>ឈ្មោះផលិតផល <span class="required-star">*</span></label>
                                        <input type="text" name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}"
                                               placeholder="ឧ: កម្ចីខ្នាតតូច">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>ការពិពណ៌នា</label>
                                <textarea name="description" rows="3"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="ព័ត៌មានលម្អិតអំពីផលិតផលកម្ចីនេះ...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Amount & Term --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="dollar-sign" style="width:16px;height:16px;"></i>
                                ចំនួនទឹកប្រាក់ និង រយៈពេល
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ចំនួនអប្បបរមា ($) <span class="required-star">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                            <input type="number" name="min_amount" step="0.01" min="0"
                                                   class="form-control @error('min_amount') is-invalid @enderror"
                                                   value="{{ old('min_amount') }}"
                                                   placeholder="100.00">
                                            @error('min_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ចំនួនអតិបរមា ($) <span class="required-star">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                            <input type="number" name="max_amount" step="0.01" min="0"
                                                   class="form-control @error('max_amount') is-invalid @enderror"
                                                   value="{{ old('max_amount') }}"
                                                   placeholder="10000.00">
                                            @error('max_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>រយៈពេលអតិបរមា (ខែ) <span class="required-star">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="max_term_months" min="1" max="360"
                                                   class="form-control @error('max_term_months') is-invalid @enderror"
                                                   value="{{ old('max_term_months', 12) }}">
                                            <div class="input-group-append"><span class="input-group-text">ខែ</span></div>
                                            @error('max_term_months')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ថ្ងៃអត់ការប្រាក់ (Grace Period) <span class="required-star">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="grace_period_days" min="0"
                                                   class="form-control @error('grace_period_days') is-invalid @enderror"
                                                   value="{{ old('grace_period_days', 3) }}">
                                            <div class="input-group-append"><span class="input-group-text">ថ្ងៃ</span></div>
                                            @error('grace_period_days')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Interest & Rates --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="percent" style="width:16px;height:16px;"></i>
                                អត្រាការប្រាក់ និង ការប្រាក់ផ្សេង
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>អត្រាការប្រាក់ (% / ខែ) <span class="required-star">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="interest_rate" step="0.0001" min="0" max="100"
                                                   class="form-control @error('interest_rate') is-invalid @enderror"
                                                   value="{{ old('interest_rate', 1.5) }}">
                                            <div class="input-group-append"><span class="input-group-text">% / ខែ</span></div>
                                            @error('interest_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ការប្រាក់ពន្យារ (% / ខែ) <span class="required-star">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="late_fee_rate" step="0.0001" min="0"
                                                   class="form-control @error('late_fee_rate') is-invalid @enderror"
                                                   value="{{ old('late_fee_rate', 1.5) }}">
                                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                                            @error('late_fee_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ការប្រាក់ទោស (%) <span class="required-star">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="penalty_rate" step="0.0001" min="0"
                                                   class="form-control @error('penalty_rate') is-invalid @enderror"
                                                   value="{{ old('penalty_rate', 0) }}">
                                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                                            @error('penalty_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Interest Type --}}
                            <div class="form-group mt-2">
                                <label>ប្រភេទការប្រាក់ <span class="required-star">*</span></label>
                                @error('interest_type')
                                    <div class="text-danger small mb-1">{{ $message }}</div>
                                @enderror
                                <div class="row">
                                    @foreach([
                                        'FLAT'             => ['label' => 'Flat (ថេរ)', 'desc' => 'ការប្រាក់ថេរគ្រប់ខែ', 'icon' => 'minus'],
                                        'REDUCING_BALANCE' => ['label' => 'Reducing Balance', 'desc' => 'ការប្រាក់ថយចុះតាមដើមទុន', 'icon' => 'trending-down'],
                                        'COMPOUND'         => ['label' => 'Compound (ស្មុគ)', 'desc' => 'ការប្រាក់ប្រមូលទៅលើការប្រាក់', 'icon' => 'repeat'],
                                    ] as $value => $info)
                                        <div class="col-md-4 mb-2">
                                            <div class="radio-card {{ old('interest_type') === $value ? 'border-primary bg-light' : '' }}">
                                                <label class="d-flex align-items-start cursor-pointer w-100 mb-0" style="cursor:pointer;">
                                                    <input type="radio" name="interest_type" value="{{ $value }}"
                                                           class="mt-1 mr-2" {{ old('interest_type', 'FLAT') === $value ? 'checked' : '' }}>
                                                    <div>
                                                        <div class="font-weight-semibold">
                                                            <i data-feather="{{ $info['icon'] }}" style="width:14px;height:14px;"></i>
                                                            {{ $info['label'] }}
                                                        </div>
                                                        <small class="text-muted">{{ $info['desc'] }}</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-lg-4">
                    {{-- Requirements --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="shield" style="width:16px;height:16px;"></i>
                                លក្ខខណ្ឌទំររ/ទ្រព្យ
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>មេគុណចំណូលអ្នកធានា (Multiplier) <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="guarantor_income_multiplier" step="0.1" min="0"
                                           class="form-control @error('guarantor_income_multiplier') is-invalid @enderror"
                                           value="{{ old('guarantor_income_multiplier', 1.5) }}">
                                    <div class="input-group-append"><span class="input-group-text">ដង</span></div>
                                    @error('guarantor_income_multiplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="input-hint">ឧទាហរណ៍៖ 1.5 ដងនៃប្រាក់ត្រូវបង់</small>
                            </div>
                            <div class="form-group">
                                <label>ត្រូវការទ្រព្យធានាពី ($) <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" name="requires_collateral_above" step="0.01" min="0"
                                           class="form-control @error('requires_collateral_above') is-invalid @enderror"
                                           value="{{ old('requires_collateral_above', 5000) }}">
                                    @error('requires_collateral_above')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="input-hint">ចំនួនខ្ចីលើសពីនេះ ត្រូវការទ្រព្យធានា</small>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="settings" style="width:16px;height:16px;"></i>
                                ការកំណត់
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="statusSwitch"
                                       name="status" {{ old('status', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="statusSwitch">
                                    បើកដំណើរការផលិតផល
                                </label>
                            </div>
                            <small class="input-hint d-block mt-2">ផលិតផលដែលបើក អាចប្រើបានក្នុងការបង្កើតកម្ចី</small>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block mb-2">
                                <i data-feather="save" style="width:16px;height:16px;"></i>
                                រក្សាទុកផលិតផល
                            </button>
                            <a href="{{ route('loan_products.index') }}" class="btn btn-secondary btn-block">
                                <i data-feather="x" style="width:16px;height:16px;"></i>
                                បោះបង់
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        if (typeof feather !== 'undefined' && feather) feather.replace();
    });
</script>
@endpush
