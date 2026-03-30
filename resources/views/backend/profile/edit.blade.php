@extends('backend.layout.master')

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">ព័ត៌មានគណនីរបស់ខ្ញុំ</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-muted">ទំព័រដើម</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">គណនីរបស់ខ្ញុំ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success - </strong> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Error - </strong> Please check the form below for errors.
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    <div class="profile-img-container mb-3 d-flex justify-content-center">
                                        <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('backend_assets/assets/images/users/profile-pic.jpg') }}" 
                                             alt="user" 
                                             class="rounded-circle img-thumbnail" 
                                             id="preview-image"
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    <div class="custom-file w-75 mx-auto">
                                        <input type="file" class="custom-file-input" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                        <label class="custom-file-label" for="image">ជ្រើសរើសរូបភាព</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <h4 class="card-title">ព័ត៌មានផ្ទាល់ខ្លួន</h4>
                                    <hr>
                                    
                                    <div class="form-group">
                                        <label for="name">ឈ្មោះពេញ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="បញ្ចូលឈ្មោះពេញរបស់អ្នក" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="username">ឈ្មោះគណនី</label>
                                        <input type="text" class="form-control" id="username" value="{{ $user->username }}" readonly disabled>
                                        <small class="form-text text-muted">មិនអាចផ្លាស់ប្តូរឈ្មោះគណនីបានទេ</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">អាសយដ្ឋានអ៊ីមែល <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="បញ្ចូលអាសយដ្ឋានអ៊ីមែលរបស់អ្នក" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">លេខទូរស័ព្ទ</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="បញ្ចូលលេខទូរស័ព្ទរបស់អ្នក">
                                    </div>

                                    <h4 class="card-title mt-5">ផ្លាស់ប្តូរពាក្យសម្ងាត់</h4>
                                    <hr>
                                    <p class="text-muted">ទុកចោលបើអ្នកមិនចង់ផ្លាស់ប្តូរពាក្យសម្ងាត់របស់អ្នកទេ</p>

                                    <div class="form-group">
                                        <label for="current_password">ពាក្យសម្ងាត់បច្ចុប្បន្ន</label>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="បញ្ចូលពាក្យសម្ងាត់បច្ចុប្បន្ន">
                                    </div>

                                    <div class="form-group">
                                        <label for="password">ពាក្យសម្ងាត់ថ្មី</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="បញ្ចូលពាក្យសម្ងាត់ថ្មី">
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="បញ្ចូលពាក្យសម្ងាត់ថ្មីម្តងទៀត">
                                    </div>

                                    <div class="form-actions mt-4">
                                        <button type="submit" class="btn btn-primary"> <i class="fa fa-check"></i> រក្សាទុកការផ្លាស់ប្តូរ</button>
                                        <a href="{{ route('dashboard.index') }}" class="btn btn-dark">បោះបង់</a>
                                    </div>
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
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
            
            // Update label text
            var fileName = $(input).val().split('\\').pop();
            $(input).next('.custom-file-label').addClass("selected").html(fileName);
        }
    }
</script>
@endpush