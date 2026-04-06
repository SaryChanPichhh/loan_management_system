@extends('backend.layout.master')

@push('styles')
<style>
.check-row { display:flex; align-items:center; padding:6px 0; border-bottom:1px solid #f0f0f0; }
.check-row:last-child { border-bottom:0; }
.check-icon { width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; margin-right:10px; flex-shrink:0; }
.check-icon.pass { background:#d4edda; color:#28a745; }
.check-icon.fail { background:#f8d7da; color:#dc3545; }
.check-label { flex:1; font-size:0.875rem; }
.risk-card { border-left: 4px solid; }
.risk-card.pass-border { border-color: #28a745; }
.risk-card.fail-border { border-color: #dc3545; }
</style>
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    ពិនិត្យ និងអនុម័តកម្ចី
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.show', $loan->id) }}">{{ $loan->loan_code }}</a></li>
                        <li class="breadcrumb-item active">ពិនិត្យ</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">
            {{-- Left: Loan Info + Eligibility + History --}}
            <div class="col-lg-8">

                {{-- Loan Summary Card --}}
                <div class="card">
                    <div class="card-header py-2">
                        <h5 class="mb-0">សេចក្តីសង្ខេបនៃកម្ចី — <strong>{{ $loan->loan_code }}</strong></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-muted mb-0">អ្នកខ្ចី</p>
                                <h5>{{ $customer->name ?? 'N/A' }}</h5>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">ចំនួនទឹកប្រាក់</p>
                                <h5>${{ number_format($loan->principal_amount, 2) }}</h5>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Loan Product</p>
                                <h5>{{ $product->name ?? '—' }}</h5>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">ការប្រាក់ (Snapshot)</p>
                                <h5>{{ $loan->interest_rate }}%</h5>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <p class="text-muted mb-0">រយៈពេល</p>
                                <p>{{ $loan->duration_months }} ខែ</p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">ថ្ងៃចាប់ផ្តើម</p>
                                <p>{{ optional($loan->start_date)->format('d/m/Y') ?? '—' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Grace Period End</p>
                                <p>{{ $loan->grace_days ? $loan->grace_days . ' ថ្ងៃ' : '—' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">ថ្ងៃទូទាត់ដំបូង</p>
                                <p>{{ optional($loan->first_payment_date)->format('d/m/Y') ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Guarantor Required</p>
                                <p>
                                    @if($loan->guarantor_required)
                                        <span class="badge badge-warning">បាទ</span>
                                    @else
                                        <span class="badge badge-secondary">ទេ</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Collateral Required</p>
                                <p>
                                    @if($loan->collateral_required)
                                        <span class="badge badge-warning">បាទ</span>
                                    @else
                                        <span class="badge badge-secondary">ទេ</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-0">គោលបំណង</p>
                                <p>{{ $loan->purpose ?? '—' }}</p>
                            </div>
                        </div>
                        @if($loan->application)
                            <div class="alert alert-info py-1 mb-0">
                                <small><i data-feather="link" class="mr-1"></i>
                                    ពីសំណើ: <strong>{{ $loan->application->application_code }}</strong>
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Eligibility Checklist --}}
                <div class="card risk-card {{ $allChecksPassed ? 'pass-border' : 'fail-border' }}">
                    <div class="card-header py-2 {{ $allChecksPassed ? 'bg-success' : 'bg-danger' }} text-white">
                        <h5 class="mb-0">
                            @if($allChecksPassed)
                                ✔ ការផ្ទៀងផ្ទាត់ប្រឆាំងគ្រប់ — អាចអនុម័ត
                            @else
                                ✘ ការផ្ទៀងផ្ទាត់ខ្លះបរាជ័យ — ពិនិត្យម្តងទៀត
                            @endif
                        </h5>
                    </div>
                    <div class="card-body py-2">
                        {{-- CUSTOMER --}}
                        <p class="text-muted mb-1 mt-2"><small><strong>CUSTOMER VALIDATION</strong></small></p>
                        @php
                            $checkItems = [
                                'customer_active'  => 'អតិថិជន Active និង មិន Soft-Deleted',
                                'age_verified'     => 'អាយុបានផ្ទៀងផ្ទាត់ (18–65 ឆ្នាំ)',
                                'no_existing_loan' => 'មិនមានកម្ចីកំពុងដំណើរការ (Over-lending Guard)',
                                'has_document'     => 'ឯកសារ KYC / National ID បានបង្ហោះ',
                                'has_income_info'  => 'ព័ត៌មានមុខរបរ និង ចំណូលប្រចាំខែ',
                                'credit_score_ok'  => 'Credit Score ស្ថិតក្នុង 300–900',
                                'product_active'   => 'Loan Product Active',
                                'amount_in_range'  => 'ចំនួនទឹកប្រាក់ស្ថិតក្នុង Range របស់ Product',
                                'term_ok'          => 'រយៈពេលមិនលើស Max Term Months',
                                'guarantor_ok'     => 'Guarantor (Active, Document) — ប្រសិនតម្រូវ',
                            ];
                        @endphp
                        @foreach($checkItems as $key => $label)
                            @php $passed = $checks[$key] ?? false; @endphp
                            <div class="check-row">
                                <div class="check-icon {{ $passed ? 'pass' : 'fail' }}">
                                    {{ $passed ? '✓' : '✗' }}
                                </div>
                                <div class="check-label">{{ $label }}</div>
                                @if(!$passed)
                                    <span class="badge badge-danger">Failed</span>
                                @else
                                    <span class="badge badge-success">OK</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Customer Details --}}
                @if($customer)
                <div class="card">
                    <div class="card-header py-2"><h5 class="mb-0">ព័ត៌មានអតិថិជន</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">លេខកូដ</small>
                                <div>{{ $customer->code }}</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">ទូរស័ព្ទ</small>
                                <div>{{ $customer->phone }}</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">ប្រភេទ</small>
                                <div>{{ $customer->type }}</div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <small class="text-muted">មុខរបរ</small>
                                <div>{{ $customer->occupation ?? '—' }}</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">ចំណូល/ខែ</small>
                                <div>${{ number_format($customer->monthly_income ?? 0, 2) }}</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Credit Score</small>
                                <div>{{ $customer->credit_score ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Product vs Requested Comparison --}}
                @if($product)
                <div class="card">
                    <div class="card-header py-2"><h5 class="mb-0">Product Constraints vs. Requested</h5></div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead><tr><th>លក្ខខណ្ឌ</th><th>Product</th><th>Requested</th><th>Result</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>ចំនួនទឹកប្រាក់</td>
                                    <td>${{ number_format((float)$product->min_amount, 0) }} – ${{ number_format((float)$product->max_amount, 0) }}</td>
                                    <td>${{ number_format($loan->principal_amount, 0) }}</td>
                                    <td>
                                        @if($checks['amount_in_range'])
                                            <span class="badge badge-success">OK</span>
                                        @else
                                            <span class="badge badge-danger">FAIL</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>រយៈពេល</td>
                                    <td>≤ {{ $product->max_term_months }} ខែ</td>
                                    <td>{{ $loan->duration_months }} ខែ</td>
                                    <td>
                                        @if($checks['term_ok'])
                                            <span class="badge badge-success">OK</span>
                                        @else
                                            <span class="badge badge-danger">FAIL</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Guarantor Above</td>
                                    <td>${{ number_format((float)($product->requires_guarantor_above ?? 500), 0) }}</td>
                                    <td>{{ $requiresGuarantor ? 'តម្រូវ' : 'មិនតម្រូវ' }}</td>
                                    <td>
                                        @if($checks['guarantor_ok'])
                                            <span class="badge badge-success">OK</span>
                                        @else
                                            <span class="badge badge-danger">FAIL</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Collateral Above</td>
                                    <td>${{ number_format((float)($product->requires_collateral_above ?? 5000), 0) }}</td>
                                    <td>{{ $loan->collateral_required ? 'តម្រូវ' : 'មិនតម្រូវ' }}</td>
                                    <td><span class="badge badge-secondary">Info</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Guarantors --}}
                @if($guarantors->count() > 0)
                <div class="card">
                    <div class="card-header py-2"><h5 class="mb-0">អ្នកធានា</h5></div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead><tr><th>ឈ្មោះ</th><th>National ID</th><th>ទំនាក់ទំនង</th><th>ស្ថានភាព</th><th>ឯកសារ</th></tr></thead>
                            <tbody>
                                @foreach($guarantors as $g)
                                <tr>
                                    <td>{{ $g->full_name }}</td>
                                    <td>{{ $g->national_id ?? '—' }}</td>
                                    <td>{{ $g->relationship ?? '—' }}</td>
                                    <td>
                                        @if($g->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($g->status === 'released')
                                            <span class="badge badge-secondary">Released</span>
                                        @else
                                            <span class="badge badge-danger">{{ $g->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($g->document_path)
                                            <span class="badge badge-success">មាន</span>
                                        @else
                                            <span class="badge badge-danger">គ្មាន</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Past Loans History --}}
                <div class="card">
                    <div class="card-header py-2"><h5 class="mb-0">ប្រវត្តិការខ្ចី</h5></div>
                    <div class="card-body">
                        @forelse($pastLoans as $past)
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span><strong>{{ $past->loan_code }}</strong> — ${{ number_format($past->principal_amount, 0) }}</span>
                            <span>
                                <span class="badge {{ $past->statusBadge() }}">{{ $past->statusLabel() }}</span>
                                <small class="text-muted ml-2">{{ optional($past->start_date)->format('d/m/Y') ?? '—' }}</small>
                            </span>
                        </div>
                        @empty
                        <p class="text-muted mb-0">មិនមានប្រវត្តិកម្ចីពីមុន</p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- Right: Actions --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top:80px;">

                    {{-- Approve --}}
                    <div class="card border-success">
                        <div class="card-header bg-success text-white py-2">
                            <h5 class="mb-0"><i data-feather="check-circle" class="mr-1"></i> អនុម័តកម្ចី</h5>
                        </div>
                        <div class="card-body">
                            @if(!$allChecksPassed)
                                <div class="alert alert-warning py-2">
                                    <small>⚠ ការផ្ទៀងផ្ទាត់មួយចំនួនបរាជ័យ។ ពិចារណាឡើងវិញមុនពេលអនុម័ត។</small>
                                </div>
                            @endif
                            <form action="{{ route('loans.approve', $loan->id) }}" method="POST" id="approveForm">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block"
                                        onclick="return confirm('តើអ្នកប្រាកដថាចង់អនុម័តកម្ចីនេះ? អ្នកនឹងត្រូវបើកប្រាក់ (Disburse) នៅជំហានបន្ទាប់។')">
                                    <i data-feather="check"></i> អនុម័ត (Approve)
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Reject --}}
                    <div class="card border-danger mt-3">
                        <div class="card-header bg-danger text-white py-2">
                            <h5 class="mb-0"><i data-feather="x-circle" class="mr-1"></i> បដិសេធកម្ចី</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('loans.reject', $loan->id) }}" method="POST" id="rejectForm">
                                @csrf
                                <div class="form-group">
                                    <label for="rejected_reason">មូលហេតុបដិសេធ <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="rejected_reason" name="rejected_reason"
                                              rows="3" required minlength="5" maxlength="1000"
                                              placeholder="ពន្យល់ហេតុផលបដិសេធ..."></textarea>
                                    @error('rejected_reason')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="button" class="btn btn-danger btn-block" id="rejectBtn">
                                    <i data-feather="x"></i> បដិសេធ
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit Link --}}
                    <div class="card mt-3">
                        <div class="card-body py-2">
                            <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-outline-primary btn-block btn-sm">
                                <i data-feather="edit"></i> កែសម្រួលកម្ចី
                            </a>
                            <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-outline-secondary btn-block btn-sm mt-1">
                                <i data-feather="eye"></i> មើលព័ត៌មានលម្អិត
                            </a>
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
$(document).ready(function() {
    "use strict";
    $(".preloader").fadeOut();

    $('#rejectBtn').on('click', function() {
        const reason = $('#rejected_reason').val().trim();
        if (!reason || reason.length < 5) {
            alert('សូមបញ្ចូលមូលហេតុបដិសេធ (យ៉ាងតិច 5 តួ)');
            return;
        }
        if (confirm('តើអ្នកប្រាកដថាចង់បដិសេធកម្ចីនេះ? សកម្មភាពនេះមិនអាចត្រលប់វិញ។')) {
            $('#rejectForm').submit();
        }
    });

    if (typeof feather !== 'undefined') feather.replace();
});
</script>
@endpush
