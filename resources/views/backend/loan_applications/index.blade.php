@extends('backend.layout.master')

@push('styles')
    <link href="{{ asset('backend_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush

@section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">សំណើសុំកម្ចី</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                            <li class="breadcrumb-item active" aria-current="page">បញ្ជីសំណើសុំកម្ចី</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <a href="{{ route('loan_applications.create') }}" class="btn btn-primary">
                        <i data-feather="plus"></i> បង្កើតសំណើសុំថ្មី
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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i data-feather="x-circle" style="width:16px;height:16px;"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="applications_table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>លេខកូដសំណើ</th>
                                        <th>ឈ្មោះអតិថិជន</th>
                                        <th>ផលិតផល</th>
                                        <th>ទំហំប្រាក់ស្នើ</th>
                                        <th>រយៈពេល</th>
                                        <th>ស្ថានភាព</th>
                                        <th class="text-center">សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td><strong><a href="{{ route('loan_applications.show', $application->id) }}">{{ $application->application_code }}</a></strong></td>
                                            <td>
                                                @if($application->customer)
                                                   <a href="{{ route('customer.show', $application->customer_id) }}">{{ $application->customer->name }}</a>
                                                @else
                                                   -
                                                @endif
                                            </td>
                                            <td>{{ $application->product ? $application->product->name : '-' }}</td>
                                            <td>${{ number_format($application->requested_amount, 2) }}</td>
                                            <td>{{ $application->requested_months }} ខែ</td>
                                            <td>{!! $application->status_badge_html !!}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if($application->status === 'pending')
                                                        <form action="{{ route('loan_applications.update_status', $application->id) }}" method="POST" style="display:inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="under_review">
                                                            <button type="submit" class="btn btn-sm btn-success" title="ពិនិត្យ">
                                                                <i data-feather="check-square"></i> Review
                                                            </button>
                                                        </form>
                                                    @elseif($application->status === 'under_review')
                                                        <a href="{{ route('loan_applications.show', $application->id) }}" class="btn btn-sm btn-success" title="បន្តការពិនិត្យ">
                                                            <i data-feather="check-square"></i> Review
                                                        </a>
                                                    @endif

                                                    @if(in_array($application->status, ['pending', 'under_review']))
                                                    <a href="{{ route('loan_applications.edit', $application->id) }}" class="btn btn-sm btn-primary" title="កែសម្រួល">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <form action="{{ route('loan_applications.destroy', $application->id) }}" method="POST" style="display:inline" onsubmit="return confirm('តើអ្នកប្រាកដទេ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="លុប">
                                                            <i data-feather="trash-2"></i>
                                                        </button>
                                                    </form>
                                                    @endif
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
            $('#applications_table').DataTable({
                pageLength: 15,
                responsive: true,
                order: [[0, 'desc']],
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
