@extends('backend.layout.master') @push('styles')
    <link
        href="{{
        asset(
            'backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css'
        )
    }}"
        rel="stylesheet"
    />
@endpush @section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3
                        class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2"
                    >
                        ការគ្រប់គ្រងកម្ចី
                    </h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard.index') }}"
                                    >ផ្ទាំងគ្រប់គ្រង</a
                                    >
                                </li>
                                <li
                                    class="breadcrumb-item active"
                                    aria-current="page"
                                >
                                    កម្ចី
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a
                            href="{{ route('loans.create') }}"
                            class="btn btn-primary"
                        >
                            <i data-feather="plus"></i> បង្កើតកម្ចីថ្មី
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{
                                        !request('status') ? 'active' : ''
                                    }}"
                                        href="{{ route('loans.index') }}"
                                    >
                                        កម្ចីទាំងអស់
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{
                                        request('status') == 'pending'
                                            ? 'active'
                                            : ''
                                    }}"
                                        href="{{ route('loans.index', ['status' => 'pending']) }}"
                                    >
                                        កំពុងរង់ចាំ
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{
                                        request('status') == 'active'
                                            ? 'active'
                                            : ''
                                    }}"
                                        href="{{ route('loans.index', ['status' => 'active']) }}"
                                    >
                                        កំពុងដំណើរការ
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{
                                        request('status') == 'completed'
                                            ? 'active'
                                            : ''
                                    }}"
                                        href="{{ route('loans.index', ['status' => 'completed']) }}"
                                    >
                                        បានបញ្ចប់
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{
                                        request('status') == 'defaulted'
                                            ? 'active'
                                            : ''
                                    }}"
                                        href="{{ route('loans.index', ['status' => 'defaulted']) }}"
                                    >
                                        មិនបានសង
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    id="loan_table"
                                    class="table table-striped table-bordered no-wrap"
                                >
                                    <thead>
                                    <tr>
                                        <th>លេខសម្គាល់កម្ចី</th>
                                        <th>ឈ្មោះអ្នកខ្ចី</th>
                                        <th>ចំនួនទឹកប្រាក់</th>
                                        <th>ស្ថានភាព</th>
                                        <th>ថ្ងៃចាប់ផ្តើម</th>
                                        <th class="text-center">សកម្មភាព</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>LOAN-001</td>
                                        <td>John Doe</td>
                                        <td>$5,000.00</td>
                                        <td>
                                            <span class="badge badge-warning"
                                            >កំពុងរង់ចាំ</span
                                            >
                                        </td>
                                        <td>2024-01-15</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a
                                                    href="{{
                                                        route('loans.show', 1)
                                                    }}"
                                                    class="btn btn-sm btn-info"
                                                    title="មើលព័ត៌មាន"
                                                >
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a
                                                    href="{{
                                                        route('loans.edit', 1)
                                                    }}"
                                                    class="btn btn-sm btn-primary"
                                                    title="កែសម្រួល"
                                                >
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <a
                                                    href="#"
                                                    class="btn btn-sm btn-success"
                                                    title="អនុម័ត"
                                                >
                                                    <i data-feather="check"></i>
                                                </a>
                                                <a
                                                    href="#"
                                                    class="btn btn-sm btn-danger"
                                                    title="បដិសេធ"
                                                >
                                                    <i data-feather="x"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection @push('scripts')
    <script src="{{
        asset(
            'backend_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js'
        )
    }}"></script>
    <script src="{{
        asset(
            'backend_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js'
        )
    }}"></script>

    <script>
        $(document).ready(function () {
            $("#loan_table").DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    search: "ស្វែងរក៖",
                    lengthMenu: "បង្ហាញ _MENU_ ទិន្នន័យ",
                    info: "បង្ហាញពីលេខ _START_ ដល់ _END_ នៃទិន្នន័យសរុប _TOTAL_",
                    infoEmpty: "មិនមានទិន្នន័យ",
                    infoFiltered: "(ចម្រាញ់ចេញពីទិន្នន័យសរុប _MAX_)",
                    paginate: {
                        first: "ដំបូង",
                        last: "ចុងក្រោយ",
                        next: "បន្ទាប់",
                        previous: "មុន"
                    },
                    zeroRecords: "មិនមានទិន្នន័យដែលអ្នកស្វែងរកឡើយ"
                }
            });

            if (typeof feather !== "undefined" && feather) {
                feather.replace();
            }
        });
    </script>
@endpush
