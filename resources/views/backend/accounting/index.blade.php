@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper pb-5">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">តារាងគណនេយ្យ (Chart of Accounts)</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 text-muted">
                            <li class="breadcrumb-item">ទិដ្ឋភាពទូទៅនៃសមតុល្យគណនេយ្យរបស់អ្នក</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a href="{{ route('accounting.journal') }}" class="btn btn-primary rounded-pill px-4">
                            <i data-feather="list" class="mr-1" style="width: 16px;"></i> ទិនានុប្បវត្តិទូទៅ (General Journal)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Account Summary Cards -->
            <div class="row">
                @php
                    $assetBalance = $accounts->where('type', 'Asset')->sum('balance');
                    $revenueBalance = $accounts->where('type', 'Revenue')->sum('balance');
                @endphp
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-lg bg-primary text-white">
                        <div class="card-body">
                            <h6 class="font-weight-normal text-white-50">សរុបទ្រព្យសកម្ម (Total Assets)</h6>
                            <h3 class="mb-0 font-weight-bold">${{ number_format($assetBalance, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-lg bg-success text-white">
                        <div class="card-body">
                            <h6 class="font-weight-normal text-white-50">សរុបចំណូល (Total Revenue)</h6>
                            <h3 class="mb-0 font-weight-bold">${{ number_format($revenueBalance, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-xl mt-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="text-dark">
                                    <th class="px-4 py-3">លេខកូដ (Code)</th>
                                    <th class="py-3">ឈ្មោះគណនេយ្យ (Account Name)</th>
                                    <th class="py-3">ប្រភេទ (Type)</th>
                                    <th class="py-3 text-right pr-4">សមតុល្យ (Current Balance)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="font-weight-bold text-dark">{{ $account->code }}</span>
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('accounting.ledger', $account->id) }}" class="text-primary font-weight-medium">
                                            {{ $account->name }}
                                        </a>
                                        <p class="small text-muted mb-0">{{ $account->description }}</p>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge badge-pill 
                                            @if($account->type == 'Asset') badge-info
                                            @elseif($account->type == 'Revenue') badge-success
                                            @elseif($account->type == 'Liability') badge-warning
                                            @else badge-secondary @endif px-3">
                                            {{ $account->type }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right pr-4">
                                        <h6 class="font-weight-bold mb-0 {{ $account->balance < 0 ? 'text-danger' : 'text-dark' }}">
                                            ${{ number_format(abs($account->balance), 2) }}
                                            @if($account->balance < 0) <small>(Cr)</small> @endif
                                        </h6>
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
@endsection

@push('styles')
<style>
    .rounded-xl { border-radius: 1rem !important; }
    .rounded-lg { border-radius: 0.75rem !important; }
    .table td { vertical-align: middle; }
</style>
@endpush
