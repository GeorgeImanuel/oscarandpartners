@php
    use Carbon\Carbon;
    $now = Carbon::now();
@endphp

<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{ asset('assets/images/dashboard/1.png') }}" alt="" />
        {{-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div> --}}
        <a href="{{ route('user.profile') }}">
            <h6 class="mt-3 f-14 f-w-600">{{ auth()->user()->name }}</h6>
        </a>
        <p class="mb-0 font-roboto">{{ auth()->user()->roles->first()->name ?? 'N/A' }}</p>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ routeActive('dashboard') }}" href="{{ route('dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Kartu Produksi</h6>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ routeActive(['cards.index', 'cards.create', 'cards.thisMonth', 'cards.nextMonth', 'cards.edit']) }}" href="javascript:void(0)">
                            <i data-feather="folder"></i>
                            <span>Kartu Produksi</span>
                        </a>

                        <ul class="nav-submenu menu-content" style="display: {{ routeBlock(['cards.index', 'cards.create', 'cards.thisMonth', 'cards.nextMonth', 'cards.edit']) }}">
                            <li>
                                <a href="{{ route('cards.index') }}" class="{{ routeActive(['cards.index', 'cards.create']) }}"><i data-feather="file-text"></i><span>Semua Kartu</span></a>
                            </li>

                            <li>
                                <a href="{{ route('cards.thisMonth') }}" class="{{ routeActive('cards.thisMonth') }}"><i data-feather="calendar"></i><span>Bulan Ini ( {{ $now->translatedFormat('F Y') }} )</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('cards.nextMonth') }}" class="{{ routeActive('cards.nextMonth') }}">
                                    <i data-feather="calendar"></i>
                                    <span>Bulan Depan ( {{ $now->copy()->addMonth()->translatedFormat('F Y') }} )</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('cards.reportByRelation') }}" class="{{ routeActive('cards.reportByRelation') }}">
                                    <i data-feather="file-text"></i>
                                    <span>Kartu Per Relasi</span>
                                </a>
                            </li>

                            @hasanyrole('owner|admin|finance')
                                <li>
                                    <a href="{{ route('cards.reportByObject') }}" class="{{ routeActive('cards.reportByObject') }}">
                                        <i data-feather="file-text"></i>
                                        <span>Kartu Per Objek</span>
                                    </a>
                                </li>
                            @endhasanyrole

                        </ul>
                    </li>

                    @hasanyrole('owner|admin|finance')
                        <li class="sidebar-main-title">
                            <div>
                                <h6>Payment</h6>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="credit-card"></i>
                                <span>Payment</span>
                            </a>

                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('payments.index') }}" class="{{ routeActive(['payments.index', 'payments.create']) }}"><i data-feather="file-text"></i><span>Semua Payment</span></a>
                                </li>

                                <li>
                                    <a href="{{ route('payments.overdue') }}" class="{{ routeActive('payments.overdue') }}">
                                        <i data-feather="calendar"></i>
                                        <span>Pembayaran Terlambat</span>
                                    </a>
                            </ul>

                        </li>
                        <li class="sidebar-main-title">
                            <div>
                                <h6>Polis</h6>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('policies.index') }}" href="{{ route('policies.index') }}"><i data-feather="airplay"></i><span>Polis</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('insurances.index') }}" href="{{ route('insurances.index') }}"><i data-feather="airplay"></i><span>Asuransi</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('coverages.index') }}" href="{{ route('coverages.index') }}"><i data-feather="airplay"></i><span>Coverages</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('relations.index') }}" href="{{ route('relations.index') }}"><i data-feather="airplay"></i><span>Relations</span></a>
                        </li>

                        <li class="sidebar-main-title">
                            <div>
                                <h6>Objek</h6>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('objects.index') }}" href="{{ route('objects.index') }}"><i data-feather="package"></i><span>Objek</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('object-types.index') }}" href="{{ route('object-types.index') }}"><i data-feather="layers"></i><span>Tipe Objek</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('parts.index') }}" href="{{ route('parts.index') }}"><i data-feather="box"></i><span>Bagian Objek</span></a>
                        </li>
                    @endhasanyrole

                    @hasanyrole('owner|admin')
                        <li class="sidebar-main-title">
                            <div>
                                <h6>Pengguna</h6>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('clients.index') }}" href="{{ route('clients.index') }}"><i data-feather="users"></i><span>Clients</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('relations.index') }}" href="{{ route('relations.index') }}"><i data-feather="airplay"></i><span>Relasi Client</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('users.index') }}" href="{{ route('users.index') }}"><i data-feather="user"></i><span>Staff</span></a>
                        </li>
                    @endhasanyrole

                    @hasanyrole('owner|admin|finance')
                        <li class="sidebar-main-title">
                            <div>
                                <h6>Pengaturan</h6>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('settings.index') }}" href="{{ route('settings.index') }}"><i data-feather="settings"></i><span>Presentase</span></a>
                        </li>
                    @endhasanyrole

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Profile</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ routeActive('user.profile') }}" href="{{ route('user.profile') }}"><i data-feather="user"></i><span>Edit Profile</span></a>
                    </li>

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
