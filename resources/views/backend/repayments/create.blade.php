@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-3" style="padding-bottom: 250px;">
        
        <!-- Header Section -->
        <div class="d-flex align-items-center justify-content-between ml-4 mr-4 mb-4">
            <h4 class="mb-0 font-weight-bold text-dark">
                <a href="{{ route('repayments.index') }}" class="text-muted text-decoration-none mr-2">
                    <i data-feather="arrow-left" style="width: 20px; height: 20px;"></i>
                </a>
                កត់ត្រាការបង់ប្រាក់ (Record Repayment)
            </h4>
        </div>

        <div class="ml-4 mr-4">
            <div class="row">
                <!-- Form Section -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-xl">
                        <div class="card-body p-4">
                            
                            @if (session('error'))
                                <div class="alert alert-danger rounded-lg mb-4">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('repayments.store') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="text-primary font-weight-bold mb-4">
                                            <i data-feather="file-text" class="mr-2" style="width: 18px; height: 18px;"></i>ព័ត៌មានការបង់ប្រាក់ (Payment Information)
                                        </h6>
                                        
                                        <div class="form-group mb-4">
                                            <label class="form-label font-weight-medium text-dark">ជ្រើសរើសកម្ចី / Select Loan <span class="text-danger">*</span></label>
                                            <div class="input-group shadow-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-0"><i data-feather="search" style="color: #6c757d; width: 16px;"></i></span>
                                                </div>
                                                <select name="loan_id" id="loan_id_select" class="form-control border-left-0 custom-select font-weight-bold" required onchange="onLoanChange(this)">
                                                    <option value="" disabled {{ !$loan_id ? 'selected' : '' }}>--- ជ្រើសរើសលេខកូដកម្ចី (Select Loan Code) ---</option>
                                                    @foreach($loans as $loan)
                                                        <option value="{{ $loan->id }}" {{ $loan_id == $loan->id ? 'selected' : '' }}>
                                                            {{ $loan->loan_code }} - {{ $loan->customer->name }} (${{ number_format($loan->principal_amount, 2) }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @if($selectedLoan && $nextSchedule)
                                            <div class="alert alert-info border-0 shadow-sm rounded-lg mb-4" style="background-color: #f0f7ff;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 text-primary font-weight-bold">ឥឡូវនេះដល់វគ្គបង់ទី៖ {{ $nextSchedule->installment_number }} (Current Installment)</h6>
                                                        <p class="mb-0 text-dark">កាលបរិច្ឆេទកំណត់បង់៖ <span class="font-weight-bold">{{ \Carbon\Carbon::parse($nextSchedule->due_date)->format('d M, Y') }}</span></p>
                                                    </div>
                                                    <div class="text-right">
                                                        <h5 class="mb-0 text-primary font-weight-bold">${{ number_format($nextSchedule->amount_due - $nextSchedule->amount_paid, 2) }}</h5>
                                                        <small class="text-muted">ទឹកប្រាក់ត្រូវបង់សរុប (Amount Due)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label font-weight-medium text-dark">ទឹកប្រាក់បង់ / Amount to Pay ($) <span class="text-danger">*</span></label>
                                                    <div class="input-group shadow-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light border-0 text-success font-weight-bold">$</span>
                                                        </div>
                                                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control border-left-0 text-success font-weight-bold" 
                                                            value="{{ old('amount', $nextSchedule ? $nextSchedule->amount_due - $nextSchedule->amount_paid : '') }}" 
                                                            placeholder="0.00" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label font-weight-medium text-dark">កាលបរិច្ឆេទបង់ / Payment Date <span class="text-danger">*</span></label>
                                                    <div class="input-group shadow-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light border-0"><i data-feather="calendar" style="color: #6c757d; width: 16px;"></i></span>
                                                        </div>
                                                        <input type="date" name="payment_date" class="form-control border-left-0" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label font-weight-medium text-dark">វិធីបង់ប្រាក់ / Payment Method <span class="text-danger">*</span></label>
                                                    <select name="payment_method" class="form-control shadow-sm custom-select" required>
                                                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>សាច់ប្រាក់សុទ្ធ (Cash)</option>
                                                        <option value="ABA Bank" {{ old('payment_method') == 'ABA Bank' ? 'selected' : '' }}>ABA Bank (Transfer)</option>
                                                        <option value="Acleda" {{ old('payment_method') == 'Acleda' ? 'selected' : '' }}>អេស៊ីលីដា (Acleda)</option>
                                                        <option value="Wing" {{ old('payment_method') == 'Wing' ? 'selected' : '' }}>វីង (Wing Money)</option>
                                                        <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>ផ្សេងៗ (Other)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label font-weight-medium text-dark">លេខកូដប្រតិបត្តិការ / Reference NO.</label>
                                                    <div class="input-group shadow-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light border-0"><i data-feather="credit-card" style="color: #6c757d; width: 16px;"></i></span>
                                                        </div>
                                                        <input type="text" name="reference_number" class="form-control border-left-0" value="{{ old('reference_number') }}" placeholder="លេខ Transaction (ប្រសិនបើមាន)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label class="form-label font-weight-medium text-dark">កំណត់ចំណាំ / Notes</label>
                                            <textarea name="notes" class="form-control shadow-sm" rows="3" placeholder="ព័ត៌មានបន្ថែមទាក់ទងនឹងការបង់ប្រាក់នេះ...">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Actions -->
                                <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-3">
                                    <a href="{{ route('repayments.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-dark font-weight-bold mr-2">
                                        បោះបង់ (Cancel)
                                    </a>
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow font-weight-bold custom-btn-primary">
                                        <i data-feather="save" style="width: 16px; margin-right: 5px;"></i> បញ្ជាក់ការបង់ប្រាក់ (Confirm Payment)
                                    </button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>

                <!-- Side Section: Quick Information -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card shadow-sm border-0 rounded-xl mb-4">
                        <div class="card-body p-4">
                            <h6 class="text-dark font-weight-bold mb-4">ព័ត៌មានអតិថិជន (Customer Overview)</h6>
                            @if($selectedLoan)
                                <div class="text-center mb-4">
                                    <div class="avatar-lg bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i data-feather="user" class="text-primary" style="width: 40px; height: 40px;"></i>
                                    </div>
                                    <h5 class="font-weight-bold text-dark mb-1">{{ $selectedLoan->customer->name }}</h5>
                                    <span class="badge badge-primary rounded-pill px-3 py-2">ID: {{ $selectedLoan->customer->code }}</span>
                                </div>
                                <hr>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between mb-3 text-dark">
                                        <span>លេខទូរស័ព្ទ (Phone):</span>
                                        <span class="font-weight-bold">{{ $selectedLoan->customer->phone ?? 'N/A' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 text-dark">
                                        <span>ផលិតផលកម្ចី (Product):</span>
                                        <span class="font-weight-bold text-primary">{{ $selectedLoan->product->name ?? 'Loan' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 text-dark">
                                        <span>ស្ថានភាពកម្ចី (Status):</span>
                                        <span class="badge badge-success px-2 py-1">{{ ucfirst($selectedLoan->status) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i data-feather="info" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                                    <p class="text-muted">សូមជ្រើសរើសលេខកូដកម្ចី ដើម្បីមើលព័ត៌មានលម្អិត។</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($selectedLoan)
                    <div class="card shadow-sm border-0 rounded-xl bg-primary text-white">
                        <div class="card-body p-4">
                            <h6 class="font-weight-bold mb-4">សេចក្តីសង្ខេបហិរញ្ញវត្ថុ (Financial Summary)</h6>
                            <div class="d-flex justify-content-between mb-3">
                                <span>ទឹកប្រាក់កម្ចីសរុប:</span>
                                <span class="font-weight-bold text-white">${{ number_format($selectedLoan->principal_amount, 2) }}</span>
                            </div>
                            <!-- Calculation based on paid installments can be added here if needed -->
                            <div class="d-flex justify-content-between mb-3">
                                <span>រយៈពេលខ្ចី:</span>
                                <span class="font-weight-bold text-white">{{ $selectedLoan->duration_months }} ខែ (Months)</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>អត្រាការប្រាក់:</span>
                                <span class="font-weight-bold text-white">{{ $selectedLoan->interest_rate }}% / year</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .rounded-xl { border-radius: 1rem !important; }
        .rounded-lg { border-radius: 0.75rem !important; }
        .custom-select { border-radius: 0.5rem; height: auto; padding-top: 10px; padding-bottom: 10px; }
        .input-group-prepend .input-group-text { border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; border: none; background: #f8f9fa; }
        .input-group .form-control { border-radius: 0.5rem !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; }
        .custom-btn-primary { background: linear-gradient(135deg, #4e73df, #224abe); border: none; transition: all 0.3s; }
        .custom-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(78, 115, 223, 0.25) !important; }
        .avatar-lg { border: 4px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
    @endpush

    @push('scripts')
    <script>
        function onLoanChange(select) {
            const loanId = select.value;
            if (loanId) {
                // Redirect with the selected loan_id to fetch next installment details
                window.location.href = `{{ route('repayments.create') }}?loan_id=${loanId}`;
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
    @endpush
@endsection
