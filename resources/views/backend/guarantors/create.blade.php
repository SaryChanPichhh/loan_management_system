@extends('backend.layout.master')

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">បង្កើតអ្នកធានាថ្មី</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('guarantors.index') }}">បញ្ជីអ្នកធានា</a></li>
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
                        <form action="{{ route('guarantors.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                <h4 class="card-title">ព័ត៌មានអ្នកធានា</h4>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ឈ្មោះអ្នកធានា <span class="text-danger">*</span></label>
                                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>អត្តសញ្ញាណប័ណ្ណ <span class="text-danger">*</span></label>
                                            <input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>លេខទូរស័ព្ទ</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ចំណូលប្រចាំខែ ($)</label>
                                            <input type="number" step="0.01" name="income" class="form-control" value="{{ old('income') }}" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ទំនាក់ទំនងជាមួយអ្នកខ្ចី</label>
                                            <input type="text" name="relationship" class="form-control" value="{{ old('relationship') }}" placeholder="ឧ. បងប្អូនកូនបង្កើត">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ស្ថានភាព <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control" required>
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>សកម្ម</option>
                                                <option value="released" {{ old('status') == 'released' ? 'selected' : '' }}>រួចរាល់ទំហំធានា</option>
                                                <option value="defaulted" {{ old('status') == 'defaulted' ? 'selected' : '' }}>ខកខាន</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ឯកសារភ្ជាប់ (រូបភាព ឬ PDF)</label>
                                            <input type="file" name="document" class="form-control-file">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>អាសយដ្ឋាន</label>
                                            <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-info">រក្សាទុក</button>
                                    <a href="{{ route('guarantors.index') }}" class="btn btn-dark">បោះបង់</a>
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
