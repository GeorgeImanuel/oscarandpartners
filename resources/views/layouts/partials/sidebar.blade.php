<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{asset('assets/images/dashboard/1.png')}}" alt="" />
        {{-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div> --}}
        <a href="user-profile"> <h6 class="mt-3 f-14 f-w-600">{{ auth()->user()->name }}</h6></a>
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
                    <li class="sidebar-main-title">
                        <div>
                            <h6>General</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/dashboard') }}" href="javascript:void(0)"><i data-feather="home"></i><span>Dashboard</span></a>                  
                        <a class="nav-link menu-title link-{{ prefixActive('/relations') }}" href="{{ route('relations.index') }}"><i data-feather="airplay"></i><span>Relations</span></a>
                        
                        <a class="nav-link menu-title {{ prefixActive('/kartuproduksi') }}" href="javascript:void(0)"><i data-feather="file-text"></i><span>Kartu Produksi</span></a>
                        <ul class="nav-submenu menu-content"  style="display: {{ prefixBlock('/kartuproduksi') }};">
                            <li><a href="{{ route('cards.view') }}" class="{{prefixActive('cards.view')}}">View Tabel</a></li>
                            <li><a href="{{ route('cards.create') }}" class="{{prefixActive('cards.create')}}">Create</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ prefixActive('/insurances') }}" href="{{ route('insurances.index') }}"><i data-feather="airplay"></i><span>Insurances</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ prefixActive('/policies') }}" href="{{ route('policies.index') }}"><i data-feather="airplay"></i><span>Policies</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ prefixActive('/clients') }}" href="{{ route('clients.index') }}"><i data-feather="users"></i><span>Clients</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ prefixActive('/users') }}" href="{{ route('users.index') }}"><i data-feather="user"></i><span>Users</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ prefixActive('/settings') }}" href="{{ route('settings.index') }}"><i data-feather="user"></i><span>Setting</span></a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
