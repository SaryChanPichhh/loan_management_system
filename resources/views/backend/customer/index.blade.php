@extends('backend.layout.master') @section('contents')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h4
                    class="page-title text-truncate text-dark font-weight-medium mb-1 p-1"
                >
                    អតិថិជន
                </h4>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <a
                        href="{{ route('customer.create') }}"
                        class="btn btn-primary btn-rounded"
                    >
                        <i class="fas fa-plus"></i> បន្ថែមអតិថិជនថ្មី
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input
                            type="text"
                            id="searchInput"
                            class="form-control"
                            placeholder="ស្វែងរកអតិថិជន (ឈ្មោះ, លេខកូដ, លេខទូរស័ព្ទ)..."
                        />
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped no-wrap">
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
                                <th class="text-center">រូបភាព</th>
                                <th class="text-center">ឯកសារ</th>
                                <th class="text-right">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody id="customerTableBody">
                            @include('backend.customer.partials.table')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div
    class="modal fade"
    id="imageModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ឯកសារ</h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img
                    id="modalImage"
                    src="{{
                        asset(
                            'backend_assets/assets/images/background/beauty.jpg'
                        )
                    }}"
                    class="img-fluid rounded"
                    alt="Document Image"
                />
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $("#searchInput").on("keyup", function () {
            var value = $(this).val();
            $.ajax({
                url: "{{ route('customer.index') }}",
                type: "GET",
                data: { search: value },
                success: function (data) {
                    $("#customerTableBody").html(data);
                },
            });
        });

        $("#imageModal").on("show.bs.modal", function (event) {
            var button = $(event.relatedTarget);
            var imageSrc = button.data("image");
            var modal = $(this);
            modal.find(".modal-body img").attr("src", imageSrc);
        });
    });
</script>
@endpush @endsection
