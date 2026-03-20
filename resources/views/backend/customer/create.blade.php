@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                        បន្ថែមអតិថិជនថ្មី
                    </h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('customer.index') }}">អតិថិជន</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    បន្ថែមថ្មី
                                </li>
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
                            <div class="d-flex align-items-center mb-4">
                                <h4 class="card-title mb-0">ទម្រង់ព័ត៌មានអតិថិជន</h4>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>មានបញ្ហា!</strong> សូមពិនិត្យព័ត៌មានខាងក្រោម៖
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <h5 class="card-title border-bottom pb-3 mb-4">ព័ត៌មានទូទៅ</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="code">លេខកូដ <span class="text-danger">*</span></label>
                                                <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="បញ្ចូលលេខកូដ">
                                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">ឈ្មោះអតិថិជន <span class="text-danger">*</span></label>
                                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="បញ្ចូលឈ្មោះអតិថិជន">
                                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="gender">ភេទ <span class="text-danger">*</span></label>
                                                <select id="gender" name="gender" class="form-control custom-select @error('gender') is-invalid @enderror">
                                                    <option value="">ជ្រើសរើសភេទ</option>
                                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>ប្រុស</option>
                                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>ស្រី</option>
                                                </select>
                                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone">លេខទូរសព្ទ <span class="text-danger">*</span></label>
                                                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="បញ្ចូលលេខទូរសព្ទ">
                                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status">ស្ថានភាព <span class="text-danger">*</span></label>
                                                <select id="status" name="status" class="form-control custom-select @error('status') is-invalid @enderror">
                                                    <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>កំពុងដំណើរការ</option>
                                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>ផ្អាក</option>
                                                </select>
                                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">អាស័យដ្ឋាន <span class="text-danger">*</span></label>
                                                <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="បញ្ចូលអាស័យដ្ឋាន">
                                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="type">ប្រភេទ <span class="text-danger">*</span></label>
                                                <input type="text" id="type" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" placeholder="បញ្ចូលប្រភេទអតិថិជន">
                                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="card-title border-bottom pb-3 mb-4 mt-4">ឯកសារភ្ជាប់</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="document">រូបថត/ឯកសារ</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('document') is-invalid @enderror" id="document" name="document" onchange="previewImage(event)">
                                                    <label class="custom-file-label" for="document">ជ្រើសរើសឯកសារ...</label>
                                                </div>
                                                @error('document')<div class="text-danger mt-2 small">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ការពិនិត្យមើលរូបភាព</label>
                                                <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="min-height: 200px; border: 2px dashed #e8eef3 !important;">
                                                    <img id="preview" src="#" class="img-fluid rounded d-none shadow-sm" style="max-height: 180px; object-fit: cover;" alt="Preview">
                                                    <div id="empty-preview" class="text-center text-muted">
                                                        <i data-feather="image" class="mb-2 text-secondary" style="width: 40px; height: 40px;"></i>
                                                        <p class="mb-0">មិនទាន់មានរូបភាព</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions mt-4 text-right">
                                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-rounded mr-2 px-4">
                                        <i data-feather="x" class="mr-1" style="width: 16px; height: 16px;"></i> បោះបង់
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-rounded px-4">
                                        <i data-feather="save" class="mr-1" style="width: 16px; height: 16px;"></i> រក្សាទុក
                                    </button>
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
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        const empty = document.getElementById('empty-preview');

        // Update custom file label
        if (input.files && input.files.length > 0) {
            const fileName = input.files[0].name;
            input.nextElementSibling.innerText = fileName;
        } else {
            input.nextElementSibling.innerText = 'ជ្រើសរើសឯកសារ...';
        }

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                empty.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
            empty.classList.remove('d-none');
        }
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined' && feather) {
            feather.replace();
        }
    });
</script>
@endpush