@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper pb-5">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-dark font-weight-medium mb-1 pt-2">សៀវភៅធំ (Ledger): {{ $account->name }}</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('accounting.index') }}">គណនេយ្យ</a></li>
                            <li class="breadcrumb-item active">{{ $account->code }}</li>
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
            <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="text-dark small uppercase">
                                    <th class="px-4 py-3">កាលបរិច្ឆេទ (Date)</th>
                                    <th class="py-3">ពិពណ៌នា (Description)</th>
                                    <th class="py-3 text-right">ជំពាក់ (Debit)</th>
                                    <th class="py-3 text-right">មាន (Credit)</th>
                                    <th class="py-3 text-right pr-4">សមតុល្យរត់ (Running Balance)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $runningBalance = 0; @endphp
                                @forelse($items as $item)
                                    @php
                                        if (in_array($account->type, ['Asset', 'Expense'])) {
                                            $runningBalance += ($item->type == 'Debit' ? $item->amount : -$item->amount);
                                        } else {
                                            $runningBalance += ($item->type == 'Credit' ? $item->amount : -$item->amount);
                                        }
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-muted">{{ $item->entry->entry_date->format('d M, Y') }}</td>
                                        <td class="py-3 font-weight-medium text-dark">
                                            {{ $item->entry->description }}
                                            <br><small class="text-muted">Ref: {{ $item->entry->reference_type }} #{{ $item->entry->reference_id }}</small>
                                        </td>
                                        <td class="py-3 text-right text-dark">
                                            {{ $item->type == 'Debit' ? '$' . number_format($item->amount, 2) : '-' }}
                                        </td>
                                        <td class="py-3 text-right text-dark">
                                            {{ $item->type == 'Credit' ? '$' . number_format($item->amount, 2) : '-' }}
                                        </td>
                                        <td class="py-3 text-right pr-4 font-weight-bold">
                                            ${{ number_format(abs($runningBalance), 2) }}
                                            @if($runningBalance < 0) <small>(Cr)</small> @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-5 text-center text-muted">
                                            <i data-feather="info" class="mb-2"></i>
                                            <p class="mb-0">មិនមានប្រតិបត្តិការសម្រាប់គណនេយ្យនេះនៅឡើយទេ</p>
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
@endsection
