@extends('backend.layout.master') @push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush @section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">ការបង់ប្រាក់កម្ចី</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('loans.show', $loan->id) }}">{{ $loan->loan_code }}</a></li>
                            <li class="breadcrumb-item active">ការបង់ប្រាក់</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">តារាងកាលវិភាគបង់ប្រាក់</h4>
                            <div class="table-responsive">
                                <table id="payments_table" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ខែ</th>
                                        <th>ថ្ងៃត្រូវបង់</th>
                                        <th>ចំនួនទឹកប្រាក់</th>
                                        <th>បានបង់?</th>
                                        <th>ចំនួនបានបង់</th>
                                        <th>ស្ថានភាព</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($loan->schedules as $i => $schedule)
                                        @php
                                            $isOverdue = $schedule->status !== 'paid' && \Carbon\Carbon::parse($schedule->due_date)->lt(now());
                                        @endphp
                                        <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $schedule->due_date }}</td>
                                            <td>${{ number_format($schedule->amount_due, 2) }}</td>
                                            <td>
                                                @if($schedule->status === 'paid')
                                                    <span class="badge badge-success">បាទ/ចាស</span>
                                                @else
                                                    <span class="badge badge-danger">ទេ</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($schedule->amount_paid, 2) }}</td>
                                            <td>
                                                @if($schedule->status === 'paid')
                                                    <span class="badge badge-success">បានបង់</span>
                                                @elseif($isOverdue)
                                                    <span class="badge badge-danger">ហួសកំណត់</span>
                                                @else
                                                    <span class="badge badge-warning">កំពុងរង់ចាំ</span>
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

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">សរុបបានបង់</h5>
                            <h3 class="text-success">${{ number_format($totalPaid, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">សមតុល្យនៅសល់</h5>
                            <h3 class="text-danger">${{ number_format($remainingBalance, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ការបង់ប្រាក់ហួសកំណត់</h5>
                            <h3 class="text-danger">{{ $overdueCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection @push('scripts')
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            "use strict";
            $(".preloader").fadeOut();
            if ($("#payments_table").length) {
                try {
                    $("#payments_table").DataTable({
                        pageLength: 12, responsive: true, order: [[0, "asc"]],
                        language: { search: "ស្វែងរក៖", lengthMenu: "បង្ហាញ _MENU_ បញ្ជី", info: "បង្ហាញពីលេខ _START_ ដល់ _END_ នៃបញ្ជីសរុប _TOTAL_", paginate: { next: "បន្ទាប់", previous: "មុន" } }
                    });
                } catch (e) { console.error("DataTable error:", e); }
            }
            if (typeof feather !== "undefined" && feather) { feather.replace(); }
        });
    </script>
@endpush
