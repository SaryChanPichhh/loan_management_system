@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                        របាយការណ៍ និងការវិភាគ
                    </h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/admin') }}">ផ្ទាំងគ្រប់គ្រង</a>
                                </li>
                                <li class="breadcrumb-item active">របាយការណ៍</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">ចម្រាញ់របាយការណ៍</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <label>ចាប់ពីថ្ងៃ</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>ដល់ថ្ងៃ</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>ស្ថានភាព</label>
                            <select class="form-control">
                                <option>ទាំងអស់</option>
                                <option>កំពុងដំណើរការ</option>
                                <option>បានបញ្ចប់</option>
                                <option>ហួសកំណត់</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                ទាញយករបាយការណ៍
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-group">
                <div class="card border-right">
                    <div class="card-body">
                        <h3 class="text-dark font-weight-medium">$1,250,000</h3>
                        <h6 class="text-muted mb-0">សរុបកម្ចីដែលបានផ្តល់ជូន</h6>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">
                        <h3 class="text-success font-weight-medium">$320,000</h3>
                        <h6 class="text-muted mb-0">សរុបប្រាក់ចំណេញការប្រាក់</h6>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">
                        <h3 class="text-warning font-weight-medium">$85,000</h3>
                        <h6 class="text-muted mb-0">សមតុល្យដែលនៅសល់</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-danger font-weight-medium">27</h3>
                        <h6 class="text-muted mb-0">កម្ចីហួសកំណត់</h6>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ការបែងចែកស្ថានភាពកម្ចី</h4>
                            <div id="loan-status-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ប្រាក់ចំណេញប្រចាំខែ</h4>
                            <div id="profit-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="card-title">របាយការណ៍កម្ចី</h4>
                        <div>
                            <button class="btn btn-outline-danger btn-sm mr-2">
                                ទាញយកជា PDF
                            </button>
                            <button class="btn btn-outline-success btn-sm">
                                ទាញយកជា Excel
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>អ្នកខ្ចី</th>
                                <th>ចំនួនកម្ចី</th>
                                <th>ស្ថានភាព</th>
                                <th>ថ្ងៃចេញកម្ចី</th>
                                <th>សមតុល្យ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>$25,000</td>
                                <td><span class="badge badge-success">បានបញ្ចប់</span></td>
                                <td>2025-01-15</td>
                                <td>$0</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>$40,000</td>
                                <td><span class="badge badge-warning">កំពុងដំណើរការ</span></td>
                                <td>2025-03-10</td>
                                <td>$12,500</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Michael Lee</td>
                                <td>$18,000</td>
                                <td><span class="badge badge-danger">ហួសកំណត់</span></td>
                                <td>2025-02-05</td>
                                <td>$6,800</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            "use strict";

            // Loan Status Donut Chart
            Morris.Donut({
                element: 'loan-status-chart',
                data: [
                    { label: "កំពុងដំណើរការ", value: 120 },
                    { label: "បានបញ្ចប់", value: 90 },
                    { label: "ហួសកំណត់", value: 27 }
                ],
                colors: ['#ffc107', '#28a745', '#dc3545'],
                resize: true
            });

            // Monthly Profit Bar Chart
            Morris.Bar({
                element: 'profit-chart',
                data: [
                    { y: 'មករា', profit: 18000 },
                    { y: 'កុម្ភៈ', profit: 22000 },
                    { y: 'មីនា', profit: 19500 },
                    { y: 'មេសា', profit: 26000 },
                    { y: 'ឧសភា', profit: 24000 },
                    { y: 'មិថុនា', profit: 30000 }
                ],
                xkey: 'y',
                ykeys: ['profit'],
                labels: ['ប្រាក់ចំណេញ'],
                barColors: ['#5f76e8'],
                resize: true
            });
        });
    </script>
@endpush
