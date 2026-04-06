@extends('backend.layout.master')

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">បង្កើតសំណើសុំកម្ចីថ្មី</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('loan_applications.index') }}">បញ្ជីសំណើសុំកម្ចី</a></li>
                            <li class="breadcrumb-item active" aria-current="page">បង្កើតថ្មី</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('loan_applications.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <h4 class="card-title">ព័ត៌មានសំណើ</h4>
                                <hr>
                                
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>លេខកូដសំណើ <span class="text-danger">*</span></label>
                                            <input type="text" name="application_code" class="form-control" value="{{ old('application_code', $application_code) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ជ្រើសរើសអតិថិជន <span class="text-danger">*</span></label>
                                            <select name="customer_id" class="form-control" required>
                                                <option value="">-- ជ្រើសរើស --</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }} (Monthly income: ${{ $customer->monthly_income }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none" id="guarantors-row">
                                    <div class="col-md-12">
                                        <div class="card bg-light border mb-3">
                                            <div class="card-body py-2 px-3">
                                                <h6 class="card-title text-dark mb-2 font-weight-bold"><i class="fas fa-users mr-1"></i> អ្នកធានារបស់អតិថិជន (Guarantors)</h6>
                                                <div id="guarantor-list-container" class="mb-2"></div>
                                                <a href="{{ route('guarantors.create') }}" target="_blank" class="btn btn-sm btn-outline-primary" id="btn-create-guarantor" style="display: none;">
                                                    <i class="fas fa-plus mr-1"></i> បង្កើតអ្នកធានាថ្មី
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ជ្រើសរើសផលិតផលកម្ចី <span class="text-danger">*</span></label>
                                            <select name="product_id" class="form-control" required>
                                                <option value="">-- ជ្រើសរើស --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} (Min: ${{ $product->min_amount }} - Max: ${{ $product->max_amount }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ទំហំប្រាក់ស្នើសុំ ($) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="requested_amount" class="form-control" value="{{ old('requested_amount') }}" required min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>រយៈពេលស្នើសុំ (ខែ) <span class="text-danger">*</span></label>
                                            <input type="number" name="requested_months" class="form-control" value="{{ old('requested_months') }}" required min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ថ្ងៃចាប់ផ្តើម</label>
                                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" min="{{ date('Y-m-01') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ថ្ងៃបញ្ចប់</label>
                                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>គោលបំណង</label>
                                            <textarea name="purpose" class="form-control" rows="3">{{ old('purpose') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="requirement-panel" class="alert alert-info d-none mt-3">
                                            <h5 class="alert-heading font-weight-bold"><i class="fas fa-info-circle mr-2"></i> លក្ខខណ្ឌតម្រូវ (Requirements):</h5>
                                            <ul id="requirement-list" class="mb-0 ml-3 text-dark">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-info">រក្សាទុក</button>
                                    <a href="{{ route('loan_applications.index') }}" class="btn btn-dark">បោះបង់</a>
                                </div>
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
    $(document).ready(function() {
        const products = {!! $products->toJson() !!};
        const customers = {!! $customers->toJson() !!};
        
        const customerSelect = $('select[name="customer_id"]');
        const productSelect = $('select[name="product_id"]');
        const amountInput = $('input[name="requested_amount"]');
        const monthsInput = $('input[name="requested_months"]');
        
        const guarantorsRow = $('#guarantors-row');
        const guarantorListContainer = $('#guarantor-list-container');
        const btnCreateGuarantor = $('#btn-create-guarantor');

        function validateAmount() {
            const productId = productSelect.val();
            const amount = parseFloat(amountInput.val());
            
            if (productId && amount) {
                const product = products.find(p => p.id == productId);
                if (product) {
                    const min = parseFloat(product.min_amount);
                    const max = parseFloat(product.max_amount);
                    
                    if (amount < min || amount > max) {
                        amountInput.addClass('is-invalid');
                        if (!$('#amount-error').length) {
                            amountInput.after(`<div id="amount-error" class="invalid-feedback">ប្រាក់ស្នើសុំត្រូវនៅចន្លោះ $${min} ដល់ $${max}</div>`);
                        } else {
                            $('#amount-error').text(`ប្រាក់ស្នើសុំត្រូវនៅចន្លោះ $${min} ដល់ $${max}`);
                        }
                    } else {
                        amountInput.removeClass('is-invalid');
                        $('#amount-error').remove();
                    }
                }
            } else {
                amountInput.removeClass('is-invalid');
                $('#amount-error').remove();
            }
        }

        function validateMonths() {
            const productId = productSelect.val();
            const months = parseInt(monthsInput.val());
            
            if (productId && months) {
                const product = products.find(p => p.id == productId);
                if (product) {
                    const maxMonths = parseInt(product.max_term_months);
                    
                    if (months > maxMonths) {
                        monthsInput.addClass('is-invalid');
                        if (!$('#months-error').length) {
                            monthsInput.after(`<div id="months-error" class="invalid-feedback">រយៈពេលស្នើសុំមិនអាចលើសពី ${maxMonths} ខែ</div>`);
                        } else {
                            $('#months-error').text(`រយៈពេលស្នើសុំមិនអាចលើសពី ${maxMonths} ខែ`);
                        }
                    } else {
                        monthsInput.removeClass('is-invalid');
                        $('#months-error').remove();
                    }
                }
            } else {
                monthsInput.removeClass('is-invalid');
                $('#months-error').remove();
            }
        }

        function updateRequirements() {
            const productId = productSelect.val();
            const customerId = customerSelect.val();
            const amount = parseFloat(amountInput.val());
            const months = parseInt(monthsInput.val());
            
            const reqPanel = $('#requirement-panel');
            const reqList = $('#requirement-list');
            reqList.empty();

            if (!productId || isNaN(amount) || amount <= 0 || isNaN(months) || months <= 0) {
                reqPanel.addClass('d-none');
                return;
            }

            const product = products.find(p => p.id == productId);
            const customer = customerId ? customers.find(c => c.id == customerId) : null;
            if (!product) return;

            reqPanel.removeClass('d-none');
            
            // Estimate monthly payment
            let rate = product.interest_rate ? parseFloat(product.interest_rate) / 100 / 12 : 0;
            let monthlyPayment = amount / months;
            if (rate > 0) {
                monthlyPayment = (amount * rate * Math.pow(1 + rate, months)) / (Math.pow(1 + rate, months) - 1);
            }

            let customerPassesIncomeRule = true;
            let multiplier = product.guarantor_income_multiplier ? parseFloat(product.guarantor_income_multiplier) : 1.5;
            
            if (amount >= 500) {
                const reqCustomerIncome = monthlyPayment * multiplier;
                if (!customer || !customer.monthly_income || parseFloat(customer.monthly_income) < reqCustomerIncome) {
                    customerPassesIncomeRule = false;
                    reqPanel.removeClass('alert-info alert-warning alert-danger').addClass('alert-danger');
                    reqList.append(`<li><strong>ចំណូលអតិថិជនមិនគ្រប់គ្រាន់:</strong> ត្រូវមានយ៉ាងហោចណាស់ <strong class="text-danger">$${reqCustomerIncome.toFixed(2)} /ខែ</strong> (${multiplier} ដង នៃប្រាក់ត្រូវបង់ $${monthlyPayment.toFixed(2)})</li>`);
                } else {
                    reqList.append(`<li><strong class="text-success">ចំណូលអតិថិជនគ្រប់គ្រាន់:</strong> មាន $${parseFloat(customer.monthly_income).toFixed(2)} (ធំជាងកម្រិតទាបបំផុត $${reqCustomerIncome.toFixed(2)})</li>`);
                }
            }

            if (amount < 500) {
                if (customerPassesIncomeRule) reqPanel.removeClass('alert-warning alert-danger').addClass('alert-info');
                reqList.append('<li><strong>ក្រោម $500:</strong> មិនតម្រូវឲ្យមានទ្រព្យបញ្ចាំ ឬអ្នកធានាឡើយ។ (No collateral needed)</li>');
            } else if (amount >= 500) {
                if (customerPassesIncomeRule) reqPanel.removeClass('alert-info alert-danger').addClass('alert-warning');
                if (multiplier > 0) {
                    const reqIncome = monthlyPayment * multiplier;
                    reqList.append(`<li><strong>ចាប់ពី $500 ឡើងទៅ:</strong> តម្រូវឲ្យមានអ្នកធានា (Guarantor required)</li>`);
                    reqList.append(`<li>ចំណូលអ្នកធានាត្រូវមានយ៉ាងហោចណាស់: <strong class="text-danger">$${reqIncome.toFixed(2)} /ខែ</strong> (${multiplier} ដងនៃប្រាក់សងសរុប)</li>`);
                } else {
                    reqList.append(`<li><strong>ចាប់ពី $500 ឡើងទៅ:</strong> មិនតម្រូវឲ្យមានអ្នកធានាសម្រាប់ផលិតផលនេះទេ (មេគុណ = 0)</li>`);
                }
            }
        }

        function updateGuarantorView() {
            const customerId = customerSelect.val();
            if (!customerId) {
                guarantorsRow.addClass('d-none');
                return;
            }

            const customer = customers.find(c => c.id == customerId);
            guarantorsRow.removeClass('d-none');
            guarantorListContainer.empty();

            if (customer && customer.guarantors && customer.guarantors.length > 0) {
                let html = '<ul class="list-unstyled mb-0">';
                customer.guarantors.forEach(g => {
                    html += `<li>- <strong>${g.full_name}</strong> (ចំណូល: $${parseFloat(g.income || 0).toFixed(2)} | ទំនង: ${g.relationship || 'N/A'}) <span class="badge badge-success ml-1">សកម្ម</span></li>`;
                });
                html += '</ul>';
                guarantorListContainer.html(html);
                btnCreateGuarantor.hide();
            } else {
                guarantorListContainer.html('<span class="text-danger">មិនមានអ្នកធានាសកម្មទេ!</span>');
                btnCreateGuarantor.show();
            }
        }

        customerSelect.on('change', function() {
            updateGuarantorView();
            updateRequirements();
        });

        productSelect.on('change', function() {
            validateAmount();
            validateMonths();
            updateRequirements();
        });

        amountInput.on('input', function() {
            validateAmount();
            updateRequirements();
        });
        monthsInput.on('input', function() {
            validateMonths();
            updateRequirements();
            updateEndDate();
        });

        const startDateInput = $('input[name="start_date"]');
        const endDateInput = $('input[name="end_date"]');

        function updateEndDate() {
            const startDate = startDateInput.val();
            const months = parseInt(monthsInput.val());
            
            if (startDate && !isNaN(months)) {
                let date = new Date(startDate);
                date.setMonth(date.getMonth() + months);
                
                const year = date.getFullYear();
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');
                
                endDateInput.val(`${year}-${month}-${day}`);
            }
        }

        startDateInput.on('change', updateEndDate);
        
        // Run once on load if needed
        updateEndDate();
    });
</script>
@endpush
