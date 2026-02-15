<div id="step-4"
    class="setup-content">

    <div class="card mb-3 p-3 shadow-sm">
        <div class="col-md-12 mb-3">
            <h5 class="text-primary">Payment</h5>
        </div>
        {{-- B. PENGATURAN METODE PEMBAYARAN --}}
        <div class="col-md-12 mb-3">
            <h6 class="fw-bold mb-3">
                <i class="fa fa-cogs"></i> Konfigurasi Pembayaran
            </h6>

            <div class="row">
                {{-- Dropdown Metode Pembayaran --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Tipe Pembayaran <span class="text-danger">*</span></label>
                    <select name="payment_type_id"
                        id="payment_type_id"
                        class="form-control form-select"
                        required>
                        <option value="">Pilih Metode</option>
                        @foreach ($paymentTypes as $type)
                            <option value="{{ $type->id }}"
                                data-name="{{ strtolower($type->name) }}"
                                {{ isset($card) && $card->payment_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Konfigurasi Cicilan (Hidden by default) --}}
                <div class="col-md-4 mb-3"
                    id="installment-config-area"
                    style="display: none;">
                    <label class="form-label fw-bold">Jumlah Termin</label>
                    <div class="input-group">
                        <input type="number"
                            id="termin-count"
                            class="form-control"
                            min="2"
                            max="12"
                            value="2"
                            placeholder="Masukkan jumlah termin">
                    </div>
                    <small class="text-muted">Masukkan jumlah installment.</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label"
                        for="deadline_at">Tanggal Deadline</label>
                    <input id="deadline_at"
                        class="form-control @error('deadline_at') is-invalid @enderror"
                        name="deadline_at"
                        type="date"
                        value="{{ isset($card) ? $card->deadline_at : old('deadline_at') }}"
                        required />
                    @error('deadline_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- C. TABEL JADWAL PEMBAYARAN (Untuk Cicilan) --}}
        <div class="col-md-12"
            id="installment-rows-container"
            style="display: none;">
            <h6 class="fw-bold mb-3">
                <i class="fa fa-calendar"></i> Jadwal Pembayaran
            </h6>
            <div class="table-responsive">
                <table class="table-manual"
                    style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background:#f8f9fa;">
                            <th width="5%"
                                class="text-center">#</th>
                            <th width="15%">Jatuh Tempo</th>
                            <th width="20%">Nominal Tagihan</th>
                            <th width="20%">Komisi</th>
                            <th width="20%">Pajak</th>
                            <th width="20%">Netto</th>
                        </tr>
                    </thead>
                    <tbody id="installment-inputs-body">
                        {{-- Rows generated here via JS --}}
                    </tbody>
                    <tfoot style="background-color: #e9ecef; font-weight:bold;">
                        <tr>
                            <td colspan="2"
                                class="text-end"
                                style="border:1px solid #dee2e6; padding:8px;">Total Rencana:</td>
                            <td class="text-end"
                                id="installment-total-display"
                                style="border:1px solid #dee2e6; padding:8px;">Rp 0</td>
                        </tr>
                        <tr>
                            <td colspan="3"
                                class="text-end"
                                style="border:1px solid #dee2e6; padding:6px;">
                                <small id="installment-diff-display"
                                    class="text-muted">Selisih: Rp 0</small>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-3 p-3 shadow-sm"
        id="preview-premi-card"
        style="display: {{ isset($card) && $card->payment_type_id ? 'block' : 'none' }};">

        <div class="row">
            {{-- A. TABEL PERHITUNGAN PREMI (Seperti di detail) --}}
            <div class="col-md-12 mb-3">
                <h6 class="fw-bold mb-3">
                    <i class="fa fa-calculator"></i> Preview Perhitungan Premi
                </h6>
                <div class="table-responsive">
                    <table class="table-manual"
                        style="width: 100%; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr>
                                {{-- Kolom Share Asuransi --}}
                                <th colspan="3"
                                    style="border:1px solid #dee2e6; padding:8px; background:#f8f9fa; text-align:center; font-weight:bold;">Share Asuransi</th>

                                {{-- Kolom Financials --}}
                                <th rowspan="2"
                                    style="border:1px solid #dee2e6; padding:8px; background:#f8f9fa; text-align:center; font-weight:bold; vertical-align:middle;">Biaya<br>Polis</th>
                                <th rowspan="2"
                                    style="border:1px solid #dee2e6; padding:8px; background:#f8f9fa; text-align:center; font-weight:bold; vertical-align:middle;">Komisi</th>
                                <th rowspan="2"
                                    style="border:1px solid #dee2e6; padding:8px; background:#f8f9fa; text-align:center; font-weight:bold; vertical-align:middle;">Pajak</th>
                                <th rowspan="2"
                                    style="border:1px solid #dee2e6; padding:8px; background:#f8f9fa; text-align:center; font-weight:bold; vertical-align:middle;">Premi<br>Netto</th>
                            </tr>
                            <tr>
                                <th style="border:1px solid #dee2e6; padding:8px; background:#f8f9fa; width:20%; font-weight:bold;">Nama Asuransi</th>
                                <th style="border:1px solid #dee2e6; padding:8px; background:#f8f9fa; width:8%; text-align:center; font-weight:bold;">Share</th>
                                <th style="border:1px solid #dee2e6; padding:8px; background:#f8f9fa; width:15%; text-align:right; font-weight:bold;">Nominal</th>
                            </tr>
                        </thead>
                        <tbody id="preview-table-body">
                            {{-- Diisi otomatis via JavaScript (renderStep5Table) --}}
                        </tbody>
                        <tfoot style="background-color: #e9ecef; font-weight:bold;">
                            <tr>
                                <td colspan="2"
                                    class="text-center"
                                    style="border:1px solid #dee2e6; padding:8px;">TOTAL</td>
                                <td class="text-end"
                                    id="prev-total-premi"
                                    style="border:1px solid #dee2e6; padding:8px;">0</td>
                                <td class="text-end"
                                    id="prev-total-biaya"
                                    style="border:1px solid #dee2e6; padding:8px;">0</td>
                                <td class="text-end"
                                    id="prev-total-komisi"
                                    style="border:1px solid #dee2e6; padding:8px;">0</td>
                                <td class="text-end"
                                    id="prev-total-pajak"
                                    style="border:1px solid #dee2e6; padding:8px;">0</td>
                                <td class="text-end"
                                    id="prev-total-netto"
                                    style="border:1px solid #dee2e6; padding:8px;">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function renderStep5Table(totalPremi, policyFee, totalKomisi, totalPajak, totalNetto) {
            var tbody = $('#preview-table-body');
            tbody.empty();

            var insuranceItems = $('#insurance-list-container .insurance-item');
            var rowCount = insuranceItems.length;

            if (rowCount === 0) {
                tbody.html('<tr><td colspan="7" class="text-center">Belum ada data asuransi</td></tr>');
                // Reset footer jika kosong
                $('#prev-total-premi').text(formatRupiah(0));
                $('#prev-total-biaya').text(formatRupiah(0));
                $('#prev-total-komisi').text(formatRupiah(0));
                $('#prev-total-pajak').text(formatRupiah(0));
                $('#prev-total-netto').text(formatRupiah(0));
                return;
            }

            // Variabel Akumulator untuk Footer (Biar hasil penjumlahan baris akurat)
            var sumPremi = 0;
            var sumKomisi = 0;
            var sumPajak = 0;
            var sumNetto = 0;

            // Ambil base premi murni (Total - Biaya Polis) untuk dasar pembagian share
            var basePremi = totalPremi - policyFee;

            // Loop setiap baris asuransi dari Step 4
            insuranceItems.each(function(index) {
                var inputs = $(this).find('input');
                var name = inputs.eq(0).val();
                var shareStr = inputs.eq(1).val();
                var share = parseFloat(shareStr) / 100;
                var taxInput = $(this).find('.insurance-tax-per-item');
                var rowPajak = parseFloat(taxInput.val()) || 0;
                var rowPremi = basePremi * share;
                var rowBiaya = (index === 0) ? policyFee : 0;
                var globalKomisi = parseFloat($('#commision_bruto').val()) || 0;
                var rowKomisi = globalKomisi * share;

                // Hitung Netto per baris
                // Rumus: Premi - Komisi + Pajak + Biaya Polis (hanya baris pertama)
                // Pajak ditambahkan karena dibayar nasabah dan disetor ke asuransi
                var rowNetto = rowPremi - rowKomisi + rowPajak + rowBiaya;

                // Akumulasi ke Total Footer
                sumPremi += rowPremi;
                sumKomisi += rowKomisi;
                sumPajak += rowPajak;
                sumNetto += rowNetto;

                // Render HTML Row
                var html = '<tr>';
                // Kolom 1-3: Asuransi
                html += '<td class="ps-3">' + name + '</td>';
                html += '<td class="text-center">' + parseFloat(shareStr).toFixed(2) + '%</td>';
                html += '<td class="text-end pe-3">' + formatRupiah(rowPremi) + '</td>';

                // Kolom 4: Biaya Polis (Rowspan)
                if (index === 0) {
                    html += '<td rowspan="' + rowCount + '" class="text-end pe-3 bg-white" style="vertical-align: middle;">' +
                        formatRupiah(policyFee) +
                        '</td>';
                }

                // Kolom 5-7: Financials
                html += '<td class="text-end pe-3">' + formatRupiah(rowKomisi) + '</td>';
                html += '<td class="text-end pe-3">' + formatRupiah(rowPajak) + '</td>';
                html += '<td class="text-end pe-3 fw-bold text-dark">' + formatRupiah(rowNetto) + '</td>';
                html += '</tr>';

                tbody.append(html);
            });

            // Update Footer Totals dengan hasil penjumlahan Loop (Lebih presisi)
            $('#prev-total-premi').text(formatRupiah(sumPremi)); // Premi + Biaya
            $('#prev-total-biaya').text(formatRupiah(policyFee));
            $('#prev-total-komisi').text(formatRupiah(sumKomisi));
            $('#prev-total-pajak').text(formatRupiah(sumPajak));
            $('#prev-total-netto').text(formatRupiah(sumNetto));

            // console.log('Step 5 Table rendered. Totals:', {
            //     premi: sumPremi + policyFee,
            //     biaya: policyFee,
            //     komisi: sumKomisi,
            //     pajak: sumPajak,
            //     netto: sumNetto
            // });
        }

        // Add event listeners to trigger calculation when parameters change
        $(document).ready(function() {

            // Listen to insurance tax inputs (using event delegation for dynamic elements)
            $(document).on('input change', '.insurance-tax-per-item', function() {
                calculateTotalTax();
                update_payment(); // Trigger payment calculation after tax changes
            });

            // Listen to step navigation untuk render Step 5 Table
            $('div.setup-panel div a[href="#step-4"]').on('click', function() {
                // Tunggu animasi selesai baru render
                setTimeout(function() {
                    var totalPremi = parseFloat($('#premi_value').val()) || 0;
                    var policyFee = parseFloat($('#policy_fee').val()) || 0;
                    var totalKomisi = parseFloat($('#commision_bruto').val()) || 0;
                    var totalPajak = parseFloat($('#tax').val()) || 0;
                    var totalNetto = parseFloat($('#insurance_pay').val()) || 0;

                    renderStep5Table(totalPremi, policyFee, totalKomisi, totalPajak, totalNetto);
                }, 100);
            });

            // 1. Hapus #discount dari list ini karena ID itu salah & butuh logic khusus
            $('#object_value, #policy_fee, #premi_percentages, #commision_percentage, #tjh_value, #discount_agent').on('input change', function() {
                update_payment();
            });

            // 2. Tambahkan Listener Khusus Diskon Rupiah (#discount_value)
            // Supaya pas ketik Rupiah -> Hitung Persen -> Masuk ke Nilai Produksi
            $('#discount_value').on('input change', function() {
                sync_discount('value'); // Sinkronisasi: Hitung % dari Rp yang diketik
                update_payment(); // Hitung ulang semua (Nilai Produksi berubah)
            });

            // 3. Tambahkan Listener Khusus Diskon Persen (#discount_percentage)
            // Supaya pas ketik Persen -> Hitung Rupiah -> Masuk ke Nilai Produksi
            $('#discount_percentage').on('input change', function() {
                sync_discount('percentage'); // Sinkronisasi: Hitung Rp dari % yang diketik
                update_payment(); // Hitung ulang semua
            });

            // Initial calculation on page load
            setTimeout(function() {
                update_payment();
            }, 100);

            // Payment Type Change Handler - Auto show/hide installment area
            $('#payment_type_id').on('change', function() {
                var paymentId = $(this).val();
                var name = $(this).find(':selected').data('name') || '';

                if (paymentId) {
                    // Kalau user pilih apapun (tidak kosong), munculkan tabel
                    $('#preview-premi-card').slideDown();

                    // Opsional: Render ulang tabelnya biar angkanya fresh
                    // renderStep5Table(...); 
                } else {
                    // Kalau user pilih "Pilih Metode" (kosong), sembunyikan tabel
                    $('#preview-premi-card').slideUp();
                }

                // Cek keywords installment/cicilan
                if (name.includes('installment') || name.includes('cicilan') || name.includes('termin')) {
                    $('#installment-config-area').slideDown();
                    $('#installment-rows-container').slideDown();

                    // Enable inputs
                    $('#installment-inputs-body input').prop('disabled', false);

                    // Auto generate default 2 rows jika belum ada
                    setTimeout(function() {
                        if ($('#installment-inputs-body').children().length === 0) {
                            generateSchedule();
                        }
                    }, 300);
                } else {
                    $('#installment-config-area').slideUp();
                    $('#installment-rows-container').slideUp();

                    // Disable inputs agar tidak tersubmit ke controller
                    $('#installment-inputs-body input').prop('disabled', true);
                }
            });

            function generateSchedule() {
                var count = parseInt($('#termin-count').val()) || 2;

                var rawPremi = parseFloat($('#premi_value').val()) || 0;
                var rawPolicyFee = parseFloat($('#policy_fee').val()) || 0;
                var discount_agent = parseFloat($('#discount_agent').val()) || 0;
                
                var baseAmount = rawPremi - rawPolicyFee - discount_agent;

                if (count < 1) return;

                var totalKomisi = parseFloat($('#commision_bruto').val()) || 0;
                var totalPajak = parseFloat($('#tax').val()) || 0;

                var amountPerItem = baseAmount / count;
                var komisiPerItem = totalKomisi / count;
                var pajakPerItem = totalPajak / count;

                var currencyLabel = window.currentCurrency || 'Rp';

                var deadlineStr = $('#deadline_at').val();
                var baseDate = deadlineStr ? new Date(deadlineStr) : new Date();

                var tbody = $('#installment-inputs-body');
                tbody.empty();

                for (var i = 0; i < count; i++) {
                    var d = new Date(baseDate);
                    d.setDate(d.getDate() + (i * 30));
                    var dateStr = d.toISOString().split('T')[0];
                    var sequence = i + 1;

                    var paymentAmount = amountPerItem;
                    if (i === 0) {
                        paymentAmount += rawPolicyFee;
                    }

                    var nettoPerItem = paymentAmount - komisiPerItem + pajakPerItem;

                    var html = `
                            <tr class="installment-row">
                                <td class="text-center align-middle fw-bold" style="border:1px solid #dee2e6;">
                                    ${sequence}
                                    <input type="hidden" name="installments[${i}][sequence]" value="${sequence}">
                                </td>
                                
                                <td style="border:1px solid #dee2e6;">
                                    <input type="date" name="installments[${i}][due_date]" class="form-control form-control-sm" value="${dateStr}" required>
                                </td>
                                
                                {{-- Input Nominal Tagihan --}}
                                <td style="border:1px solid #dee2e6;">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">${currencyLabel}</span>
                                        <input type="number" 
                                               name="installments[${i}][amount]" 
                                               class="form-control form-control-sm installment-amount-input fw-bold text-end" 
                                               value="${paymentAmount.toFixed(2)}" 
                                               step="0.01" required>
                                        <input type="hidden" 
                                               name="installments[${i}][netto]" 
                                               class="installment-netto-input" 
                                               value="${nettoPerItem.toFixed(2)}">
                                    </div>
                                </td>

                                {{-- Kolom Komisi (Read Only) --}}
                                <td class="text-end small align-middle val-komisi" style="border:1px solid #dee2e6;">
                                    ${formatRupiah(komisiPerItem)}
                                </td>

                                {{-- Kolom Pajak (Read Only) --}}
                                <td class="text-end small align-middle val-pajak" style="border:1px solid #dee2e6;">
                                    ${formatRupiah(pajakPerItem)}
                                </td>

                                {{-- Kolom Netto (Read Only) --}}
                                <td class="text-end small align-middle fw-bold val-netto" style="border:1px solid #dee2e6;">
                                    ${formatRupiah(nettoPerItem)}
                                </td>
                            </tr>
                        `;
                    tbody.append(html);
                }
                recalcInstallmentTotal();
            }

            // Generate Input Rows
            $('#btn-generate-schedule').on('click', function() {
                generateSchedule();
            });

            $('#termin-count').on('input change', function() {
                // Gunakan debounce (delay dikit) biar gak render setiap ketikan keyboard
                clearTimeout(window.terminTimeout);
                window.terminTimeout = setTimeout(function() {
                    if ($('#installment-config-area').is(':visible')) {
                        generateSchedule();
                    }
                }, 100); // Delay 0.5 detik
            });

            // Recalculate Total on input change (Manual Edit)
            $(document).on('input', '.installment-amount-input', function() {
                recalcInstallmentTotal();
            });

            function recalcInstallmentTotal() {
                var sumAmount = 0;

                // Ambil total master dari Step 4
                var totalKomisi = parseFloat($('#commision_bruto').val()) || 0;
                var totalPajak = parseFloat($('#tax').val()) || 0;

                var rowCount = $('.installment-amount-input').length;

                $('.installment-amount-input').each(function(index) {
                    var val = parseFloat($(this).val()) || 0;
                    sumAmount += val;

                    // Hitung ulang rincian per baris (Dibagi rata sesuai jumlah row)
                    var rowKomisi = totalKomisi / rowCount;
                    var rowPajak = totalPajak / rowCount;

                    // Netto per baris = Payment Amount - Komisi + Pajak
                    // Payment Amount untuk row pertama sudah termasuk policy_fee
                    var rowNetto = val - rowKomisi + rowPajak;

                    // Update Text di Kolom Rincian masing-masing
                    var $row = $(this).closest('tr');
                    $row.find('.val-komisi').text(formatRupiah(rowKomisi));
                    $row.find('.val-pajak').text(formatRupiah(rowPajak));
                    $row.find('.val-netto').text(formatRupiah(rowNetto));
                    
                    // Update hidden input netto
                    $row.find('.installment-netto-input').val(rowNetto.toFixed(2));
                });

                // Update Total & Selisih Tagihan
                var rawPremi = parseFloat($('#premi_value').val()) || 0; // premi_value = production_value + policy_fee
                var discount_agent = parseFloat($('#discount_agent').val()) || 0;
                
                // Total yang harus dibayar = premi_value - discount_agent (sesuai client_pay formula)
                var expectedTotal = rawPremi - discount_agent;

                var diff = expectedTotal - sumAmount;

                $('#installment-total-display').text(formatRupiah(sumAmount));

                var diffEl = $('#installment-diff-display');

                if (Math.abs(diff) < 1) {
                    diffEl.text('Selisih: 0 (Pas)');
                    diffEl.removeClass('text-danger').addClass('text-success');
                } else {
                    diffEl.text('Selisih: ' + formatRupiah(diff));
                    diffEl.removeClass('text-success').addClass('text-danger');
                }
            }

            $(document).on('input change', '.insurance-percentage', function() {
                updatePercentageTotal('.insurance-percentage', '#total-percentage', '#percentage-info');
            });

            $(document).on('change', '.is-main-checkbox', function() {
                if (this.checked) {
                    $('.is-main-checkbox').not(this).prop('checked', false);
                }
            });

            if ($('#policy_id').val()) {
                var selectedOption = $('#policy_id').find('option:selected');
                var currencyCode = selectedOption.data('currency');
                updateCurrencyLabel(currencyCode);
            }

            // Auto set deadline_at to +30 days from policy started_date
            $('#policy_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var startedDateStr = selectedOption.data('started-date');
                
                if (startedDateStr) {
                    var startDate = new Date(startedDateStr);
                    startDate.setDate(startDate.getDate() + 30);
                    
                    // Format to YYYY-MM-DD for input type="date"
                    var year = startDate.getFullYear();
                    var month = String(startDate.getMonth() + 1).padStart(2, '0');
                    var day = String(startDate.getDate()).padStart(2, '0');
                    var deadlineStr = year + '-' + month + '-' + day;
                    
                    $('#deadline_at').val(deadlineStr);
                }
            });

            // Listen to started_date input change (for new policy form)
            $(document).on('change', '#started_date', function() {
                var startedDateStr = $(this).val();
                
                if (startedDateStr) {
                    var startDate = new Date(startedDateStr);
                    startDate.setDate(startDate.getDate() + 30);
                    
                    var year = startDate.getFullYear();
                    var month = String(startDate.getMonth() + 1).padStart(2, '0');
                    var day = String(startDate.getDate()).padStart(2, '0');
                    var deadlineStr = year + '-' + month + '-' + day;
                    
                    $('#deadline_at').val(deadlineStr);
                }
            });

            // Remove validation error styling when user changes the field
            $(document).on('change', '#ended_date, #deadline_at', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback:not([data-original])').remove();
            });

            // Real-time validation: Deadline tidak lebih awal dari Periode Awal
            $(document).on('change', '#deadline_at', function() {
                var startedDateStr = null;
                
                // Ambil started_date dari dropdown atau input
                if ($('#policy_id').val()) {
                    var selectedOption = $('#policy_id').find('option:selected');
                    startedDateStr = selectedOption.data('started-date');
                }
                if (!startedDateStr && $('#started_date').val()) {
                    startedDateStr = $('#started_date').val();
                }

                var deadlineStr = $(this).val();

                if (startedDateStr && deadlineStr) {
                    var startDate = new Date(startedDateStr);
                    var deadlineDate = new Date(deadlineStr);

                    if (deadlineDate < startDate) {
                        $(this).addClass('is-invalid');
                        
                        // Tampilkan pesan error jika belum ada
                        if (!$(this).siblings('.invalid-feedback').length) {
                            $(this).after('<div class="invalid-feedback d-block">Tanggal Deadline tidak boleh lebih awal dari Periode Awal Pertanggungan</div>');
                        }
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).siblings('.invalid-feedback:not([data-original])').remove();
                    }
                }
            });

            // Trigger on page load if policy already selected
            if ($('#policy_id').val()) {
                $('#policy_id').trigger('change');
            }
        });
    </script>
@endpush
