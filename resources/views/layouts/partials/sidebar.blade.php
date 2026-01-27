@php
    use Carbon\Carbon;
    $now = Carbon::now();
@endphp

<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary"
            href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle"
            src="{{ asset('assets/images/dashboard/1.png') }}"
            alt="" />
        {{-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div> --}}
        <a href="{{ route('user.profile') }}">
            <h6 class="mt-3 f-14 f-w-600">{{ auth()->user()->name }}</h6>
        </a>
        <p class="mb-0 font-roboto">{{ auth()->user()->roles->first()->name ?? 'N/A' }}</p>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow"
                id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ routeActive('dashboard') }}"
                            href="{{ route('dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Produksi</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ routeActive(['cards.index', 'cards.create', 'cards.thisMonth', 'cards.nextMonth', 'cards.edit']) }}"
                            href="javascript:void(0)">
                            <i data-feather="folder"></i>
                            <span>Kartu Produksi</span>
                        </a>

                        <ul class="nav-submenu menu-content"
                            style="display: {{ routeBlock(['cards.index', 'cards.reportByRelation', 'cards.reportByObject']) }}">
                            <li>
                                <a href="{{ route('cards.index') }}"
                                    class="{{ routeActive(['cards.index', 'cards.create']) }}"><i data-feather="file-text"></i><span>Semua Kartu</span></a>
                            </li>

                            <li>
                                <a href="{{ route('cards.reportByRelation') }}"
                                    class="{{ routeActive('cards.reportByRelation') }}">
                                    <i data-feather="file-text"></i>
                                    <span>Kartu Per Relasi</span>
                                </a>
                            </li>

                            @hasanyrole('owner|admin|finance')
                                <li>
                                    <a href="{{ route('cards.reportByObject') }}"
                                        class="{{ routeActive('cards.reportByObject') }}">
                                        <i data-feather="file-text"></i>
                                        <span>Kartu Per Objek</span>
                                    </a>
                                </li>
                            @endhasanyrole

                        </ul>
                    </li>

                    @hasanyrole('owner|finance')
                        <li class="dropdown">
                            <a class="nav-link menu-title"
                                href="javascript:void(0)">
                                <i data-feather="credit-card"></i>
                                <span>Payment</span>
                            </a>

                            <ul class="nav-submenu menu-content"
                                style="display: {{ routeBlock(['payments.index', 'payments.overdue']) }}">
                                <li>
                                    <a href="{{ route('payments.index') }}"
                                        class="{{ routeActive(['payments.index', 'payments.create']) }}"><i data-feather="file-text"></i><span>Semua Payment</span></a>
                                </li>

                                <li>
                                    <a href="{{ route('payments.overdue') }}"
                                        class="{{ routeActive('payments.overdue') }}">
                                        <i data-feather="calendar"></i>
                                        <span>Pembayaran Terlambat</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="nav-link menu-title {{ routeActive('reports.*') }}"
                                href="javascript:void(0)">
                                <i data-feather="bar-chart-2"></i>
                                <span>Laporan</span>
                            </a>
                            <ul class="nav-submenu menu-content"
                                style="display: {{ routeBlock('reports.*') }}">
                                <li>
                                    <a href="{{ route('reports.evaluation') }}"
                                        class="{{ routeActive('reports.evaluation') }}">
                                        <i data-feather="trending-up"></i>
                                        <span>Evaluasi Produksi</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('claims.index') }}"
                                href="{{ route('claims.index') }}"><i data-feather="alert-circle"></i><span>Claim</span></a>
                        </li>

                        <li class="sidebar-main-title">
                            <div>
                                <h6>Polis</h6>
                            </div>
                        </li>

                        {{-- 1. GROUP DROPDOWN POLIS (Isinya: Index, Bulan Ini, Bulan Depan) --}}
                        <li class="dropdown">
                            <a class="nav-link menu-title {{ routeActive(['policies.index', 'policies.thisMonth', 'policies.nextMonth']) }}"
                                href="javascript:void(0)">
                                <i data-feather="airplay"></i><span>Polis</span>
                            </a>
                            
                            <ul class="nav-submenu menu-content" 
                                style="display: {{ routeBlock(['policies.index', 'policies.thisMonth', 'policies.nextMonth']) }}">
                                
                                {{-- Item 1: Semua Polis (Index) --}}
                                <li>
                                    <a href="{{ route('policies.index') }}" class="{{ routeActive('policies.index') }}">
                                        <i data-feather="list"></i><span>Semua Polis</span>
                                    </a>
                                </li>

                                {{-- Item 2: Bulan Ini --}}
                                <li>
                                    <a href="{{ route('policies.thisMonth') }}" class="{{ routeActive('policies.thisMonth') }}">
                                        <i data-feather="calendar"></i><span> Polis Berakhir Bulan Ini ( {{ $now->translatedFormat('F Y') }} )</span>
                                    </a>
                                </li>

                                {{-- Item 3: Bulan Depan --}}
                                <li>
                                    <a href="{{ route('policies.nextMonth') }}" class="{{ routeActive('policies.nextMonth') }}">
                                        <i data-feather="calendar"></i><span> Polis Berakhir Bulan Depan ( {{ $now->copy()->addMonth()->translatedFormat('F Y') }} )</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- 2. ITEM SENDIRI-SENDIRI (Button Lepasan) --}}
                        {{-- Mata Uang --}}
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('currencies.index') }}"
                                href="{{ route('currencies.index') }}">
                                <i data-feather="dollar-sign"></i><span>Mata Uang</span>
                            </a>
                        </li>

                        {{-- Asuransi --}}
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('insurances.index') }}"
                                href="{{ route('insurances.index') }}">
                                <i data-feather="shield"></i><span>Asuransi</span>
                            </a>
                        </li>

                        {{-- Coverages --}}
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('coverages.index') }}"
                                href="{{ route('coverages.index') }}">
                                <i data-feather="check-square"></i><span>Coverages</span>
                            </a>
                        </li>

                        <li class="sidebar-main-title">
                            <div>
                                <h6>Objek</h6>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('objects.index') }}"
                                href="{{ route('objects.index') }}"><i data-feather="package"></i><span>Objek</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('object-occupations.index') }}"
                                href="{{ route('object-occupations.index') }}"><i data-feather="layers"></i><span>Objek Occupation</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('interests.index') }}"
                                href="{{ route('interests.index') }}"><i data-feather="box"></i><span>Interest</span></a>
                        </li>
                    @endhasanyrole

                    @hasanyrole('owner|admin')
                        <li class="sidebar-main-title">
                            <div>
                                <h6>Pengguna</h6>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title link-nav {{ routeActive('clients.index') }}"
                                href="{{ route('clients.index') }}"><i data-feather="users"></i><span>Clients</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('relations.index') }}"
                                href="{{ route('relations.index') }}"><i data-feather="airplay"></i><span>Relasi (Grup) Client</span></a>
                            <a class="nav-link menu-title link-nav {{ routeActive('users.index') }}"
                                href="{{ route('users.index') }}"><i data-feather="user"></i><span>Staff</span></a>
                        </li>
                    @endhasanyrole

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Profile</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ routeActive('user.profile') }}"
                            href="{{ route('user.profile') }}"><i data-feather="user"></i><span><i class="icon-pencil me-2"></i>Edit Profile</span></a>
                    </li>

                </ul>
            </div>
            <div class="right-arrow"
                id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
