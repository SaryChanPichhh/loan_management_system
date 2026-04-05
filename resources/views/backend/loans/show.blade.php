@extends('backend.layout.master')
@push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
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
                        <form action="{{ route('loans.disburse', $loan->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('តើអ្នកពិតជាចង់បើកប្រាក់កម្ចីនេះមែនទេ?')">
                                <i data-feather="dollar-sign"></i> បើកប្រាក់ (Disburse)
                            </button>
                        </form>
                    @elseif($loan->status === 'active')
                        <a href="{{ route('loans.payments', $loan->id) }}" class="btn btn-primary btn-sm">
                            <i data-feather="dollar-sign"></i> ការទូទាត់
                        </a>
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
                'completed'    => 'alert-secondary',
                'rejected'     => 'alert-danger',
                'defaulted'    => 'alert-danger',
                'written_off'  => 'alert-dark',
                default        => 'alert-light',
            };
        @endphp
        <div class="alert {{ $bannerClass }} d-flex align-items-center mb-3">
            <span class="badge {{ $loan->statusBadge() }} mr-2" style="font-size:1rem;">{{ $loan->statusLabel() }}</span>
            <span>{{ $loan->loan_code }}</span>
            @if($loan->application)
                <span class="ml-3 text-muted"><small>ពីសំណើ: {{ $loan->application->application_code }}</small></span>
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
                                <p>{{ optional($loan->grace_period_end_date)->format('d/m/Y') ?? '—' }}</p>
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
                                        <span class="badge badge-light">ទេ</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Collateral Required</p>
                                <p>
                                    @if($loan->collateral_required)
                                        <span class="badge badge-warning">បាទ</span>
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
                                    <span class="badge {{ $g->status === 'active' ? 'badge-success' : 'badge-secondary' }} mr-2">{{ $g->status }}</span>
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
                            <form action="{{ route('loans.disburse', $loan->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('តើអ្នកពិតជាចង់បើកប្រាក់កម្ចីនេះមែនទេ?')">
                                    <i data-feather="dollar-sign"></i> បើកប្រាក់ (Disburse)
                                </button>
                            </form>
                        @elseif($loan->status === 'active')
                            <a href="{{ route('loans.payments', $loan->id) }}" class="btn btn-info btn-block">
                                <i data-feather="dollar-sign"></i> ការទូទាត់
                            </a>
                        @elseif($loan->status === 'completed')
                            <div class="alert alert-success py-2 mb-0">
                                <i data-feather="check-circle"></i> <strong>កម្ចីបានបញ្ចប់រួចរាល់</strong>
                            </div>
                        @elseif($loan->status === 'rejected')
                            <div class="alert alert-danger py-2 mb-0">
                                <i data-feather="x-circle"></i> <strong>បានបដិសេធ</strong>
                            </div>
                        @elseif($loan->status === 'defaulted')
                            <div class="alert alert-warning py-2 mb-0">
                                <i data-feather="alert-triangle"></i> <strong>Default — ទំនាក់ទំនងក្រុមការងារ</strong>
                            </div>
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
                                    <th>ចំនួនត្រូវបង់</th>
                                    <th>ចំនួនបានបង់</th>
                                    <th>ស្ថានភាព</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($loan->schedules as $i => $schedule)
                                    @php
                                        $isOverdue = $schedule->status !== 'paid' &&
                                            \Carbon\Carbon::parse($schedule->due_date)->lt(now());
                                    @endphp
                                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $schedule->due_date }}</td>
                                        <td>${{ number_format($schedule->amount_due, 2) }}</td>
                                        <td>${{ number_format($schedule->amount_paid, 2) }}</td>
                                        <td>
                                            @if($schedule->status === 'paid')
                                                <span class="badge badge-success">បានបង់</span>
                                            @elseif($isOverdue)
                                                <span class="badge badge-danger">ហួសកំណត់</span>
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
        {{-- Payment Schedule as like calendar --}}
        @if($loan->schedules->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2">
                        <h5 class="mb-0">កាលវិភាគទូទាត់ជាប្រតិទិន (Payment Schedule Calendar)</h5>
                    </div>
                    <div class="card-body">
                        <div id="payment-calendar"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
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

        // Payment Schedule Calendar
        @if($loan->schedules->count() > 0)
        var calendarEl = document.getElementById('payment-calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                @foreach($loan->schedules as $schedule)
                {
                    title: '${{ number_format($schedule->amount_due, 2) }}',
                    start: '{{ $schedule->due_date }}',
                    @php
                        $eventColor = match($schedule->status) {
                            'paid' => '#28a745', // green
                            default => \Carbon\Carbon::parse($schedule->due_date)->lt(now()) ? '#dc3545' : '#ffc107' // red if overdue, yellow if pending
                        };
                    @endphp
                    backgroundColor: '{{ $eventColor }}',
                    borderColor: '{{ $eventColor }}',
                    textColor: '#ffffff',
                    extendedProps: {
                        status: '{{ $schedule->status }}',
                        amountPaid: '${{ number_format($schedule->amount_paid, 2) }}',
                        isOverdue: {{ \Carbon\Carbon::parse($schedule->due_date)->lt(now()) && $schedule->status !== 'paid' ? 'true' : 'false' }}
                    }
                }@if(!$loop->last),@endif
                @endforeach
            ],
            eventClick: function(info) {
                var props = info.event.extendedProps;
                var statusText = props.status === 'paid' ? 'បានបង់' : (props.isOverdue ? 'ហួសកំណត់' : 'រង់ចាំ');
                var statusClass = props.status === 'paid' ? 'badge-success' : (props.isOverdue ? 'badge-danger' : 'badge-warning');

                // Show event details in a modal or alert
                alert('ថ្ងៃត្រូវបង់: ' + info.event.start.toLocaleDateString() + '\n' +
                      'ចំនួនត្រូវបង់: ' + info.event.title + '\n' +
                      'ចំនួនបានបង់: ' + props.amountPaid + '\n' +
                      'ស្ថានភាព: ' + statusText);
            },
            eventMouseEnter: function(info) {
                // Optional: Show tooltip on hover
            }
        });
        calendar.render();
        @endif

        if (typeof feather !== "undefined") feather.replace();
    });
    </script>
@endpush
