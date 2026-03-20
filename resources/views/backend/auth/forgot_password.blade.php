@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h3 class="text-dark font-weight-medium">
                                    ភ្លេចពាក្យសម្ងាត់
                                </h3>
                                <p class="text-muted">
                                    បញ្ចូលអ៊ីមែលដើម្បីទទួលតំណកំណត់ពាក្យសម្ងាត់ថ្មី
                                </p>
                            </div>

                            <form id="forgotForm">
                                <div class="form-group">
                                    <label for="email">
                                        អ៊ីមែល
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        placeholder="បញ្ចូលអ៊ីមែល"
                                        required
                                    />
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i data-feather="mail"></i>
                                        ផ្ញើតំណកំណត់ពាក្យសម្ងាត់
                                    </button>
                                </div>
                            </form>

                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-muted">
                                    ត្រឡប់ទៅទំព័រចូលប្រើប្រាស់
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $(".preloader").fadeOut();

            $("#forgotForm").on("submit", function (e) {
                e.preventDefault();
                alert("តំណកំណត់ពាក្យសម្ងាត់ត្រូវបានផ្ញើទៅអ៊ីមែលរបស់អ្នក!");
            });

            if (feather) feather.replace();
        });
    </script>
@endpush
