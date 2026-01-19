@extends('layouts.master')

@section('title')Detail Pembayaran
@endsection

@push('css')
    <style>
        /* Style untuk Tombol agar mirip DataTables */
        .btn-print-custom {
            background-color: #0d6efd;
            color: white;
            border: 1px solid #0d6efd;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-print-custom:hover { background-color: #0b5ed7; }

        .table-responsive-custom {
            overflow-x: auto; /* Aktifkan scroll samping */
            -webkit-overflow-scrolling: touch;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        /* 2. Update Table Manual biar Lebar di Web */
        .table-manual {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            
            /* INI PENTING: Paksa tabel melebar min 1200px biar ga gepeng */
            min-width: 1200px; 
            
            font-size: 12px; 
            color: #000;
        }

        .table-manual th, .table-manual td {
            border: 1px solid #000 !important;
            padding: 8px 10px; /* Padding web agak lega */
            vertical-align: middle;
            white-space: nowrap; /* Teks jangan turun baris di web */
        }
        .table-manual thead th {
            background-color: #d1d1d1 !important;
            color: #000 !important;
            font-weight: bold;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-total {
            background-color: #e9ecef !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        
        /* Dynamic Installment Form Styles */
        .installment-row {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .installment-row:hover {
            background-color: #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .installment-amount {
            font-weight: bold;
            font-size: 1.05em;
        }
        .form-control-plaintext {
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
            margin-bottom: 0;
            line-height: 1.5;
        }
        
        @media print {
            .no-print { display: none !important; }
            
            /* Matikan scroll saat print */
            .table-responsive-custom {
                overflow: visible !important;
                border: none !important;
            }

            /* Reset tabel biar muat di kertas (Hapus min-width tadi) */
            .table-manual {
                width: 100% !important;
                min-width: 0 !important; /* RESET disini */
                font-size: 10px !important; /* Kecilkan font */
            }

            .table-manual th, .table-manual td {
                padding: 4px !important; /* Rapatkan padding */
                white-space: normal !important; /* Teks boleh turun baris */
            }

            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 10mm; size: landscape; } 
        }
    </style>
@endpush

@section('content')
    @component('components.breadcrumb')
    @slot('breadcrumb_title')
    <h3>Detail Pembayaran</h3>
    @endslot
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pembayaran</a></li>
    <li class="breadcrumb-item active">Detail</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 no-print">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Perhitungan Pembayaran Premi</h5>
                            <div class="d-flex gap-2">
                                <button onclick="printTableOnly()" class="btn btn-primary btn-sm">
                                    <i class="fa fa-print"></i> Print Laporan
                                </button>
                                <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $card = $payment->productionCard;
                            $policy = $card->policy;
                            $client = $card->client;
                        @endphp

                        {{-- Info Header Laporan --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2 text-center">PERHITUNGAN PEMBAYARAN PREMI {{ strtoupper($policy->object->name ?? '') }}</h6>
                            <div style="font-size: 11px; text-align: center;">
                                <strong>NO POLIS :</strong> {{ $policy->policy_number ?? '-' }} / 
                                {{ $policy->started_date ? $policy->started_date->format('d M Y') : '-' }} - 
                                {{ $policy->ended_date ? $policy->ended_date->format('Y') : '-' }}
                            </div>
                        </div>

                        @if($card->payment_type_id == 1)
                        <div class="row mb-3 no-print">
                            <div class="col-12">
                                {{-- Hapus bg-light, tambah shadow-sm dan card-header biar tegas --}}
                                <div class="card border shadow-sm mb-3">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="card-title mb-0"><i class="fa fa-cogs"></i> Pengaturan Pembayaran</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6 border-end">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label text-muted small text-uppercase fw-bold">Tipe Pembayaran</label>
                                                    <select id="payment-type-select" class="form-select form-select-lg">
                                                        @foreach($paymentTypes as $type)
                                                            <option value="{{ $type->id }}" data-name="{{ strtolower($type->name) }}"
                                                                {{ $card->paymentType && $card->paymentType->id == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {{-- Installment Count Input --}}
                                                <div id="installment-section" style="display: none;">
                                                    <label class="form-label text-muted small text-uppercase fw-bold">Jumlah Cicilan</label>
                                                    <div class="input-group">
                                                        <input type="number" id="installment-count" class="form-control form-control-lg" 
                                                            min="1" max="12" value="{{ count($allPayments) > 0 ? count($allPayments) : 1 }}">
                                                        <span class="input-group-text">Kali</span>
                                                    </div>
                                                    <small class="text-muted">Ubah angka untuk generate otomatis, atau gunakan tombol Tambah/Hapus di bawah.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- SECTION 2: DETAIL POLIS --}}
                        <div class="row mb-3 no-print">
                            <div class="col-12">
                                <div class="card border mb-3">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="card-title mb-0"><i class="fa fa-file-text-o"></i> Detail Polis</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tr>
                                                        <td width="30%" class="text-muted">Client</td>
                                                        <td class="fw-bold">: {{ $client->user->name ?? $client->id_name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Object</td>
                                                        <td class="fw-bold">: {{ $policy->object->name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Total Premi</td>
                                                        <td class="fw-bold text-primary">: Rp {{ numberFormat($payment->amount ?? 0) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tr>
                                                        <td width="30%" class="text-muted">Biaya Polis</td>
                                                        <td class="fw-bold">: Rp {{ numberFormat($card->policy_fee ?? 0) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Komisi</td>
                                                        <td class="fw-bold">: {{ number_format(($card->commision_percentages ?? 0) * 100, 2) }}%</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic Installment Form --}}
                        <div id="installment-items-section" class="mb-4 no-print" style="display: none;">
                            <div class="card border">
                                <div class="card-header bg-light border-bottom">
                                    <h6 class="mb-0 text-dark"><i class="fa fa-list-ol"></i> Rincian Cicilan</h6>
                                </div>
                                <div class="card-body">
                                    <div id="installments-container"></div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="alert alert-light border mb-0 py-2">
                                                <i class="fa fa-info-circle"></i> Total Cicilan: <strong id="total-installments-display">0</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="alert alert-warning mb-0 py-2 text-end border-warning">
                                                Total Nilai: <strong id="total-amount-display">Rp 0</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 text-center border-top pt-3">
                                        <button type="button" id="apply-installments-btn" class="btn btn-success px-5">
                                            <i class="fa fa-save"></i> SIMPAN PERUBAHAN
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel View Laporan --}}
                        <div class="table-responsive-custom">
                             {{-- ... (Isi tabel paymentTable BIARKAN SAMA SEPERTI SEBELUMNYA) ... --}}
                             {{-- Copy paste tabel dari kode sebelumnya di sini --}}
                             <table class="table-manual" id="paymentTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width: 10%;"></th>
                                        <th colspan="3" style="border-bottom: none;">Ko. Asuransi</th>
                                        <th rowspan="2" style="width: 10%;">Biaya<br>Polis</th>
                                        <th rowspan="2" style="width: 10%;">Komisi</th>
                                        <th rowspan="2" style="width: 10%;">Pajak</th>
                                        <th rowspan="2" style="width: 12%;">Premi<br>Netto</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="width: 25%;">Share</th>
                                        <th style="width: 15%;">Premi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grandTotalPremi = 0; $grandTotalBiaya = 0; $grandTotalKomisi = 0;
                                        $grandTotalPajak = 0; $grandTotalNetto = 0;
                                    @endphp

                                    @foreach($allPayments as $index => $pmt)
                                        @php
                                            $policyInsurances = $pmt->productionCard->policy->policyInsurances;
                                            $insuranceCount = $policyInsurances->count();
                                            $rowSpanCount = $insuranceCount;
                                            
                                            $subtotalPremi = 0; $subtotalBiaya = 0; $subtotalKomisi = 0;
                                            $subtotalPajak = 0; $subtotalNetto = 0;
                                        @endphp

                                        @foreach($policyInsurances as $loopIndex => $pi)
                                            @php
                                                $share = $pi->percentage;
                                                $premi = $pmt->amount * ($share);
                                                $commPercent = $card->commision_percentages;

                                                $biayaPolis = 0;
                                                if ($index == 0 && $loop->first) {
                                                    $biayaPolis = $card->policy_fee ?? 0; 
                                                }

                                                $komisi = $premi * $commPercent;
                                                $pajak = ($pmt->amount > 0) ? ($card->tax * ($premi / $pmt->amount)) : 0;
                                                $netto = $premi - $komisi - $pajak + $biayaPolis;

                                                $subtotalPremi += $premi;
                                                $subtotalBiaya += $biayaPolis;
                                                $subtotalKomisi += $komisi;
                                                $subtotalPajak += $pajak;
                                                $subtotalNetto += $netto;
                                            @endphp
                                            
                                            <tr>
                                                @if($loop->first)
                                                    <td rowspan="{{ $rowSpanCount }}" class="text-center" style="vertical-align: top; padding-top: 10px;">
                                                        ANGSURAN {{ $index + 1 }}<br>
                                                        <small>{{ $pmt->deadline_at ? $pmt->deadline_at->format('d/m/Y') : '' }}</small>
                                                    </td>
                                                @endif

                                                <td>{{ $pi->insurance->name }}</td>
                                                <td class="text-center">{{ number_format(($share * 100), 2) }}%</td>
                                                <td class="text-end">{{ numberFormat($premi) }}</td>
                                                
                                                @if($loop->first)
                                                    <td rowspan="{{ $rowSpanCount }}" class="text-end" style="vertical-align: top; padding-top: 8px;">
                                                        {{ numberFormat($biayaPolis) }}
                                                    </td>
                                                @endif
                                                
                                                <td class="text-end">{{ numberFormat($komisi) }}</td>
                                                <td class="text-end">{{ numberFormat($pajak) }}</td>
                                                <td class="text-end">{{ numberFormat($netto) }}</td>
                                            </tr>
                                        @endforeach

                                        <tr class="bg-total" style="border-top: 2px solid #000;">
                                            <td colspan="3"></td> 
                                            <td class="text-end">{{ numberFormat($subtotalPremi) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalBiaya) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalKomisi) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalPajak) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalNetto) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Konfigurasi Awal
                const totalPremi = {{ $payment->amount ?? 0 }};
                const commissionPercent = {{ $card->commision_percentages ?? 0 }};
                const totalTax = {{ $card->tax ?? 0 }};

                // 1. Inisialisasi Tampilan Awal
                checkPaymentType();

                // 2. Handler Ganti Tipe Pembayaran
                $('#payment-type-select').on('change', function() {
                    checkPaymentType();
                });

                // 3. Handler Input Jumlah Cicilan (Generator Otomatis)
                $('#installment-count').on('input change', function() {
                    let count = parseInt($(this).val()) || 0;
                    if (count < 1) count = 1;
                    
                    // Generate ulang item sesuai jumlah baru
                    generateInstallmentItems(count);
                });

                // 4. Handler Tombol TAMBAH MANUAL
                $('#add-installment-btn').on('click', function() {
                    // Tambah 1 baris kosong
                    addInstallmentRow(0); 
                    
                    // Update state (Input Jumlah ikut nambah)
                    syncInstallmentState();
                });

                // 5. Handler Tombol HAPUS MANUAL
                $(document).on('click', '.remove-installment-btn', function() {
                    const rowCount = $('#installments-container .installment-row').length;
                    if (rowCount <= 1) {
                        Swal.fire('Minimal 1 cicilan!', '', 'warning');
                        return;
                    }

                    // Hapus baris ini
                    $(this).closest('.installment-row').remove();
                    
                    // Update state (Input Jumlah ikut kurang & Renumbering)
                    syncInstallmentState();
                });

                // 6. Handler Hitung Otomatis saat Nilai Rupiah diketik
                $(document).on('input', '.installment-amount', function() {
                    const $row = $(this).closest('.installment-row');
                    const amount = parseFloat($(this).val()) || 0;
                    calculateRowValues($row, amount);
                    updateTotalDisplay();
                });

                // 7. Handler SIMPAN PERUBAHAN (AJAX Save)
                $('#apply-installments-btn').on('click', function() {
                    const $btn = $(this);
                    const originalText = $btn.html();
                    
                    // Validasi: Cek apakah ada installment
                    const rows = $('#installments-container .installment-row');
                    if (rows.length === 0) {
                        Swal.fire('Error', 'Tidak ada cicilan untuk disimpan!', 'error');
                        return;
                    }

                    // Confirm save
                    Swal.fire({
                        title: 'Simpan Perubahan?',
                        html: `Ini akan mengupdate pembayaran cicilan.<br><strong>Total: ${rows.length} cicilan</strong>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Disable button & show loading
                            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

                            // Collect data
                            const installments = [];
                            rows.each(function() {
                                installments.push({
                                    amount: parseFloat($(this).find('.installment-amount').val()) || 0,
                                    due_date: $(this).find('.installment-due-date').val()
                                });
                            });

                            const paymentTypeId = $('#payment-type-select').val();

                            // Send AJAX
                            $.ajax({
                                url: '{{ route("payments.updateInstallments", $payment->id) }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    payment_type_id: paymentTypeId,
                                    installments: installments
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: response.message,
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            // Redirect ke index payments
                                            window.location.href = '{{ route("payments.index") }}';
                                        });
                                    } else {
                                        throw new Error(response.message || 'Gagal menyimpan');
                                    }
                                },
                                error: function(xhr) {
                                    let errorMsg = 'Terjadi kesalahan saat menyimpan';
                                    
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMsg = xhr.responseJSON.message;
                                    }
                                    
                                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                                        const errors = xhr.responseJSON.errors;
                                        errorMsg += '<br><ul style="text-align:left">';
                                        Object.keys(errors).forEach(key => {
                                            errorMsg += `<li>${errors[key][0]}</li>`;
                                        });
                                        errorMsg += '</ul>';
                                    }

                                    Swal.fire('Gagal!', errorMsg, 'error');
                                },
                                complete: function() {
                                    // Re-enable button
                                    $btn.prop('disabled', false).html(originalText);
                                }
                            });
                        }
                    });
                });

                // --- FUNGSI-FUNGSI BANTUAN ---

                function checkPaymentType() {
                    const selectedType = $('#payment-type-select option:selected').data('name');
                    
                    if (selectedType === 'installment' || selectedType === 'cicilan') {
                        $('#installment-section').slideDown();
                        
                        // Jika container kosong, generate sesuai input yang ada
                        if ($('#installments-container').children().length === 0) {
                            $('#installment-count').trigger('change');
                        }
                        $('#installment-items-section').slideDown();
                    } else {
                        $('#installment-section').slideUp();
                        $('#installment-items-section').slideUp();
                    }
                }

                function generateInstallmentItems(count) {
                    $('#installments-container').empty();
                    const amountPerInstallment = totalPremi / count;
                    
                    for (let i = 0; i < count; i++) {
                        addInstallmentRow(amountPerInstallment);
                    }
                    updateTotalDisplay();
                }

                function addInstallmentRow(amount = 0) {
                    // Hitung index sementara berdasarkan jumlah elemen yg ada
                    const newIndex = $('#installments-container .installment-row').length + 1;
                    
                    // Hitung tanggal jatuh tempo (Default +30 hari per cicilan)
                    const today = new Date();
                    today.setDate(today.getDate() + (newIndex * 30));
                    const dueDate = today.toISOString().split('T')[0];

                    const html = `
                        <div class="installment-row mb-2 p-3 bg-white border rounded shadow-sm">
                            <div class="row align-items-end">
                                <div class="col-md-1 text-center">
                                    <label class="form-label fw-bold installment-label text-primary">Cicilan #${newIndex}</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Nilai (Rp)</label>
                                    <input type="number" class="form-control fw-bold installment-amount" 
                                        value="${amount.toFixed(0)}" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Jatuh Tempo</label>
                                    <input type="date" class="form-control installment-due-date" value="${dueDate}">
                                </div>
                                <div class="col-md-4">
                                    <div class="row g-0">
                                        <div class="col-4 px-1">
                                            <label class="small text-muted d-block">Komisi</label>
                                            <span class="installment-commission small">0</span>
                                        </div>
                                        <div class="col-4 px-1">
                                            <label class="small text-muted d-block">Pajak</label>
                                            <span class="installment-tax small">0</span>
                                        </div>
                                        <div class="col-4 px-1">
                                            <label class="small text-muted d-block fw-bold">Netto</label>
                                            <span class="installment-netto small fw-bold text-success">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    $('#installments-container').append(html);
                    
                    // Hitung ulang nilai baris baru ini
                    const $newRow = $('#installments-container .installment-row').last();
                    calculateRowValues($newRow, amount);
                }

                // Fungsi Sync Penting: Agar input manual dan input angka sinkron
                function syncInstallmentState() {
                    const rows = $('#installments-container .installment-row');
                    const count = rows.length;

                    // 1. Update Input Angka di atas
                    $('#installment-count').val(count);

                    // 2. Update Label Urutan (Renumbering Cicilan #1, #2, dst)
                    rows.each(function(index) {
                        $(this).find('.installment-label').text(`Cicilan #${index + 1}`);
                    });

                    // 3. Update Total di bawah
                    updateTotalDisplay();
                }

                function calculateRowValues($row, amount) {
                    const commission = amount * commissionPercent;
                    const tax = amount * taxPercent;
                    const netto = amount - commission - tax;
                    
                    $row.find('.installment-commission').text(formatNumber(commission));
                    $row.find('.installment-tax').text(formatNumber(tax));
                    $row.find('.installment-netto').text(formatNumber(netto));
                }

                function updateTotalDisplay() {
                    const count = $('#installments-container .installment-row').length;
                    let totalAmount = 0;
                    
                    $('#installments-container .installment-amount').each(function() {
                        totalAmount += parseFloat($(this).val()) || 0;
                    });
                    
                    $('#total-installments-display').text(count);
                    $('#total-amount-display').text('Rp ' + formatNumber(totalAmount));
                }

                function formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                }
            });
            
            // Function Print (Tetap sama)
            function printTableOnly() {
                var objectName = "{{ strtoupper($policy->object->name ?? '-') }}";
                var policyNo = "{{ $policy->policy_number ?? '-' }}";
                var clientName = "{{ $client->user->name ?? $client->id_name ?? '-' }}";
                var tableHTML = document.getElementById('paymentTable').outerHTML;
                var win = window.open('', '', 'height=700,width=900');
                win.document.write('<html><head><title>Print Laporan</title>');
                win.document.write('<style>body{font-family:Arial,sans-serif;padding:20px}.table-manual{width:100%;border-collapse:collapse;font-size:10px}.table-manual th,.table-manual td{border:1px solid #000;padding:4px;vertical-align:middle}.table-manual thead th{background-color:#d1d1d1!important;font-weight:700;text-align:center;-webkit-print-color-adjust:exact}.bg-total{background-color:#e9ecef!important;font-weight:700;-webkit-print-color-adjust:exact}.text-end{text-align:right}.text-center{text-align:center}.fw-bold{font-weight:700}@page{size:landscape;margin:10mm}</style>');
                win.document.write('</head><body><div style="text-align:center;margin-bottom:20px"><h3 style="margin:0">PERHITUNGAN PEMBAYARAN PREMI</h3><p style="margin:5px 0;font-weight:700">'+objectName+'</p></div><table style="width:100%;font-size:11px;margin-bottom:10px;border:none"><tr><td style="border:none"><b>NO POLIS:</b> '+policyNo+'</td><td style="border:none;text-align:right"><b>Client:</b> '+clientName+'</td></tr></table>'+tableHTML+'</body></html>');
                win.document.close();
                win.focus();
                setTimeout(function(){win.print();win.close()},500);
            }
        </script>
        
        {{-- Load SweetAlert2 for notifications --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
@endsection