@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper pb-5">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-dark font-weight-medium mb-1 pt-2">ទិនានុប្បវត្តិទូទៅ (General Journal)</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('accounting.index') }}">គណនេយ្យ</a></li>
                            <li class="breadcrumb-item active">បញ្ជីប្រតិបត្តិការ (All Transactions)</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a href="{{ route('accounting.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i data-feather="arrow-left" class="mr-1" style="width: 16px;"></i> ត្រឡប់ក្រោយ (Back)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Journal Entries Table -->
            @foreach($entries as $entry)
            <div class="card shadow-sm border-0 rounded-xl mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-light p-2 rounded mr-3">
                            <i data-feather="file-text" class="text-primary" style="width: 18px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-dark">{{ $entry->description }}</h5>
                            <small class="text-muted">Ref: {{ $entry->reference_type }} #{{ $entry->reference_id }} | {{ $entry->entry_date->format('d M, Y') }}</small>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="small d-block text-muted">Recorded by</span>
                        <span class="font-weight-medium text-dark">{{ $entry->creator->name ?? 'System' }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="bg-light text-muted small uppercase">
                                    <th class="px-5">គណនេយ្យ (Account)</th>
                                    <th class="text-right pr-5" style="width: 200px;">ជំពាក់ (Debit)</th>
                                    <th class="text-right pr-5" style="width: 200px;">មាន (Credit)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entry->items as $item)
                                <tr>
                                    <td class="px-5 py-2">
                                        <div class="{{ $item->type == 'Credit' ? 'pl-4' : '' }}">
                                            <span class="text-dark font-weight-medium">{{ $item->account->name }}</span>
                                            <span class="badge badge-light border ml-1">{{ $item->account->code }}</span>
                                        </div>
                                    </td>
                                    <td class="text-right pr-5 py-2 font-weight-bold text-dark">
                                        {{ $item->type == 'Debit' ? '$' . number_format($item->amount, 2) : '' }}
                                    </td>
                                    <td class="text-right pr-5 py-2 font-weight-bold text-dark">
                                        {{ $item->type == 'Credit' ? '$' . number_format($item->amount, 2) : '' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-4">
                {{ $entries->links() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .rounded-xl { border-radius: 1rem !important; }
    .bg-primary-light { background-color: rgba(95, 118, 232, 0.1); }
    .table td, .table th { border-top: none; }
</style>
@endpush
