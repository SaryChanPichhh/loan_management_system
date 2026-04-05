@foreach($schedules as $key => $s)
<tr class="border-0">
    <td class="text-muted border-top">{{ $key + 1 }}</td>
    <td class="border-top">
        <h5 class="text-dark mb-0 font-16 font-weight-medium">
            {{ optional($s->loan->customer)->name ?? 'មិនស្គាល់អតិថិជន' }}
        </h5>
        <span class="text-muted font-12">{{ optional($s->loan->customer)->code ?? 'No Code' }}</span>
    </td>
    <td class="border-top">
        <a href="{{ route('loans.show', $s->loan_id) }}" class="font-weight-medium text-primary">
            {{ optional($s->loan)->loan_code ?? 'No Loan Code' }}
        </a>
    </td>
    <td class="text-center border-top">
        {{ $s->installment_number ?? 1 }}
    </td>
    <td class="border-top">
        <h5 class="text-dark mb-0 font-16 font-weight-medium">${{ number_format($s->amount_due, 2) }}</h5>
    </td>
    <td class="border-top">
        <h5 class="text-dark mb-0 font-16 font-weight-medium text-success">${{ number_format($s->amount_paid, 2) }}</h5>
    </td>
    <td class="border-top">
        @if($s->status == 'pending')
            <span class="badge badge-warning text-dark px-3 py-1 font-12">រង់ចាំបង់</span>
        @elseif($s->status == 'partial')
            <span class="badge badge-info text-white px-3 py-1 font-12">បង់បានខ្លះ</span>
        @elseif($s->status == 'overdue')
            <span class="badge badge-danger text-white px-3 py-1 font-12">ហួសកាលកំណត់</span>
        @else
            <span class="badge badge-secondary text-white px-3 py-1 font-12 text-capitalize">{{ $s->status }}</span>
        @endif
    </td>
    <td class="text-center border-top">
        <a href="{{ route('repayments.create', $s->loan_id) }}" class="btn btn-sm btn-primary rounded-circle shadow-sm" title="ធ្វើការបង់ប្រាក់">
            <i class="fas fa-plus"></i>
        </a>
    </td>
</tr>
@endforeach
