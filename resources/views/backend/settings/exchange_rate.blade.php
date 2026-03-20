@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-1 mb-4">
        <div class="search-bar d-flex align-items-center gap-2 ml-4 mr-4">

            <!-- SEARCH (expand) -->
            <div class="navbar-nav me-auto rounded-3 px-2 flex-grow-1">
                <div class="nav-item d-flex align-items-center">
                    <input type="text" class="form-control custom-search-input shadow-none ps-2"
                        placeholder="ស្វែងរកទិន្នន័យ" aria-label="Search..." />
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="d-flex gap-2">
                <button class="btn  primary-btn mr-2"> ទាយយករបាយការណ៍</button>
                <a class="btn  primary-btn" href="{{ route('settings.exchange_rate.insert') }}">បង្កើតថ្មី</a>
            </div>

        </div>

        <div class="
                    mt-4 ml-4 mr-4">
            <table id="zero_config" class="table table-striped no-wrap">
                <thead>
                    <tr>
                        <th>ល.រ</th>
                        <th>រូបិយប័ណ្ណមូលដ្ឋាន</th>
                        <th>រូបិយប័ណ្ណគោលដៅ</th>
                        <th>អត្រាប្តូរប្រាក់</th>
                        <th>កាលបរិច្ឆេទ</th>
                        <th>ប្រភព</th>
                        <th>បង្កើតដោយ</th>
                        <th>បានបង្កើតនៅ</th>
                        <th>ស្ថានភាព</th>
                        <th class="text-center">ឯកសារ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>USD</td>
                        <td>KHR</td>
                        <td>4,100</td>
                        <td>2026-01-13</td>
                        <td>NBC</td>
                        <td>Admin</td>
                        <td>2026-01-13 09:15</td>
                        <td>សកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>KHR</td>
                        <td>USD</td>
                        <td>0.00024</td>
                        <td>2026-01-13</td>
                        <td>Market</td>
                        <td>Admin</td>
                        <td>2026-01-13 09:20</td>
                        <td>សកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>USD</td>
                        <td>THB</td>
                        <td>35.5</td>
                        <td>2026-01-13</td>
                        <td>Bank</td>
                        <td>Pich</td>
                        <td>2026-01-13 09:25</td>
                        <td>សកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>THB</td>
                        <td>USD</td>
                        <td>0.028</td>
                        <td>2026-01-13</td>
                        <td>Bank</td>
                        <td>Pich</td>
                        <td>2026-01-13 09:30</td>
                        <td>សកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>EUR</td>
                        <td>USD</td>
                        <td>1.09</td>
                        <td>2026-01-13</td>
                        <td>ECB</td>
                        <td>Admin</td>
                        <td>2026-01-13 09:35</td>
                        <td>សកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>USD</td>
                        <td>EUR</td>
                        <td>0.92</td>
                        <td>2026-01-13</td>
                        <td>ECB</td>
                        <td>Admin</td>
                        <td>2026-01-13 09:40</td>
                        <td>សកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>JPY</td>
                        <td>USD</td>
                        <td>0.0068</td>
                        <td>2026-01-13</td>
                        <td>Market</td>
                        <td>Pich</td>
                        <td>2026-01-13 09:45</td>
                        <td>អសកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>USD</td>
                        <td>JPY</td>
                        <td>147</td>
                        <td>2026-01-13</td>
                        <td>Market</td>
                        <td>Pich</td>
                        <td>2026-01-13 09:50</td>
                        <td>សកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>USD</td>
                        <td>VND</td>
                        <td>24,500</td>
                        <td>2026-01-13</td>
                        <td>Bank</td>
                        <td>Admin</td>
                        <td>2026-01-13 09:55</td>
                        <td>សកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>VND</td>
                        <td>USD</td>
                        <td>0.000041</td>
                        <td>2026-01-13</td>
                        <td>Bank</td>
                        <td>Admin</td>
                        <td>2026-01-13 10:00</td>
                        <td>អសកម្ម</td>
                        <td class="text-center">—</td>
                        <td>

                            <div class="dropdown sub-dropdown ">
                                <a class=" btn-link text-muted dropdown-toggl   e" type="button" id="dd1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">

                                    <a class="dropdown-item" href="">កែប្រែ</a>
                                    <a class="dropdown-item" href="">លុប</a>
                                </div>
                            </div>

                        </td>
                    </tr>

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

    <div class="modal fade rounded-2xl" id="customerModal" tabindex="-1">
        <div class="modal-dialog modal-lg rounded-2xl">
            <div class="modal-content rounded-2xl">

                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">បញ្ចូលព័ត៌មានអតិថិជន</h5>

                        <i class="fas fa-minus btn-close hover-row" data-dismiss="modal"></i>

                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label>លេខកូដ</label>
                                <input type="text" name="code" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>ឈ្មោះអតិថិជន</label>
                                <input type="text" name="customer_name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>ភេទ</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">-- ជ្រើសរើស --</option>
                                    <option value="Male">ប្រុស</option>
                                    <option value="Female">ស្រី</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>លេខទូរសព្ទ</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label>អាស័យដ្ឋាន</label>
                                <textarea name="address" class="form-control"></textarea>
                            </div>



                            <div class="col-md-6">
                                <label>ស្ថានភាព</label>
                                <select name="status" class="form-control">
                                    <option value="Active">កំពុងកម្ចី</option>
                                    <option value="Inactive">បានបង់ដាច់</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label>ឯកសារ</label>
                                <input type="file" name="document" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-success">រក្សាទុក</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
