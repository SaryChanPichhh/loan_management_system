@extends('backend.layout.master') @section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3
                        class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2"
                    >
                        កែសម្រួលព័ត៌មានកម្ចី
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
                                    កែសម្រួល
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
                            <h4 class="card-title">ទម្រង់កែសម្រួលព័ត៌មានកម្ចី</h4>
                            <p class="text-warning">
                                <i data-feather="alert-circle"></i> អាចកែសម្រួលបានតែនៅពេលស្ថានភាពស្ថិតក្នុង <strong>កំពុងរង់ចាំ (Pending)</strong> ប៉ុណ្ណោះ
                            </p>
                            <form id="editLoanForm">
                                <div class="form-group">
                                    <label for="edit_loan_amount"
                                    >ចំនួនទឹកប្រាក់កម្ចី ($)
                                        <span class="text-danger">*</span></label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="edit_loan_amount"
                                        name="loan_amount"
                                        value="5000"
                                        min="0"
                                        step="0.01"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="edit_interest_rate"
                                    >អត្រាការប្រាក់ (%)
                                        <span class="text-danger">*</span></label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="edit_interest_rate"
                                        name="interest_rate"
                                        value="12"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="edit_duration"
                                    >រយៈពេល (ខែ)
                                        <span class="text-danger">*</span></label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="edit_duration"
                                        name="duration"
                                        value="12"
                                        min="1"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="save"></i> ធ្វើបច្ចុប្បន្នភាពកម្ចី
                                    </button>
                                    <a
                                        href="{{ route('loans.show', 1) }}"
                                        class="btn btn-secondary"
                                    >
                                        <i data-feather="x"></i> បោះបង់
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">តម្លៃគណនាដោយស្វ័យប្រវត្តិ</h4>
                            <div class="form-group">
                                <label>ប្រាក់បង់ប្រចាំខែ</label>
                                <h3
                                    class="text-primary"
                                    id="edit_monthly_installment"
                                >
                                    $444.24
                                </h3>
                            </div>
                            <div class="form-group">
                                <label>ការប្រាក់សរុប</label>
                                <h4 class="text-info" id="edit_total_interest">
                                    $330.88
                                </h4>
                            </div>
                            <div class="form-group">
                                <label>ទឹកប្រាក់សរុបត្រូវសង</label>
                                <h4 class="text-success" id="edit_total_payable">
                                    $5,330.88
                                </h4>
                            </div>
                            <hr />
                            <div class="form-group">
                                <small class="text-muted"
                                >* តម្លៃនឹងធ្វើបច្ចុប្បន្នភាពដោយស្វ័យប្រវត្តិនៅពេលអ្នកកែសម្រួល</small
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection @push('scripts')
    <script>
        $(document).ready(function () {
            "use strict";

            // Ensure preloader is hidden
            $(".preloader").fadeOut();

            // Calculate loan details
            function calculateEditLoan() {
                const loanAmount = parseFloat($("#edit_loan_amount").val()) || 0;
                const interestRate =
                    parseFloat($("#edit_interest_rate").val()) || 0;
                const duration = parseInt($("#edit_duration").val()) || 0;

                if (loanAmount > 0 && interestRate > 0 && duration > 0) {
                    const monthlyRate = interestRate / 100 / 12;
                    const monthlyInstallment =
                        (loanAmount *
                            monthlyRate *
                            Math.pow(1 + monthlyRate, duration)) /
                        (Math.pow(1 + monthlyRate, duration) - 1);
                    const totalPayable = monthlyInstallment * duration;
                    const totalInterest = totalPayable - loanAmount;

                    $("#edit_monthly_installment").text(
                        "$" + monthlyInstallment.toFixed(2)
                    );
                    $("#edit_total_interest").text("$" + totalInterest.toFixed(2));
                    $("#edit_total_payable").text("$" + totalPayable.toFixed(2));
                }
            }

            // Auto-calculate on input change
            $("#edit_loan_amount, #edit_interest_rate, #edit_duration").on(
                "input",
                calculateEditLoan
            );

            // Form submission
            $("#editLoanForm").on("submit", function (e) {
                e.preventDefault();
                alert("ព័ត៌មានកម្ចីត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!");
                window.location.href = '{{ route("loans.show", 1) }}';
            });

            // Initialize Feather Icons
            if (typeof feather !== "undefined" && feather) {
                feather.replace();
            }
        });
    </script>
@endpush
