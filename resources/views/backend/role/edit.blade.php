@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-dark font-weight-medium mb-1 pt-2">
                        កំណត់សិទ្ធិផ្ទាល់ខ្លួន
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('role.index') }}">គ្រប់គ្រងសិទ្ធិ</a>
                            </li>
                            <li class="breadcrumb-item active">
                                កំណត់សិទ្ធិអ្នកប្រើប្រាស់
                            </li>
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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="card-title mb-0">កំណត់សិទ្ធិសម្រាប់: <span class="text-primary">{{ $user->name }}</span></h4>
                                    <p class="text-muted mb-0">អ៊ីមែល: {{ $user->email }} | ឈ្មោះអ្នកប្រើ: {{ $user->username }}</p>
                                </div>
                                <a href="{{ route('role.index') }}" class="btn btn-secondary">
                                    <i data-feather="arrow-left" class="feather-sm"></i> ត្រឡប់ក្រោយ
                                </a>
                            </div>

                            <form method="POST" action="{{ route('user.permissions.update', $user->id) }}">
                                @csrf
                                <div class="alert alert-info d-flex align-items-center mb-4">
                                    <i data-feather="info" class="me-2"></i>
                                    <div>សិទ្ធិដែលមានពណ៌ប្រផេះ (Disabled) គឺជាសិទ្ធិដែលទទួលបានពីតួនាទីរួចហើយ។ អ្នកមិនអាចដកវាចេញនៅទីនេះបានទេ។</div>
                                </div>
                                
                                <div class="row">
                                    @php
                                        $inheritedIds = $user->roles->flatMap(fn($r) => $r->permissions->pluck('id'))->unique()->toArray();
                                        $directIds = $user->permissions->pluck('id')->toArray();
                                    @endphp
                                    
                                    @foreach($permissionsByModule as $module => $permissions)
                                        <div class="col-md-4 col-sm-6 mb-4">
                                            <div class="card bg-light border h-100">
                                                <div class="card-body">
                                                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                                                        <i data-feather="package" class="feather-sm me-1"></i> {{ $module }}
                                                    </h5>
                                                    @foreach($permissions as $permission)
                                                        @php
                                                            $isInherited = in_array($permission->id, $inheritedIds);
                                                            $isDirect = in_array($permission->id, $directIds);
                                                        @endphp
                                                        <div class="form-check mb-2 custom-control custom-checkbox">
                                                            <input class="form-check-input custom-control-input" type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}" 
                                                                   id="perm_{{ $permission->id }}"
                                                                   {{ $isInherited || $isDirect ? 'checked' : '' }}
                                                                   {{ $isInherited ? 'readonly onclick="return false;"' : '' }}>
                                                            <label class="form-check-label custom-control-label {{ $isInherited ? 'text-muted' : '' }}" for="perm_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                                @if($isInherited)
                                                                    <span class="badge bg-secondary ms-1" style="font-size: 0.7em;">តួនាទី</span>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <hr>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary px-5 btn-lg">
                                        <i data-feather="save" class="feather-sm me-1"></i> រក្សាទុកសិទ្ធិ
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
        $(document).ready(function () {
            $(".preloader").fadeOut();
            if (window.feather) window.feather.replace();
        });
    </script>
@endpush