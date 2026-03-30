@extends('backend.layout.master')
@push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-dark font-weight-medium mb-1 pt-2">
                    <i data-feather="alert-triangle" class="text-danger mr-1"></i>
                    កម្ចីដែលមិនបានសង (Defaulted / Written-Off)
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item active">Defaulted</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center">
                <div class="float-right">
                    <a href="{{ route('loans.index') }}" class="btn btn-secondary btn-sm">
                        <i data-feather="arrow-left"></i> ត្រលប់
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        {{-- Summary Stats --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card border-danger">
                    <div class="card-body py-3 text-center">
                        <h4 class="text-danger mb-0">{{ $defaultedLoans->count() }}</h4>
                        <small class="text-muted">កម្ចី Default សរុប</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-danger">
                    <div class="card-body py-3 text-center">
                        <h4 class="text-danger mb-0">${{ number_format($defaultedLoans->sum('principal_amount'), 0) }}</h4>
                        <small class="text-muted">ទឹកប្រាក់ Principal សរុប</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-danger">
                    <div class="card-body py-3 text-center">
                        <h4 class="text-danger mb-0">${{ number_format($defaultedLoans->sum('remaining_balance'), 0) }}</h4>
                        <small class="text-muted">សមតុល្យនៅសល់សរុប</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="defaulted_loans" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>លេខកម្ចី</th>
                                    <th>អ្នកខ្ចី</th>
                                    <th>Product</th>
                                    <th>ចំនួន Principal ($)</th>
                                    <th>សមតុល្យនៅសល់ ($)</th>
                                    <th>ថ្ងៃចាប់ផ្តើម</th>
                                    <th>ថ្ងៃហួសកំណត់</th>
                                    <th>ស្ថានភាព</th>
                                    <th class="text-center">សកម្មភាព</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($defaultedLoans as $loan)
                                    <tr>
                                        <td><strong>{{ $loan->loan_code }}</strong></td>
                                        <td>{{ $loan->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $loan->product->name ?? '—' }}</td>
                                        <td>${{ number_format($loan->principal_amount, 2) }}</td>
                                        <td class="text-danger font-weight-bold">${{ number_format($loan->remaining_balance, 2) }}</td>
                                        <td>{{ optional($loan->start_date)->format('d/m/Y') ?? '—' }}</td>
                                        <td>
                                            @if($loan->overdue_days > 0)
                                                <span class="badge badge-danger">{{ $loan->overdue_days }} ថ្ងៃ</span>
                                            @else
                                                <span class="badge badge-secondary">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $loan->statusBadge() }}">{{ $loan->statusLabel() }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('loans.show', $loan->id) }}"
                                               class="btn btn-sm btn-info" title="មើលព័ត៌មាន">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i data-feather="check-circle" class="text-success"></i>
                                            មិនមានកម្ចី Default នៅពេលនេះ
                                        </td>
                                    </tr>
                                @endforelse
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
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
    $(document).ready(function () {
        $("#defaulted_loans").DataTable({
            order: [[6, "desc"]], // sort by overdue days desc
            pageLength: 15, responsive: true,
            language: {
                search: "ស្វែងរក៖",
                lengthMenu: "បង្ហាញ _MENU_ បញ្ជី",
                info: "បង្ហាញ _START_–_END_ / _TOTAL_",
                paginate: { previous: "មុន", next: "បន្ទាប់" },
                zeroRecords: "មិនមានទិន្នន័យ"
            }
        });
        if (typeof feather !== "undefined" && feather) { feather.replace(); }
    });
    </script>
@endpush
