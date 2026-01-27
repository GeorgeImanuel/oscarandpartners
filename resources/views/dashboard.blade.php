@extends('layouts.master')

@section('title', 'Dashboard')

@push('css')
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/animate.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/chartist.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/prism.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vector-map.css')}}">
@endpush

@section('content')
  @yield('breadcrumb-list')
  
  <div class="container-fluid dashboard-default-sec">
    
    {{-- BARIS 1: WELCOME & WIDGETS --}}
    <div class="row mb-4">
      
      {{-- 1. WELCOME MESSAGE (FIXED BACKGROUND & SIZE) --}}
      <div class="col-xl-6 col-lg-12 box-col-12">
         {{-- Kita pakai Inline Style (style="") biar warnanya PASTI keluar dan tidak tertimpa --}}
         <div class="card h-100" style="background: linear-gradient(145deg, #24695c 0%, #1b4c43 100%) !important; color: #fff !important; overflow: hidden; border: none; min-height: 200px;">
             
             {{-- Hiasan Background (Lingkaran Transparan) --}}
             <div style="position: absolute; top: -20px; right: -20px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
             <div style="position: absolute; bottom: -40px; left: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

             <div class="card-body d-flex align-items-center">
                 <div class="w-100 text-center">
                    <h3 class="font-light text-white mb-2">Welcome Back, {{ explode(' ', $user->name)[0] }}!</h3>
                    <p class="text-white mb-4" style="opacity: 0.9;">Dashboard monitoring performa bisnis & tagihan Oscar & Partners.</p>
                    <button class="btn btn-light btn-sm text-dark shadow-sm" onclick="window.location.reload()">
                        <i data-feather="refresh-cw" style="width: 12px; height: 12px;" class="me-1"></i> Refresh Data
                    </button>
                 </div>
             </div>
         </div>
      </div>

      {{-- 2. STATISTIK KECIL (Polis & Nasabah) --}}
      <div class="col-xl-6 col-lg-12 box-col-12">
          <div class="row">
              {{-- Widget Polis --}}
              <div class="col-md-6 col-sm-6">
                <div class="card income-card card-primary h-100">
                  <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                    <div class="round-box mb-3">
                      <i data-feather="file-text" style="color: white"></i>
                    </div>
                    <h5>{{ $activePolicies }}</h5>
                    <p class="text-muted">Polis Aktif</p>
                    <a class="btn-arrow arrow-primary mt-2" href="{{ route('policies.index') }}">
                        View Details
                    </a>
                  </div>
                </div>
              </div>

              {{-- Widget Nasabah --}}
              <div class="col-md-6 col-sm-6">
                <div class="card income-card card-secondary h-100">
                  <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                    <div class="round-box mb-3">
                      <i data-feather="users" style="color: white"></i>
                    </div>
                    <h5>{{ $totalClients }}</h5>
                    <p class="text-muted">Total Nasabah</p>
                    <a class="btn-arrow arrow-secondary mt-2" href="{{ route('clients.index') }}">
                        View Details
                    </a>
                  </div>
                </div>
              </div>
          </div>
      </div>
    </div>

    {{-- BARIS 2: GRAFIK AREA --}}  
    {{-- 1. GRAFIK PREMI --}}
    <div class="col-xl box-col dashboard-sec">
      <div class="card trasaction-sec h-100">
          <div class="card-header pb-0">
            <div class="header-top d-sm-flex align-items-center justify-content-between">
              <h5>Trend Premi</h5>
              <div class="center-content text-end">
                <p class="mb-0 text-muted">Total Produksi</p>
                {{-- Total Premi --}}
                <h4 class="font-primary">Rp {{ number_format($totalBalance ?? 0, 0, ',', '.') }}</h4>
              </div>
            </div>
          </div>
          <div class="card-body pb-4 pt-3 px-3">
            <div style="overflow-x: auto; width: 100%; padding-bottom: 15px;">
              <div id="chart-premi" style="min-height: 300px; min-width: 1000px;"></div>
            </div>
          </div>
      </div>
    </div>

    {{-- 2. GRAFIK KOMISI --}}
    <div class="col-xl box-col dashboard-sec">
      <div class="card trasaction-sec h-100">
          <div class="card-header pb-0">
            <div class="header-top d-sm-flex align-items-center justify-content-between">
              <h5>Trend Komisi</h5>
              <div class="center-content text-end">
                <p class="mb-0 text-muted">Total Komisi</p>
                {{-- Kalau ada variabel total komisi dari controller, pasang disini. Kalo ga ada, hitung manual array sum --}}
                <h4 style="color: #158df7;">Rp {{ number_format(array_sum($komisiData) ?? 0, 0, ',', '.') }}</h4>
              </div>
            </div>
          </div>
          <div class="card-body pb-4 pt-3 px-3">
            <div style="overflow-x: auto; width: 100%; padding-bottom: 15px;">
              <div id="chart-komisi" style="min-height: 300px; min-width: 1000px;"></div>
            </div>
          </div>
      </div>
    </div>

      
    <div class="row mt-4">
      <div class="col-xl-12 recent-order-sec">
        <div class="card border-0 shadow-sm">
          
          <div class="card-header pb-3 bg-white d-flex justify-content-between align-items-center px-4 pt-4">
              <h5 class="mb-0 text-dark">Tagihan Overdue (Belum Terbayar)</h5>
              
              <a href="{{ route('payments.overdue') }}" class="btn btn-sm px-3" style="background-color: #e9ecef; color: #495057; border: none;">
                  Lihat Semua
              </a>
          </div>

          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="text-secondary fw-normal border-bottom-0 py-3 ps-3">Nasabah</th>
                    <th class="text-secondary fw-normal border-bottom-0 py-3">No Polis</th>
                    <th class="text-secondary fw-normal border-bottom-0 py-3">Jatuh Tempo</th>
                    <th class="text-secondary fw-normal border-bottom-0 py-3">Nominal</th>
                    <th class="text-secondary fw-normal border-bottom-0 py-3">Status</th>
                    <th class="text-secondary fw-normal border-bottom-0 py-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($overduePayments as $payment)
                  <tr>
                    <td class="ps-3 py-3">
                      <div class="d-flex flex-column">
                          <span class="font-weight-bold text-dark" style="color: #2b2b2b !important;">
                              {{ $payment->card->client->id_name }}
                          </span>
                          <span class="text-muted small">Card ID: {{ $payment->card_id }}</span>
                      </div>
                    </td>
                    
                    <td class="py-3 text-dark font-weight-bold">{{ $payment->card->policy->policy_number ?? '-' }}</td>
                    
                    <td class="py-3">
                      <div class="d-flex flex-column justify-content-center">
                          <span class="text-danger fw-bold">
                              {{ $payment->deadline_at ? \Carbon\Carbon::parse($payment->deadline_at)->format('d M Y') : '-' }}
                          </span>
                          <span class="text-muted small">
                              Telat {{ intval(\Carbon\Carbon::parse($payment->deadline_at)->diffInDays(now())) }} hari
                          </span>
                      </div>
                    </td>
                    
                    <td class="py-3">
                       <span class="font-weight-bold text-dark">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </td>
                    
                    <td class="py-3">
                       <span class="text-danger fw-bold text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                           OVERDUE
                       </span>
                    </td>
                    
                    <td class="py-3 text-center">
                       <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-outline-primary btn-xs px-3 rounded-pill" style="font-size: 11px;">Detail</a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="6" class="text-center p-5 text-muted bg-light">
                          <div class="mb-2"><i data-feather="check-circle" style="width: 40px; height: 40px; color: #6c757d;"></i></div>
                          <h6 class="text-dark">Tidak ada tagihan overdue!</h6>
                      </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  @push('scripts')
    <script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
    <script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
    <script src="{{asset('assets/js/custom-card/custom-card.js')}}"></script>

    <script>
        // Fungsi helper buat format Rupiah di Tooltip/Y-Axis
        function formatRupiah(val) {
             return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi helper buat bikin singkatan angka (1Jt, 100K)
        function formatYLabel(value) {
            if (value >= 1000000000) return (value / 1000000000).toFixed(1) + "M";
            if (value >= 1000000) return (value / 1000000).toFixed(1) + "Jt";
            if (value >= 1000) return (value / 1000).toFixed(0) + "K";
            return value;
        }

        // --- KONFIGURASI UMUM CHART ---
        var commonChartOptions = {
            chart: {
                type: 'area', 
                height: 300,
                fontFamily: 'Roboto, sans-serif',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: @json($labels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { show: true, style: { colors: '#888', fontSize: '12px' } },
                tooltip: { enabled: false }
            },
            yaxis: {
                show: true, 
                labels: {
                    style: { colors: '#888' },
                    formatter: function (value) { return formatYLabel(value); }
                }
            },
            grid: {
                show: true,
                borderColor: '#f1f1f1',
                strokeDashArray: 4, 
                padding: { top: 0, right: 20, bottom: 0, left: 10 } 
            },
            fill: {
                type: 'gradient', 
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            legend: { show: false }, // Legend hide aja karena judul card udah jelas
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return formatRupiah(val); } }
            }
        };

        // --- 1. RENDER CHART PREMI (HIJAU) ---
        if(typeof chartPremi !== 'undefined') { chartPremi.destroy(); }
        
        var optionsPremi = {
            ...commonChartOptions,
            series: [{
                name: 'Total Premi',
                data: @json(array_values($incomeData))
            }],
            colors: ['#24695c'], // Warna Hijau (Primary Theme)
        };

        var chartPremi = new ApexCharts(document.querySelector("#chart-premi"), optionsPremi);
        chartPremi.render();


        // --- 2. RENDER CHART KOMISI (BIRU) ---
        if(typeof chartKomisi !== 'undefined') { chartKomisi.destroy(); }

        var optionsKomisi = {
            ...commonChartOptions,
            series: [{
                name: 'Komisi Netto',
                data: @json(array_values($komisiData))
            }],
            colors: ['#158df7'], // Warna Biru Cerah (Biar beda)
        };

        var chartKomisi = new ApexCharts(document.querySelector("#chart-komisi"), optionsKomisi);
        chartKomisi.render();

    </script>
  @endpush
@endsection