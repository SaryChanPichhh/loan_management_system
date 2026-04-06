@extends('backend.layout.master')

@push('styles')
<style>
    .detail-card { margin-bottom: 20px; }
    .detail-label { font-size: 0.85rem; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 5px; }
    .detail-value { font-size: 1.1rem; color: #343a40; font-weight: 500; }
    .status-badge { font-size: 0.9rem; padding: 6px 12px; }
    .info-icon { width: 18px; height: 18px; margin-right: 8px; color: #007bff; }
</style>
@endpush

@section('contents')
<div class="page-wrapper">
    {{-- Breadcrumb --}}
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    ព័ត៌មានលម្អិតផលិតផលកម្ចី
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('loan_products.index') }}">ផលិតផលកម្ចី</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ព័ត៌មានលម្អិត</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <a href="{{ route('loan_products.edit', $loanProduct->id) }}" class="btn btn-warning mr-2">
                        <i data-feather="edit"></i> កែប្រែ
                    </a>
                    <a href="{{ route('loan_products.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left"></i> ត្រឡប់ក្រោយ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            {{-- Main Details --}}
            <div class="col-lg-8">
                <div class="card detail-card">
                    <div class="card-header bg-white d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">
                            <i data-feather="package" class="info-icon"></i>
                            {{ $loanProduct->name }}
                        </h4>
                        <div>
                            @if($loanProduct->status)
                                <span class="badge badge-success status-badge">ដំណើរការ</span>
                            @else
                                <span class="badge badge-danger status-badge">បិទដំណើរការ</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="detail-label">លេខកូដផលិតផល</div>
                                <div class="detail-value text-primary font-weight-bold">
                                    {{ $loanProduct->product_code }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">កាលបរិច្ឆេទបង្កើត</div>
                                <div class="detail-value">
                                    {{ $loanProduct->created_at->format('d-M-Y h:i A') }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="detail-label">ការពិពណ៌នា</div>
                            <div class="detail-value" style="font-size: 1rem;">
                                {{ $loanProduct->description ?: 'មិនមានការពិពណ៌នា' }}
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3 mt-4"><i data-feather="dollar-sign" class="info-icon text-success"></i> ទិន្នន័យទឹកប្រាក់ & រយៈពេល</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="detail-label">ទឹកប្រាក់អប្បបរមា</div>
                                <div class="detail-value text-success">${{ number_format($loanProduct->min_amount, 2) }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">ទឹកប្រាក់អតិបរមា</div>
                                <div class="detail-value text-success">${{ number_format($loanProduct->max_amount, 2) }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">រយៈពេលកម្ចីអតិបរមា</div>
                                <div class="detail-value">{{ $loanProduct->max_term_months }} ខែ</div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3 mt-4"><i data-feather="percent" class="info-icon text-warning"></i> ការប្រាក់ & ការពិន័យ</h5>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="detail-label">អត្រាការប្រាក់</div>
                                <div class="detail-value text-danger">{{ number_format($loanProduct->interest_rate, 2) }}% / ខែ</div>
                            </div>
                            <div class="col-md-3">
                                <div class="detail-label">ប្រភេទការប្រាក់</div>
                                <div class="detail-value">{{ $loanProduct->interestTypeLabel() }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="detail-label">ការប្រាក់ពន្យារ</div>
                                <div class="detail-value">{{ number_format($loanProduct->late_fee_rate, 2) }}% / ខែ</div>
                            </div>
                            <div class="col-md-3">
                                <div class="detail-label">ការប្រាក់ទោស</div>
                                <div class="detail-value">{{ number_format($loanProduct->penalty_rate, 2) }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="col-lg-4">
                <div class="card detail-card border-left border-primary">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i data-feather="shield" class="info-icon"></i>
                            លក្ខខណ្ឌតម្រូវការ
                        </h5>
                        
                        <div class="mb-3">
                            <div class="detail-label">ថ្ងៃអនុញ្ញាតពន្យារបង់ប្រាក់ (Grace Period)</div>
                            <div class="detail-value">
                                <span class="badge badge-info status-badge">{{ $loanProduct->grace_period_days }} ថ្ងៃ</span>
                            </div>
                            <small class="text-muted mt-1 d-block">ចំនួនថ្ងៃអាចយឺតបានដោយមិនមានការពិន័យ</small>
                        </div>
                        
                        <hr>

                        <div class="mb-3">
                            <div class="detail-label">មេគុណចំណូលអ្នកធានា</div>
                            <div class="detail-value"><span class="text-danger">{{ number_format($loanProduct->guarantor_income_multiplier, 2) }}</span> ដងនៃប្រាក់សងប្រចាំខែ</div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <div class="detail-label">កម្រិតតម្រូវឱ្យមានទ្រព្យធានា</div>
                            <div class="detail-value">កម្ចីលើសពី <span class="text-danger">${{ number_format($loanProduct->requires_collateral_above, 2) }}</span></div>
                        </div>

                    </div>
                </div>

                <div class="card detail-card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">សកម្មភាពបញ្ជា</h6>
                        
                        <form action="{{ route('loan_products.toggle_status', $loanProduct->id) }}" method="POST" class="mb-2">
                            @csrf
                            @if($loanProduct->status)
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i data-feather="toggle-right"></i> បិទដំណើរការផលិតផលនេះ
                                </button>
                            @else
                                <button type="submit" class="btn btn-success btn-block">
                                    <i data-feather="toggle-left"></i> បើកដំណើរការផលិតផលនេះ
                                </button>
                            @endif
                        </form>

                        <form action="{{ route('loan_products.destroy', $loanProduct->id) }}" method="POST" onsubmit="return confirm('តើអ្នកប្រាកដថាចង់លុបផលិតផលនេះមែនទេ? ការលុបមិនអាចត្រឡប់វិញបានទេ។');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-block">
                                <i data-feather="trash-2"></i> លុបផលិតផលនេះចោល
                            </button>
                        </form>
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
        if (typeof feather !== 'undefined' && feather) {
            feather.replace();
        }
    });
</script>
@endpush
