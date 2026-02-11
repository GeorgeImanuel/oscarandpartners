<div id="step-3"
    class="setup-content">
    {{-- SECTION 1: TOP (Pertanggungan & Parameter Tarif) --}}
    <div class="card mb-3 p-3 shadow-sm">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h5 class="text-primary mb-3">1. Parameter Dasar</h5>
            </div>

            {{-- KIRI: Harga Pertanggungan --}}
            <div class="col-md-6">
                <div class="h-100 d-flex flex-column justify-content">
                    <label class="form-label fw-bold">Harga Pertanggungan (Total)</label>

                    <div class="input-group">
                        <span class="input-group-text currency-label">Rp</span>
                        <input id="object_value"
                            class="form-control form-control-lg fw-bold check-negative"
                            name="object_value"
                            type="text"
                            value="{{ isset($card) ? number_format($card->policy->getTotalObjectValue(), 0, ',', '.') : '0' }}"
                            readonly
                            style="background-color: #f8f9fa;">
                    </div>

                    <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                        <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                    </div>
                </div>
            </div>

            {{-- KANAN: Parameter Tarif (Stacked) --}}
            <div class="col-md-6">
                <div class="row g-2">
                    <div class="col-md-12 mb-3">
                        <label class="form-label mb-1">Tarif Premi</label>
                        <div class="input-group">
                            {{-- Tambahkan class 'validate-percentage' dan min/max --}}
                            <input id="premi_percentages"
                                class="form-control validate-percentage coverage_percentage_total"
                                name="premi_percentages"
                                type="number"
                                step="0.00001"
                                min="0"
                                max="100"
                                value="{{ old('premi_percentages', isset($card) ? $card->premi_percentages * 100 : ($policy->coverage_percentage ?? 0) * 100) }}"
                                required
                                readonly>
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="text-danger small percent-alert mt-1"
                            style="display:none;">
                            <i class="fa fa-exclamation-circle"></i> Nilai harus diantara 0-100
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label mb-1">Nilai TJH</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input id="tjh_value"
                                class="form-control"
                                name="tjh_value"
                                type="number"
                                value="{{ old('tjh_value', isset($card) ? $card->tjh_value : $card->object->tjh_value ?? 0) }}"
                                required
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label mb-1">Discount</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    {{-- Tambahkan class 'validate-percentage' dan min/max --}}
                                    <input id="discount_percentage"
                                        class="form-control validate-percentage check-negative"
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="0.00001"
                                        name="discount_percentage"
                                        value="{{ isset($card) ? $card->discount_percentage : old('discount_percentage', 0) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                {{-- ALERT MERAH --}}
                                <div class="text-danger small percent-alert mt-1"
                                    style="display:none;">
                                    <i class="fa fa-exclamation-circle"></i> Nilai harus diantara 0-100
                                </div>
                                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <span class="input-group-text currency-label">Rp</span>
                                    <input id="discount_value"
                                        class="form-control check-negative"
                                        type="number"
                                        name="discount_value"
                                        aria-label="Amount (to the nearest dollar)"
                                        value="{{ isset($card) ? $card->discount_value : old('discount_value', 0) }}">
                                </div>
                                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2: PREMI (Produksi -> Biaya Polis -> Total) --}}
    <div class="card mb-3 p-3 shadow-sm">
        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <h5 class="text-primary mb-3">2. Perhitungan Premi</h5>
            </div>

            {{-- Flow Horizontal: Produksi + Biaya = Total --}}
            <div class="col-md-4">
                <label class="form-label text-muted">Nilai Produksi (Output)</label>
                <div class="input-group mb-3">
                    <span class="input-group-text currency-label">Rp</span>
                    <input id="production_value"
                        class="form-control bg-light check-negative"
                        name="production_value"
                        type="number"
                        value="{{ isset($card) ? $card->production_value : old('production_value', 0) }}"
                        readonly>
                </div>
                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold text-primary">Biaya Polis (Input)</label>
                <div class="input-group mb-3">
                    <span class="input-group-text currency-label">Rp</span>
                    <input id="policy_fee"
                        class="form-control"
                        name="policy_fee"
                        type="number"
                        value="{{ isset($card) ? $card->policy_fee : old('policy_fee', 0) }}"
                        required>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Total Premi (Output)</label>
                <div class="input-group mb-3">
                    <span class="input-group-text currency-label">Rp</span>
                    <input id="premi_value"
                        class="form-control fw-bold bg-light check-negative"
                        name="premi_value"
                        type="number"
                        value="{{ isset($card) ? $card->premi : old('premi', 0) }}"
                        readonly>
                </div>
                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card mb-0"
                    style="border: 2px solid #ddd; background-color: #fcfcfc;">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-3 text-center"
                            style="border-bottom: 1px dashed #ccc; padding-bottom: 10px;">Rincian Coverage</h6>

                        {{-- Dynamic Coverage List Container --}}
                        <div id="coverage-list-container">
                            {{-- Server-side rendered (for edit mode) --}}
                            @if (isset($card->policy) && $card->policy->policyCoverages && $card->policy->policyCoverages->count() > 0)
                                @foreach ($card->policy->policyCoverages as $index => $polCov)
                                    <div class="row g-2 align-items-center mb-3 coverage-item">
                                        <div class="col-md-8">
                                            <label class="small text-muted mb-0">Coverage</label>
                                            <input type="text"
                                                class="form-control form-control-sm bg-white"
                                                value="{{ $polCov->coverage->name ?? '-' }}"
                                                readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="small text-muted mb-0">Persentase</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text"
                                                    class="form-control text-center bg-white"
                                                    value="{{ ($polCov->percentage ?? 0) * 100 }}"
                                                    readonly>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="my-2 text-muted">
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center text-muted small"
                                    id="no-coverage-msg">Tidak ada data coverage terhubung.</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: KOMISI --}}
    <div class="card mb-3 p-3 shadow-sm">
        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <h5 class="text-primary mb-3">3. Perhitungan Komisi</h5>
            </div>

            {{-- BAGIAN ATAS: Persentase Total & Total Komisi --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Persentase Total Komisi</label>
                <div class="input-group">
                    {{-- Tambahkan class 'validate-percentage' dan min/max --}}
                    <input id="commision_percentage"
                        class="form-control validate-percentage check-negative"
                        name="commision_percentage"
                        type="number"
                        step="0.01"
                        min="0"
                        max="100"
                        value="{{ isset($card) ? $card->commision_percentage * 100 : old('commision_percentage', 0) }}"
                        required>
                    <span class="input-group-text">%</span>
                </div>
                {{-- ALERT MERAH --}}
                <div class="text-danger small percent-alert mt-1"
                    style="display:none;">
                    <i class="fa fa-exclamation-circle"></i> Nilai harus diantara 0-100
                </div>
                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label text-muted">Total Komisi (Output)</label>
                <div class="input-group">
                    <span class="input-group-text currency-label">Rp</span>
                    <input id="commision_bruto"
                        class="form-control bg-light fw-bold check-negative"
                        name="commision_bruto"
                        type="number"
                        value="{{ isset($card) ? $card->commision_bruto : old('commision_bruto', 0) }}"
                        readonly>
                </div>
                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                </div>
            </div>

            {{-- BAGIAN TENGAH: KOTAK RINCIAN ASURANSI (THE BOX) --}}
            <div class="col-md-12 mb-3">
                <div class="card mb-0"
                    style="border: 2px solid #ddd; background-color: #fcfcfc;">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-3 text-center"
                            style="border-bottom: 1px dashed #ccc; padding-bottom: 10px;">Rincian Share & Pajak Per Asuransi</h6>

                        {{-- Dynamic Insurance List Container --}}
                        <div id="insurance-list-container">
                            {{-- Server-side rendered (for edit mode) --}}
                            @if (isset($card->policy) && $card->policy->policyInsurances->count() > 0)
                                @foreach ($card->policy->policyInsurances as $index => $polIns)
                                    <div class="row g-2 align-items-center mb-3 insurance-item">
                                        {{-- Kolom 1: Nama Asuransi --}}
                                        <div class="col-md-5">
                                            <label class="small text-muted mb-0">Asuransi</label>
                                            <input type="text"
                                                class="form-control form-control-sm bg-white"
                                                value="{{ $polIns->insurance->name ?? '-' }}"
                                                readonly>
                                        </div>

                                        {{-- Kolom 2: Share % --}}
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-0">Share</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text"
                                                    class="form-control text-center bg-white"
                                                    value="{{ ($polIns->percentage ?? 0) * 100 }}"
                                                    readonly>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        {{-- Kolom 3: Input Pajak (Rp) --}}
                                        <div class="col-md-4">
                                            <label class="small text-muted mb-0 fw-bold">Pajak (Input)</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text currency-label">Rp</span>
                                                <input type="number"
                                                    name="insurance_tax[{{ $polIns->insurance_id }}]"
                                                    class="form-control insurance-tax-per-item"
                                                    data-insurance-id="{{ $polIns->insurance_id }}"
                                                    placeholder="0"
                                                    value="0">
                                            </div>
                                        </div>
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="my-2 text-muted">
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center text-muted small"
                                    id="no-insurance-msg">Tidak ada data asuransi terhubung.</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- BAGIAN BAWAH: TOTAL PAJAK, DISC, BERSIH (SEJAJAR) --}}
            <div class="col-md-4">
                <label class="form-label text-muted">Total Pajak (Auto Sum)</label>
                <div class="input-group">
                    <span class="input-group-text currency-label">Rp</span>
                    <input id="tax"
                        class="form-control bg-light check-negative"
                        name="tax"
                        type="number"
                        value="{{ isset($card) ? $card->tax : old('tax', 0) }}"
                        readonly>
                </div>
                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Discount Agent</label>
                <div class="input-group">
                    <span class="input-group-text currency-label">Rp</span>
                    <input id="discount_agent"
                        class="form-control check-negative"
                        name="discount_agent"
                        type="number"
                        value="{{ isset($card) ? $card->discount_agent : old('discount_agent', 0) }}">
                </div>
                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label text-muted">Komisi Bersih</label>
                <div class="input-group">
                    <span class="input-group-text currency-label">Rp</span>
                    <input id="commision_netto"
                        class="form-control bg-light check-negative"
                        name="commision_netto"
                        type="number"
                        value="{{ isset($card) ? $card->commision_netto : old('commision_netto', 0) }}"
                        readonly>
                </div>
                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                </div>
            </div>
        </div>
    </div>

    <!-- Pembayaran Section -->
    <div class="card mb-3 p-3 shadow-sm">
        <h6 class="mb-3 mt-4">Informasi Pembayaran</h6>
        <div class="row">
            {{-- KARTU KIRI: TAGIHAN NASABAH --}}
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header border-bottom py-2"
                        style="background-color: #f8f9fa;">
                        <strong>Tagihan ke Nasabah</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td>Total Premi</td>
                                {{-- Class: lbl-total-premi --}}
                                <td class="text-end lbl-total-premi">
                                    Rp{{ isset($card) ? number_format($card->premi, 0, ',', '.') : '0' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Diskon/Agen</td>
                                {{-- Class: lbl-discount-agent --}}
                                <td class="text-end text-danger lbl-discount-agent">
                                    Rp{{ isset($card) ? number_format($card->discount, 0, ',', '.') : '0' }}
                                </td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Netto Tagihan</strong></td>
                                {{-- Class: lbl-netto-tagihan --}}
                                <td class="text-end fw-bold lbl-netto-tagihan"
                                    style="font-size: 1.1em;">
                                    Rp{{ isset($card) ? number_format($card->client_pay, 0, ',', '.') : '0' }}
                                </td>
                            </tr>
                        </table>
                        <input type="hidden"
                            name="client_pay"
                            id="client_pay"
                            value="{{ isset($card) ? $card->client_pay : 0 }}">
                    </div>
                </div>
            </div>

            {{-- KARTU KANAN: PEMBAYARAN KE ASURANSI --}}
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header border-bottom py-2"
                        style="background-color: #f8f9fa;">
                        <strong>Pembayaran ke Asuransi</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td>Total Premi</td>
                                {{-- Class: lbl-total-premi (Sama kayak kiri) --}}
                                <td class="text-end lbl-total-premi">
                                    Rp{{ isset($card) ? number_format($card->premi, 0, ',', '.') : '0' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Komisi Bersih</td>
                                {{-- Class: lbl-komisi-bersih --}}
                                <td class="text-end text-success lbl-komisi-bersih">
                                    Rp{{ isset($card) ? number_format($card->commision_netto, 0, ',', '.') : '0' }}
                                </td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Netto Bayar</strong></td>
                                {{-- Class: lbl-netto-bayar --}}
                                <td class="text-end fw-bold lbl-netto-bayar"
                                    style="font-size: 1.1em;">
                                    Rp{{ isset($card) ? number_format($card->insurance_pay, 0, ',', '.') : '0' }}
                                </td>
                            </tr>
                        </table>
                        <input type="hidden"
                            name="insurance_pay"
                            id="insurance_pay"
                            value="{{ isset($card) ? $card->insurance_pay : 0 }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>

        function sync_discount(source) {
            var rawValue = $('#object_value').val();
            if (typeof rawValue === 'string') {
                rawValue = rawValue.replace(/\./g, '').replace(/,/g, '.');
            }
            var object_value = parseFloat(rawValue) || 0;
            var premi_percentages = parseFloat($('#premi_percentages').val()) || 0;
            var tjh_value = parseFloat($('#tjh_value').val()) || 0;

            // Hitung Premi Dasar (Sebelum Diskon) untuk basis perhitungan persen
            // Rumus: (Harga Pertanggungan * Tarif) + TJH
            var base_premium = (object_value * (premi_percentages / 100)) + tjh_value;

            // Hitung meskipun negatif, asal bukan 0
            if (base_premium != 0) {
                if (source === 'percentage') {
                    // User isi persen, hitung rupiah
                    var disc_pct = parseFloat($('#discount_percentage').val()) || 0;
                    var disc_val = base_premium * (disc_pct / 100);
                    $('#discount_value').val(disc_val.toFixed(2)); // Update Input Rupiah (2 desimal)
                    
                    // Trigger check negatif untuk discount_value
                    if (typeof window.toggleNegativeWarning === 'function') {
                        window.toggleNegativeWarning($('#discount_value')[0]);
                    }
                } else if (source === 'value') {
                    // User isi rupiah, hitung persen
                    var disc_val = parseFloat($('#discount_value').val()) || 0;
                    var disc_pct = (disc_val / base_premium) * 100;
                    $('#discount_percentage').val(disc_pct.toFixed(5)); // Update Input Persen (5 desimal)
                    
                    // Trigger check negatif untuk discount_percentage
                    if (typeof window.toggleNegativeWarning === 'function') {
                        window.toggleNegativeWarning($('#discount_percentage')[0]);
                    }
                }
            }
        }

        function update_payment() {
            var rawValue = $('#object_value').val();
            if (typeof rawValue === 'string') {
                rawValue = rawValue.replace(/\./g, '').replace(/,/g, '.');
            }

            var object_value = parseFloat(rawValue) || 0;
            var premi_percentages = parseFloat($('#premi_percentages').val()) || 0;
            var policy_fee = parseInt($('#policy_fee').val()) || 0;
            var commision_percentage = parseFloat($('#commision_percentage').val()) || 0;
            var discount = parseFloat($('#discount_value').val()) || 0;
            var tjh_value = parseInt($('#tjh_value').val()) || 0;
            var discount_agent = parseInt($('#discount_agent').val()) || 0;

            const productionValue = production_value(object_value, premi_percentages / 100, tjh_value, discount);
            const premiValue = premi_value(productionValue, policy_fee);
            const commisionBruto = commision_bruto(productionValue, commision_percentage / 100, discount);
            const taxValue = parseFloat($('#tax').val()) || 0;
            const commisionNetto = commision_netto(commisionBruto, taxValue, discount_agent);
            const clientPay = client_pay(premiValue, discount_agent);
            const insurancePay = insurance_pay(premiValue, commisionNetto);

            $('#production_value').val(productionValue.toFixed(2));
            $('#premi_value').val(premiValue.toFixed(2));
            $('#commision_bruto').val(commisionBruto.toFixed(2));
            $('#commision_netto').val(commisionNetto.toFixed(2));
            $('#client_pay').val(clientPay.toFixed(2));
            $('#insurance_pay').val(insurancePay.toFixed(2));

            $('.lbl-total-premi').text(formatRupiah(premiValue));
            $('.lbl-discount-agent').text(formatRupiah(discount_agent));
            $('.lbl-netto-tagihan').text(formatRupiah(clientPay));
            $('.lbl-komisi-bersih').text(formatRupiah(commisionNetto));
            $('.lbl-netto-bayar').text(formatRupiah(insurancePay));
        }

        // Calculate total tax from all insurance tax inputs
        function calculateTotalTax() {
            var totalTax = 0;
            $('.insurance-tax-per-item').each(function() {
                var value = parseFloat($(this).val()) || 0;
                totalTax += value;
            });
            $('#tax').val(totalTax.toFixed(2));
            // console.log('Total Tax calculated:', totalTax);
            return totalTax; // Return the value for use in update_payment
        }



        $(document).ready(function() {
            $(document).on('input change keyup', '.check-negative', function() {
                toggleNegativeWarning(this);
                // Tetap jalankan perhitungan
                if (typeof update_payment === 'function') update_payment();
                if (typeof calculateTotalTax === 'function') calculateTotalTax(); 
            });

            $('.check-negative').each(function() {
                toggleNegativeWarning(this);
            });

            // Polling untuk monitor readonly fields yang berubah via JS
            setInterval(function() {
                $('#step-3 .check-negative[readonly], #step-3 .check-negative.bg-light').each(function() {
                    var currentValue = $(this).val();
                    var lastValue = $(this).data('last-checked-value');
                    
                    if (currentValue !== lastValue) {
                        $(this).data('last-checked-value', currentValue);
                        if (typeof window.toggleNegativeWarning === 'function') {
                            window.toggleNegativeWarning(this);
                        }
                    }
                });
            }, 300);

            window.copyCoverageToTab3 = function() {
                // console.log('📋 copyCoverageToTab3 called');
                var $target = $('#coverage-list-container');
                var $sources = $('#policy-form-container .item-coverage');

                // console.log('Target:', $target.length, 'Sources:', $sources.length);
                $target.empty();

                if ($sources.length === 0) {
                    $target.html('<div class="text-center text-muted small">Tidak ada data coverage.</div>');
                    // console.log('❌ No sources found');
                    return;
                }

                var totalPercentage = 0;
                var clonedCount = 0;

                $sources.each(function(index) {
                    // console.log('Processing source', index, 'visible:', $(this).is(':visible'));

                    // GET TEXT DARI ORIGINAL SEBELUM CLONE
                    var originalCoverageName = $(this).find('select[name="coverage_code[]"]').find('option:selected').text().trim();
                    // console.log('Coverage Name:', originalCoverageName);

                    // CLONE & BERSIHKAN
                    var $cloned = $(this).clone();
                    $cloned.find('.remove-item-btn').closest('.col-md-auto').remove();

                    // TRANSFORM INPUT
                    $cloned.find('input, select').each(function() {
                        var $el = $(this);

                        if ($el.attr('name') === 'coverage_percentage[]') {
                            var val = parseFloat($el.val()) || 0;
                            totalPercentage += val;
                            // console.log('Coverage %:', val);
                        }

                        if ($el.is('select') && $el.attr('name') === 'coverage_code[]') {
                            // Gunakan text yang sudah diambil dari original
                            $el.replaceWith('<input class="form-control form-control-sm bg-white" value="' + originalCoverageName + '" readonly>');
                        } else if ($el.is('select')) {
                            var text = $el.find('option:selected').text().trim();
                            $el.replaceWith('<input class="form-control form-control-sm bg-white" value="' + text + '" readonly>');
                        } else {
                            $el.attr('readonly', true)
                                .addClass('bg-white')
                                .removeAttr('name')
                                .removeAttr('id');
                        }
                    });
                    $cloned.find('.select2-container').remove();

                    // APPEND
                    $target.append($cloned);
                    clonedCount++;

                    if (index < $sources.length - 1) {
                        $target.append('<hr class="my-2 text-muted">');
                    }
                });

                if (totalPercentage > 0) $('#premi_percentages').val(totalPercentage.toFixed(5));
                // console.log('✅ Coverage cloned:', clonedCount, 'items, total:', totalPercentage);
                // console.log('Container HTML after:', $target.html().substring(0, 200));
            };

            window.copyInsuranceToTab3 = function() {
                // console.log('📋 copyInsuranceToTab3 called');
                var $target = $('#insurance-list-container');
                var $sources = $('#policy-form-container .item-1');

                // console.log('Target:', $target.length, 'Sources:', $sources.length);
                $target.empty();

                if ($sources.length === 0) {
                    $target.html('<div class="text-center text-muted small">Tidak ada data asuransi.</div>');
                    // console.log('❌ No sources found');
                    return;
                }

                var clonedCount = 0;

                $sources.each(function(index) {
                    // console.log('Processing insurance', index, 'visible:', $(this).is(':visible'));

                    // GET TEXT DARI ORIGINAL SEBELUM CLONE
                    var originalInsuranceName = $(this).find('select[name="insurance_id[]"]').find('option:selected').text().trim();
                    var originalInsuranceId = $(this).find('select[name="insurance_id[]"]').val();
                    // console.log('Insurance ID:', originalInsuranceId, 'Name:', originalInsuranceName);

                    // CLONE
                    var $cloned = $(this).clone();

                    // Add insurance-item class for consistent selector usage
                    $cloned.addClass('insurance-item');

                    // BERSIHKAN
                    $cloned.find('.remove-item-btn').closest('div[class^="col-"]').remove();
                    $cloned.find('.is-main-checkbox').closest('div[class^="col-"]').remove();

                    var $colName = $cloned.find('select[name="insurance_id[]"]').closest('div[class^="col-"]');
                    $colName.removeClass().addClass('col-md-5');

                    // B. Kolom Share -> Ubah jadi col-md-3
                    var $colShare = $cloned.find('input[name="insurance_percentage[]"]').closest('div[class^="col-"]');
                    $colShare.removeClass().addClass('col-md-3');

                    // 4. TRANSFORM INPUT (Ubah Select jadi Text Readonly)
                    $cloned.find('input, select').each(function() {
                        var $el = $(this);
                        if ($el.is('select') && $el.attr('name') === 'insurance_id[]') {
                            // Gunakan text yang sudah diambil dari original
                            $el.replaceWith('<input class="form-control form-control-sm bg-white" value="' + originalInsuranceName + '" readonly>');
                        } else if ($el.is('select')) {
                            var text = $el.find('option:selected').text().trim();
                            $el.replaceWith('<input class="form-control form-control-sm bg-white" value="' + text + '" readonly>');
                        } else {
                            $el.attr('readonly', true)
                                .addClass('bg-white')
                                .removeAttr('name')
                                .removeAttr('id');
                        }
                    });
                    $cloned.find('.select2-container').remove();

                    // INJECT TAX INPUT (KHUSUS TAB 3)
                    var oldTaxVal = $(`input[name="insurance_tax[${originalInsuranceId}]"]`).val() || 0;
                    var taxHtml = `
                        <div class="col-md-4 mb-2">
                            <label class="small text-muted mb-0 fw-bold">Pajak (Input)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text currency-label">Rp</span>
                                <input type="number" 
                                    name="insurance_tax[${originalInsuranceId}]" 
                                    class="form-control insurance-tax-per-item" 
                                    value="${oldTaxVal}" 
                                    placeholder="0">
                            </div>
                            <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                                <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                            </div>
                        </div>`;

                    // Append ke row yang tepat (handle nested row structure)
                    if ($cloned.children('.row').length > 0) {
                        // Struktur nested row (empty state)
                        $cloned.children('.row').first().append(taxHtml);
                    } else {
                        // Struktur row langsung
                        $cloned.append(taxHtml);
                    }

                    $target.append($cloned);

                    if (index < $sources.length - 1) $target.append('<hr class="my-2 text-muted">');
                    clonedCount++;
                });

                if (typeof window.attachTaxInputListeners === 'function') window.attachTaxInputListeners();
                if (typeof window.calculateTotalTax === 'function') window.calculateTotalTax();
                
                // Update currency labels for injected tax inputs
                if (typeof updateCurrencyLabel === 'function' && window.currentCurrency) {
                    updateCurrencyLabel(window.currentCurrency);
                }
                
                // console.log('✅ Insurance cloned:', clonedCount, 'items');
                // console.log('Container HTML after:', $target.html().substring(0, 200));
            };

            window.attachTaxInputListeners = function() {
                $('.insurance-tax-per-item').off('input blur keyup').on('input blur keyup', function() {
                    toggleNegativeWarning(this);
                    if (typeof window.calculateTotalTax === 'function') window.calculateTotalTax();
                    if (typeof update_payment === 'function') update_payment();
                });
            };

            // Trigger saat Tab 3 diklik
            $(document).on('click', 'a[href="#step-3"]', function() {
                setTimeout(function() {
                    // 1. Sync values dari Tab 2 (Object Value & TJH)
                    if (typeof sumInputValues === 'function') {
                        // Sum semua TJH dari Tab 2 - HANYA dari policy-form-container
                        sumInputValues($('#policy-form-container input[name^="tjh"]'), '#tjh_value');
                        
                        // Sum semua Object Value dari Tab 2 - HANYA dari policy-form-container
                        sumInputValues($('#policy-form-container input[name="object_value_input"]'), '#object_value');
                    }

                    // 2. Jalankan Kloning Tampilan
                    if (typeof window.copyCoverageToTab3 === 'function') window.copyCoverageToTab3();
                    if (typeof window.copyInsuranceToTab3 === 'function') window.copyInsuranceToTab3();

                    // 3. Update currency labels
                    if (typeof updateCurrencyLabel === 'function' && window.currentCurrency) {
                        updateCurrencyLabel(window.currentCurrency);
                    }

                    // 4. Hitung Duit
                    if (typeof update_payment === 'function') update_payment();
                }, 150);
            });

            $('#premi_percentages, #policy_fee, #commision_percentage, #tjh_value, #discount_agent').on('input change blur', function() {
                if (typeof update_payment === 'function') update_payment();
            });

            $('#discount_percentage').on('input change blur', function() {
                if (typeof sync_discount === 'function') sync_discount('percentage');
                if (typeof update_payment === 'function') update_payment();
            });
            $('#discount_value').on('input change blur', function() {
                if (typeof sync_discount === 'function') sync_discount('value');
                if (typeof update_payment === 'function') update_payment();
            });

            // Initial Load Check (Edit Mode)
            setTimeout(function() {
                if ($('#step-3').is(':visible')) {
                    console.log('🔄 Initial load - Tab 3 visible, syncing from Tab 2...');
                    // Sync TJH dari Tab 2 saat pertama load - HANYA dari policy-form-container
                    if (typeof sumInputValues === 'function') {
                        sumInputValues($('#policy-form-container input[name^="tjh"]'), '#tjh_value');
                        sumInputValues($('#policy-form-container input[name="object_value_input"]'), '#object_value');
                    }
                    
                    if (typeof window.copyCoverageToTab3 === 'function') window.copyCoverageToTab3();
                    if (typeof window.copyInsuranceToTab3 === 'function') window.copyInsuranceToTab3();
                    
                    // Update currency labels
                    if (typeof updateCurrencyLabel === 'function' && window.currentCurrency) {
                        updateCurrencyLabel(window.currentCurrency);
                    }
                    
                    if (typeof update_payment === 'function') update_payment();
                }
            }, 800);
        });
    </script>
@endpush
