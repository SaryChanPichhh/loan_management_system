@extends('backend.layout.master')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    ប្រតិទិនទូទាត់ប្រាក់កម្ចី
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item active">ប្រតិទិនទូទាត់</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center">
                <div class="float-right">
                    <a href="{{ route('loans.index') }}" class="btn btn-secondary btn-sm">
                        <i data-feather="arrow-left"></i> ត្រឡប់ក្រោយ
                    </a>
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

        {{-- Today's Payments Summary --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i data-feather="calendar"></i> ទូទាត់ថ្ងៃនេះ ({{ \Carbon\Carbon::today()->format('d/m/Y') }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($todayPayments->count() > 0)
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h2 class="text-primary">{{ $todayPayments->count() }}</h2>
                                        <p class="text-muted mb-0">ចំនួនអ្នកជំពាក់</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h2 class="text-success">${{ number_format($todayPayments->sum('amount_due'), 2) }}</h2>
                                        <p class="text-muted mb-0">ទឹកប្រាក់សរុប</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h2 class="text-info">${{ number_format($todayPayments->sum('amount_paid'), 2) }}</h2>
                                        <p class="text-muted mb-0">បានទូទាត់</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h2 class="text-danger">${{ number_format($todayPayments->sum('amount_due') - $todayPayments->sum('amount_paid'), 2) }}</h2>
                                        <p class="text-muted mb-0">នៅសល់</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i data-feather="check-circle" class="text-success" style="width: 48px; height: 48px;"></i>
                                <h5 class="mt-2">គ្មានការទូទាត់សម្រាប់ថ្ងៃនេះ</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Calendar --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2">
                        <h5 class="mb-0">ប្រតិទិនទូទាត់ប្រាក់កម្ចី (Payment Calendar)</h5>
                    </div>
                    <div class="card-body">
                        <div id="payment-calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Today's Payment Details --}}
        @if($todayPayments->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2">
                        <h5 class="mb-0">ព័ត៌មានលម្អិតនៃការទូទាត់ថ្ងៃនេះ</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="today-payments-table" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>កូដកម្ចី</th>
                                    <th>អ្នកខ្ចី</th>
                                    <th>ការប្រាក់</th>
                                    <th>ចំនួនត្រូវបង់</th>
                                    <th>ចំនួនបានបង់</th>
                                    <th>ស្ថានភាព</th>
                                    <th>សកម្មភាព</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($todayPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->loan->loan_code ?? 'N/A' }}</td>
                                        <td>{{ $payment->loan->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->installment_number }}</td>
                                        <td>${{ number_format($payment->amount_due, 2) }}</td>
                                        <td>${{ number_format($payment->amount_paid, 2) }}</td>
                                        <td>
                                            @if($payment->status === 'paid')
                                                <span class="badge badge-success">បានបង់</span>
                                            @elseif($payment->status === 'partial')
                                                <span class="badge badge-warning">បង់ផ្នែក</span>
                                            @else
                                                <span class="badge badge-danger">មិនទាន់បង់</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('loans.show', $payment->loan_id) }}" class="btn btn-sm btn-primary">
                                                <i data-feather="eye"></i> មើល
                                            </a>
                                            @if($payment->status !== 'paid')
                                                <a href="{{ route('loans.payments', $payment->loan_id) }}" class="btn btn-sm btn-success ml-1">
                                                    <i data-feather="dollar-sign"></i> បង់ប្រាក់
                                                </a>
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
@endsection

@push('scripts')
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
    $(document).ready(function() {
        "use strict";
        $(".preloader").fadeOut();

        // Today's payments table
        if ($("#today-payments-table").length) {
            try {
                $("#today-payments-table").DataTable({
                    pageLength: 10, responsive: true,
                    language: {
                        search: "ស្វែងរក៖", lengthMenu: "បង្ហាញ _MENU_ បញ្ជី",
                        info: "បង្ហាញ _START_–_END_ / _TOTAL_",
                        paginate: { next: "បន្ទាប់", previous: "មុន" }
                    }
                });
            } catch (e) { console.error(e); }
        }

        // Payment Calendar
        var calendarEl = document.getElementById('payment-calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                @foreach($calendarEvents as $event)
                {
                    title: '{{ $event['title'] }}',
                    start: '{{ $event['date'] }}',
                    backgroundColor: '{{ $event['color'] }}',
                    borderColor: '{{ $event['color'] }}',
                    textColor: '#ffffff',
                    extendedProps: {
                        count: {{ $event['count'] }},
                        totalAmount: {{ $event['total_amount'] }},
                        paidAmount: {{ $event['paid_amount'] }},
                        date: '{{ $event['date'] }}'
                    }
                }@if(!$loop->last),@endif
                @endforeach
            ],
            eventClick: function(info) {
                var props = info.event.extendedProps;
                var modalContent = `
                    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">ការទូទាត់នៅថ្ងៃទី ${props.date}</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-primary">${props.count}</h4>
                                                <p class="text-muted">ចំនួនអ្នកជំពាក់</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-success">$${props.totalAmount.toFixed(2)}</h4>
                                                <p class="text-muted">ទឹកប្រាក់សរុប</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-info">$${props.paidAmount.toFixed(2)}</h4>
                                                <p class="text-muted">បានទូទាត់</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="text-center">
                                        <a href="#" class="btn btn-primary" onclick="viewPaymentsForDate('${props.date}')">
                                            <i data-feather="list"></i> មើលព័ត៌មានលម្អិត
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('body').append(modalContent);
                $('#paymentModal').modal('show');
                $('#paymentModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            },
            eventMouseEnter: function(info) {
                // Optional: Show tooltip
                var props = info.event.extendedProps;
                info.el.title = `${props.count} payments - Total: $${props.totalAmount.toFixed(2)}`;
            }
        });
        calendar.render();

        if (typeof feather !== "undefined") feather.replace();
    });

    function viewPaymentsForDate(date) {
        // You can implement AJAX call here to load payments for specific date
        // For now, just close the modal
        $('#paymentModal').modal('hide');
    }
    </script>
@endpush