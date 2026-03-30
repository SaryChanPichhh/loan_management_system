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
                                                        {{ $customer->name }} ({{ $customer->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
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
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>គោលបំណង</label>
                                            <textarea name="purpose" class="form-control" rows="3">{{ old('purpose') }}</textarea>
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
        const productSelect = $('select[name="product_id"]');
        const amountInput = $('input[name="requested_amount"]');
        const monthsInput = $('input[name="requested_months"]');

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

        productSelect.on('change', function() {
            validateAmount();
            validateMonths();
        });
        amountInput.on('input', validateAmount);
        monthsInput.on('input', validateMonths);
    });
</script>
@endpush
