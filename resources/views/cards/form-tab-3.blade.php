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
                    <label class="form-label fw-bold">Harga Pertanggungan 
                        <span id="endorsement-label-properti" style="display:none;">(Selisih dari Parent)</span>
                    </label>

                    <!-- Hidden field to store parent object value for Endors calculation -->
                    <input type="hidden" id="parent_object_value" value="0">
                    
                    <!-- Hidden field to store raw numeric value for form submission -->
                    <input type="hidden" id="object_value_raw" name="object_value" value="{{ isset($card) ? $card->policy->getTotalObjectValue() : 0 }}">

                    <div class="input-group">
                        <span class="input-group-text currency-label">Rp</span>
                        <input id="object_value"
                            class="form-control form-control-lg fw-bold check-negative"
                            type="number"
                            value="{{ isset($card) ? $card->policy->getTotalObjectValue() : 0 }}"
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
                        value="{{ isset($card) ? $card->production_value : old('production_value', 0) }}">
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
                        step="0.00001"
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
                            @foreach ($card->policy->policyInsurances->sortByDesc('percentage') as $index => $polIns)
                                <div class="row g-2 align-items-center mb-3 insurance-item">
                                    {{-- Kolom 1: Nama Asuransi (Ubah jadi col-md-4) --}}
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-0">Asuransi</label>
                                        <input type="text"
                                            class="form-control form-control-sm bg-white"
                                            value="{{ $polIns->insurance->name ?? '-' }}"
                                            readonly>
                                    </div>

                                    {{-- Kolom 2: Share % (Tetap col-md-3) --}}
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

                                    {{-- Kolom 3: Input Pajak Persen & Nominal (Ubah jadi col-md-5) --}}
                                    <div class="col-md-5">
                                        <label class="small text-muted mb-0 fw-bold">Pajak (Input)</label>
                                        <div class="row g-1">
                                            <div class="col-5">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" 
                                                        class="form-control insurance-tax-percent-per-item check-negative" 
                                                        data-insurance-id="{{ $polIns->insurance_id }}" 
                                                        placeholder="0" step="0.00001" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-7">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text currency-label">Rp</span>
                                                    {{-- Value nominal tetap diambil dari database untuk mode Edit --}}
                                                    <input type="number"
                                                        name="insurance_tax[{{ $polIns->insurance_id }}]"
                                                        class="form-control insurance-tax-per-item check-negative"
                                                        data-insurance-id="{{ $polIns->insurance_id }}"
                                                        placeholder="0"
                                                        value="{{ old('insurance_tax.'.$polIns->insurance_id, 0) }}"> {{-- Sesuaikan variabel value ini dengan struktur data pajakmu jika ada --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                                            <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
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
                <div class="mb-3">
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

                <div>
                    <label class="form-label" for="agent_asuransi">Agent Asuransi</label>
                    <input class="form-control @error('agent_asuransi') is-invalid @enderror" 
                        id="agent_asuransi" 
                        name="agent_asuransi" 
                        type="text" 
                        value="{{ old('agent_asuransi', isset($card) ? $card->agent_asuransi : '') }}" 
                        placeholder="Masukkan Nama Agent Asuransi (Opsional)">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
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

                <div>
                    <label class="form-label" for="agent_okj">Co Agent</label>
                    <input class="form-control @error('agent_okj') is-invalid @enderror" 
                           id="agent_okj" 
                           name="agent_okj" 
                           type="text" 
                           value="{{ old('agent_okj', isset($card) ? $card->agent_okj : '') }}" 
                           placeholder="Masukkan Nama Co Agent (Opsional)">
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

    <!-- Hidden inputs untuk menyimpan nilai pajak per insurance -->
    <div id="tax-hidden-inputs-container" style="display:none;"></div>

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
                                {{-- Mengambil nilai dari Netto Tagihan --}}
                                <td class="text-end lbl-netto-tagihan">
                                    Rp{{ isset($card) ? number_format($card->client_pay, 0, ',', '.') : '0' }}
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
        // === HELPER FUNCTION: Calculate Days Between Two Dates ===
        function calculateDaysBetween(startDate, endDate) {
            if (!startDate || !endDate) return null;
            var start = new Date(startDate);
            var end = new Date(endDate);
            var diffTime = Math.abs(end - start);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }

        // === HELPER FUNCTION: Get Prorate Factor for Endors ===
        function getEndorsProrateInfo() {
            var cardTypeId = $('#card_type_id').val();
            var endorsId = {{ config('constants.card_type.endors') }};
            
            if (cardTypeId != endorsId) {
                return { isEndors: false, days: null, daysFactor: 1 };
            }
            
            var startedDate = $('#started_date').val() || $('#policy_id').find('option:selected').data('started-date');
            var endedDate = $('#ended_date').val();
            var days = calculateDaysBetween(startedDate, endedDate);
            
            return {
                isEndors: true,
                days: days,
                daysFactor: days ? days / 365 : 1
            };
        }

        function sync_discount(source) {
            var object_value = parseNumber($('#object_value').val()) || 0;
            var premi_percentages = parseNumber($('#premi_percentages').val()) || 0;
            var tjh_value = parseNumber($('#tjh_value').val()) || 0;

            // Get prorate info for Endors
            var prorateInfo = getEndorsProrateInfo();
            var base_premium;
            
            if (prorateInfo.isEndors && prorateInfo.days) {
                // Use prorated formula: (Harga * Tarif * (hari/365)) + TJH
                base_premium = (object_value * (premi_percentages / 100) * prorateInfo.daysFactor) + tjh_value;
            } else {
                // Regular formula for New and Renewal: (Harga Pertanggungan * Tarif) + TJH
                base_premium = (object_value * (premi_percentages / 100)) + tjh_value;
            }

            // Hitung meskipun negatif, asal bukan 0
            if (base_premium != 0) {
                if (source === 'percentage') {
                    // User isi persen, hitung rupiah
                    var disc_pct = parseNumber($('#discount_percentage').val()) || 0;
                    var disc_val = base_premium * (disc_pct / 100);
                    $('#discount_value').val(disc_val.toFixed(2)); // Update Input Rupiah (2 desimal)
                    
                    // Trigger check negatif untuk discount_value
                    if (typeof window.toggleNegativeWarning === 'function') {
                        window.toggleNegativeWarning($('#discount_value')[0]);
                    }
                } else if (source === 'value') {
                    // User isi rupiah, hitung persen
                    var disc_val = parseNumber($('#discount_value').val()) || 0;
                    var disc_pct = (disc_val / base_premium) * 100;
                    $('#discount_percentage').val(disc_pct.toFixed(5)); // Update Input Persen (5 desimal)
                    
                    // Trigger check negatif untuk discount_percentage
                    if (typeof window.toggleNegativeWarning === 'function') {
                        window.toggleNegativeWarning($('#discount_percentage')[0]);
                    }
                }
            }
        }
        
        // === HELPER FUNCTION: Sync Tax Percentage & Value ===
        function sync_insurance_tax($row, source) {
            // 1. Ambil nilai dari Total Komisi (Output)
            var baseTotal = parseNumber($('#commision_bruto').val()) || 0; 
            
            // 2. Ambil Persentase Asuransi (Share) di baris ini (PERBAIKAN SELECTOR)
            var shareVal = parseNumber($row.find('.col-md-3 input').first().val()) || 0; 
            
            // 3. Dasar pengali pajaknya = Total Komisi * (Share % Asuransi)
            var baseTaxValue = baseTotal * (shareVal / 100); 

            var $percentInput = $row.find('.insurance-tax-percent-per-item');
            var $valueInput = $row.find('.insurance-tax-per-item');

            if (baseTaxValue !== 0) {
                if (source === 'percentage') {
                    // User input %, hitung nominal Rupiah/USD
                    var pct = parseNumber($percentInput.val()) || 0;
                    var val = baseTaxValue * (pct / 100);
                    $valueInput.val(val.toFixed(2)); // Set ke 2 desimal
                    
                    if (typeof window.toggleNegativeWarning === 'function') {
                        window.toggleNegativeWarning($valueInput[0]);
                    }
                } else if (source === 'value') {
                    // User input nominal Rupiah/USD, hitung %
                    var val = parseNumber($valueInput.val()) || 0;
                    var pct = (val / baseTaxValue) * 100;
                    $percentInput.val(pct.toFixed(5)); // Set ke 5 desimal
                    
                    if (typeof window.toggleNegativeWarning === 'function') {
                        window.toggleNegativeWarning($percentInput[0]);
                    }
                }
            } else {
                // Jika baseTaxValue 0 (Total Komisi 0), set hasil 0
                if (source === 'percentage') {
                    $valueInput.val(0);
                } else {
                    $percentInput.val(0);
                }
            }
        }

        function update_payment(triggerSource = 'auto') {
            var object_value = parseNumber($('#object_value').val()) || 0;
            var premi_percentages = parseNumber($('#premi_percentages').val()) || 0;
            var policy_fee = parseNumber($('#policy_fee').val()) || 0;
            var commision_percentage = parseNumber($('#commision_percentage').val()) || 0;
            var discount = parseNumber($('#discount_value').val()) || 0;
            var tjh_value = parseNumber($('#tjh_value').val()) || 0;
            var discount_agent = parseNumber($('#discount_agent').val()) || 0;

            var prorateInfo = getEndorsProrateInfo();
            var $prodInput = $('#production_value'); // Ambil elemen input Nilai Produksi
            var productionValue = parseNumber($prodInput.val()) || 0;
            
            // 1. Hitung potensi nilai produksi dari rumus
            let calculatedProd = 0;
            if (prorateInfo.isEndors && prorateInfo.days) {
                var endorsType = $('#endors_type').val() || window.endorsType;
                calculatedProd = production_value_endors(object_value, premi_percentages / 100, tjh_value, discount, prorateInfo.days);
            } else {
                calculatedProd = production_value(object_value, premi_percentages / 100, tjh_value, discount);
            }

            // 2. CEK KONDISI PENGUNCIAN
            let isZeroParams = (object_value === 0 && premi_percentages === 0 && tjh_value === 0);

            if (triggerSource !== 'manual_production') {
                if (!isZeroParams) {
                    // JIKA ADA PARAMETER RUMUS: Kunci kotak, kasih warna abu-abu, pakai hasil rumus
                    $prodInput.prop('readonly', true).addClass('bg-light');
                    productionValue = calculatedProd;
                    $prodInput.val(productionValue.toFixed(2));
                } else {
                    // JIKA PARAMETER RUMUS 0: Buka kunci kotak, biarkan nilai manual
                    $prodInput.prop('readonly', false).removeClass('bg-light');
                    // Tidak menimpa productionValue, tetap menggunakan angka yang ada di input
                }
            }

            // --- 3. Lanjut hitung berantai ke bawah ---
            const premiValue = premi_value(productionValue, policy_fee);
            const commisionBruto = commision_bruto(productionValue, commision_percentage / 100, discount);
            const taxValue = parseNumber($('#tax').val()) || 0;
            const commisionNetto = commision_netto(commisionBruto, taxValue, discount_agent);
            const clientPay = client_pay(premiValue, discount_agent);
            const insurancePay = insurance_pay(clientPay, commisionNetto); 

            $('#premi_value').val(premiValue.toFixed(2));
            $('#commision_bruto').val(commisionBruto.toFixed(2));
            $('#commision_netto').val(commisionNetto.toFixed(2));
            $('#client_pay').val(clientPay.toFixed(2));
            $('#insurance_pay').val(insurancePay.toFixed(2));

            if (typeof syncObjectValueToHidden === 'function') {
                syncObjectValueToHidden();
            }

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
                var value = parseNumber($(this).val()) || 0;
                totalTax += value;
            });
            $('#tax').val(totalTax.toFixed(2));
            // console.log('Total Tax calculated:', totalTax);
            return totalTax; // Return the value for use in update_payment
        }

        // Sync formatted object_value to hidden raw field
        function syncObjectValueToHidden() {
            var numericValue = parseNumber($('#object_value').val()) || 0;
            $('#object_value_raw').val(numericValue);
        }

        $(document).ready(function() {
            $(document).on('input change keyup', '.check-negative', function() {
                toggleNegativeWarning(this);
                
                // Jika yang diketik adalah kolom Nilai Produksi, beritahu sistem
                if ($(this).attr('id') === 'production_value') {
                    if (typeof update_payment === 'function') update_payment('manual_production');
                } else {
                    if (typeof update_payment === 'function') update_payment('auto');
                }
                
                if (typeof calculateTotalTax === 'function') calculateTotalTax(); 
            });

            $(document).on('focus', 'input[type="number"]:not([readonly])', function() {
                // Jika nilainya 0, kosongkan saat diklik
                if ($(this).val() == '0' || $(this).val() == '0.00' || $(this).val() == '0.00000') {
                    $(this).val('');
                }
            });

            $(document).on('blur', 'input[type="number"]:not([readonly])', function() {
                // Jika ditinggalkan dalam keadaan kosong, kembalikan ke 0
                if ($(this).val() === '') {
                    $(this).val('0');
                    $(this).trigger('input'); // Trigger event input agar kalkulasi (update_payment) otomatis berjalan
                }
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
                    // SKIP: Item yang tidak visible atau hidden
                    if ($(this).css('display') === 'none') {
                        return; // continue to next iteration
                    }
                    
                    // console.log('Processing source', index, 'visible:', $(this).is(':visible'));

                    // GET TEXT DARI ORIGINAL SEBELUM CLONE
                    var originalCoverageName = $(this).find('select[name="coverage_code[]"]').find('option:selected').text().trim();
                    var originalCoverageCode = $(this).find('select[name="coverage_code[]"]').val();
                    
                    // SKIP: Jika coverage belum dipilih (code kosong)
                    if (!originalCoverageCode || originalCoverageCode === '' || originalCoverageName === '- Pilih Coverage -') {
                        return; // continue to next iteration
                    }
                    
                    // console.log('Coverage Name:', originalCoverageName);

                    // CLONE & BERSIHKAN
                    var $cloned = $(this).clone();
                    $cloned.find('.remove-item-btn').closest('.col-md-auto').remove();

                    // TRANSFORM INPUT
                    $cloned.find('input, select').each(function() {
                        var $el = $(this);

                        if ($el.attr('name') === 'coverage_percentage[]') {
                            var val = parseNumber($el.val()) || 0;
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

                // Cek jika tidak ada item valid ter-clone
                if (clonedCount === 0) {
                    $target.html('<div class="text-center text-muted small">Tidak ada data coverage valid.</div>');
                    return;
                }

                if (totalPercentage > 0) $('#premi_percentages').val(totalPercentage.toFixed(5));
                // console.log('✅ Coverage cloned:', clonedCount, 'items, total:', totalPercentage);
                // console.log('Container HTML after:', $target.html().substring(0, 200));
            };

            window.copyInsuranceToTab3 = function() {
                var $target = $('#insurance-list-container');
                var $sources = $('#policy-form-container .item-1');

                // Simpan nilai pajak sebelum clone untuk restore nanti
                var savedTaxValues = {};
                $target.find('.insurance-tax-per-item').each(function() {
                    var insuranceId = $(this).data('insurance-id');
                    if (insuranceId) {
                        savedTaxValues[insuranceId] = $(this).val() || 0;
                    }
                });

                $target.empty();

                if ($sources.length === 0) {
                    $target.html('<div class="text-center text-muted small">Tidak ada data asuransi.</div>');
                    // console.log('❌ No sources found');
                    return;
                }

                var clonedCount = 0;
                var itemsToAppend = []; // Array untuk menyimpan items sebelum diurutkan

                $sources.each(function(index) {
                    // SKIP: Template (no-print) dan item yang tidak visible
                    if ($(this).hasClass('no-print') || $(this).css('display') === 'none') {
                        return; // continue to next iteration
                    }
                    
                    // GET TEXT DARI ORIGINAL SEBELUM CLONE
                    var originalInsuranceName = $(this).find('select[name="insurance_id[]"]').find('option:selected').text().trim();
                    var originalInsuranceId = $(this).find('select[name="insurance_id[]"]').val();
                    
                    // SKIP: Jika asuransi belum dipilih (ID kosong atau null)
                    if (!originalInsuranceId || originalInsuranceId === '' || originalInsuranceName === '- Pilih Asuransi -') {
                        return; // continue to next iteration
                    }
                    
                    // console.log('Processing insurance', index, 'visible:', $(this).is(':visible'));
                    // console.log('Insurance ID:', originalInsuranceId, 'Name:', originalInsuranceName);

                    // CLONE
                    var $cloned = $(this).clone();

                    // Add insurance-item class for consistent selector usage
                    $cloned.addClass('insurance-item');

                    // BERSIHKAN
                    $cloned.find('.remove-item-btn').closest('div[class^="col-"]').remove();
                    $cloned.find('.is-main-checkbox').closest('div[class^="col-"]').remove();

                    var $colName = $cloned.find('select[name="insurance_id[]"]').closest('div[class^="col-"]');
                    $colName.removeClass().addClass('col-md-4'); 

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

                    
                    var oldTaxVal = savedTaxValues[originalInsuranceId] || 0;
                    var taxHtml = `
                        <div class="col-md-5 mb-2">
                            <label class="small text-muted mb-0 fw-bold">Pajak (Input)</label>
                            <div class="row g-1">
                                <div class="col-5">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                            class="form-control insurance-tax-percent-per-item check-negative" 
                                            data-insurance-id="${originalInsuranceId}" 
                                            placeholder="0" step="0.00001" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text currency-label">Rp</span>
                                        <input type="number" 
                                            name="insurance_tax[${originalInsuranceId}]"
                                            data-insurance-id="${originalInsuranceId}"
                                            class="form-control insurance-tax-per-item check-negative" 
                                            value="${oldTaxVal}" 
                                            placeholder="0">
                                    </div>
                                </div>
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

                    // Ambil nilai persentase untuk sorting
                    var percentageValue = parseNumber($(this).find('input[name="insurance_percentage[]"]').val()) || 0;
                    
                    // Simpan ke array dengan data untuk sorting
                    itemsToAppend.push({
                        element: $cloned,
                        percentage: percentageValue
                    });

                    clonedCount++;
                });

                // Cek jika tidak ada item yang valid ter-clone
                if (clonedCount === 0 || itemsToAppend.length === 0) {
                    $target.html('<div class="text-center text-muted small">Tidak ada data asuransi valid.</div>');
                    return;
                }

                // Urutkan array berdasarkan persentase (terbesar ke terkecil)
                itemsToAppend.sort(function(a, b) {
                    return b.percentage - a.percentage;
                });

                // Append semua items yang sudah terurut
                itemsToAppend.forEach(function(item, index) {
                    $target.append(item.element);
                    if (index < itemsToAppend.length - 1) {
                        $target.append('<hr class="my-2 text-muted">');
                    }
                });

                if (typeof window.attachTaxInputListeners === 'function') window.attachTaxInputListeners();
                if (typeof window.calculateTotalTax === 'function') window.calculateTotalTax();
                if (typeof window.syncTaxToHiddenInputs === 'function') window.syncTaxToHiddenInputs();
                
                if (typeof updateCurrencyLabel === 'function' && window.currentCurrency) {
                    updateCurrencyLabel(window.currentCurrency);
                }
                
                // console.log('✅ Insurance cloned:', clonedCount, 'items');
                // console.log('Container HTML after:', $target.html().substring(0, 200));
            };

            window.attachTaxInputListeners = function() {
                $('.insurance-tax-per-item, .insurance-tax-percent-per-item').off('input blur keyup').on('input blur keyup', function() {
                    toggleNegativeWarning(this);
                    
                    var $row = $(this).closest('.insurance-item'); 
                    
                    if ($(this).hasClass('insurance-tax-percent-per-item')) {
                        sync_insurance_tax($row, 'percentage');
                    } else {
                        sync_insurance_tax($row, 'value');
                    }

                    syncTaxToHiddenInputs();
                    if (typeof window.calculateTotalTax === 'function') window.calculateTotalTax();
                    if (typeof update_payment === 'function') update_payment();
                });
            };

            window.syncTaxToHiddenInputs = function() {
                var $container = $('#tax-hidden-inputs-container');
                $container.empty();
                
                $('.insurance-tax-per-item').each(function() {
                    var insuranceId = $(this).data('insurance-id');
                    var value = $(this).val() || 0;
                    $container.append(`<input type="hidden" name="insurance_tax[${insuranceId}]" value="${value}">`);
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

                    // 1.5. Calculate difference for Endors type
                    var cardTypeId = $('#card_type_id').val();
                    var endorsId = {{ config('constants.card_type.endors') }};
                    
                    if (cardTypeId == endorsId) {
                        var endorsType = $('#endors_type').val() || window.endorsType;
                        var currentObjectValue = parseNumber($('#object_value').val()) || 0;
                        var parentObjectValue = parseNumber($('#parent_object_value').val()) || 0;
                        
                        if (endorsType === 'properti') {
                            // PROPERTI: Show label dan hitung selisih (current - parent)
                            $('#endorsement-label-properti').show();
                            $('#endorsement-label-kendaraan').hide();
                            
                            var difference = currentObjectValue - parentObjectValue;
                            $('#object_value').val(difference);
                            
                            console.log('Endors Properti:', { current: currentObjectValue, parent: parentObjectValue, difference: difference });
                        } else if (endorsType === 'kendaraan') {
                            // KENDARAAN: Show label, nilai langsung (tidak dikurangi parent)
                            $('#endorsement-label-properti').hide();
                            $('#endorsement-label-kendaraan').show();
                            
                            console.log('Endors Kendaraan:', { current: currentObjectValue });
                        } else {
                            // No endors type selected yet
                            $('#endorsement-label-properti').hide();
                            $('#endorsement-label-kendaraan').hide();
                        }
                    } else {
                        // Hide endorsement labels for other types
                        $('#endorsement-label-properti').hide();
                        $('#endorsement-label-kendaraan').hide();
                    }

                    // 2. Jalankan Kloning Tampilan
                    if (typeof window.copyCoverageToTab3 === 'function') window.copyCoverageToTab3();
                    if (typeof window.copyInsuranceToTab3 === 'function') window.copyInsuranceToTab3();

                    // 3. Update currency labels
                    if (typeof updateCurrencyLabel === 'function' && window.currentCurrency) {
                        updateCurrencyLabel(window.currentCurrency);
                    }

                    // 🔥 3.5. SYNC NILAI PAJAK KE HIDDEN INPUTS
                    if (typeof window.syncTaxToHiddenInputs === 'function') window.syncTaxToHiddenInputs();

                    // 4. Hitung Duit
                    if (typeof update_payment === 'function') update_payment();
                    
                    // 5. Sync object_value to hidden field
                    if (typeof syncObjectValueToHidden === 'function') syncObjectValueToHidden();
                }, 150);
            });

            $('#premi_percentages, #policy_fee, #commision_percentage, #tjh_value, #discount_agent').on('input change blur', function() {
                if (typeof update_payment === 'function') update_payment();
                if (typeof syncObjectValueToHidden === 'function') syncObjectValueToHidden();
            });

            $('#discount_percentage').on('input change blur', function() {
                if (typeof sync_discount === 'function') sync_discount('percentage');
                if (typeof update_payment === 'function') update_payment();
                if (typeof syncObjectValueToHidden === 'function') syncObjectValueToHidden();
            });
            $('#discount_value').on('input change blur', function() {
                if (typeof sync_discount === 'function') sync_discount('value');
                if (typeof update_payment === 'function') update_payment();
                if (typeof syncObjectValueToHidden === 'function') syncObjectValueToHidden();
            });

            // Trigger KHUSUS untuk Input Manual Nilai Produksi
            $('#production_value').on('input change blur', function() {
                // Panggil update_payment dengan flag 'manual_production' 
                // agar sistem tahu ini diketik manual dan tidak boleh ditimpa rumus
                update_payment('manual_production'); 
                
                if (typeof calculateTotalTax === 'function') calculateTotalTax(); 
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
                    if (typeof window.syncTaxToHiddenInputs === 'function') window.syncTaxToHiddenInputs();
                    if (typeof update_payment === 'function') update_payment();
                    if (typeof syncObjectValueToHidden === 'function') syncObjectValueToHidden();
                }
            }, 800);
        });
    </script>
@endpush
