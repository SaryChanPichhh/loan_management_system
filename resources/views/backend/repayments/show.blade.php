@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper pb-5">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                        ព័ត៌មានលម្អិតនៃការបង់ប្រាក់ (Repayment Details)
                    </h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0 text-muted">
                                <li class="breadcrumb-item"><a href="{{ route('repayments.index') }}">ប្រវត្តិបង់ប្រាក់</a></li>
                                <li class="breadcrumb-item active">លេខប័ណ្ណ #{{ $repayment->id }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a href="{{ route('repayments.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i data-feather="arrow-left" class="mr-1" style="width: 16px;"></i> ត្រឡប់ក្រោយ (Back)
                        </a>
                        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 ml-2">
                            <i data-feather="printer" class="mr-1" style="width: 16px;"></i> បោះពុម្ព (Print)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <!-- Receipt Main Content -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
                        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-success-light p-2 rounded-circle mr-3">
                                    <i data-feather="check-circle" class="text-success"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 font-weight-bold text-dark">វិក្កយបត្របង់ប្រាក់ជោគជ័យ</h5>
                                    <small class="text-muted">បានកត់ត្រានៅថ្ងៃទី: {{ $repayment->created_at->format('d M, Y H:i A') }}</small>
                                </div>
                            </div>
                            <div class="text-right">
                                <h4 class="mb-0 font-weight-bold text-primary">${{ number_format($repayment->amount, 2) }}</h4>
                                <span class="badge badge-success rounded-pill px-3">{{ strtoupper($repayment->status) }}</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <h6 class="text-muted font-weight-bold mb-3">ព័ត៌មានអតិថិជន (Customer Info)</h6>
                                    <p class="mb-1 text-dark"><strong>ឈ្មោះ:</strong> {{ $repayment->loan->customer->name }}</p>
                                    <p class="mb-1 text-dark"><strong>លេខកូដ:</strong> {{ $repayment->loan->customer->code }}</p>
                                    <p class="mb-1 text-dark"><strong>លេខទូរស័ព្ទ:</strong> {{ $repayment->loan->customer->phone }}</p>
                                </div>
                                <div class="col-sm-6 text-sm-right mt-3 mt-sm-0">
                                    <h6 class="text-muted font-weight-bold mb-3">ព័ត៌មានប្រតិបត្តិការ (Transaction Info)</h6>
                                    <p class="mb-1 text-dark"><strong>លេខកម្ចី:</strong> {{ $repayment->loan->loan_code }}</p>
                                    <p class="mb-1 text-dark"><strong>វិធីបង់ប្រាក់:</strong> {{ $repayment->payment_method }}</p>
                                    <p class="mb-1 text-dark"><strong>លេខយោង:</strong> {{ $repayment->reference_number ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr class="bg-light text-dark">
                                            <th class="font-weight-bold">ការពិពណ៌នា (Description)</th>
                                            <th class="text-right font-weight-bold">ចំនួនទឹកប្រាក់ (Amount)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ការបង់ប្រាក់សម្រាប់វគ្គទី #{{ $repayment->schedule->installment_number ?? 'N/A' }}</td>
                                            <td class="text-right">${{ number_format($repayment->amount, 2) }}</td>
                                        </tr>
                                        <tr class="text-muted" style="font-size: 0.9rem;">
                                            <td class="pl-4">- ប្រាក់ដើម (Principal Paid)</td>
                                            <td class="text-right">${{ number_format($repayment->principal_paid, 2) }}</td>
                                        </tr>
                                        <tr class="text-muted" style="font-size: 0.9rem;">
                                            <td class="pl-4">- ការប្រាក់ (Interest Paid)</td>
                                            <td class="text-right">${{ number_format($repayment->interest_paid, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-top">
                                            <th class="pt-3 font-weight-bold h5">សរុប (Total Received)</th>
                                            <th class="pt-3 text-right text-primary h5 font-weight-bold">${{ number_format($repayment->amount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            @if($repayment->notes)
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="font-weight-bold mb-2">កំណត់ចំណាំ (Notes):</h6>
                                <p class="mb-0 text-muted italic">{{ $repayment->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Audit Trail Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-xl">
                        <div class="card-body p-4">
                            <h6 class="text-dark font-weight-bold mb-4">
                                <i data-feather="shield" class="mr-2 text-primary" style="width: 18px;"></i> សវនកម្មហិរញ្ញវត្ថុ (Audit Trail)
                            </h6>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="avatar-md bg-primary-light rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                    <i data-feather="user" class="text-primary"></i>
                                </div>
                                <div>
                                    <span class="text-muted d-block small">អ្នកទទួលប្រាក់ (Collected By)</span>
                                    <span class="font-weight-bold text-dark h6 mb-0">{{ $repayment->receivedBy->name ?? 'System' }}</span>
                                </div>
                            </div>

                            <hr>

                            <div class="mt-4">
                                <div class="mb-3">
                                    <label class="text-muted small d-block mb-1">កាលបរិច្ឆេទប្រតិបត្តិការ:</label>
                                    <span class="text-dark font-weight-medium">{{ $repayment->payment_date->format('d M, Y') }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small d-block mb-1">ពេលវេលាបញ្ចូលទិន្នន័យ:</label>
                                    <span class="text-dark font-weight-medium">{{ $repayment->created_at->format('H:i:s A, d-M-Y') }}</span>
                                </div>
                                <div>
                                    <label class="text-muted small d-block mb-1">ស្ថានភាពការបង់ប្រាក់:</label>
                                    <span class="badge badge-success px-3 py-1 rounded-pill">VERIFIED & SIGNED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-xl bg-primary text-white mt-4">
                        <div class="card-body p-4 text-center">
                            <i data-feather="award" class="mb-3" style="width: 48px; height: 48px;"></i>
                            <h5>ការបង់ប្រាក់ត្រឹមត្រូវ</h5>
                            <p class="small opacity-75 mb-0">ប្រតិបត្តិការនេះត្រូវបានផ្ទៀងផ្ទាត់ដោយប្រព័ន្ធ និងចុះហត្ថលេខាដោយបុគ្គលិកទទួលបន្ទុក។</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .rounded-xl { border-radius: 1rem !important; }
        .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
        .bg-primary-light { background-color: rgba(95, 118, 232, 0.1); }
        .italic { font-style: italic; }
        @media print {
            .page-breadcrumb, .btn, .footer { display: none !important; }
            .card { box-shadow: none !important; border: 1px solid #eee !important; }
            .page-wrapper { margin-left: 0 !important; padding: 0 !important; }
        }
    </style>
    @endpush
@endsection
