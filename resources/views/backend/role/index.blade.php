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
                <div class="col-5 text-right">
                    <button class="btn btn-primary">
                        <i data-feather="plus"></i> បង្កើតតួនាទីថ្មី
                    </button>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">

                <!-- Roles List -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">បញ្ជីតួនាទី</h4>

                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Admin
                                    <span class="badge badge-primary">ពេញលេញ</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Manager
                                    <span class="badge badge-info">មធ្យម</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Staff
                                    <span class="badge badge-secondary">កម្រិតទាប</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">កំណត់សិទ្ធិ (Permissions)</h4>
                            <p class="text-muted">
                                ជ្រើសរើសសិទ្ធិសម្រាប់តួនាទី <strong>Admin</strong>
                            </p>

                            <form id="permissionForm">
                                <div class="row">

                                    <div class="col-md-6">
                                        <h5>Loan Management</h5>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="loan_view" checked>
                                            <label class="custom-control-label" for="loan_view">មើលកម្ចី</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="loan_create" checked>
                                            <label class="custom-control-label" for="loan_create">បង្កើតកម្ចី</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="loan_approve" checked>
                                            <label class="custom-control-label" for="loan_approve">អនុម័តកម្ចី</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5>User Management</h5>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="user_view" checked>
                                            <label class="custom-control-label" for="user_view">មើលអ្នកប្រើប្រាស់</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="user_create" checked>
                                            <label class="custom-control-label" for="user_create">បង្កើតអ្នកប្រើប្រាស់</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="user_delete" checked>
                                            <label class="custom-control-label" for="user_delete">លុបអ្នកប្រើប្រាស់</label>
                                        </div>
                                    </div>

                                </div>

                                <hr>

                                <button type="submit" class="btn btn-success">
                                    <i data-feather="save"></i> រក្សាទុកសិទ្ធិ
                                </button>
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

            $("#permissionForm").on("submit", function (e) {
                e.preventDefault();
                alert("សិទ្ធិត្រូវបានរក្សាទុកជោគជ័យ!");
            });

            if (feather) feather.replace();
        });
    </script>
@endpush
