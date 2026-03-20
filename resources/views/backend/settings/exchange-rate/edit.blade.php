@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-1 mb-4">
        
        <div class="d-flex align-items-center justify-content-between ml-4 mr-4 mb-4">
            <h4 class="mb-0 font-weight-bold text-dark">កែប្រែអត្រាប្តូរប្រាក់</h4>
            <a href="{{ route('settings.exchange_rate') }}" class="btn btn-light shadow-sm rounded-pill px-4 text-muted">
                <i data-feather="arrow-left" class="mr-1" style="width: 16px; height: 16px;"></i> ត្រឡប់ក្រោយ
            </a>
        </div>

        <div class="ml-4 mr-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body p-5">
                    
                    @if($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 rounded-lg pb-1 mb-4">
                            <ul class="mb-0 list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li class="mb-1"><i data-feather="alert-circle" class="mr-2 text-danger" style="width: 16px; height: 16px;"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('settings.exchange_rate.update', $exchange_rate->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-medium text-dark">រូបិយប័ណ្ណមូលដ្ឋាន <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i data-feather="dollar-sign" style="width: 18px; height: 18px;"></i></span>
                                    </div>
                                    <select name="base_currency" class="form-control border-left-0 shadow-none ps-0" required style="cursor:pointer">
                                        @foreach(['USD','KHR','THB','JPY','EUR','GBP','VND','AUD','CAD','SGD','CNY'] as $currency)
                                            <option value="{{ $currency }}" {{ old('base_currency', $exchange_rate->base_currency) == $currency ? 'selected' : '' }}>{{ $currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-medium text-dark">រូបិយប័ណ្ណគោលដៅ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i data-feather="credit-card" style="width: 18px; height: 18px;"></i></span>
                                    </div>
                                    <select name="target_currency" class="form-control border-left-0 shadow-none ps-0" required style="cursor:pointer">
                                        @foreach(['USD','KHR','THB','JPY','EUR','GBP','VND','AUD','CAD','SGD','CNY'] as $currency)
                                            <option value="{{ $currency }}" {{ old('target_currency', $exchange_rate->target_currency) == $currency ? 'selected' : '' }}>{{ $currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-medium text-dark">អត្រាប្តូរប្រាក់ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i data-feather="percent" style="width: 18px; height: 18px;"></i></span>
                                    </div>
                                    <input type="number" step="0.0001" name="rate" class="form-control border-left-0 shadow-none ps-0" value="{{ old('rate', $exchange_rate->rate) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-medium text-dark">កាលបរិច្ឆេទ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i data-feather="calendar" style="width: 18px; height: 18px;"></i></span>
                                    </div>
                                    <input type="date" name="exchange_date" class="form-control border-left-0 shadow-none ps-0" value="{{ old('exchange_date', $exchange_rate->exchange_date) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-medium text-dark">ប្រភព</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i data-feather="bookmark" style="width: 18px; height: 18px;"></i></span>
                                    </div>
                                    <input type="text" name="source" class="form-control border-left-0 shadow-none ps-0" value="{{ old('source', $exchange_rate->source) }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-medium text-dark">បង្កើតដោយ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i data-feather="user" style="width: 18px; height: 18px;"></i></span>
                                    </div>
                                    <input type="text" name="created_by" class="form-control border-left-0 shadow-none ps-0" value="{{ old('created_by', $exchange_rate->created_by) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-medium text-dark">ស្ថានភាព <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required style="cursor:pointer">
                                    <option value="1" {{ old('status', $exchange_rate->status) == '1' ? 'selected' : '' }}>សកម្មភាព</option>
                                    <option value="0" {{ old('status', $exchange_rate->status) == '0' ? 'selected' : '' }}>អសកម្ម</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label font-weight-medium text-dark">រូបថតឯកសារ / ឯកសារយោង</label>
                                <div class="d-flex align-items-center bg-light p-3 rounded shadow-sm border">
                                    <div class="image-preview-wrapper mr-4">
                                        <img id="documentPreview" src="{{ $exchange_rate->document ? Storage::url($exchange_rate->document) : asset('backend_assets/assets/images/no_image.jpg') }}" alt="Preview" class="img-thumbnail shadow-sm bg-white" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <label class="btn btn-outline-primary rounded-pill px-4 mb-2 shadow-sm" style="cursor: pointer;">
                                            <i data-feather="upload-cloud" class="mr-1" style="width: 16px; height: 16px;"></i> ផ្លាស់ប្តូរឯកសារ
                                            <input type="file" name="document" id="documentInput" class="d-none" accept=".jpg,.jpeg,.png">
                                        </label>
                                        <small class="text-muted d-block"><i data-feather="info" style="width: 14px; height: 14px;"></i> អនុញ្ញាត: JPG, JPEG, PNG (មិនលើស២MB)</small>
                                        <small class="text-muted mt-1 d-block">(ទុកចោលបើមិនចង់ផ្លាស់ប្តូរឯកសារថ្មី)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top d-flex gap-3">
                                <button type="submit" class="btn btn-success primary-btn px-5 rounded-pill shadow-sm">
                                    <i data-feather="check" class="mr-1 text-white border-0" style="width: 18px; height: 18px;"></i> រក្សាទុកការកែប្រែ
                                </button>
                                <a href="{{ route('settings.exchange_rate') }}" class="btn btn-light px-5 rounded-pill shadow-sm ml-2">បោះបង់</a>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('documentInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('documentPreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ $exchange_rate->document ? Storage::url($exchange_rate->document) : asset('backend_assets/assets/images/no_image.jpg') }}";
        }
    });
</script>
@endpush
