@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-1 mb-4">
        <div class="d-flex flex-column ml-4 mr-4">
            <h4 class="mb-3 font-weight-bold text-dark">កំណត់ហេតុសកម្មភាព (Activity Logs)</h4>
        </div>

        <div class="mt-2 ml-4 mr-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 no-wrap align-middle">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="py-3 px-4 font-weight-medium">ល.រ</th>
                                    <th class="py-3 font-weight-medium">អ្នកប្រើប្រាស់ (User)</th>
                                    <th class="py-3 font-weight-medium">សកម្មភាព (Action)</th>
                                    <th class="py-3 font-weight-medium">បញ្ជាក់ (Description)</th>
                                    <th class="py-3 font-weight-medium">អាសយដ្ឋាន (IP)</th>
                                    <th class="py-3 font-weight-medium">ពេលវេលា (Time)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $key => $log)
                                <tr class="border-bottom">
                                    <td class="px-4 py-3">{{ $logs->firstItem() + $key }}</td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light text-primary rounded-circle text-center d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px;">
                                                <b>{{ substr($log->user_name, 0, 1) }}</b>
                                            </div>
                                            <span class="font-weight-bold">{{ $log->user_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if(str_contains(strtolower($log->action), 'created'))
                                            <span class="badge badge-success rounded-pill px-3 py-1">{{ $log->action }}</span>
                                        @elseif(str_contains(strtolower($log->action), 'updated'))
                                            <span class="badge badge-warning text-dark rounded-pill px-3 py-1">{{ $log->action }}</span>
                                        @elseif(str_contains(strtolower($log->action), 'deleted'))
                                            <span class="badge badge-danger rounded-pill px-3 py-1">{{ $log->action }}</span>
                                        @else
                                            <span class="badge badge-info text-white rounded-pill px-3 py-1">{{ $log->action }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-muted">{{ $log->description }}</td>
                                    <td class="py-3">{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td class="py-3 text-muted">{{ $log->created_at->format('d/m/Y h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">មិនមានកំណត់ហេតុសកម្មភាពទេ</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination block -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
