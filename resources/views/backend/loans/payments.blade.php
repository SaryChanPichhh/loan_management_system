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
                        ការបង់ប្រាក់កម្ចី
                    </h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard.index') }}"
                                    >ផ្ទាំងគ្រប់គ្រង</a
                                    >
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('loans.index') }}">កម្ចី</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('loans.show', 1) }}"
                                    >LOAN-001</a
                                    >
                                </li>
                                <li
                                    class="breadcrumb-item active"
                                    aria-current="page"
                                >
                                    ការបង់ប្រាក់
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <button
                            class="btn btn-primary"
                            data-toggle="modal"
                            data-target="#addPaymentModal"
                        >
                            <i data-feather="plus"></i> បន្ថែមការបង់ប្រាក់
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">តារាងកាលវិភាគបង់ប្រាក់</h4>
                            <div class="table-responsive">
                                <table
                                    id="payments_table"
                                    class="table table-striped table-bordered"
                                >
                                    <thead>
                                    <tr>
                                        <th>ខែ</th>
                                        <th>ថ្ងៃត្រូវបង់</th>
                                        <th>ចំនួនទឹកប្រាក់</th>
                                        <th>បានបង់?</th>
                                        <th>ថ្ងៃខែឆ្នាំបង់</th>
                                        <th>វិធីសាស្ត្របង់ប្រាក់</th>
                                        <th>ស្ថានភាព</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="table-danger">
                                        <td>1</td>
                                        <td>2024-02-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-danger"
                                            >ទេ</span
                                            >
                                        </td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>
                                            <span class="badge badge-danger"
                                            >ហួសកំណត់</span
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>2024-03-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-success"
                                            >បាទ/ចាស</span
                                            >
                                        </td>
                                        <td>2024-03-10</td>
                                        <td>ផ្ទេរតាមធនាគារ</td>
                                        <td>
                                            <span class="badge badge-success"
                                            >បានបង់</span
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>2024-04-15</td>
                                        <td>$444.24</td>
                                        <td>
                                            <span class="badge badge-danger"
                                            >ទេ</span
                                            >
                                        </td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>
                                            <span class="badge badge-warning"
                                            >កំពុងរង់ចាំ</span
                                            >
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">សរុបបានបង់</h5>
                            <h3 class="text-success">$444.24</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">សមតុល្យនៅសល់</h5>
                            <h3 class="text-danger">$4,886.64</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ការបង់ប្រាក់ហួសកំណត់</h5>
                            <h3 class="text-danger">1</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">បន្ថែមការបង់ប្រាក់</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm">
                        <div class="form-group">
                            <label for="payment_date"
                            >ថ្ងៃខែឆ្នាំបង់ប្រាក់
                                <span class="text-danger">*</span></label
                            >
                            <input
                                type="date"
                                class="form-control"
                                id="payment_date"
                                name="payment_date"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label for="amount_paid"
                            >ចំនួនទឹកប្រាក់បង់ ($)
                                <span class="text-danger">*</span></label
                            >
                            <input
                                type="number"
                                class="form-control"
                                id="amount_paid"
                                name="amount_paid"
                                placeholder="បញ្ចូលចំនួនទឹកប្រាក់"
                                min="0"
                                step="0.01"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label for="payment_method"
                            >វិធីសាស្ត្របង់ប្រាក់
                                <span class="text-danger">*</span></label
                            >
                            <select
                                class="form-control"
                                id="payment_method"
                                name="payment_method"
                                required
                            >
                                <option value="">ជ្រើសរើសវិធីសាស្ត្រ</option>
                                <option value="cash">សាច់ប្រាក់ (Cash)</option>
                                <option value="bank_transfer">ផ្ទេរតាមធនាគារ (Bank Transfer)</option>
                                <option value="check">សែក (Check)</option>
                                <option value="mobile_payment">
                                    ទូទាត់តាមទូរស័ព្ទ (Mobile Payment)
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_notes">ចំណាំ</label>
                            <textarea
                                class="form-control"
                                id="payment_notes"
                                name="payment_notes"
                                rows="3"
                                placeholder="បន្ថែមចំណាំផ្សេងៗ..."
                            ></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        បោះបង់
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        id="savePaymentBtn"
                    >
                        រក្សាទុកការបង់ប្រាក់
                    </button>
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
            "use strict";

            // Ensure preloader is hidden
            $(".preloader").fadeOut();

            // Initialize Payments DataTable only if table exists
            if ($("#payments_table").length) {
                try {
                    $("#payments_table").DataTable({
                        pageLength: 12,
                        responsive: true,
                        order: [[0, "asc"]],
                        language: {
                            search: "ស្វែងរក៖",
                            lengthMenu: "បង្ហាញ _MENU_ បញ្ជី",
                            info: "បង្ហាញពីលេខ _START_ ដល់ _END_ នៃបញ្ជីសរុប _TOTAL_",
                            paginate: {
                                next: "បន្ទាប់",
                                previous: "មុន"
                            }
                        }
                    });
                } catch (e) {
                    console.error("DataTable initialization error:", e);
                }
            }

            // Save Payment
            $("#savePaymentBtn").on("click", function () {
                const form = $("#addPaymentForm");
                if (form[0].checkValidity()) {
                    alert(
                        "ការបង់ប្រាក់ត្រូវបានរក្សាទុកដោយជោគជ័យ! សមតុល្យនៅសល់ត្រូវបានធ្វើបច្ចុប្បន្នភាព។"
                    );
                    $("#addPaymentModal").modal("hide");
                    form[0].reset();
                    location.reload();
                } else {
                    form[0].reportValidity();
                }
            });

            // Initialize Feather Icons
            if (typeof feather !== "undefined" && feather) {
                feather.replace();
            }
        });
    </script>
@endpush
