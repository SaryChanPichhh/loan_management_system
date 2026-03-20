@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3
                        class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2"
                    >
                        ពិនិត្យពាក្យស្នើសុំកម្ចី
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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('loans.show', 1) }}"
                                    >LOAN-001</a
                                    >
                                </li>
                                <li
                                    class="breadcrumb-item active"
                                    aria-current="page"
                                >
                                    ពិនិត្យ
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">សេចក្តីសង្ខេបនៃកម្ចី</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">អ្នកខ្ចី</p>
                                    <h5>John Doe</h5>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">ចំនួនទឹកប្រាក់កម្ចី</p>
                                    <h5>$5,000.00</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">អត្រាការប្រាក់</p>
                                    <h5>12%</h5>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">រយៈពេល</p>
                                    <h5>12 ខែ</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">
                                        ប្រាក់បង់ប្រចាំខែ
                                    </p>
                                    <h5>$444.24</h5>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">ទឹកប្រាក់សរុបត្រូវសង</p>
                                    <h5>$5,330.88</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ប្រវត្តិនៃការខ្ចីប្រាក់</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>លេខសម្គាល់កម្ចី</th>
                                        <th>ចំនួនទឹកប្រាក់</th>
                                        <th>ស្ថានភាព</th>
                                        <th>កាលបរិច្ឆេទ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>LOAN-000</td>
                                        <td>$3,000.00</td>
                                        <td>
                                            <span class="badge badge-success"
                                            >បានបញ្ចប់</span
                                            >
                                        </td>
                                        <td>2023-06-15</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ការវាយតម្លៃហានិភ័យ</h4>
                            <div class="form-group">
                                <label for="risk_note">ចំណាំអំពីហានិភ័យ (បើមាន)</label>
                                <textarea
                                    class="form-control"
                                    id="risk_note"
                                    name="risk_note"
                                    rows="3"
                                    placeholder="បន្ថែមចំណាំអំពីការវាយតម្លៃហានិភ័យនៅទីនេះ..."
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">សកម្មភាព</h4>
                            <form id="reviewForm">
                                <div class="form-group">
                                    <label for="review_notes">មតិយោបល់ពិនិត្យ</label>
                                    <textarea
                                        class="form-control"
                                        id="review_notes"
                                        name="review_notes"
                                        rows="4"
                                        placeholder="បន្ថែមមតិយោបល់ពិនិត្យ..."
                                    ></textarea>
                                </div>
                                <div class="form-group">
                                    <button
                                        type="button"
                                        class="btn btn-success btn-block mb-2"
                                        id="approveBtn"
                                    >
                                        <i data-feather="check"></i> អនុម័តកម្ចី
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-block"
                                        id="rejectBtn"
                                    >
                                        <i data-feather="x"></i> បដិសេធកម្ចី
                                    </button>
                                </div>
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

            // Approve Loan
            $("#approveBtn").on("click", function () {
                if (
                    confirm(
                        "តើអ្នកប្រាកដថាចង់អនុម័តកម្ចីនេះមែនទេ? ស្ថានភាពនឹងប្តូរទៅជា 'បានអនុម័ត'។"
                    )
                ) {
                    alert("កម្ចីត្រូវបានអនុម័តដោយជោគជ័យ! ស្ថានភាព៖ បានអនុម័ត (Approved)");
                    window.location.href = '{{ route("loans.show", 1) }}';
                }
            });

            // Reject Loan
            $("#rejectBtn").on("click", function () {
                if (
                    confirm(
                        "តើអ្នកប្រាកដថាចង់បដិសេធកម្ចីនេះមែនទេ? ស្ថានភាពនឹងប្តូរទៅជា 'បដិសេធ'។"
                    )
                ) {
                    alert("កម្ចីត្រូវបានបដិសេធ! ស្ថានភាព៖ បដិសេធ (Rejected)");
                    window.location.href = '{{ route("loans.show", 1) }}';
                }
            });

            // Initialize Feather Icons
            if (typeof feather !== "undefined" && feather) {
                feather.replace();
            }
        });
    </script>
@endpush
