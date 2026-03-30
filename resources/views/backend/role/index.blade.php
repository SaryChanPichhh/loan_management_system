@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-dark font-weight-medium mb-1 pt-2">
                        គ្រប់គ្រងតួនាទី & សិទ្ធិ
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a>
                            </li>
                            <li class="breadcrumb-item active">
                                តួនាទី & សិទ្ធិ
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- User Grid Management -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">គ្រប់គ្រងសិទ្ធិអ្នកប្រើប្រាស់</h4>
                            <h6 class="card-subtitle text-muted mb-3">បញ្ជីអ្នកប្រើប្រាស់និងសិទ្ធិរបស់ពួកគេ</h6>
                            
                            <div class="table-responsive">
                                <table id="userTable" class="table table-striped table-bordered no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ឈ្មោះ</th>
                                            <th>ឈ្មោះអ្នកប្រើ (Username)</th>
                                            <th>អ៊ីមែល</th>
                                            <th>តួនាទី</th>
                                            <th>សិទ្ធិផ្ទាល់ខ្លួន</th>
                                            <th>សកម្មភាព</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td><span class="badge bg-info text-dark">{{ $user->username }}</span></td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill bg-secondary text-white">{{ $user->permissions->count() }} សិទ្ធិ</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('user.permissions.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                                        <i data-feather="edit-2" class="feather-sm"></i> កំណត់សិទ្ធិ
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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

            // DataTable Initialization
            $("#userTable").DataTable({
                "pageLength": 10,
                "language": {
                    "search": "ស្វែងរក:",
                    "lengthMenu": "បង្ហាញ _MENU_ ជួរ",
                    "info": "បង្ហាញពី _START_ ដល់ _END_ នៃ _TOTAL_ ជួរ",
                    "paginate": {
                        "first": "ដំបូង",
                        "last": "ចុងក្រោយ",
                        "next": "បន្ទាប់",
                        "previous": "មុន"
                    }
                }
            });
        });
    </script>
@endpush
