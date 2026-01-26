<div class="page-main-header">
  <div class="main-header-right row m-0">
    <div class="main-header-left">
      <div class="logo-wrapper"><a href="{{ route('index') }}"><img class="img-fluid" src="{{asset('assets/images/logo/logo.png')}}" alt=""></a></div>
      <div class="dark-logo-wrapper"><a href="{{ route('index') }}"><img class="img-fluid" src="{{asset('assets/images/logo/dark-logo.png')}}" alt=""></a></div>
      <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle">    </i></div>
    </div>
    {{-- <div class="left-menu-header col">
      <ul>
        <li>
          <form class="form-inline search-form">
            <div class="search-bg"><i class="fa fa-search"></i>
              <input class="form-control-plaintext" placeholder="Search here.....">
            </div>
          </form>
          <span class="d-sm-none mobile-search search-bg"><i class="fa fa-search"></i></span>
        </li>
      </ul>
    </div> --}}
    <div class="nav-right col pull-right right-menu p-0">
      <ul class="nav-menus">
        {{-- <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
        <li class="onhover-dropdown">
          <div class="bookmark-box"><i data-feather="star"></i></div>
          <div class="bookmark-dropdown onhover-show-div">
            <div class="form-group mb-0">
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                <input class="form-control" type="text" placeholder="Search for bookmark...">
              </div>
            </div>
            <ul>
              <li class="add-to-bookmark"><i class="bookmark-icon" data-feather="inbox"></i>Email<span class="pull-right"><i data-feather="star"></i></span></li>
              <li class="add-to-bookmark"><i class="bookmark-icon" data-feather="message-square"></i>Chat<span class="pull-right"><i data-feather="star"></i></span></li>
              <li class="add-to-bookmark"><i class="bookmark-icon" data-feather="command"></i>Feather Icon<span class="pull-right"><i data-feather="star"></i></span></li>
              <li class="add-to-bookmark"><i class="bookmark-icon" data-feather="airplay"></i>Widgets<span class="pull-right"><i data-feather="star">   </i></span></li>
            </ul>
          </div>
        </li> --}}
        <li class="onhover-dropdown">
          <div class="notification-box">
            <i data-feather="bell"></i>
            {{-- Tampilkan dot merah dan jumlah kalau ada data --}}
            @if(isset($overdueNotifications) && $overdueNotifications->count() > 0)
                <span class="dot-animated"></span>
                <span class="badge badge-pill badge-danger">{{ $overdueNotifications->count() }}</span>
            @endif
          </div>

          <ul class="notification-dropdown onhover-show-div">
            <li>
              <p class="f-w-700 mb-0">
                  You have {{ isset($overdueNotifications) ? $overdueNotifications->count() : 0 }} Overdue Payments
              </p>
            </li>

            @if(isset($overdueNotifications) && $overdueNotifications->count() > 0)
                @foreach($overdueNotifications->take(10) as $payment)
                    <li class="noti-danger">
                        <a href="{{ route('payments.show', $payment->id) }}" style="display: block; text-decoration: none; color: inherit;">
                            <div class="media">
                                <span class="notification-bg bg-light-danger"><i data-feather="alert-circle"></i></span>
                                <div class="media-body">
                                    {{-- Baris 1: Nama Nasabah (Tertanggung) --}}
                                    {{-- Mengambil nama client dari relasi card --}}
                                    <h6 class="mt-0 mb-1 f-12 font-weight-bold">
                                        {{ $payment->card->client->id_name}}
                                    </h6>
                                    
                                    {{-- Baris 2: Nomor Polis & Info Tagihan --}}
                                    <p class="mb-1 f-10 text-muted">
                                        Polis: {{ $payment->card->policy->policy_number ?? '-' }} <br>
                                        Tagihan #{{ $payment->id }}
                                    </p>
                                    
                                    {{-- Baris 3: Status Telat --}}
                                    <span class="f-12 text-danger font-weight-bold">
                                        <i class="fa fa-clock-o me-1"></i>
                                        Telat {{ intval(\Carbon\Carbon::parse($payment->deadline_at)->diffInDays(now())) }} Hari
                                    </span>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach

                <li class="text-center p-2"> 
                    <a class="btn btn-link btn-sm f-w-700" href="{{ route('payments.overdue') }}">Lihat Semua Tunggakan</a>
                </li>
            @else
                <li>
                    <div class="media">
                        <div class="media-body text-center p-3">
                            <p class="mb-0">Tidak ada tunggakan!</p>
                        </div>
                    </div>
                </li>
            @endif
          </ul>
        </li>
        {{-- <li>
            <div class="mode"><i class="fa fa-moon-o"></i></div>
        </li>
        <li class="onhover-dropdown">
          <i data-feather="message-square"></i>
          <ul class="chat-dropdown onhover-show-div">
            <li>
              <div class="media">
                <img class="img-fluid rounded-circle me-3" src="{{asset('assets/images/user/4.jpg')}}" alt="">
                <div class="media-body">
                  <span>Ain Chavez</span>
                  <p class="f-12 light-font">Lorem Ipsum is simply dummy...</p>
                </div>
                <p class="f-12">32 mins ago</p>
              </div>
            </li>
            <li>
              <div class="media">
                <img class="img-fluid rounded-circle me-3" src="{{asset('assets/images/user/1.jpg')}}" alt="">
                <div class="media-body">
                  <span>Erica Hughes</span>
                  <p class="f-12 light-font">Lorem Ipsum is simply dummy...</p>
                </div>
                <p class="f-12">58 mins ago</p>
              </div>
            </li>
            <li>
              <div class="media">
                <img class="img-fluid rounded-circle me-3" src="{{asset('assets/images/user/2.jpg')}}" alt="">
                <div class="media-body">
                  <span>Kori Thomas</span>
                  <p class="f-12 light-font">Lorem Ipsum is simply dummy...</p>
                </div>
                <p class="f-12">1 hr ago</p>
              </div>
            </li>
            <li class="text-center"> <a class="f-w-700" href="javascript:void(0)">See All     </a></li>
          </ul>
        </li> --}}
        <li class="onhover-dropdown p-0">
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-primary-light" type="submit"><i data-feather="log-out"></i>Log out</button>
          </form>
        </li>
      </ul>
    </div>
    <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
  </div>
</div>
