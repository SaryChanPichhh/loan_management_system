@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-dark font-weight-medium mb-1 pt-2">
                        គ្រប់គ្រងសិទ្ធិ (Permissions)
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a>
                            </li>
                            <li class="breadcrumb-item active">
                                បញ្ជីសិទ្ធិ
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-5 text-right">
                    <a href="{{ route('permission.create') }}" class="btn btn-primary">
                        <i data-feather="plus"></i> បង្កើតសិទ្ធិថ្មី
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table id="permissionTable" class="table table-striped table-bordered no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ឈ្មោះសិទ្ធិ (Feature)</th>
                                            <th>Slug (Route Name)</th>
                                            <th>ម៉ូឌុល (Module)</th>
                                            <th>ការពិពណ៌នា</th>
                                            <th>សកម្មភាព</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                            <tr>
                                                <td><strong>{{ $permission->name }}</strong></td>
                                                <td><span class="badge badge-info">{{ $permission->slug }}</span></td>
                                                <td><span class="badge badge-secondary">{{ $permission->module }}</span></td>
                                                <td>{{ $permission->description }}</td>
                                                <td>
                                                    <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-sm btn-warning">
                                                        <i data-feather="edit" class="feather-sm"></i> កែប្រែ
                                                    </a>
                                                    <form action="{{ route('permission.destroy', $permission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបសិទ្ធិនេះមែនទេ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i data-feather="trash-2" class="feather-sm"></i> លុប
                                                        </button>
                                                    </form>
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

            $("#permissionTable").DataTable({
                "pageLength": 25,
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