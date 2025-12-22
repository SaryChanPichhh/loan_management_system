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
                    <tr>
                        <td>1</td>
                        <td>01-CUS-00001</td>
                        <td>នី សំរ៉ាងមាលតី</td>
                        <td>ស្រី</td>
                        <td>03125555</td>
                        <td>ខេត្តកណ្តាល</td>
                        <td>អតិថិជនទូទៅ</td>
                        <td>
                            <span class="text-success">កំពុងកម្ចី</span>
                        </td>

                        <td class="text-center">
                            <i class="fas fa-download text-primary" style="cursor:pointer;" data-toggle="modal"
                                data-target="#imageModal"
                                data-image="{{ asset('backend_assets/assets/images/favicon.png') }}">
                            </i>
                        </td>

                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggle" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                    <a class="dropdown-item" href="#">Insert</a>
                                    <a class="dropdown-item" href="#">Update</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>

                        </td>
                    </tr>

                </tbody>
                <tfoot>
                    <tr>
                        <th>ល.រ</th>
                        <th>លេខកូដ</th>
                        <th>ឈ្មោះអតិថិជន</th>
                        <th>ភេទ</th>
                        <th>លេខទូរសព្ទ</th>
                        <th>អាស័យដ្ខាន</th>
                        <th>ប្រភេទ</th>
                        <th>ស្ថានភាព</th>
                        <th>ឯកសារ</th>
                        <th></th>
                    </tr>
                </tfoot>
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
