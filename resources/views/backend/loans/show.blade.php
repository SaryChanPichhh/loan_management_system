@extends('backend.layout.master')
@push('styles')
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
                        ព័ត៌មានលម្អិតនៃកម្ចី
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
                                    <a href="{{ route('loans.index') }}">កម្ចី</a>
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
            </div>
        </div>
        <div class="container-fluid">
            @php $loanStatus = 'pending'; @endphp

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
                                <div class="col-md-3">
                                    <p class="text-muted mb-0">ចំនួនទឹកប្រាក់កម្ចី</p>
                                    <h5>$5,000.00</h5>
                                </div>
                                <div class="col-md-2">
                                    <p class="text-muted mb-0">អត្រាការប្រាក់</p>
                                    <h5>12%</h5>
                                </div>
                                <div class="col-md-2">
                                    <p class="text-muted mb-0">រយៈពេល</p>
                                    <h5>12 ខែ</h5>
                                </div>
                                <div class="col-md-2">
                                    <p class="text-muted mb-0">ស្ថានភាព</p>
                                    @if($loanStatus === 'pending')
                                        <span class="badge badge-warning">កំពុងរង់ចាំ</span>
                                    @elseif($loanStatus === 'approved')
                                        <span class="badge badge-success"
                                        >បានអនុម័ត</span
                                        >
                                    @elseif($loanStatus === 'active')
                                        <span class="badge badge-primary">កំពុងដំណើរការ</span>
                                    @elseif($loanStatus === 'completed')
                                        <span class="badge badge-info">បានបញ្ចប់</span>
                                    @elseif($loanStatus === 'rejected')
                                        <span class="badge badge-danger">បដិសេធ</span>
                                    @elseif($loanStatus === 'defaulted')
                                        <span class="badge badge-danger"
                                        >មិនបានសង</span
                                        >
                                    @endif
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">ថ្ងៃបង្កើត</p>
                                    <p>2024-01-15</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ព័ត៌មានលម្អិតអំពីហិរញ្ញវត្ថុ</h4>
                            <table class="table">
                                <tr>
                                    <td><strong>ប្រាក់បង់ប្រចាំខែ</strong></td>
                                    <td class="text-right">$444.24</td>
                                </tr>
                                <tr>
                                    <td><strong>ការប្រាក់សរុប</strong></td>
                                    <td class="text-right">$330.88</td>
                                </tr>
                                <tr>
                                    <td><strong>ទឹកប្រាក់សរុបត្រូវសង</strong></td>
                                    <td class="text-right">$5,330.88</td>
                                </tr>
                                <tr>
                                    <td><strong>សមតុល្យនៅសល់</strong></td>
                                    <td class="text-right text-danger">
                                        $5,330.88
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">សកម្មភាពរហ័ស</h4>

                            {{-- Pending Status: Link to Review Page --}}
                            @if($loanStatus === 'pending')
                                <div class="alert alert-info">
                                    <i data-feather="info" class="mr-2"></i>
                                    <strong>កម្ចីកំពុងរង់ចាំការពិនិត្យ។</strong>
                                </div>
                                <a
                                    href="{{ route('loans.review', 1) }}"
                                    class="btn btn-primary btn-block"
                                >
                                    <i data-feather="file-text"></i> ពិនិត្យពាក្យស្នើសុំកម្ចី
                                </a>
                            @endif

                            {{-- Approved Status: Link to Activate --}}
                            @if($loanStatus === 'approved')
                                <div class="alert alert-success">
                                    <i data-feather="check-circle" class="mr-2"></i>
                                    <strong>កម្ចីនេះត្រូវបានអនុម័តហើយ។</strong>
                                </div>
                                <a href="#" class="btn btn-primary btn-block">
                                    <i data-feather="play"></i> ដំណើរការបញ្ចេញកម្ចី
                                </a>
                            @endif

                            {{-- Active Status: Link to Record Payment --}}
                            @if($loanStatus === 'active')
                                <div class="alert alert-primary">
                                    <i data-feather="activity" class="mr-2"></i>
                                    <strong>កម្ចីកំពុងមានសកម្មភាពបច្ចុប្បន្ន។</strong>
                                </div>
                                <a
                                    href="{{ route('loans.payments', 1) }}"
                                    class="btn btn-info btn-block"
                                >
                                    <i data-feather="dollar-sign"></i> កត់ត្រាការបង់ប្រាក់
                                </a>
                            @endif

                            {{-- Completed Status: View Only --}}
                            @if($loanStatus === 'completed')
                                <div class="alert alert-success">
                                    <i data-feather="check-circle" class="mr-2"></i>
                                    <strong>កម្ចីត្រូវបានបង់បញ្ចប់រួចរាល់។</strong>
                                </div>
                                <p class="text-muted mb-0">
                                    <i data-feather="eye" class="mr-2"></i>
                                    ព័ត៌មានកម្ចីនេះសម្រាប់តែមើលប៉ុណ្ណោះ។ ការបង់ប្រាក់ទាំងអស់ត្រូវបានបញ្ចប់។
                                </p>
                            @endif

                            {{-- Rejected Status: View Only --}}
                            @if($loanStatus === 'rejected')
                                <div class="alert alert-danger">
                                    <i data-feather="x-circle" class="mr-2"></i>
                                    <strong>កម្ចីនេះត្រូវបានបដិសេធ។</strong>
                                </div>
                                <p class="text-muted mb-0">
                                    <i data-feather="eye" class="mr-2"></i>
                                    ព័ត៌មានកម្ចីនេះសម្រាប់តែមើលប៉ុណ្ណោះ។ ពាក្យស្នើសុំត្រូវបានបដិសេធ។
                                </p>
                            @endif

                            {{-- Defaulted Status: View Only --}}
                            @if($loanStatus === 'defaulted')
                                <div class="alert alert-warning">
                                    <i data-feather="alert-triangle" class="mr-2"></i>
                                    <strong>កម្ចីនេះស្ថិតក្នុងស្ថានភាពមិនបានសង។</strong>
                                </div>
                                <p class="text-muted mb-0">
                                    <i data-feather="eye" class="mr-2"></i>
                                    ព័ត៌មានកម្ចីនេះសម្រាប់តែមើលប៉ុណ្ណោះ។ សូមទាក់ទងក្រុមការងារគ្រប់គ្រងកម្ចីសម្រាប់សកម្មភាពបន្ត។
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">តារាងកាលវិភាគបង់ប្រាក់</h4>
                            <div class="table-responsive">
                                <table
                                    id="payment_schedule"
                                    class="table table-striped table-bordered"
                                >
                                    <thead>
                                    <tr>
                                        <th>ខែ</th>
                                        <th>ថ្ងៃត្រូវបង់</th>
                                        <th>ចំនួនទឹកប្រាក់</th>
                                        <th>បានបង់?</th>
                                        <th>ថ្ងៃបង់ប្រាក់</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2024-02-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-danger"
                                            >ទេ</span
                                            >
                                        </td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>2024-03-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-danger"
                                            >ទេ</span
                                            >
                                        </td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>2024-04-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-danger"
                                            >ទេ</span
                                            >
                                        </td>
                                        <td>-</td>
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

@endsection
@push('scripts')
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
        // Hide preloader immediately
        if (typeof $ !== "undefined") {
            $(document).ready(function () {
                $(".preloader").fadeOut();
            });
        } else {
            window.addEventListener("load", function () {
                var preloader = document.querySelector(".preloader");
                if (preloader) {
                    preloader.style.display = "none";
                }
            });
        }

        $(document).ready(function () {
            "use strict";

            // Initialize Payment Schedule DataTable only if table exists
            if ($("#payment_schedule").length) {
                try {
                    $("#payment_schedule").DataTable({
                        pageLength: 12,
                        responsive: true,
                        language: {
                            search: "ស្វែងរក៖",
                            lengthMenu: "បង្ហាញ _MENU_ បញ្ជី",
                            info: "បង្ហាញពីលេខ _START_ ដល់ _END_ នៃបញ្ជីសរុប _TOTAL_",
                            paginate: {
                                next: "បន្ទាប់",
                                previous: "មុន"
                            }
                        }
                    });
                } catch (e) {
                    console.error("DataTable initialization error:", e);
                }
            }

            // Initialize Feather Icons
            if (typeof feather !== "undefined" && feather) {
                feather.replace();
            }
        });
    </script>
@endpush
