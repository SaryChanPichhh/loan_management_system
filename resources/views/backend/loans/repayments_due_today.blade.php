@extends('backend.layout.master')

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">ការបង់ប្រាក់ត្រូវបង់នៅថ្ងៃនេះ</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item active">ការបង់ប្រាក់ថ្ងៃនេះ</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center text-right">
                <h3 class="text-primary font-weight-bold mb-0">{{ now()->format('d-M-Y') }}</h3>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{ count($schedules) }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">ចំនួនអតិថិជនត្រូវបង់</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">${{ number_format($schedules->sum('amount_due'), 2) }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">ទឹកប្រាក់ត្រូវប្រមូលសរុប</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input type="text" id="scheduleSearch" class="form-control border-left-0" placeholder="ស្វែងរកតាមឈ្មោះ ឬលេខកូដ...">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover no-wrap v-middle mb-0">
                                <thead class="bg-primary text-white">
                                    <tr class="border-0">
                                        <th class="border-0 font-14 font-weight-medium">ល.រ</th>
                                        <th class="border-0 font-14 font-weight-medium">អតិថិជន</th>
                                        <th class="border-0 font-14 font-weight-medium">លេខកូដកម្ចី</th>
                                        <th class="border-0 font-14 font-weight-medium text-center">លើកទី</th>
                                        <th class="border-0 font-14 font-weight-medium">ទឹកប្រាក់ត្រូវបង់</th>
                                        <th class="border-0 font-14 font-weight-medium">បានបង់ខ្លះ</th>
                                        <th class="border-0 font-14 font-weight-medium">ស្ថានភាព</th>
                                        <th class="border-0 font-14 font-weight-medium text-center">សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody id="repaymentsTableBody">
                                    @include('backend.loans.partials.repayments_table')
                                </tbody>
                            </table>
                        </div>
                        
                        @if(count($schedules) === 0)
                            <div class="text-center py-5">
                                <img src="{{ asset('backend_assets/assets/images/background/no_data.svg') }}" alt="No Data" class="mb-3" style="max-width: 150px;">
                                <h5 class="text-muted">មិនមានការបង់ប្រាក់សម្រាប់ថ្ងៃនេះទេ</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#scheduleSearch').on('keyup', function() {
            var value = $(this).val();
            $.ajax({
                url: "{{ route('loans.repayments_due_today') }}",
                type: "GET",
                data: { search: value },
                success: function(data) {
                    $('#repaymentsTableBody').html(data);
                }
            });
        });
    });
</script>
@endpush
