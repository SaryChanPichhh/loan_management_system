<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md">
        <div class="navbar-header p-0 m-0" data-logobg="skin6">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a
                class="nav-toggler waves-effect waves-light d-block d-md-none"
                href="javascript:void(0)"
                ><i class="ti-menu ti-close"></i
            ></a>
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-brand">
                <!-- Logo icon -->
                <a href="{{ route('dashboard.index') }}">
                    <b class="">
                        <!-- Dark Logo icon -->
                        <img
                            style="width: 230px; padding: 0"
                            src="{{
                                asset(
                                    'backend_assets/assets/images/company_logo.png'
                                )
                            }}"
                            alt="homepage"
                            class="dark-logo"
                        />
                        <!-- Light Logo icon -->
                    </b>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a
                class="topbartoggler d-block d-md-none waves-effect waves-light"
                href="javascript:void(0)"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
                ><i class="ti-more"></i
            ></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                <!-- Notification -->
                @php
                    $notifications = \App\Models\Notification::latest()->take(5)->get();
                    $unreadCount = \App\Models\Notification::where('is_read', false)->count();
                @endphp
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle pl-md-3 position-relative"
                        href="javascript:void(0)"
                        id="bell"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span><i data-feather="bell" class="svg-icon"></i></span>
                        @if($unreadCount > 0)
                            <span class="badge badge-primary notify-no rounded-circle">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown" style="width: 300px;">
                        <ul class="list-style-none">
                            <li>
                                <div class="message-center notifications position-relative">
                                    @forelse($notifications as $notif)
                                    <!-- Message -->
                                    <a href="{{ route('notification.index') }}" class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                        <div class="btn {{ $notif->type == 'warning' ? 'btn-warning' : 'btn-primary' }} rounded-circle btn-circle">
                                            <i data-feather="{{ $notif->type == 'warning' ? 'alert-triangle' : 'bell' }}" class="text-white" style="width: 14px;"></i>
                                        </div>
                                        <div class="w-75 d-inline-block v-middle pl-2">
                                            <h6 class="message-title mb-0 mt-1" style="font-size: 0.85rem;">{{ $notif->title }}</h6>
                                            <span class="font-12 d-block text-muted text-truncate">{{ $notif->message }}</span>
                                            <span class="font-10 text-nowrap d-block text-muted">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                    @empty
                                    <div class="p-3 text-center text-muted">
                                        <i data-feather="smile" class="mb-2"></i>
                                        <p class="small mb-0">មិនមានការជូនដំណឹងថ្មីទេ (No new notifications)</p>
                                    </div>
                                    @endforelse
                                </div>
                            </li>
                            <li>
                                <a class="nav-link pt-3 text-center text-dark" href="{{ route('notification.index') }}">
                                    <strong>មើលទាំងអស់ (View all)</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- End Notification -->
                <!-- ============================================================== -->
                <!-- create new -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="navbarDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i data-feather="settings" class="svg-icon"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"
                            >Something else here</a
                        >
                    </div>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link" href="javascript:void(0)">
                        <div class="customize-input">
                            <select
                                class="custom-select form-control bg-white custom-radius custom-shadow border-0"
                            >
                                <option selected>EN</option>
                                <option value="1">AB</option>
                                <option value="2">AK</option>
                                <option value="3">BE</option>
                            </select>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-right">
                <!-- ============================================================== -->
                <!-- Search -->
                <!-- ============================================================== -->
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link" href="javascript:void(0)">
                        <form>
                            <div class="customize-input">
                                <input
                                    class="form-control custom-shadow custom-radius border-0 bg-white"
                                    type="search"
                                    placeholder="Search"
                                    aria-label="Search"
                                />
                                <i
                                    class="form-control-icon"
                                    data-feather="search"
                                ></i>
                            </div>
                        </form>
                    </a>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        href="javascript:void(0)"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <img
                            src="{{
                                Auth::user()->image 
                                ? asset('storage/' . Auth::user()->image) 
                                : asset('backend_assets/assets/images/users/profile-pic.jpg')
                            }}"
                            alt="user"
                            class="rounded-circle"
                            width="40"
                            height="40"
                            style="object-fit: cover"
                        />
                        <span class="ml-2 d-none d-lg-inline-block"
                            ><span>សួរស្តី,</span>
                            <span
                                class="text-dark"
                                >{{ Auth::user()->name ?? 'User' }}</span
                            >
                            <i data-feather="chevron-down" class="svg-icon"></i
                        ></span>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-right user-dd animated flipInY"
                    >
                        <a
                            class="dropdown-item"
                            href="{{ route('profile.edit') }}"
                            ><i
                                data-feather="user"
                                class="svg-icon mr-2 ml-1"
                            ></i>
                            My Profile</a
                        >
                        <a class="dropdown-item" href="javascript:void(0)"
                            ><i
                                data-feather="credit-card"
                                class="svg-icon mr-2 ml-1"
                            ></i>
                            My Balance</a
                        >
                        <a class="dropdown-item" href="javascript:void(0)"
                            ><i
                                data-feather="mail"
                                class="svg-icon mr-2 ml-1"
                            ></i>
                            Inbox</a
                        >
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)"
                            ><i
                                data-feather="settings"
                                class="svg-icon mr-2 ml-1"
                            ></i>
                            Account Setting</a
                        >
                        <div class="dropdown-divider"></div>
                        <a
                            class="dropdown-item d-flex align-items-center"
                            href="javascript:void(0)"
                            onclick="confirmLogout()"
                        >
                            <i
                                data-feather="power"
                                class="svg-icon mr-2 ml-1 text-danger"
                                style="width: 18px;"
                            ></i>
                            <span class="font-weight-medium">ចាកចេញ (Logout)</span>
                        </a>
                        <form
                            id="logout-form"
                            action="{{ route('logout') }}"
                            method="POST"
                            class="d-none"
                        >
                            @csrf
                        </form>
                        <div class="dropdown-divider"></div>
                        <div class="pl-4 p-3">
                            <a
                                href="{{ route('profile.edit') }}"
                                class="btn btn-sm btn-info"
                                >View Profile</a
                            >
                        </div>
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>
        </div>
    </nav>
</header>
