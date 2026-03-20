@extends('backend.layout.master') @push('styles')
    <link
        href="{{
        asset(
            'backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css'
        )
    }}"
        rel="stylesheet"
    />
@endpush @section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3
                        class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2"
                    >
                        ព័ត៌មានលម្អិតនៃការសងប្រាក់
                    </h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard.index') }}"
                                    >ផ្ទាំងគ្រប់គ្រង</a
                                    >
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('repayments.index') }}"
                                    >ការសងប្រាក់</a
                                    >
                                </li>
                                <li
                                    class="breadcrumb-item active"
                                    aria-current="page"
                                >
                                    LOAN-001
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a
                            href="{{ route('repayments.index') }}"
                            class="btn btn-secondary"
                        >
                            <i data-feather="arrow-left"></i> ត្រឡប់ក្រោយ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">សេចក្តីសង្ខេបនៃកម្ចី</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="text-muted mb-0">ឈ្មោះអ្នកខ្ចី</p>
                                    <h5>John Doe</h5>
                                </div>
                                <div class="col-md-2">
                                    <p class="text-muted mb-0">ចំនួនទឹកប្រាក់កម្ចី</p>
                                    <h5>$5,000.00</h5>
                                </div>
                                <div class="col-md-2">
                                    <p class="text-muted mb-0">អត្រាការប្រាក់</p>
                                    <h5>12%</h5>
                                </div>
                                <div class="col-md-2">
                                    <p class="text-muted mb-0">រយៈពេលកម្ចី</p>
                                    <h5>12 ខែ</h5>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-muted mb-0">សមតុល្យនៅសល់</p>
                                    <h5 class="text-danger">$3,555.52</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">កាលវិភាគសងប្រាក់</h4>
                            <div class="table-responsive">
                                <table
                                    id="repayment_schedule"
                                    class="table table-striped table-bordered"
                                >
                                    <thead>
                                    <tr>
                                        <th>ដំណាក់កាលទី #</th>
                                        <th>ថ្ងៃត្រូវបង់</th>
                                        <th>ចំនួនទឹកប្រាក់</th>
                                        <th>ស្ថានភាព</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2024-02-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-success"
                                            >បានបង់</span
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>2024-03-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-success"
                                            >បានបង់</span
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>2024-04-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-warning"
                                            >មិនទាន់បង់</span
                                            >
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ប្រវត្តិនៃការបង់ប្រាក់</h4>
                            <div class="table-responsive">
                                <table
                                    id="payment_history"
                                    class="table table-striped table-bordered"
                                >
                                    <thead>
                                    <tr>
                                        <th>ថ្ងៃបង់ប្រាក់</th>
                                        <th>ចំនួនទឹកប្រាក់បានបង់</th>
                                        <th>វិធីសាស្ត្រ</th>
                                        <th>សម្គាល់</th>
                                        <th>ស្ថានភាព</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>2024-02-14</td>
                                        <td>$444.24</td>
                                        <td>សាច់ប្រាក់</td>
                                        <td>ការបង់ប្រាក់ដំណាក់កាលទីមួយ</td>
                                        <td>
                                            <span class="badge badge-success"
                                            >បានបង់</span
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2024-03-12</td>
                                        <td>$444.24</td>
                                        <td>ផ្ទេរតាមធនាគារ</td>
                                        <td>ការបង់ប្រាក់ដំណាក់កាលទីពីរ</td>
                                        <td>
                                            <span class="badge badge-success"
                                            >បានបង់</span
                                            >
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection @push('scripts')
    <script src="{{
        asset(
            'backend_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js'
        )
    }}"></script>
    <script src="{{
        asset(
            'backend_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js'
        )
    }}"></script>
    <script>
        $(document).ready(function () {
            "use strict";

            // Hide preloader immediately
            $(".preloader").fadeOut();

            var khmerDataTable = {
                search: "ស្វែងរក៖",
                lengthMenu: "បង្ហាញ _MENU_ បញ្ជី",
                info: "បង្ហាញពីលេខ _START_ ដល់ _END_ នៃបញ្ជីសរុប _TOTAL_",
                infoEmpty: "មិនមានទិន្នន័យ",
                infoFiltered: "(ចម្រាញ់ចេញពីបញ្ជីសរុប _MAX_)",
                paginate: {
                    first: "ដំបូង",
                    last: "ចុងក្រោយ",
                    next: "បន្ទាប់",
                    previous: "មុន",
                }
            };

            // Initialize Repayment Schedule DataTable
            if ($("#repayment_schedule").length) {
                $("#repayment_schedule").DataTable({
                    pageLength: 12,
                    responsive: true,
                    order: [[0, "asc"]],
                    language: khmerDataTable
                });
            }

            // Initialize Payment History DataTable
            if ($("#payment_history").length) {
                $("#payment_history").DataTable({
                    pageLength: 10,
                    responsive: true,
                    order: [[0, "desc"]],
                    language: khmerDataTable
                });
            }

            if (typeof feather !== "undefined" && feather) {
                feather.replace();
            }
        });
    </script>
@endpush
