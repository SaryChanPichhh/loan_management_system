@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-3" style="padding-bottom: 250px;">
        
        <!-- Header Section -->
        <div class="d-flex align-items-center justify-content-between ml-4 mr-4 mb-4">
            <h4 class="mb-0 font-weight-bold text-dark">
                <a href="{{ route('repayments.index') }}" class="text-muted text-decoration-none mr-2">
                    <i data-feather="arrow-left" style="width: 20px; height: 20px;"></i>
                </a>
                កត់ត្រាការបង់ប្រាក់ថ្មី (New Repayment)
            </h4>
        </div>

        <div class="ml-4 mr-4">
            <div class="card shadow-sm border-0 rounded-xl">
                <div class="card-body p-5">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-lg mb-4">
                            <div class="d-flex align-items-center mb-2 font-weight-bold">
                                <i data-feather="alert-circle" class="mr-2" style="width: 18px; height: 18px;"></i>
                                សូមពិនិត្យមើលកំហុសខាងក្រោម៖
                            </div>
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('repayments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6 border-right pr-md-4">
                                <h6 class="text-primary font-weight-bold mb-4">
                                    <i data-feather="file-text" class="mr-2" style="width: 18px; height: 18px;"></i>ព័ត៌មានកម្ចី (Loan Info)
                                </h6>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-medium text-dark">លេខកម្ចី / Loan Reference <span class="text-danger">*</span></label>
                                    <div class="input-group shadow-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0"><i data-feather="hash" style="color: #6c757d; width: 16px;"></i></span>
                                        </div>
                                        <input type="text" name="loan_reference" class="form-control border-left-0 font-weight-bold" value="{{ old('loan_reference', $loan_id ?? '') }}" placeholder="ឧ. LN-00123" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-medium text-dark">ឈ្មោះអតិថិជន / Customer Name <span class="text-danger">*</span></label>
                                    <div class="input-group shadow-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0"><i data-feather="users" style="color: #6c757d; width: 16px;"></i></span>
                                        </div>
                                        <select name="customer_name" class="form-control border-left-0 custom-select" required>
                                            <option value="" disabled {{ old('customer_name') ? '' : 'selected' }}>ជ្រើសរើសអតិថិជន (Select Customer)...</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->name }}" {{ old('customer_name') == $customer->name ? 'selected' : '' }}>
                                                    {{ $customer->name }} {{ $customer->code ? '- '.$customer->code : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-medium text-dark">ទឹកប្រាក់បង់ / Amount Repaid ($) <span class="text-danger">*</span></label>
                                    <div class="input-group shadow-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0 text-success font-weight-bold">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control border-left-0 text-success font-weight-bold" value="{{ old('amount') }}" placeholder="0.00" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-medium text-dark">កាលបរិច្ឆេទ / Payment Date <span class="text-danger">*</span></label>
                                    <div class="input-group shadow-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0"><i data-feather="calendar" style="color: #6c757d; width: 16px;"></i></span>
                                        </div>
                                        <input type="date" name="payment_date" class="form-control border-left-0" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="col-md-6 pl-md-4 mt-4 mt-md-0">
                                <h6 class="text-success font-weight-bold mb-4">
                                    <i data-feather="dollar-sign" class="mr-2" style="width: 18px; height: 18px;"></i>ព័ត៌មានប្រតិបត្តិការ (Transaction Info)
                                </h6>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-medium text-dark">វិធីបង់ប្រាក់ / Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-control shadow-sm custom-select" required>
                                        <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>ជ្រើសរើសវិធីសាស្ត្រ...</option>
                                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>សាច់ប្រាក់សុទ្ធ (Cash)</option>
                                        <option value="ABA Bank" {{ old('payment_method') == 'ABA Bank' ? 'selected' : '' }}>ABA Bank (Transfer)</option>
                                        <option value="Acleda" {{ old('payment_method') == 'Acleda' ? 'selected' : '' }}>អេស៊ីលីដា (Acleda)</option>
                                        <option value="Wing" {{ old('payment_method') == 'Wing' ? 'selected' : '' }}>វីង (Wing Money)</option>
                                        <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>ផ្សេងៗ (Other)</option>
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-medium text-dark">លេខកូដប្រតិបត្តិការ / Reference NO.</label>
                                    <div class="input-group shadow-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0"><i data-feather="credit-card" style="color: #6c757d; width: 16px;"></i></span>
                                        </div>
                                        <input type="text" name="reference_number" class="form-control border-left-0" value="{{ old('reference_number') }}" placeholder="លេខ Transaction (ប្រសិនបើមាន)">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-medium text-dark">ស្ថានភាព / Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control shadow-sm custom-select font-weight-bold" required>
                                        <option value="Paid" class="text-success" {{ old('status', 'Paid') == 'Paid' ? 'selected' : '' }}>Paid (បានបង់ជោគជ័យ)</option>
                                        <option value="Pending" class="text-warning" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending (កំពុងរង់ចាំ)</option>
                                        <option value="Failed" class="text-danger" {{ old('status') == 'Failed' ? 'selected' : '' }}>Failed (ប្រតិបត្តិការបរាជ័យ)</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-medium text-dark">កំណត់ចំណាំ / Notes</label>
                                    <textarea name="notes" class="form-control shadow-sm" rows="3" placeholder="ព័ត៌មានបន្ថែមទាក់ទងនឹងការបង់ប្រាក់នេះ...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-3">
                            <a href="{{ route('repayments.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-dark font-weight-bold mr-2">
                                <i data-feather="x" style="width: 16px; margin-right: 5px;"></i> បោះបង់ (Cancel)
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow font-weight-bold custom-btn-primary">
                                <i data-feather="save" style="width: 16px; margin-right: 5px;"></i> រក្សាទុកទិន្នន័យ (Save)
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .rounded-xl { border-radius: 1rem !important; }
        .rounded-lg { border-radius: 0.75rem !important; }
        .form-control, .custom-select { border-radius: 0; outline: none; border: 1px solid #ced4da; }
        .input-group-prepend .input-group-text { border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; border: 1px solid #ced4da; }
        .input-group .form-control { border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }
        .form-control:focus, .custom-select:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15); z-index: 10; }
        .custom-btn-primary { background: linear-gradient(135deg, #007bff, #0056b3); border: none; transition: transform 0.2s; }
        .custom-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,123,255,0.2) !important; }
        textarea.form-control { border-radius: 0.5rem; }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
    @endpush
@endsection
