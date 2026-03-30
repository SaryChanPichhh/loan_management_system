@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-dark font-weight-medium mb-1 pt-2">
                        កែប្រែសិទ្ធិ
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">បញ្ជីសិទ្ធិ</a></li>
                            <li class="breadcrumb-item active">កែប្រែ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('permission.update', $permission->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">ឈ្មោះសិទ្ធិ (Feature Name) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="slug">Slug (Route Name) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $permission->slug) }}" required>
                                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <small class="text-muted">ត្រូវគ្នានឹងឈ្មោះ Route នៅក្នុងប្រព័ន្ធ (Route Name)</small>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="module">ម៉ូឌុល (Module) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('module') is-invalid @enderror" id="module" name="module" value="{{ old('module', $permission->module) }}" required>
                                        @error('module') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="description">ការពិពណ៌នា</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $permission->description) }}</textarea>
                                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="text-right">
                                    <a href="{{ route('permission.index') }}" class="btn btn-secondary mr-2">បោះបង់</a>
                                    <button type="submit" class="btn btn-success"><i data-feather="save"></i> រក្សាទុកការកែប្រែ</button>
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
        $(document).ready(function () {
            $(".preloader").fadeOut();
            if (window.feather) window.feather.replace();
        });
    </script>
@endpush