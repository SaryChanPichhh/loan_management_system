@extends('backend.layout.master')
@push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush

@section('contents')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">ការគ្រប់គ្រងកម្ចី</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                                <li class="breadcrumb-item active" aria-current="page">កម្ចី</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a href="{{ route('loans.create') }}" class="btn btn-primary">
                            <i data-feather="plus"></i> បង្កើតកម្ចីថ្មី
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            {{-- Status Tabs --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-2">
                            <ul class="nav nav-tabs" role="tablist">
                                @php
                                    $tabs = [
                                        null        => ['label' => 'ទាំងអស់',           'badge' => 'secondary'],
                                        'pending'   => ['label' => 'កំពុងរង់ចាំ',      'badge' => 'warning'],
                                        'under_review' => ['label' => 'កំពុងពិនិត្យ', 'badge' => 'info'],
                                        'approved'  => ['label' => 'បានអនុម័ត',        'badge' => 'primary'],
                                        'active'    => ['label' => 'កំពុងដំណើរការ',    'badge' => 'success'],
                                        'completed' => ['label' => 'បានបញ្ចប់',        'badge' => 'secondary'],
                                        'defaulted' => ['label' => 'មិនបានសង',         'badge' => 'danger'],
                                        'rejected'  => ['label' => 'បដិសេធ',           'badge' => 'danger'],
                                        'written_off' => ['label' => 'ចាត់ទុកជាខាត',  'badge' => 'dark'],
                                    ];
                                @endphp
                                @foreach($tabs as $tabStatus => $tabInfo)
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('status') === $tabStatus ? 'active' : '' }}"
                                           href="{{ route('loans.index', $tabStatus ? ['status' => $tabStatus] : []) }}">
                                            {{ $tabInfo['label'] }}
                                            @if(isset($statusCounts[$tabStatus]) && $statusCounts[$tabStatus] > 0)
                                                <span class="badge badge-{{ $tabInfo['badge'] }} ml-1">{{ $statusCounts[$tabStatus] }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loans Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="loan_table" class="table table-striped table-bordered no-wrap">
                                    <thead>
                                    <tr>
                                        <th>លេខកម្ចី</th>
                                        <th>អ្នកខ្ចី</th>
                                        <th>Product</th>
                                        <th>ចំនួនទឹកប្រាក់</th>
                                        <th>រយៈពេល</th>
                                        <th>ការប្រាក់ (%)</th>
                                        <th>ស្ថានភាព</th>
                                        <th>ថ្ងៃចាប់ផ្តើម</th>
                                        <th class="text-center">សកម្មភាព</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($loans as $loan)
                                        <tr>
                                            <td><strong>{{ $loan->loan_code }}</strong></td>
                                            <td>{{ $loan->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $loan->product->name ?? '—' }}</td>
                                            <td>${{ number_format($loan->principal_amount, 2) }}</td>
                                            <td>{{ $loan->duration_months }} ខែ</td>
                                            <td>{{ $loan->interest_rate }}%</td>
                                            <td>
                                                <span class="badge {{ $loan->statusBadge() }}">{{ $loan->statusLabel() }}</span>
                                            </td>
                                            <td>{{ $loan->start_date ? $loan->start_date->format('d/m/Y') : '—' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('loans.show', $loan->id) }}"
                                                       class="btn btn-sm btn-info" title="មើលព័ត៌មាន">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                    @if($loan->status === 'pending')
                                                        <a href="{{ route('loans.edit', $loan->id) }}"
                                                           class="btn btn-sm btn-warning" title="កែសម្រួល">
                                                            <i data-feather="edit"></i>
                                                        </a>
                                                        <a href="{{ route('loans.review', $loan->id) }}"
                                                           class="btn btn-sm btn-success" title="ពិនិត្យ/អនុម័ត">
                                                            <i data-feather="check-circle"></i>
                                                        </a>
                                                        <form action="{{ route('loans.destroy', $loan->id) }}"
                                                              method="POST" style="display:inline"
                                                              onsubmit="return confirm('តើអ្នកប្រាកដថាចង់លុបកម្ចីនេះមែនទេ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="លុប">
                                                                <i data-feather="trash-2"></i>
                                                            </button>
                                                        </form>
                                                    @elseif($loan->status === 'active')
                                                        <a href="{{ route('loans.payments', $loan->id) }}"
                                                           class="btn btn-sm btn-primary" title="ការទូទាត់">
                                                            <i data-feather="dollar-sign"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i data-feather="inbox"></i> មិនមានទិន្នន័យ
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
            $("#loan_table").DataTable({
                pageLength: 15,
                responsive: true,
                language: {
                    search: "ស្វែងរក៖",
                    lengthMenu: "បង្ហាញ _MENU_ ទិន្នន័យ",
                    info: "បង្ហាញពីលេខ _START_ ដល់ _END_ នៃទិន្នន័យសរុប _TOTAL_",
                    infoEmpty: "មិនមានទិន្នន័យ",
                    infoFiltered: "(ចម្រាញ់ចេញពីទិន្នន័យសរុប _MAX_)",
                    paginate: { first: "ដំបូង", last: "ចុងក្រោយ", next: "បន្ទាប់", previous: "មុន" },
                    zeroRecords: "មិនមានទិន្នន័យដែលអ្នកស្វែងរកឡើយ"
                }
            });

            if (typeof feather !== "undefined" && feather) { feather.replace(); }
        });
    </script>
@endpush
