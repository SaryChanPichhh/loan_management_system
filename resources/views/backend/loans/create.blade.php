@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3
                        class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2"
                    >
                        បង្កើតពាក្យស្នើសុំកម្ចី
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
                                    បង្កើតថ្មី
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
                            <h4 class="card-title">ទម្រង់ពាក្យស្នើសុំកម្ចី</h4>
                            <form id="loanForm">
                                <div class="form-group">
                                    <label for="borrower"
                                    >អ្នកខ្ចី
                                        <span class="text-danger">*</span></label
                                    >
                                    <select
                                        class="form-control"
                                        id="borrower"
                                        name="borrower"
                                        required
                                    >
                                        <option value="">ជ្រើសរើសអ្នកខ្ចី</option>
                                        <option value="1">John Doe</option>
                                        <option value="2">Jane Smith</option>
                                        <option value="3">Robert Johnson</option>
                                        <option value="4">Emily Davis</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="loan_amount"
                                    >ចំនួនទឹកប្រាក់កម្ចី ($)
                                        <span class="text-danger">*</span></label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="loan_amount"
                                        name="loan_amount"
                                        placeholder="បញ្ចូលចំនួនទឹកប្រាក់កម្ចី"
                                        min="0"
                                        step="0.01"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="interest_rate"
                                    >អត្រាការប្រាក់ (%)
                                        <span class="text-danger">*</span></label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="interest_rate"
                                        name="interest_rate"
                                        placeholder="បញ្ចូលអត្រាការប្រាក់"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="duration"
                                    >រយៈពេល (ខែ)
                                        <span class="text-danger">*</span></label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="duration"
                                        name="duration"
                                        placeholder="បញ្ចូលរយៈពេលគិតជាខែ"
                                        min="1"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="start_date"
                                    >ថ្ងៃចាប់ផ្តើម
                                        <span class="text-danger">*</span></label
                                    >
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="start_date"
                                        name="start_date"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        id="saveDraft"
                                    >
                                        <i data-feather="save"></i> រក្សាទុកបណ្តោះអាសន្ន
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="send"></i> បញ្ជូនពាក្យស្នើសុំ
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ការពិនិត្យមើលជាមុន</h4>
                            <div class="preview-section">
                                <div class="form-group">
                                    <label>ប្រាក់បង់ប្រចាំខែ</label>
                                    <h3
                                        class="text-primary"
                                        id="monthly_installment"
                                    >
                                        $0.00
                                    </h3>
                                </div>
                                <div class="form-group">
                                    <label>ការប្រាក់សរុប</label>
                                    <h4 class="text-info" id="total_interest">
                                        $0.00
                                    </h4>
                                </div>
                                <div class="form-group">
                                    <label>ទឹកប្រាក់សរុបត្រូវសង</label>
                                    <h4 class="text-success" id="total_payable">
                                        $0.00
                                    </h4>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <small class="text-muted"
                                    >* ការគណនានឹងធ្វើបច្ចុប្បន្នភាពភ្លាមៗពេលអ្នកបញ្ចូលលេខ</small
                                    >
                                </div>
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
            function calculateLoan() {
                const loanAmount = parseFloat($("#loan_amount").val()) || 0;
                const interestRate = parseFloat($("#interest_rate").val()) || 0;
                const duration = parseInt($("#duration").val()) || 0;

                if (loanAmount > 0 && interestRate > 0 && duration > 0) {
                    const monthlyRate = interestRate / 100 / 12;
                    const monthlyInstallment =
                        (loanAmount *
                            monthlyRate *
                            Math.pow(1 + monthlyRate, duration)) /
                        (Math.pow(1 + monthlyRate, duration) - 1);
                    const totalPayable = monthlyInstallment * duration;
                    const totalInterest = totalPayable - loanAmount;

                    $("#monthly_installment").text(
                        "$" + monthlyInstallment.toFixed(2)
                    );
                    $("#total_interest").text("$" + totalInterest.toFixed(2));
                    $("#total_payable").text("$" + totalPayable.toFixed(2));
                } else {
                    $("#monthly_installment").text("$0.00");
                    $("#total_interest").text("$0.00");
                    $("#total_payable").text("$0.00");
                }
            }

            // Auto-calculate on input change
            $("#loan_amount, #interest_rate, #duration").on("input", calculateLoan);

            // Form submission
            $("#loanForm").on("submit", function (e) {
                e.preventDefault();
                // Status will be Pending after submit
                alert("ពាក្យស្នើសុំកម្ចីត្រូវបានបញ្ជូនដោយជោគជ័យ! ស្ថានភាព៖ កំពុងរង់ចាំពិនិត្យ (Pending)");
                window.location.href = '{{ route("loans.index") }}';
            });

            // Save as draft
            $("#saveDraft").on("click", function () {
                alert("ពាក្យស្នើសុំកម្ចីត្រូវបានរក្សាទុកជាបណ្តោះអាសន្ន!");
            });

            // Initialize Feather Icons
            if (typeof feather !== "undefined" && feather) {
                feather.replace();
            }
        });
    </script>
@endpush
