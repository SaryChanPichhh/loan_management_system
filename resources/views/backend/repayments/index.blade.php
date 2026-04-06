@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-1 mb-4">
        
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between ml-4 mr-4 mb-4">
            <h4 class="mb-0 font-weight-bold text-dark">ប្រវត្តិបង់ប្រាក់ (Repayments Log)</h4>
            
            <div class="d-flex align-items-center">
                <!-- Search Form -->
                <form action="{{ route('repayments.index') }}" id="searchForm" method="GET" class="d-flex align-items-center bg-white border rounded-pill px-3 shadow-sm mr-3" style="width: 250px; height: 38px;">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <i data-feather="search" class="text-muted mr-2" style="width: 16px; height: 16px;"></i>
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកអតិថិជន..." class="form-control border-0 bg-transparent shadow-none p-0 w-100" style="outline: none; box-shadow: none;">
                </form>

                <!-- Status Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary rounded-pill shadow-sm px-4 dropdown-toggle d-flex align-items-center" type="button" id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="filter" class="mr-2" style="width: 16px; height: 16px;"></i>
                        {{ $status ? ucfirst($status) : 'ទាំងអស់ (All)' }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="filterDropdown">
                        <a class="dropdown-item" href="{{ route('repayments.index') }}">ទាំងអស់ (All)</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-success" href="{{ route('repayments.index', ['status' => 'Paid']) }}"><i data-feather="check-circle" class="mr-2" style="width:14px;height:14px;"></i>Paid (បានបង់)</a>
                        <a class="dropdown-item text-warning" href="{{ route('repayments.index', ['status' => 'Pending']) }}"><i data-feather="clock" class="mr-2" style="width:14px;height:14px;"></i>Pending (រង់ចាំ)</a>
                        <a class="dropdown-item text-danger" href="{{ route('repayments.index', ['status' => 'Failed']) }}"><i data-feather="x-circle" class="mr-2" style="width:14px;height:14px;"></i>Failed (បរាជ័យ)</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Display -->
        <div class="ml-4 mr-4" id="tableContainer">
            <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 no-wrap align-middle">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="py-3 px-4 font-weight-medium">ល.រ (No)</th>
                                    <th class="py-3 font-weight-medium">លេខកម្ចី (Reference)</th>
                                    <th class="py-3 font-weight-medium">អតិថិជន (Customer)</th>
                                    <th class="py-3 font-weight-medium text-right">ទឹកប្រាក់ (Amount)</th>
                                    <th class="py-3 font-weight-medium text-center">វិធីបង់ប្រាក់ (Method)</th>
                                    <th class="py-3 font-weight-medium">កាលបរិច្ឆេទ (Date)</th>
                                    <th class="py-3 font-weight-medium text-center">ស្ថានភាព (Status)</th>
                                    <th class="py-3 font-weight-medium text-center">សកម្មភាព (Actions)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repayments as $key => $repayment)
                                <tr class="border-bottom">
                                    <td class="px-4 py-3">{{ $repayments->firstItem() + $key }}</td>
                                    <td class="py-3">
                                        <div class="font-weight-bold text-primary">
                                            <i data-feather="file-text" style="width: 14px; height: 14px;" class="mr-1"></i> {{ $repayment->loan_reference }}
                                        </div>
                                        <small class="text-muted">Ref: {{ $repayment->reference_number ?? 'N/A' }}</small>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light text-secondary rounded-circle text-center d-flex align-items-center justify-content-center mr-2 shadow-sm" style="width: 32px; height: 32px;">
                                                <b style="font-size: 0.8rem;">{{ mb_substr($repayment->customer_name, 0, 1) }}</b>
                                            </div>
                                            <span class="font-weight-bold text-dark">{{ $repayment->customer_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-right">
                                        <span class="font-weight-bold text-dark text-success">${{ number_format($repayment->amount, 2) }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge badge-light text-secondary border rounded-pill px-3 py-1">
                                            @if(strtolower($repayment->payment_method) == 'cash')
                                                <i data-feather="dollar-sign" style="width:12px; height:12px;" class="mr-1"></i>
                                            @else
                                                <i data-feather="briefcase" style="width:12px; height:12px;" class="mr-1"></i>
                                            @endif
                                            {{ $repayment->payment_method }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-muted">
                                        {{ \Carbon\Carbon::parse($repayment->payment_date)->format('d-M-Y') }}
                                    </td>
                                    <td class="py-3 text-center">
                                        @if(strtolower($repayment->status) == 'paid')
                                            <span class="badge badge-success rounded-pill px-3 py-1"><i data-feather="check" class="mr-1" style="width:12px;height:12px;"></i> Paid</span>
                                        @elseif(strtolower($repayment->status) == 'failed')
                                            <span class="badge badge-danger rounded-pill px-3 py-1"><i data-feather="x" class="mr-1" style="width:12px;height:12px;"></i> Failed</span>
                                        @else
                                            <span class="badge badge-warning text-dark rounded-pill px-3 py-1"><i data-feather="clock" class="mr-1" style="width:12px;height:12px;"></i> Pending</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <a href="{{ route('repayments.receipt', $repayment->id) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                            <i data-feather="printer" style="width: 14px; height: 14px;" class="mr-1"></i> Receipt
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3 text-muted opacity-50" style="width: 60px; height: 60px;">
                                            <i data-feather="file-minus" style="width: 30px; height: 30px;"></i>
                                        </div>
                                        <h5 class="text-dark font-weight-bold">មិនមានទិន្នន័យ (No Data Found)</h5>
                                        <p class="text-muted">មិនមានប្រវត្តិបង់ប្រាក់នៅទីនេះទេ.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 pt-2 d-flex justify-content-end">
                {{ $repayments->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            
        </div>
    </div>

    @push('styles')
    <style>
        .rounded-xl {
            border-radius: 1rem !important;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            let debounceTimer;

            // Function to fetch and update the grid without refreshing the page
            const updateGrid = (url) => {
                const container = document.getElementById('tableContainer');
                container.style.opacity = '0.5'; // Soft loading indicator
                
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract exactly the new tableContainer HTML
                    const newContent = doc.getElementById('tableContainer').innerHTML;
                    container.innerHTML = newContent;
                    
                    // Restore opacity and re-render SVG icons
                    container.style.opacity = '1';
                    if (typeof feather !== 'undefined') feather.replace();
                })
                .catch(error => {
                    console.error('Error fetching grid:', error);
                    container.style.opacity = '1';
                });
            };

            if (searchInput) {
                // Trigger Grid Update on Typing
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        const baseUrl = new URL(searchForm.action);
                        const formData = new FormData(searchForm);
                        formData.forEach((value, key) => baseUrl.searchParams.append(key, value));
                        
                        // Push new URL to browser history silently (so copy/pasting URL works)
                        window.history.pushState({}, '', baseUrl);
                        updateGrid(baseUrl.toString());
                    }, 400); // 400ms debounce
                });

                // Prevent full native page refresh if they somehow press Enter
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                });
            }

            // Delegate events for dynamically-loaded Pagination links so they also don't refresh the page!
            document.addEventListener('click', function(e) {
                const pageLink = e.target.closest('.pagination a');
                if (pageLink) {
                    e.preventDefault();
                    window.history.pushState({}, '', pageLink.href);
                    updateGrid(pageLink.href);
                }
            });
        });
    </script>
    @endpush
@endsection
