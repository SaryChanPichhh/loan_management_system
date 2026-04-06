@extends('backend.layout.master')

@push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">អ្នកធានា</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item active" aria-current="page">បញ្ជីអ្នកធានា</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <a href="{{ route('guarantors.create') }}" class="btn btn-primary">
                        <i data-feather="plus"></i> បង្កើតអ្នកធានាថ្មី
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i data-feather="check-circle" style="width:16px;height:16px;"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="guarantors_table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ឈ្មោះអ្នកធានា</th>
                                        <th>អត្តសញ្ញាណប័ណ្ណ</th>
                                        <th>លេខទូរស័ព្ទ</th>
                                        <th>ធានាអតិថិជន</th>
                                        <th>ចំណូល ($)</th>
                                        <th>ទំនាក់ទំនង</th>
                                        <th>ស្ថានភាព</th>
                                        <th class="text-center">សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($guarantors as $i => $guarantor)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td><strong>{{ $guarantor->full_name }}</strong></td>
                                            <td>{{ $guarantor->national_id }}</td>
                                            <td>{{ $guarantor->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if($guarantor->customer)
                                                   <a href="{{ route('customer.show', $guarantor->customer_id) }}">{{ $guarantor->customer->name }}</a>
                                                @else
                                                   -
                                                @endif
                                            </td>
                                            <td><strong>${{ number_format($guarantor->income, 2) }}</strong></td>
                                            <td>{{ $guarantor->relationship ?? 'N/A' }}</td>
                                            <td>
                                                @if($guarantor->status == 'active')
                                                    <span class="badge badge-success">សកម្ម</span>
                                                @elseif($guarantor->status == 'released')
                                                    <span class="badge badge-warning">រួចរាល់</span>
                                                @else
                                                    <span class="badge badge-danger">ខកខាន</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('guarantors.show', $guarantor->id) }}" class="btn btn-sm btn-info" title="មើលព័ត៌មាន">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                    <a href="{{ route('guarantors.edit', $guarantor->id) }}" class="btn btn-sm btn-primary" title="កែសម្រួល">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <form action="{{ route('guarantors.destroy', $guarantor->id) }}" method="POST" style="display:inline" onsubmit="return confirm('តើអ្នកប្រាកដទេ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="លុប">
                                                            <i data-feather="trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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
            $('#guarantors_table').DataTable({
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
