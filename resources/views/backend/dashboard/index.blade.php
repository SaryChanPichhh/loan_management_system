@extends('backend.layout.master')
@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">អរុណសួស្តី Jason!</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">ផ្ទាំងគ្រប់គ្រង</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                            <option selected>សីហា ១៩</option>
                            <option value="1">កក្កដា ១៩</option>
                            <option value="2">មិថុនា ១៩</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card-group">
                <div class="card border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">236</h2>
                                    <span
                                        class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">កំពុងដំណើរការ</span>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">កម្ចីសរុប</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="file-text"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><sup
                                        class="set-doller">$</sup>1,850,306</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">សរុបទឹកប្រាក់កម្ចីដែលបានបញ្ចេញ
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">38</h2>
                                    <span
                                        class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">ហួសកំណត់</span>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">ការបង់ប្រាក់យឺតយ៉ាវ</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="alert-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">164</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">អ្នកខ្ចីសរុប</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ការបញ្ចេញកម្ចីប្រចាំខែ</h4>
                            <div id="morris-bar-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ស្ថានភាពសងត្រឡប់</h4>
                            <ul class="list-inline text-right">
                                <li class="list-inline-item">
                                    <h5><i class="fa fa-circle mr-1 text-success"></i>បង់រួច</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5><i class="fa fa-circle mr-1 text-warning"></i>មិនទាន់បង់</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5><i class="fa fa-circle mr-1 text-danger"></i>ហួសកំណត់</h5>
                                </li>
                            </ul>
                            <div id="morris-repayment-chart"></div>
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
        $(function () {
            "use strict";

            // Monthly Loan Disbursement Bar Chart
            Morris.Bar({
                element: 'morris-bar-chart',
                data: [
                    {y: 'មករា', disbursed: 125000, target: 150000},
                    {y: 'កុម្ភៈ', disbursed: 180000, target: 150000},
                    {y: 'មីនា', disbursed: 145000, target: 150000},
                    {y: 'មេសា', disbursed: 220000, target: 150000},
                    {y: 'ឧសភា', disbursed: 195000, target: 150000},
                    {y: 'មិថុនា', disbursed: 250000, target: 150000},
                    {y: 'កក្កដា', disbursed: 210000, target: 150000},
                    {y: 'សីហា', disbursed: 185000, target: 150000},
                    {y: 'កញ្ញា', disbursed: 230000, target: 150000},
                    {y: 'តុលា', disbursed: 200000, target: 150000},
                    {y: 'វិច្ឆិកា', disbursed: 175000, target: 150000},
                    {y: 'ធ្នូ', disbursed: 240000, target: 150000}
                ],
                xkey: 'y',
                ykeys: ['disbursed', 'target'],
                labels: ['បានបញ្ចេញ', 'គោលដៅ'],
                barColors: ['#5f76e8', '#01caf1'],
                hideHover: 'auto',
                gridLineColor: '#eef0f2',
                resize: true,
                barSizeRatio: 0.5,
                barGap: 3
            });

            // Repayment Status Area Chart
            Morris.Area({
                element: 'morris-repayment-chart',
                data: [
                    {period: 'មករា', paid: 85, unpaid: 25, overdue: 5},
                    {period: 'កុម្ភៈ', paid: 92, unpaid: 18, overdue: 3},
                    {period: 'មីនា', paid: 88, unpaid: 22, overdue: 4},
                    {period: 'មេសា', paid: 95, unpaid: 15, overdue: 2},
                    {period: 'ឧសភា', paid: 90, unpaid: 20, overdue: 3},
                    {period: 'មិថុនា', paid: 98, unpaid: 12, overdue: 1},
                    {period: 'កក្កដា', paid: 93, unpaid: 17, overdue: 2},
                    {period: 'សីហា', paid: 89, unpaid: 21, overdue: 4},
                    {period: 'កញ្ញា', paid: 96, unpaid: 14, overdue: 2},
                    {period: 'តុលា', paid: 91, unpaid: 19, overdue: 3},
                    {period: 'វិច្ឆិកា', paid: 94, unpaid: 16, overdue: 2},
                    {period: 'ធ្នូ', paid: 97, unpaid: 13, overdue: 1}
                ],
                xkey: 'period',
                ykeys: ['paid', 'unpaid', 'overdue'],
                labels: ['បង់រួច', 'មិនទាន់បង់', 'ហួសកំណត់'],
                pointSize: 3,
                fillOpacity: 0.6,
                pointStrokeColors: ['#28a745', '#ffc107', '#dc3545'],
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                lineWidth: 2,
                hideHover: 'auto',
                lineColors: ['#28a745', '#ffc107', '#dc3545'],
                resize: true
            });
        });
    </script>
@endpush
