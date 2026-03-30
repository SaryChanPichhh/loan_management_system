@extends('backend.layout.master')

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">បង្កើតការជូនដំណឹងថ្មី</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-muted">ទំព័រដើម</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('notification.index') }}" class="text-muted">ការជូនដំណឹង</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">បង្កើតថ្មី</li>
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
                        <form action="{{ route('notification.store') }}" method="POST">
                            @csrf
                            
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ចំណងជើង <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required placeholder="បញ្ចូលចំណងជើងការជូនដំណឹង">
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ប្រភេទ <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" name="type" required>
                                                <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>ព័ត៌មានទូទៅ (Info)</option>
                                                <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>ជោគជ័យ (Success)</option>
                                                <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>ការព្រមាន (Warning)</option>
                                                <option value="error" {{ old('type') == 'error' ? 'selected' : '' }}>កំហុស (Error)</option>
                                            </select>
                                            @error('type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>ជ្រើសរើសអតិថិជន <span class="text-danger">*</span></label>
                                            <select class="form-control @error('customer_id') is-invalid @enderror" name="customer_id" required>
                                                <option value="">-- សូមជ្រើសរើសអតិថិជន --</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->code }} - {{ $customer->name }} ({{ $customer->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>សារជូនដំណឹង <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="5" required placeholder="បញ្ចូលខ្លឹមសារជូនដំណឹងនៅទីនេះ...">{{ old('message') }}</textarea>
                                            @error('message')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-info">បង្កើត</button>
                                    <a href="{{ route('notification.index') }}" class="btn btn-dark">បោះបង់</a>
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