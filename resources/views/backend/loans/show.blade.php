@extends('backend.layout.master')
@push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    ព័ត៌មានលម្អិតនៃកម្ចី
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item active">{{ $loan->loan_code }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center">
                <div class="float-right">
                    <a href="{{ route('loans.schedule.print', $loan->id) }}" target="_blank" class="btn btn-secondary btn-sm mr-1">
                        <i data-feather="printer"></i> Print Schedule
                    </a>
                    @if($loan->status === 'pending')
                        <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-warning btn-sm mr-1">
                            <i data-feather="edit"></i> កែសម្រួល
                        </a>
                        <form action="{{ route('loans.submit_review', $loan->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm">
                                <i data-feather="send"></i> ស្នើសុំពិនិត្យ
                            </button>
                        </form>
                    @elseif($loan->status === 'under_review')
                        <a href="{{ route('loans.review', $loan->id) }}" class="btn btn-success btn-sm">
                            <i data-feather="check-circle"></i> ពិនិត្យ/អនុម័ត
                        </a>
                    @elseif($loan->status === 'approved')
                        <a href="{{ route('loans.disburse.form', $loan->id) }}" class="btn btn-primary btn-sm">
                            <i data-feather="dollar-sign"></i> បើកប្រាក់ (Disburse)
                        </a>
                    @elseif($loan->status === 'active')
                        <a href="{{ route('loans.payments', $loan->id) }}" class="btn btn-primary btn-sm">
                            <i data-feather="dollar-sign"></i> ការទូទាត់
                        </a>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#earlySettleModal">
                            <i data-feather="fast-forward"></i> Early Settle
                        </button>
                        @if($loan->account && $loan->account->days_past_due > 30)
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#markDefaultModal">
                                <i data-feather="alert-triangle"></i> Mark Default
                            </button>
                        @endif
                    @elseif($loan->status === 'defaulted')
                        <button type="button" class="btn btn-dark btn-sm" data-toggle="modal" data-target="#writeOffModal">
                            <i data-feather="slash"></i> Write Off
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Status Banner --}}
        @php
            $bannerClass = match($loan->status) {
                'pending'      => 'alert-warning',
                'under_review' => 'alert-info',
                'approved'     => 'alert-primary',
                'active'       => 'alert-success',
                'completed'    => 'alert-success', // Requested green banner for completed
                'rejected'     => 'alert-danger',
                'defaulted'    => 'alert-danger',
                'written_off'  => 'alert-dark',
                default        => 'alert-light',
            };
        @endphp
        <div class="alert {{ $bannerClass }} d-flex align-items-center mb-3">
            @if($loan->status === 'completed')
                <i data-feather="check-circle" class="mr-2 text-success font-weight-bold" style="width:24px; height:24px;"></i>
                <span class="badge badge-success mr-2" style="font-size:1rem;">Loan Completed</span>
            @else
                <span class="badge {{ $loan->statusBadge() }} mr-2" style="font-size:1rem;">{{ $loan->statusLabel() }}</span>
            @endif
            <strong class="mx-2">{{ $loan->loan_code }}</strong>
            @if($loan->application)
                <span class="ml-3 text-muted"><small>ពីសំណើ: {{ $loan->application->application_code }}</small></span>
            @endif
            @if($loan->status === 'defaulted')
                <span class="ml-auto badge badge-warning text-dark"><i data-feather="alert-circle" class="mr-1" style="width:14px;"></i> Guarantor Contacted / Legal Proceeding</span>
            @endif
        </div>

        {{-- Rejection Reason Banner --}}
        @if($loan->status === 'rejected' && $loan->rejected_reason)
            <div class="alert alert-danger">
                <strong>មូលហេតុបដិសេធ:</strong> {{ $loan->rejected_reason }}<br>
                <small>បដិសេធដោយ: {{ $loan->rejectedBy->name ?? '—' }}</small>
            </div>
        @endif

        <div class="row">
            {{-- Main Info --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2">
                        <h5 class="mb-0">សេចក្តីសង្ខេប</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-muted mb-0">អ្នកខ្ចី</p>
                                <h5>{{ $loan->customer->name ?? 'N/A' }}</h5>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Loan Product</p>
                                <h5>{{ $loan->product->name ?? '—' }}</h5>
                            </div>
                            <div class="col-md-2">
                                <p class="text-muted mb-0">ចំនួន (Principal)</p>
                                <h5>${{ number_format($loan->principal_amount, 2) }}</h5>
                            </div>
                            <div class="col-md-2">
                                <p class="text-muted mb-0">ចំនួនបានចាញ់</p>
                                <h5>${{ number_format($loan->disbursed_amount ?? $loan->principal_amount, 2) }}</h5>
                            </div>
                            <div class="col-md-2">
                                <p class="text-muted mb-0">ការប្រាក់ (Snapshot)</p>
                                <h5>{{ $loan->interest_rate }}%</h5>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-2">
                                <p class="text-muted mb-0">រយៈពេល</p>
                                <p>{{ $loan->duration_months }} ខែ</p>
                            </div>
                            <div class="col-md-2">
                                <p class="text-muted mb-0">ថ្ងៃចាប់ផ្តើម</p>
                                <p>{{ optional($loan->start_date)->format('d/m/Y') ?? '—' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p class="text-muted mb-0">ថ្ងៃបញ្ចប់</p>
                                <p>{{ optional($loan->end_date)->format('d/m/Y') ?? '—' }}</p>
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
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <p class="text-muted mb-0">ថ្ងៃបញ្ចប់ (End Date)</p>
                                <p class="font-weight-bold {{ $loan->status === 'completed' ? 'text-success' : '' }}">
                                    {{ optional($loan->end_date)->format('d/m/Y') ?? '—' }}
                                </p>
                            </div>
                            @if($loan->early_settlement_date)
                            <div class="col-md-4">
                                <p class="text-muted mb-0">បង់ផ្តាច់មុនកំណត់</p>
                                <p class="badge badge-warning text-dark">{{ \Carbon\Carbon::parse($loan->early_settlement_date)->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Guarantor Required</p>
                                <p>
                                    @if($loan->guarantor_required)
                                        <span class="badge badge-warning">បាទ</span>
                                    @else
                                        <span class="badge badge-light">ទេ</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Collateral Required</p>
                                <p>
                                    @if($loan->collateral_required)
                                        <span class="badge badge-warning">បាទ</span>
                                        @if($loan->status === 'completed')
                                            <span class="badge badge-info ml-1">Released</span>
                                        @elseif($loan->status === 'written_off')
                                            <span class="badge badge-dark ml-1">Seized</span>
                                        @endif
                                    @else
                                        <span class="badge badge-light">ទេ</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-0">គោលបំណង</p>
                                <p>{{ $loan->purpose ?? '—' }}</p>
                            </div>
                        </div>
                        @if($loan->note)
                        <div class="row">
                            <div class="col-12">
                                <p class="text-muted mb-0">ចំណាំ</p>
                                <p>{{ $loan->note }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Financial Summary --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header py-2"><h5 class="mb-0">ព័ត៌មានហិរញ្ញ</h5></div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>ប្រាក់បង់ប្រចាំខែ</strong></td>
                                <td class="text-right">${{ number_format($monthlyInstalment, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>ការប្រាក់សរុប</strong></td>
                                <td class="text-right">${{ number_format($totalInterest, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>ទឹកប្រាក់សរុបត្រូវសង</strong></td>
                                <td class="text-right">${{ number_format($totalPayable, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>បានសងរួច</strong></td>
                                <td class="text-right text-success">${{ number_format($totalPaid, 2) }}</td>
                            </tr>
                            <tr class="table-warning">
                                <td><strong>សមតុល្យនៅសល់</strong></td>
                                <td class="text-right text-danger"><strong>${{ number_format($remainingBalance, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Audit Trail + Quick Actions --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header py-2"><h5 class="mb-0">Audit Trail & Actions</h5></div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">បង្កើតដោយ</td>
                                <td>{{ $loan->createdBy->name ?? '—' }}</td>
                                <td class="text-muted">{{ optional($loan->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">អនុម័តដោយ</td>
                                <td>{{ $loan->approvedBy->name ?? '—' }}</td>
                                <td class="text-muted">{{ $loan->approvedBy ? optional($loan->updated_at)->format('d/m/Y H:i') : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">បដិសេធដោយ</td>
                                <td>{{ $loan->rejectedBy->name ?? '—' }}</td>
                                <td class="text-muted">—</td>
                            </tr>
                        </table>

                        {{-- Guarantors --}}
                        @if($guarantors->count())
                            <hr/>
                            <p class="text-muted mb-1"><small><strong>អ្នកធានា</strong></small></p>
                            @foreach($guarantors as $g)
                                <div class="d-flex align-items-center mb-1">
                                    @php
                                        $badgeColor = match($g->status) {
                                            'active' => 'badge-success',
                                            'released' => 'badge-info',
                                            'defaulted' => 'badge-danger',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeColor }} mr-2">{{ ucfirst($g->status) }}</span>
                                    <span>{{ $g->full_name }}</span>
                                    @if(!$g->document_path)
                                        <span class="badge badge-danger ml-2">គ្មានឯកសារ</span>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        {{-- Status-based actions --}}
                        <hr/>
                        @if($loan->status === 'pending')
                            <form action="{{ route('loans.submit_review', $loan->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info btn-block">
                                    <i data-feather="send"></i> ស្នើសុំពិនិត្យ (Submit to Review)
                                </button>
                            </form>
                        @elseif($loan->status === 'under_review')
                            <a href="{{ route('loans.review', $loan->id) }}" class="btn btn-primary btn-block">
                                <i data-feather="file-text"></i> ពិនិត្យ / អនុម័ត
                            </a>
                        @elseif($loan->status === 'approved')
                            <a href="{{ route('loans.disburse.form', $loan->id) }}" class="btn btn-primary btn-block">
                                <i data-feather="dollar-sign"></i> បើកប្រាក់ (Disburse)
                            </a>
                        @elseif($loan->status === 'active')
                            <a href="{{ route('loans.payments', $loan->id) }}" class="btn btn-info btn-block mb-2">
                                <i data-feather="dollar-sign"></i> ការទូទាត់
                            </a>
                            <button type="button" class="btn btn-warning btn-block shadow-sm" data-toggle="modal" data-target="#earlySettleModal">
                                <strong><i data-feather="fast-forward"></i> បង់ផ្តាច់មុន (Early Settle)</strong>
                            </button>
                            @if($loan->account && $loan->account->days_past_due > 30)
                                <button type="button" class="btn btn-danger btn-block shadow-sm mt-2" data-toggle="modal" data-target="#markDefaultModal">
                                    <strong><i data-feather="alert-triangle"></i> ចាត់ទុកជាបំណុលខូច (Mark Default)</strong>
                                </button>
                            @endif
                        @elseif($loan->status === 'completed')
                            <div class="alert alert-success py-2 mb-0">
                                <i data-feather="check-circle"></i> <strong>កម្ចីបានបញ្ចប់រួចរាល់</strong>
                            </div>
                        @elseif($loan->status === 'rejected')
                            <div class="alert alert-danger py-2 mb-0">
                                <i data-feather="x-circle"></i> <strong>បានបដិសេធ</strong>
                            </div>
                        @elseif($loan->status === 'defaulted')
                            <div class="alert alert-warning py-2 mb-2">
                                <i data-feather="alert-triangle"></i> <strong>Default — តំនាកតំនងក្រុមការងារ</strong>
                            </div>
                            <button type="button" class="btn btn-dark btn-block shadow-sm" data-toggle="modal" data-target="#writeOffModal">
                                <strong><i data-feather="slash"></i> Write Off កម្ចី</strong>
                            </button>
                        @elseif($loan->status === 'written_off')
                            <div class="alert alert-dark py-2 mb-0">
                                <i data-feather="slash"></i> <strong>Written Off (ខាត)</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Schedule --}}
        @if($loan->schedules->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2"><h5 class="mb-0">កាលវិភាគទូទាត់ ({{ $loan->schedules->count() }} ខែ)</h5></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="payment_schedule" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ថ្ងៃត្រូវបង់</th>
                                    <th>Grace Period</th>
                                    <th>Principal Due</th>
                                    <th>Interest Due</th>
                                    <th>ចំនួនត្រូវបង់</th>
                                    <th>ចំនួនបានបង់</th>
                                    <th>ស្ថានភាព</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($loan->schedules->sortBy('installment_number') as $schedule)
                                    @php
                                        $isOverdue = $schedule->status !== 'paid' &&
                                            \Carbon\Carbon::parse($schedule->grace_period_end_date ?? $schedule->due_date)->lt(now());
                                    @endphp
                                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                        <td>{{ $schedule->installment_number ?? ($loop->index + 1) }}</td>
                                        <td>{{ $schedule->due_date }}</td>
                                        <td>{{ $schedule->grace_period_end_date }}</td>
                                        <td>{{ $schedule->principal_due ? '$'.number_format($schedule->principal_due, 2) : '—' }}</td>
                                        <td>{{ $schedule->interest_due ? '$'.number_format($schedule->interest_due, 2) : '—' }}</td>
                                        <td>${{ number_format($schedule->amount_due, 2) }}</td>
                                        <td>${{ number_format($schedule->amount_paid, 2) }}</td>
                                        <td>
                                            @if($schedule->status === 'paid')
                                                <span class="badge badge-success">បានបង់</span>
                                            @elseif($isOverdue)
                                                <span class="badge badge-danger">ហួសកំណត់ (Overdue)</span>
                                            @else
                                                <span class="badge badge-warning">រង់ចាំ</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- Early Settlement Modal --}}
@if($loan->status === 'active')
<div class="modal fade" id="earlySettleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title font-weight-bold">
                    <i data-feather="fast-forward" class="mr-2"></i> Early Settlement (បង់ផ្តាច់)
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="alert alert-info border-0 shadow-sm rounded mb-4">
                    <i data-feather="info" class="mr-2"></i> <strong>Note:</strong> គ្មានការពិន័យ (No Penalty Applies) សម្រាប់ការបង់ផ្តាច់។
                </div>
                
                <table class="table table-bordered bg-white rounded shadow-sm">
                    <tr>
                        <td class="text-muted">កាលបរិច្ឆេទ (Today):</td>
                        <td class="font-weight-bold text-dark">{{ date('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">ប្រាក់ត្រូវបង់ផ្តាច់ (Outstanding):</td>
                        <td class="text-danger font-weight-bold" style="font-size: 1.25rem;">
                            ${{ number_format($loan->account->outstanding_balance ?? 0, 2) }}
                        </td>
                    </tr>
                </table>
                <p class="text-muted text-center mt-3 mb-0" style="font-size: 0.9rem;">
                    តើអ្នកប្រាកដថាចង់បង់ផ្តាច់កម្ចីនេះទាំងស្រុងមែនទេ?
                </p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-between bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    បោះបង់
                </button>
                <form action="{{ route('loans.early_settle', $loan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning px-4 font-weight-bold shadow-sm">
                        យល់ព្រមបង់ផ្តាច់
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endif

{{-- Mark Default Modal --}}
@if($loan->status === 'active' && $loan->account && $loan->account->days_past_due > 30)
<div class="modal fade" id="markDefaultModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title font-weight-bold text-white">
                    <i data-feather="alert-triangle" class="mr-2 text-white"></i> បញ្ជាក់ការផ្លាស់ប្តូរទៅ Default
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="alert alert-danger border-0 shadow-sm rounded mb-4">
                    <i data-feather="alert-octagon" class="mr-2"></i> <strong>ប្រមានផ្ទាល់ទី:</strong> កម្ចីនេះមានការយឺតយ៉ាវរហូតដល់ <strong>{{ $loan->account->days_past_due }} ថ្ងៃ</strong>។ ការកំណត់វាជា Default នឹងសកម្មភាពផ្លូវច្បាប់។
                </div>
                
                <p class="text-dark">
                    ការបន្តទៅមុខនឹង:
                </p>
                <ul class="text-dark">
                    <li>កំណត់ស្ថានភាពកម្ចីទៅជា <span class="badge badge-danger">Defaulted</span></li>
                    <li>ផ្លាស់ប្តូរស្ថានភាព Guarantors ទាំងអស់ទៅជា <span class="badge badge-danger">Defaulted</span></li>
                    <li>ជូនដំណឹងទៅដល់អ្នកគ្រប់គ្រងដើម្បីចាប់ផ្តើមនីតិវិធីផ្លូវច្បាប់</li>
                </ul>
                <p class="text-danger text-center mt-4 mb-0 font-weight-bold" style="font-size: 0.9rem;">
                    តើអ្នកប្រាកដថាចង់ធ្វើប្រតិបត្តិការនេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់ថយក្រោយបានទេ។
                </p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-between bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    បោះបង់
                </button>
                <form action="{{ route('loans.mark_default', $loan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4 font-weight-bold shadow-sm">
                        យល់ព្រម (Mark Default)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Write Off Modal --}}
@if($loan->status === 'defaulted')
<div class="modal fade" id="writeOffModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white border-0" style="background:#343a40;">
                <h5 class="modal-title font-weight-bold text-white">
                    <i data-feather="slash" class="mr-2"></i> បញ្ជាក់ Write Off កម្ចី
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="alert alert-danger border-0 mb-3">
                    <strong><i data-feather="alert-octagon" class="mr-1"></i> ការប្រយត្ននេះមិនអាចត្រឡប់ថយក្រោយបានទេ!</strong>
                    កម្ចីនេះនឹងត្រូវបានដោយធ្វើប្រក័ជ្អដធ្វើឋើមនេ (Bad Debt)។ ទ្រព្យបញ្ចាំនឹងត្រូវបានរឹបអូស។
                </div>

                <table class="table table-bordered bg-white shadow-sm rounded mb-3">
                    <tr>
                        <td class="text-muted">ប្រាក់បំណុលខូច (Outstanding):</td>
                        <td class="text-danger font-weight-bold" style="font-size:1.2rem;">
                            ${{ number_format($loan->account->outstanding_balance ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">ការទូទាត់ស្ថានភាពកំណត់:</td>
                        <td><span class="badge badge-dark">Written Off</span></td>
                    </tr>
                    @if($loan->collateral_required)
                    <tr>
                        <td class="text-muted">ត្រភ្យបញ្ចាំ:</td>
                        <td><span class="badge badge-dark">Seized</span></td>
                    </tr>
                    @endif
                </table>

                <div class="form-group mb-0">
                    <label class="font-weight-bold text-danger">វាយបញ្ចូល <code>CONFIRM</code> ដើម្បីបញ្ជាក់:</label>
                    <input type="text" id="writeOffConfirmInput" class="form-control"
                           placeholder="Type CONFIRM to proceed" autocomplete="off">
                    <small class="text-muted">សូម​វាយ​ CONFIRM (ជាអក្សរធំ) ដើម្បី​បើក​ប៊ូតុង​ commit</small>
                </div>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-between bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    បោះបង់
                </button>
                <form action="{{ route('loans.write_off', $loan->id) }}" method="POST" id="writeOffForm">
                    @csrf
                    <button type="submit" id="writeOffSubmitBtn" class="btn btn-dark px-4 font-weight-bold shadow-sm" disabled>
                        <i data-feather="slash"></i> យល់ព្រម Write Off
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
    $(document).ready(function() {
        "use strict";
        $(".preloader").fadeOut();
        if ($("#payment_schedule").length) {
            try {
                $("#payment_schedule").DataTable({
                    pageLength: 12, responsive: true,
                    language: {
                        search: "ស្វែងរក៖", lengthMenu: "បង្ហាញ _MENU_ បញ្ជី",
                        info: "បង្ហាញ _START_–_END_ / _TOTAL_",
                        paginate: { next: "បន្ទាប់", previous: "មុន" }
                    }
                });
            } catch (e) { console.error(e); }
        }
        if (typeof feather !== "undefined") feather.replace();

        // Write Off CONFIRM guard
        var confirmInput = document.getElementById('writeOffConfirmInput');
        var submitBtn   = document.getElementById('writeOffSubmitBtn');
        if (confirmInput && submitBtn) {
            confirmInput.addEventListener('input', function () {
                submitBtn.disabled = this.value.trim() !== 'CONFIRM';
            });
        }
        // Reset on modal close
        $('#writeOffModal').on('hidden.bs.modal', function () {
            if (confirmInput) confirmInput.value = '';
            if (submitBtn)   submitBtn.disabled = true;
        });
    });
    </script>
@endpush
