@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-1 mb-4">
        <div class="d-flex flex-column ml-4 mr-4">
            <h4 class="mb-3 font-weight-bold text-dark">អត្រាប្តូរប្រាក់</h4>
        </div>

        <div class="search-bar d-flex align-items-center gap-2 ml-4 mr-4 mb-3">
            <!-- SEARCH -->
            <div class="navbar-nav me-auto rounded-3 px-2 flex-grow-1">
                <div class="nav-item d-flex align-items-center">
                    <input type="text" class="form-control custom-search-input shadow-none ps-2"
                        placeholder="ស្វែងរកទិន្នន័យ..." aria-label="Search..." />
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary mr-2 shadow-sm rounded-pill px-4"> 
                    <i data-feather="download" class="mr-1" style="width: 16px; height: 16px;"></i> ទាញយករបាយការណ៍
                </button>
                <a class="btn primary-btn shadow-sm rounded-pill px-4 text-primary" href="{{ route('settings.exchange_rate.insert') }}">
                    <i data-feather="plus" class="mr-1" style="width: 16px; height: 16px;"></i> បង្កើតថ្មី
                </a>
            </div>
        </div>

        <div class="mt-2 ml-4 mr-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i data-feather="check-circle" class="mr-2" style="width: 18px; height: 18px;"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-hover table-striped mb-0 no-wrap align-middle">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="py-3 px-4 font-weight-medium">ល.រ</th>
                                    <th class="py-3 font-weight-medium">រូបិយប័ណ្ណមូលដ្ឋាន</th>
                                    <th class="py-3 font-weight-medium">រូបិយប័ណ្ណគោលដៅ</th>
                                    <th class="py-3 font-weight-medium">អត្រាប្តូរប្រាក់</th>
                                    <th class="py-3 font-weight-medium">កាលបរិច្ឆេទ</th>
                                    <th class="py-3 font-weight-medium">ប្រភព</th>
                                    <th class="py-3 font-weight-medium">បង្កើតដោយ</th>
                                    <th class="py-3 font-weight-medium">ស្ថានភាព</th>
                                    <th class="py-3 text-center font-weight-medium">ឯកសារ</th>
                                    <th class="py-3 text-center font-weight-medium">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exchange_rates as $key => $rate)
                                <tr class="border-bottom">
                                    <td class="px-4 py-3">{{ $key + 1 }}</td>
                                    <td class="py-3 font-weight-bold">{{ $rate->base_currency }}</td>
                                    <td class="py-3 text-muted">{{ $rate->target_currency }}</td>
                                    <td class="py-3 text-primary font-weight-bold">{{ number_format($rate->rate, 4) }}</td>
                                    <td class="py-3">{{ \Carbon\Carbon::parse($rate->exchange_date)->format('d-m-Y') }}</td>
                                    <td class="py-3">{{ $rate->source ?? 'N/A' }}</td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light text-primary rounded-circle text-center d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px;">
                                                <b>{{ substr($rate->created_by, 0, 1) }}</b>
                                            </div>
                                            {{ $rate->created_by }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if($rate->status)
                                            <span class="badge badge-success rounded-pill px-3 py-1">សកម្ម</span>
                                        @else
                                            <span class="badge badge-danger rounded-pill px-3 py-1">អសកម្ម</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-2">
                                        @if($rate->document)
                                            <img src="{{ Storage::url($rate->document) }}" alt="Doc" class="img-thumbnail shadow-sm p-0 m-0 cursor-pointer" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px;" onclick="window.open(this.src, '_blank')">
                                        @else
                                            <img src="{{ asset('backend_assets/assets/images/no_image.jpg') }}" alt="No Image" class="img-thumbnail shadow-sm p-0 m-0 opacity-50" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px;">
                                        @endif
                                    </td>
                                    <td class="text-center py-3">
                                        <div class="dropdown sub-dropdown">
                                            <a class="btn btn-sm btn-light text-muted dropdown-toggle rounded-circle p-2" type="button" id="dd{{ $rate->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-horizontal" style="width: 16px; height: 16px;"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" aria-labelledby="dd{{ $rate->id }}">
                                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('settings.exchange_rate.edit', $rate->id) }}">
                                                    <i data-feather="edit-2" class="mr-2 text-info" style="width: 16px; height: 16px;"></i> កែប្រែ
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('settings.exchange_rate.delete', $rate->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបទិន្នន័យនេះមែនទេ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center py-2">
                                                        <i data-feather="trash-2" class="mr-2" style="width: 16px; height: 16px;"></i> លុប
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">មិនមានទិន្នន័យអត្រាប្តូរប្រាក់ទេ</td>
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
