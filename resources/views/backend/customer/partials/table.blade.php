@foreach($customers as $key => $c)  
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $c->code }}</td>
        <td>{{ $c->name }}</td>
        <td>{{ $c->gender == 'Male' ? 'ប្រុស' : 'ស្រី' }}</td>
        <td>{{ $c->phone }}</td>
        <td>{{ $c->address }}</td>
        <td>{{ $c->type }}</td>

        <td>
            @if($c->status)
                <span class="text-success">កំពុងដំណើរការ</span>
            @else
                <span class="text-danger">ផ្អាក</span>
            @endif
        </td>

        <td class="text-center">
            @if($c->document)
                <i class="fas fa-download text-primary"
                   style="cursor:pointer;"
                   data-toggle="modal"
                   data-target="#imageModal"
                   data-image="{{ asset('uploads/'.$c->document) }}">
                </i>
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