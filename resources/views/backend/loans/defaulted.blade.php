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
                    <h3 class="page-title text-dark font-weight-medium mb-1 pt-2">
                        កម្ចីដែលមិនបានសង (Defaulted)
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="#">ផ្ទាំងគ្រប់គ្រង</a>
                            </li>
                            <li class="breadcrumb-item active">កម្ចីដែលមិនបានសង</li>
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
                            <h4 class="card-title">បញ្ជីកម្ចីហួសកំណត់</h4>

                            <div class="table-responsive">
                                <table
                                    id="defaulted_loans"
                                    class="table table-striped table-bordered"
                                >
                                    <thead>
                                    <tr>
                                        <th>អ្នកខ្ចី</th>
                                        <th>ចំនួនកម្ចី</th>
                                        <th>សមតុល្យនៅសល់</th>
                                        <th>ចំនួនថ្ងៃហួសកំណត់</th>
                                        <th>សកម្មភាព</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>$5,000.00</td>
                                        <td class="text-danger">$5,330.88</td>
                                        <td>
                                            <span class="badge badge-danger"
                                            >45 ថ្ងៃ</span
                                            >
                                        </td>
                                        <td>
                                            <button
                                                class="btn btn-sm btn-warning"
                                                data-toggle="modal"
                                                data-target="#restructureModal"
                                            >
                                                រៀបចំឡើងវិញ
                                            </button>
                                            <button
                                                class="btn btn-sm btn-danger"
                                                data-toggle="modal"
                                                data-target="#writeOffModal"
                                            >
                                                លុបចោលបំណុល
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Jane Smith</td>
                                        <td>$3,200.00</td>
                                        <td class="text-danger">$3,480.50</td>
                                        <td>
                                            <span class="badge badge-danger"
                                            >72 ថ្ងៃ</span
                                            >
                                        </td>
                                        <td>
                                            <button
                                                class="btn btn-sm btn-warning"
                                                data-toggle="modal"
                                                data-target="#restructureModal"
                                            >
                                                រៀបចំឡើងវិញ
                                            </button>
                                            <button
                                                class="btn btn-sm btn-danger"
                                                data-toggle="modal"
                                                data-target="#writeOffModal"
                                            >
                                                លុបចោលបំណុល
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="restructureModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">រៀបចំរចនាសម្ព័ន្ធកម្ចីឡើងវិញ</h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                            >
                                &times;
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>អត្រាការប្រាក់ថ្មី (%)</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    placeholder="ឧទាហរណ៍៖ 10"
                                />
                            </div>
                            <div class="form-group">
                                <label>រយៈពេលថ្មី (ខែ)</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    placeholder="ឧទាហរណ៍៖ 18"
                                />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">
                                បោះបង់
                            </button>
                            <button class="btn btn-warning">រក្សាទុកការផ្លាស់ប្តូរ</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="writeOffModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger">
                                បញ្ជាក់ការលុបចោលបំណុល
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                            >
                                &times;
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>មូលហេតុ</label>
                                <textarea
                                    class="form-control"
                                    rows="3"
                                    placeholder="មូលហេតុនៃការលុបចោលបំណុល..."
                                ></textarea>
                            </div>
                            <p class="text-danger mb-0">
                                សកម្មភាពនេះមិនអាចត្រឡប់ថយក្រោយបានទេ។
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">
                                បោះបង់
                            </button>
                            <button class="btn btn-danger">
                                បញ្ជាក់ការលុបចោល
                            </button>
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
            $("#defaulted_loans").DataTable({
                order: [[3, "desc"]], // sort by days overdue
                pageLength: 10,
                responsive: true,
                // Optional: Translate DataTable UI
                language: {
                    search: "ស្វែងរក៖",
                    lengthMenu: "បង្ហាញ _MENU_ បញ្ជី",
                    info: "បង្ហាញពីលេខ _START_ ដល់ _END_ នៃបញ្ជីសរុប _TOTAL_",
                    paginate: {
                        previous: "មុន",
                        next: "បន្ទាប់"
                    }
                }
            });
        });
    </script>
@endpush
