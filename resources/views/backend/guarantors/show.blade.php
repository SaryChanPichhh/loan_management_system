@extends('backend.layout.master')

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">ព័ត៌មានលម្អិតអ្នកធានា</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('guarantors.index') }}">បញ្ជីអ្នកធានា</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ឃើញព័ត៌មានលម្អិត</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <a href="{{ route('guarantors.edit', $guarantor->id) }}" class="btn btn-primary">
                        <i data-feather="edit"></i> កែសម្រួល
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ប្រវត្តិរូបអ្នកធានា</h4>
                        <table class="table table-borderless mt-4">
                            <tbody>
                                <tr>
                                    <td width="30%" class="text-muted">ឈ្មោះពេញ</td>
                                    <td><strong>{{ $guarantor->full_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">អត្តសញ្ញាណប័ណ្ណ</td>
                                    <td>{{ $guarantor->national_id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">លេខទូរស័ព្ទ</td>
                                    <td>{{ $guarantor->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">អាសយដ្ឋាន</td>
                                    <td>{{ $guarantor->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">ទំនាក់ទំនងជាមួយអតិថិជន</td>
                                    <td>{{ $guarantor->relationship ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">ស្ថានភាព</td>
                                    <td>
                                        @if($guarantor->status == 'active')
                                            <span class="badge badge-success">សកម្ម</span>
                                        @elseif($guarantor->status == 'released')
                                            <span class="badge badge-warning">រួចរាល់ទំហំធានា</span>
                                        @else
                                            <span class="badge badge-danger">ខកខាន</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">ឯកសារភ្ជាប់</td>
                                    <td>
                                        @if($guarantor->document_path)
                                            <a href="{{ asset('storage/' . $guarantor->document_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i data-feather="file-text"></i> មើលឯកសារ
                                            </a>
                                        @else
                                            <span class="text-muted">គ្មានឯកសារ</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h4 class="card-title mb-4">ព័ត៌មានអតិថិជនដែលបានធានា</h4>
                        @if($guarantor->customer)
                            <div class="text-center mb-3">
                                <h5>{{ $guarantor->customer->name }}</h5>
                                <span class="text-muted d-block mb-2">{{ $guarantor->customer->phone }}</span>
                                <a href="{{ route('customer.show', $guarantor->customer->id) }}" class="btn btn-primary btn-sm rounded-pill">ប្រវត្តិរូបអតិថិជន</a>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mt-3">
                                <span class="text-muted">ប្រភេទអតិថិជន</span>
                                <span class="font-weight-medium">{{ $guarantor->customer->type ?? 'N/A' }}</span>
                            </div>
                        @else
                            <div class="text-center text-muted">មិនមានព័ត៌មានអតិថិជននេះទេ</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
