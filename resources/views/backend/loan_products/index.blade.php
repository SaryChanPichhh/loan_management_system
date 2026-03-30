@extends('backend.layout.master')

@push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
        .badge-interest { background-color: #6f42c1; color: #fff; }
        .badge-active   { background-color: #28a745; color: #fff; }
        .badge-inactive { background-color: #dc3545; color: #fff; }
        .product-code   { font-family: monospace; font-size: .85rem; }
        .stat-card { border-left: 4px solid; border-radius: .4rem; }
        .stat-card.primary  { border-color: #007bff; }
        .stat-card.success  { border-color: #28a745; }
        .stat-card.warning  { border-color: #ffc107; }
        .stat-card.danger   { border-color: #dc3545; }
        .stat-number { font-size: 1.6rem; font-weight: 700; }
    </style>
@endpush

@section('contents')
<div class="page-wrapper">
    {{-- Breadcrumb --}}
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    ផលិតផលកម្ចី
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">ផលិតផលកម្ចី</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <a href="{{ route('loan_products.create') }}" class="btn btn-primary">
                        <i data-feather="plus"></i> បង្កើតផលិតផលថ្មី
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i data-feather="check-circle" style="width:16px;height:16px;"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i data-feather="alert-circle" style="width:16px;height:16px;"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Stats Row --}}
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="card stat-card primary py-3 px-4">
                    <div class="stat-number text-primary">{{ $products->count() }}</div>
                    <div class="text-muted small">ផលិតផលទាំងអស់</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card stat-card success py-3 px-4">
                    <div class="stat-number text-success">{{ $products->where('status', true)->count() }}</div>
                    <div class="text-muted small">ផលិតផលដំណើរការ</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card stat-card danger py-3 px-4">
                    <div class="stat-number text-danger">{{ $products->where('status', false)->count() }}</div>
                    <div class="text-muted small">ផលិតផលបិទ</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card stat-card warning py-3 px-4">
                    <div class="stat-number text-warning">
                        {{ $products->where('interest_type','FLAT')->count() }} /
                        {{ $products->where('interest_type','REDUCING_BALANCE')->count() }}
                    </div>
                    <div class="text-muted small">Flat / Reducing</div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title mb-0">
                            <i data-feather="package" style="width:18px;height:18px;"></i>
                            បញ្ជីផលិតផលកម្ចី
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="product_table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>លេខកូដ</th>
                                        <th>ឈ្មោះ</th>
                                        <th>ចំនួនទឹកប្រាក់ ($ Min–Max)</th>
                                        <th>អត្រាការប្រាក់ / ខែ</th>
                                        <th>ប្រភេទការប្រាក់</th>
                                        <th>រយៈពេលអតិបរមា</th>
                                        <th>ស្ថានភាព</th>
                                        <th class="text-center">សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $i => $product)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td><span class="product-code badge badge-light">{{ $product->product_code }}</span></td>
                                            <td><strong>{{ $product->name }}</strong><br>
                                                @if($product->description)
                                                    <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                ${{ number_format($product->min_amount, 2) }}
                                                &nbsp;–&nbsp;
                                                ${{ number_format($product->max_amount, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge badge-interest">{{ number_format($product->interest_rate, 2) }}%</span>
                                            </td>
                                            <td>
                                                @if($product->interest_type === 'FLAT')
                                                    <span class="badge badge-info">Flat</span>
                                                @elseif($product->interest_type === 'REDUCING_BALANCE')
                                                    <span class="badge badge-primary">Reducing</span>
                                                @else
                                                    <span class="badge badge-secondary">Compound</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->max_term_months }} ខែ</td>
                                            <td>
                                                @if($product->status)
                                                    <span class="badge badge-active">បើក</span>
                                                @else
                                                    <span class="badge badge-inactive">បិទ</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('loan_products.show', $product->id) }}"
                                                       class="btn btn-sm btn-info" title="មើលព័ត៌មាន">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                    <a href="{{ route('loan_products.edit', $product->id) }}"
                                                       class="btn btn-sm btn-primary" title="កែសម្រួល">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <form action="{{ route('loan_products.toggle_status', $product->id) }}"
                                                          method="POST" style="display:inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $product->status ? 'btn-warning' : 'btn-success' }}"
                                                            title="{{ $product->status ? 'បិទ' : 'បើក' }}">
                                                            <i data-feather="{{ $product->status ? 'toggle-right' : 'toggle-left' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('loan_products.destroy', $product->id) }}"
                                                          method="POST" style="display:inline"
                                                          onsubmit="return confirm('តើអ្នកប្រាកដថាចង់លុបផលិតផលនេះមែនទេ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="លុប">
                                                            <i data-feather="trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-muted">
                                                <i data-feather="inbox" style="width:32px;height:32px;"></i><br>
                                                មិនទាន់មានផលិតផលកម្ចីណាមួយទេ
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
            $('#product_table').DataTable({
                pageLength: 15,
                responsive: true,
                order: [[0, 'asc']],
                language: {
                    search: 'ស្វែងរក៖',
                    lengthMenu: 'បង្ហាញ _MENU_ ទិន្នន័យ',
                    info: 'បង្ហាញពីលេខ _START_ ដល់ _END_ នៃទិន្នន័យសរុប _TOTAL_',
                    infoEmpty: 'មិនមានទិន្នន័យ',
                    infoFiltered: '(ចម្រាញ់ចេញពីទិន្នន័យសរុប _MAX_)',
                    paginate: { first: 'ដំបូង', last: 'ចុងក្រោយ', next: 'បន្ទាប់', previous: 'មុន' },
                    zeroRecords: 'មិនមានទិន្នន័យដែលអ្នកស្វែងរកឡើយ'
                }
            });
            if (typeof feather !== 'undefined' && feather) feather.replace();
        });
    </script>
@endpush
