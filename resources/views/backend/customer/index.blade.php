@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="table-responsive ">
            <table id="zero_config" class="table table-striped no-wrap">
                <thead>
                    <tr>
                        <th>ល.រ</th>
                        <th>លេខកូដ</th>
                        <th>ឈ្មោះអតិថិជន</th>
                        <th>ភេទ</th>
                        <th>លេខទូរសព្ទ</th>
                        <th>អាស័យដ្ខាន</th>
                        <th>ប្រភេទ</th>
                        <th>ស្ថានភាព</th>
                        <th class="text-center">ឯកសារ</th>
                        <th></th>
                    </tr>
                </thead>
                    <tbody>
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
                    <!-- Insert -->
                    <a class="dropdown-item" href="{{ route('customer.create') }}">
                        Insert
                    </a>

                    <!-- Update -->
                    <a class="dropdown-item" href="{{ route('customer.edit',$c->id) }}">
                        Update
                    </a>

                    <!-- Delete -->
                    <form action="{{ route('customer.destroy',$c->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item"
                                onclick="return confirm('តើអ្នកចង់លុបទិន្នន័យនេះមែនទេ?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
                
            </table>
        </div>

    </div>



    <!-- Image Preview Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ឯកសារ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center ">
                    <img id="modalImage" src="{{ asset('backend_assets/assets/images/background/beauty.jpg') }}"
                        class="img-fluid rounded" alt="Document Image">
                </div>
            </div>
        </div>
    </div>
@endsection
