@foreach($customers as $key => $c)  
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $c->code }}</td>
        <td>{{ $c->name }}</td>
        <td>
            @if($c->gender == 'Male') ប្រុស
            @elseif($c->gender == 'Female') ស្រី
            @else ផ្សេងៗ
            @endif
        </td>
        <td>{{ $c->phone }}</td>
        <td>{{ $c->national_id }}</td>
        <td>{{ $c->email }}</td>
        <td>
            @php $score = $c->calculated_credit_score; @endphp
            @if($score >= 80)
                <span class="badge badge-success px-3 py-1 rounded-pill" title="Excellent Credit">
                    <i class="fas fa-star mr-1"></i> {{ $score }}
                </span>
            @elseif($score >= 50)
                <span class="badge badge-warning text-dark px-3 py-1 rounded-pill" title="Fair Credit">
                    <i class="fas fa-info-circle mr-1"></i> {{ $score }}
                </span>
            @else
                <span class="badge badge-danger px-3 py-1 rounded-pill" title="Poor Credit">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ $score }}
                </span>
            @endif
        </td>

        <td>
            @if($c->status)
                <span class="badge badge-success">កំពុងដំណើរការ</span>
            @else
                <span class="badge badge-danger">ផ្អាក</span>
            @endif
        </td>

        <td class="text-center">
            @if($c->document_path)
                <i class="fas fa-image text-primary"
                   style="cursor:pointer; font-size: 1.2rem;"
                   data-toggle="modal"
                   data-target="#imageModal"
                   data-image="{{ asset('uploads/'.$c->document_path) }}"
                   title="មើលឯកសារ">
                </i>
            @else
                <small class="text-muted">គ្មាន</small>
            @endif
        </td>

        <td>
            <div class="dropdown sub-dropdown">
                <a class="btn-link text-muted dropdown-toggle" data-toggle="dropdown">
                    <i data-feather="more-horizontal"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">

                    <!-- Update -->
                    <a class="dropdown-item" href="{{ route('customer.edit',$c->id) }}">
                        កែប្រែ
                    </a>

                    <!-- Delete -->
                    <form action="{{ route('customer.destroy',$c->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item"
                                onclick="return confirm('តើអ្នកចង់លុបទិន្នន័យនេះមែនទេ?')">
                            លុប
                        </button>
                    </form>
                </div>
            </div>
        </td>
    </tr>
@endforeach