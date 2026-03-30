<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a
                        class="sidebar-link sidebar-link"
                        href="{{ route('dashboard.index') }}"
                        aria-expanded="false"
                    >
                        <i data-feather="grid" class="feather-icon"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a
                        class="sidebar-link sidebar-link"
                        href="{{ route('report.index') }}"
                        aria-expanded="false"
                    >
                        <i data-feather="file-text" class="feather-icon"></i>
                        <span class="hide-menu">របាយការណ៍</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a
                        class="sidebar-link sidebar-link"
                        href="{{ route('notification.index') }}"
                        aria-expanded="false"
                    >
                        <i data-feather="bell" class="feather-icon"></i>
                        <span class="hide-menu">ការជូនដំណឹង</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a
                        class="sidebar-link sidebar-link"
                        href="{{ route('customer.index') }}"
                        aria-expanded="false"
                    ><i class="icon-people"></i
                        ><span class="hide-menu">អតិថិជន</span></a
                    >
                </li>
                <li class="sidebar-item">
                    <a
                        class="sidebar-link sidebar-link"
                        href="{{ route('guarantors.index') }}"
                        aria-expanded="false"
                    ><i data-feather="user-check" class="feather-icon"></i
                        ><span class="hide-menu">អ្នកធានា</span></a
                    >
                </li>
                <li class="nav-small-cap">
                    <span class="hide-menu">មុខងារគ្រប់គ្រង</span>
                </li>
                {{--                Loans management --}}
                <li class="sidebar-item">
                    <a
                        class="sidebar-link has-arrow"
                        href="javascript:void(0)"
                        aria-expanded="false"
                    >
                        <i data-feather="credit-card" class="feather-icon"></i>
                        <span class="hide-menu">គ្រប់គ្រង កម្ចី</span>
                    </a>
                    <ul
                        aria-expanded="false"
                        class="collapse first-level base-level-line"
                    >
                        <li class="sidebar-item">
                            <a
                                class="sidebar-link sidebar-link"
                                href="{{ route('loan_applications.index') }}"
                                aria-expanded="false"
                            >
                                <i data-feather="file-text" class="feather-icon">
                                </i>
                                <span class="hide-menu">សំណើសុំកម្ចី</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a
                                class="sidebar-link sidebar-link"
                                href="{{ route('loans.index') }}"
                                aria-expanded="false"
                            >
                                <i data-feather="list" class="feather-icon">
                                </i>
                                <span class="hide-menu">ទូទៅ</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a
                                class="sidebar-link sidebar-link"
                                href="{{ route('loans.create') }}"
                                aria-expanded="false"
                            >
                                <i
                                    data-feather="plus-circle"
                                    class="feather-icon"
                                >
                                </i>
                                <span class="hide-menu">បង្កើតកម្ចីថ្មី</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a
                                class="sidebar-link"
                                href="{{ route('loans.defaulted') }}"
                                aria-expanded="false"
                            >
                                <i
                                    data-feather="alert-triangle"
                                    class="feather-icon"
                                ></i>
                                <span class="hide-menu">កម្ចីមិនទាន់សង</span>
                            </a>
                        </li>

                    </ul>
                </li>
                
                <li class="sidebar-item">
                    <a
                        class="sidebar-link sidebar-link"
                        href="{{ route('loan_products.index') }}"
                        aria-expanded="false"
                    >
                        <i data-feather="package" class="feather-icon"></i>
                        <span class="hide-menu">Products កម្ចី</span>
                    </a>
                </li>
                {{-- Repayment --}}
                <li class="sidebar-item">
                    <a
                        class="sidebar-link has-arrow"
                        href="javascript:void(0)"
                        aria-expanded="false"
                    >
                        <i data-feather="dollar-sign" class="feather-icon"></i>
                        <span class="hide-menu">ការសងប្រាក់</span>
                    </a>
                    <ul
                        aria-expanded="false"
                        class="collapse first-level base-level-line"
                    >
                        <li class="sidebar-item">
                            <a
                                class="sidebar-link sidebar-link"
                                href="{{ route('repayments.index') }}"
                                aria-expanded="false"
                            >
                                <i data-feather="list" class="feather-icon">
                                </i>
                                <span class="hide-menu">ការសងប្រាក់ទាំងអស់</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a
                                class="sidebar-link sidebar-link"
                                href="{{ route('repayments.create', ['loan_id' => 1]) }}"
                                aria-expanded="false"
                            >
                                <i
                                    data-feather="plus-circle"
                                    class="feather-icon"
                                >
                                </i>
                                <span class="hide-menu">កត់ត្រាការបង់ប្រាក់</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Activity log--}}
                <li class="sidebar-item">
                    <a
                        class="sidebar-link sidebar-link"
                        href="{{ route('activity_log.index') }}"
                        aria-expanded="false"
                    >
                        <i data-feather="file-text" class="feather-icon"></i>
                        <span class="hide-menu">Activity Log</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a
                        class="sidebar-link sidebar-link"
                        href="{{ route('role.index') }}"
                        aria-expanded="false"
                    >
                        <i data-feather="file-text" class="feather-icon"></i>
                        <span class="hide-menu">Role</span>
                    </a>
                </li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
                                             aria-expanded="false"><i data-feather="file-text" class="feather-icon"></i><span
                            class="hide-menu">ការកំណត់ </span></a>
                    <ul aria-expanded="false" class="collapse  first-level base-level-line">
                        <li class="sidebar-item"><a href="{{ route('settings.company_profile') }}"
                                                    class="sidebar-link"><span class="hide-menu"> អំពីគណនី
                                 </span></a>
                        </li>
                        <li class="sidebar-item"><a href="{{ route('settings.exchange_rate') }}"
                                                    class="sidebar-link"><span class="hide-menu"> អត្រាការប្រាក់
                                 </span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
