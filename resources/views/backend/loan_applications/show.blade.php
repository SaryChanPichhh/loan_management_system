@extends('backend.layout.master')

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">ព័ត៌មានលម្អិតសំណើសុំកម្ចី</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('loan_applications.index') }}">បញ្ជីសំណើសុំកម្ចី</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ព័ត៌មានលម្អិត</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i data-feather="check-circle" style="width:16px;height:16px;"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i data-feather="x-circle" style="width:16px;height:16px;"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ព័ត៌មានសំណើ: <span class="text-primary">{{ $application->application_code }}</span></h4>
                        <table class="table table-borderless mt-4">
                            <tbody>
                                <tr>
                                    <td width="30%" class="text-muted">អតិថិជន</td>
                                    <td>
                                        @if($application->customer)
                                            <strong><a href="{{ route('customer.show', $application->customer->id) }}">{{ $application->customer->name }}</a></strong>
                                            ({{ $application->customer->phone }})
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">ផលិតផលកម្ចី</td>
                                    <td>{{ $application->product ? $application->product->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">ទំហំប្រាក់ស្នើសុំ</td>
                                    <td><strong>${{ number_format($application->requested_amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">រយៈពេល</td>
                                    <td><strong>{{ $application->requested_months }} ខែ</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">គោលបំណងកម្ចី</td>
                                    <td>{{ $application->purpose ?? 'មិនបញ្ជាក់' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">ស្ថានភាពបច្ចុប្បន្ន</td>
                                    <td>{!! $application->status_badge_html !!}</td>
                                </tr>
                                @if($application->status == 'rejected')
                                <tr>
                                    <td class="text-muted">មូលហេតុបដិសេធ</td>
                                    <td class="text-danger">{{ $application->rejection_reason }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(!in_array($application->status, ['approved', 'rejected', 'cancelled']))
                <div class="card">
                    <div class="card-body border-top">
                        <h4 class="card-title text-info mb-4">ផ្លាស់ប្តូរស្ថានភាពសំណើ</h4>
                        <form action="{{ route('loan_applications.update_status', $application->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>ស្ថានភាពថ្មី *</label>
                                        <select name="status" class="form-control" id="statusSelect" required onchange="toggleFormFields()">
                                            <option value="">-- ជ្រើសរើស --</option>
                                            @if($application->status == 'draft')
                                                <option value="submitted">ដាក់ស្នើ (Submit)</option>
                                                <option value="cancelled">បោះបង់ (Cancel)</option>
                                            @endif
                                            @if($application->status == 'submitted')
                                                <option value="under_review">កំពុងពិនិត្យ (Under Review)</option>
                                            @endif
                                            @if($application->status == 'under_review')
                                                <option value="approved">អនុម័ត (Approve)</option>
                                                <option value="rejected">បដិសេធ (Reject)</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row d-none" id="approvalFieldsDiv">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ទំហំប្រាក់អនុម័ត * ($)</label>
                                        <input type="number" step="0.01" name="approved_amount" class="form-control" value="{{ $application->requested_amount }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>រយៈពេលអនុម័ត * (ខែ)</label>
                                        <input type="number" name="approved_months" class="form-control" value="{{ $application->requested_months }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none" id="rejectionReasonDiv">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>មូលហេតុការបដិសេធ *</label>
                                        <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success" onclick="return confirm('តើអ្នកប្រាកដទេថាយល់ព្រមផ្លាស់ប្តូរស្ថានភាពនេះ?')">រក្សាទុកការផ្លាស់ប្តូរ</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h4 class="card-title mb-4">សកម្មភាពការងារ</h4>
                        <div class="d-flex justify-content-between mt-3 px-3">
                            <span class="text-muted">បង្កើតដោយ</span>
                            <span class="font-weight-medium">{{ $application->creator ? $application->creator->name : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2 px-3">
                            <span class="text-muted">កាលបរិច្ឆេទបង្កើត</span>
                            <span class="font-weight-medium">{{ $application->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mt-3 px-3">
                            <span class="text-muted">ពិនិត្យដោយ</span>
                            <span class="font-weight-medium">{{ $application->reviewer ? $application->reviewer->name : 'មិនទាន់មាន' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2 px-3 mb-3">
                            <span class="text-muted">ពេកវេលាពិនិត្យ</span>
                            <span class="font-weight-medium">{{ $application->reviewed_at ? $application->reviewed_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                        @if($application->status == 'approved' && !$application->loan_id)
                        <hr>
                        <div class="mt-3">
                            <a href="{{ route('loans.create', ['application_id' => $application->id]) }}" class="btn btn-primary d-block">បង្កើតកុងត្រាកម្ចី</a>
                        </div>
                        @elseif($application->loan_id)
                        <hr>
                        <div class="mt-3">
                            <a href="{{ route('loans.show', $application->loan_id) }}" class="btn btn-success d-block">មើលកុងត្រាកម្ចី</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleFormFields() {
        var status = document.getElementById('statusSelect').value;
        var reasonDiv = document.getElementById('rejectionReasonDiv');
        var approvalDiv = document.getElementById('approvalFieldsDiv');
        var reasonTextarea = reasonDiv.querySelector('textarea');
        var amountInput = approvalDiv.querySelector('input[name="approved_amount"]');
        var monthsInput = approvalDiv.querySelector('input[name="approved_months"]');
        
        // Handle Rejection Logic
        if (status === 'rejected') {
            reasonDiv.classList.remove('d-none');
            reasonTextarea.setAttribute('required', 'required');
        } else {
            reasonDiv.classList.add('d-none');
            reasonTextarea.removeAttribute('required');
        }

        // Handle Approval Logic
        if (status === 'approved') {
            approvalDiv.classList.remove('d-none');
            amountInput.setAttribute('required', 'required');
            monthsInput.setAttribute('required', 'required');
        } else {
            approvalDiv.classList.add('d-none');
            amountInput.removeAttribute('required');
            monthsInput.removeAttribute('required');
        }
    }
</script>
@endpush
