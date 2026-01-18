@extends('layouts.master')

@section('title')
    Create Kartu Produksi
@endsection

@push('css')
    <link rel="stylesheet"
        type="text/css"
        href="{{ asset('assets/css/date-picker.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>{{ isset($card) ? 'Ubah' : 'Tambah' }} Kartu Produksi</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('cards.index') }}">Kartu Produksi</a></li>
        <li class="breadcrumb-item active">{{ isset($card) ? 'Ubah' : 'Tambah' }}</li>
    @endcomponent

    <div class="container-fluid">
        <form class="needs-validation"
            action="{{ isset($card) ? route('cards.update', $card->id) : route('cards.store') }}"
            method="POST"
            novalidate>
            <div class="card">
                @csrf
                @if (isset($card))
                    @method('PUT')
                @endif
                <div class="card-body">
                    <div class="stepwizard mt-3">
                        <div class="stepwizard-row setup-panel">
                            <div class="stepwizard-step"><a class="btn btn-primary"
                                    href="#step-1">1</a>
                                <p>Detail Kartu Produksi</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light"
                                    href="#step-2">2</a>
                                <p>Detail Polis</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light"
                                    href="#step-3">3</a>
                                <p>Detail Objek</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light"
                                    href="#step-4">4</a>
                                <p>Detail Premi & Komisi</p>
                            </div>
                        </div>
                    </div>

                    {{-- CARD INFO START --}}
                    <div id="step-1"
                        class="setup-content">
                        <div class="row g-3">
                            <div class="col-md-12 mt-3">
                                <h5 class="text-primary">Detail Kartu Produksi</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label"
                                    for="card_type_id">Tipe Kartu</label>
                                <select id="card_type_id"
                                    class="form-control basic-select2 @error('card_type_id') is-invalid @enderror"
                                    name="card_type_id"
                                    required>
                                    <option value=""></option>
                                    @foreach ($cardTypes as $cardType)
                                        <option value="{{ $cardType->id }}"
                                            {{ isset($card) ? ($card->card_type_id == $cardType->id ? 'selected' : '') : (old('card_type_id') == $cardType->id ? 'selected' : '') }}>
                                            {{ $cardType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('card_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="parent_card_container"
                                class="col-md-12"
                                style="display:none;">
                                <label class="form-label"
                                    for="parent_card_id">Kartu Produksi Sebelumnya</label>
                                <select id="parent_card_id"
                                    class="form-control select2-basic-single @error('parent_card_id') is-invalid @enderror"
                                    name="parent_card_id">
                                    <option value=""></option>
                                    @foreach ($parentCards as $parentCard)
                                        @if ($parentCard->id == (isset($card) ? $card->id : null))
                                            @continue
                                        @endif
                                        <option value="{{ $parentCard->id }}"
                                            {{ isset($card) ? ($card->parent_card_id == $parentCard->id ? 'selected' : '') : (old('parent_card_id') == $parentCard->id ? 'selected' : '') }}
                                            data-client-id="{{ $parentCard->client_id }}"
                                            data-policy-id="{{ $parentCard->policy_id }}">
                                            {{ $parentCard->policy->policy_number }} -
                                            {{ $parentCard->policy->object->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_card_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"
                                    for="client_id">Client</label>
                                <select id="client_id"
                                    class="form-control select2-basic-single @error('client_id') is-invalid @enderror"
                                    name="client_id"
                                    required>
                                    <option value=""></option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ isset($card) ? ($card->client_id == $client->id ? 'selected' : '') : (old('client_id') == $client->id ? 'selected' : '') }}>
                                            {{ $client->id_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
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
                    {{-- CARD INFO END --}}

                    {{-- POLIS INFO START --}}
                    <div id="step-2"
                        class="setup-content">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5 class="text-primary">Detail Polis</h5>
                                <hr class="separator">
                            </div>
                            <div id="policy-details-container"
                                class="col-md-12">
                                <label class="form-label"
                                    for="policy_id">Nomor Polis</label>
                                <select id="policy_id"
                                    class="form-control policy-select2 @error('policy_id') is-invalid @enderror"
                                    data-existing-id="#existing-policy"
                                    data-existing-name="policy_id"
                                    data-new-id="#new-policy"
                                    data-new-name="policy_number"
                                    data-placeholder="Pilih Polis"
                                    name="policy_id"
                                    required>
                                    <option value=""></option>
                                    @foreach ($policies as $policy)
                                        <option value="{{ $policy->id }}"
                                            {{ isset($card) ? ($card->policy_id == $policy->id ? 'selected' : '') : (old('policy_id') == $policy->id ? 'selected' : '') }}>
                                            {{ $policy->policy_number }} - {{ $policy->object->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('policy_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Exist Policy Detail --}}
                            <div id="policy-details-container"
                                class="col-md-12">
                                @livewire('policy-details', ['policyId' => isset($card) ? $card->policy_id : old('policy_id')])
                            </div>

                            {{-- New Policy Input --}}
                            <div class="col-md-12">
                                @livewire('policy-form')
                            </div>

                        </div>
                    </div>
                    {{-- POLIS INFO END --}}

                    {{-- OBJECT INFO START --}}
                    <div id="step-3"
                        class="setup-content">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5 class="text-primary">Detail Objek</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label"
                                    for="object_id">Nama Objek <span class="text-danger">*</span></label>
                                <select id="object_id"
                                    class="form-control object-select2 @error('object_id') is-invalid @enderror"
                                    data-existing-id="#existing-object"
                                    data-existing-name="object_id"
                                    data-new-id="#new-object"
                                    data-new-name="object_name"
                                    data-placeholder="Pilih Objek"
                                    name="object_id"
                                    required>
                                    <option value=""></option>
                                    @foreach ($objects ?? [] as $object)
                                        <option value="{{ $object->id }}">
                                            {{ $object->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('object_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Exist Policy Detail --}}
                            <div id="object-details-container"
                                class="col-md-12">
                                @livewire('object-details', ['objectId' => isset($card) ? $card->object_id : old('object_id')], key('object-details'))
                            </div>

                            {{-- New Policy Input --}}
                            <div class="col-md-12">
                                @livewire('object-form')
                            </div>
                        </div>
                    </div>
                    {{-- OBJECT INFO END --}}

                    {{-- PREMI PARAMETER START --}}
                    {{-- STEP 4: PARAMETER & PERHITUNGAN --}}
                    <div id="step-4"
                        class="setup-content">

                        {{-- SECTION 1: TOP (Pertanggungan & Parameter Tarif) --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="text-primary mb-3">1. Parameter Dasar</h5>
                            </div>

                            {{-- KIRI: Harga Pertanggungan --}}
                            <div class="col-md-6">
                                <div class="h-100 d-flex flex-column justify-content">
                                    <label class="form-label fw-bold">Harga Pertanggungan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input id="object_value"
                                            class="form-control form-control-lg fw-bold"
                                            name="object_value"
                                            type="number"
                                            value="{{ isset($card) ? $card->object_value : old('object_value', 0) }}"
                                            readonly
                                            style="background-color: #f8f9fa;">
                                    </div>
                                </div>

                            </div>

                            {{-- KANAN: Parameter Tarif (Stacked) --}}
                            <div class="col-md-6">
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <label class="form-label mb-1">Tarif Premi</label>
                                        <div class="input-group">
                                            {{-- Tambahkan class 'validate-percent' dan min/max --}}
                                            <input id="premi_percentages"
                                                class="form-control validate-percent"
                                                name="premi_percentages"
                                                type="number"
                                                step="0.00001"
                                                min="0" max="100"
                                                value="{{ isset($card) ? $card->premi_percentages * 100 : old('premi_percentages', 0) }}"
                                                required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div class="text-danger small percent-alert mt-1" style="display:none;">
                                            <i class="fa fa-exclamation-circle"></i> Nilai harus diantara 0-100
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label mb-1">Nilai TJH</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input id="tjh_value"
                                                class="form-control"
                                                name="tjh_value"
                                                type="number"
                                                value="{{ isset($card) ? $card->tjh_value : old('tjh_value', 0) }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label mb-1">Discount</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    {{-- Tambahkan class 'validate-percent' dan min/max --}}
                                                    <input id="discount_percentage"
                                                        class="form-control validate-percent"
                                                        type="number"
                                                        min="0" max="100"
                                                        name="discount_percentage"
                                                        value="{{ isset($card) ? $card->discount_percentage : old('discount_percentage', 0) }}">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                {{-- ALERT MERAH --}}
                                                <div class="text-danger small percent-alert mt-1" style="display:none;">
                                                    <i class="fa fa-exclamation-circle"></i> Nilai harus diantara 0-100
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">Rp</span>
                                                    <input id="discount_value"
                                                        class="form-control"
                                                        type="number"
                                                        name="discount_value"
                                                        aria-label="Amount (to the nearest dollar)"
                                                        value="{{ isset($card) ? $card->discount_value : old('discount_value', 0) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- SECTION 2: PREMI (Produksi -> Biaya Polis -> Total) --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="text-primary mb-3">2. Perhitungan Premi</h5>
                            </div>

                            {{-- Flow Horizontal: Produksi + Biaya = Total --}}
                            <div class="col-md-4">
                                <label class="form-label text-muted">Nilai Produksi (Output)</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input id="production_value"
                                        class="form-control bg-light"
                                        name="production_value"
                                        type="number"
                                        value="{{ isset($card) ? $card->production_value : old('production_value', 0) }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-primary">Biaya Polis (Input)</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
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
                                    <span class="input-group-text">Rp</span>
                                    <input id="premi_value"
                                        class="form-control fw-bold bg-light"
                                        name="premi_value"
                                        type="number"
                                        value="{{ isset($card) ? $card->premi : old('premi', 0) }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- SECTION 3: KOMISI --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <h5 class="text-primary mb-2">3. Perhitungan Komisi</h5>
                            </div>

                            {{-- BAGIAN ATAS: Persentase Total & Total Komisi --}}
                            <div class="col-md-12">
                                <label class="form-label">Persentase Total Komisi</label>
                                <div class="input-group">
                                    {{-- Tambahkan class 'validate-percent' dan min/max --}}
                                    <input id="commision_percentages"
                                        class="form-control validate-percent"
                                        name="commision_percentages"
                                        type="number"
                                        step="0.01"
                                        min="0" max="100"
                                        value="{{ isset($card) ? $card->commision_percentages * 100 : old('commision_percentages', 0) }}"
                                        required>
                                    <span class="input-group-text">%</span>
                                </div>
                                {{-- ALERT MERAH --}}
                                <div class="text-danger small percent-alert mt-1" style="display:none;">
                                    <i class="fa fa-exclamation-circle"></i> Nilai harus diantara 0-100
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-muted">Total Komisi (Output)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input id="commision_bruto"
                                        class="form-control bg-light fw-bold"
                                        name="commision_bruto"
                                        type="number"
                                        value="{{ isset($card) ? $card->commision_bruto : old('commision_bruto', 0) }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- BAGIAN TENGAH: KOTAK RINCIAN ASURANSI (THE BOX) --}}
                            <div class="col-md-12 mt-4">
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
                                                    <div class="row g-2 align-items-center mb-2 insurance-item">
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
                                                            <div class="input-group">
                                                                <span class="input-group-text">Rp</span>
                                                                <input type="number"
                                                                    class="form-control form-control-sm insurance-tax-per-item"
                                                                    data-insurance-id="{{ $polIns->id }}"
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
                                    <span class="input-group-text">Rp</span>
                                    <input id="tax"
                                        class="form-control bg-light"
                                        name="tax"
                                        type="number"
                                        value="{{ isset($card) ? $card->tax : old('tax', 0) }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Discount Agent</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input id="discount_agent"
                                        class="form-control"
                                        name="discount_agent"
                                        type="number"
                                        value="{{ isset($card) ? $card->discount_agent : old('discount_agent', 0) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted">Komisi Bersih</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input id="commision_netto"
                                        class="form-control bg-light"
                                        name="commision_netto"
                                        type="number"
                                        value="{{ isset($card) ? $card->commision_netto : old('commision_netto', 0) }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Pembayaran Section -->
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
                    {{-- END STEP 4 --}}

                </div>
                <div class="card-footer	">
                    <div class="row no-gutters">
                        <div class="col-md">
                            <a class="btn btn-light"
                                href="{{ route('cards.index') }}">Kembali</a>
                        </div>
                        <div class="col-md-auto">
                            <span class="btn btn-outline-primary ms-3 mb-2 stepwizard-prev">Sebelumnya</span>
                            <span class="btn btn-primary ms-3 mb-2 stepwizard-next">Selanjutnya</span>
                            <button class="btn btn-primary ms-3 mb-2 stepwizard-submit"
                                type="submit"
                                style="display: none;">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('css')
        <link rel="stylesheet"
            type="text/css"
            href="{{ asset('assets/css/select2.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
        <script src="{{ asset('assets/js/form-validation.js') }}"></script>
        <script src="{{ asset('assets/js/formula.js') }}"></script>
        <script src="{{ asset('assets/js/multiple-item.js') }}"></script>
        <script src="{{ asset('assets/js/form-wizard/form-wizard-two.js') }}"></script>

        <script>
            initializeFormValidation({
                customValidator: function(form, fields) {
                    var errors = [];
                    // Example: Check if every location has at least 1 BoQ entry
                    var insurance_percentages = $(form).find('.insurance-percentage');
                    var totalPercentage = 0;
                    insurance_percentages.each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            var value = parseFloat($(this).val()) || 0;
                            totalPercentage += value;
                        }
                    });

                    if (totalPercentage < 0 || totalPercentage > 100) {
                        alertWarning('Total persentase asuransi harus antara 0-100%. Saat ini: ' + totalPercentage.toFixed(2) + '%', '');
                        errors.push({
                            field: '.insurance-percentage',
                            message: 'Total persentase asuransi harus antara 0-100%'
                        });
                    };

                    // Check if at least one is-main-checkbox is checked
                    var hasMainInsurance = false;
                    var hasInsurances = false;

                    $('#new-policy .is-main-checkbox').each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            hasInsurances = true;
                            if ($(this).is(':checked')) {
                                hasMainInsurance = true;
                            }
                        }
                    });

                    // Only validate if new policy form is visible and has insurance items
                    if ($('#new-policy').is(':visible') && hasInsurances && !hasMainInsurance) {
                        alertWarning('Minimal satu asuransi harus dipilih sebagai asuransi utama', '');
                        errors.push({
                            field: '.is-main-checkbox',
                            message: 'Minimal satu asuransi harus dipilih sebagai asuransi utama'
                        });
                    }

                    return {
                        isValid: errors.length === 0,
                        errors: errors
                    };
                },
            });

            initCustomSelect2("#policy_id");
            initCustomSelect2("#object_id");
            initCustomSelect2(".add-select2");

            // Toggle parent_card_id based on card_status_id
            $('#card_type_id').on('change', function() {
                var cardTypeId = $('#card_type_id').val();
                var parentContainer = $('#parent_card_container');
                var parentSelect = $('#parent_card_id');

                if (cardTypeId == '{{ config('constants.card_type.renewal') }}') {
                    parentContainer.show();
                    parentSelect.attr('required', true);

                    parentContainer.find('.required').attr('required', true);

                } else {
                    parentContainer.hide();
                    parentSelect.removeAttr('required');
                    parentSelect.val('').trigger('change'); // Clear selection

                    parentContainer.find('.required').removeAttr('required');
                }
            });

            $('#parent_card_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var clientId = selectedOption.data('client-id') || '';
                // var policyId = selectedOption.data('policy-id') || '';

                // Set the client_id select2 value
                $('#client_id').val(clientId).trigger('change');

                // Set the policy_id select2 value
                // $('#policy_id').val(policyId).trigger('change');
            });

            // Listen to policy_id dropdown change and update Livewire component
            $('#policy_id').on('change', function() {
                var policyId = $(this).val();
                $('#object_id').val('').trigger('change').prop('disabled', false);
                $('#new-object').hide();
                $('#existing-object').show();
                console.log('Policy ID changed:', policyId);

                // Find the Livewire component and update policyId
                var container = document.querySelector('#policy-details-container [wire\\:id]');
                if (container && typeof window.Livewire !== 'undefined') {
                    var livewireComponent = window.Livewire.find(container.getAttribute('wire:id'));
                    if (livewireComponent) {
                        livewireComponent.set('policyId', policyId);
                    }
                }
            });

            // Listen to object_id dropdown change and update Livewire component
            $('#object_id').on('change', function() {
                var objectId = $(this).val();
                console.log('Object ID changed:', objectId);

                var container = document.querySelector('#object-details-container [wire\\:id]');
                if (container && typeof window.Livewire !== 'undefined') {
                    var livewireComponent = window.Livewire.find(container.getAttribute('wire:id'));
                    if (livewireComponent) {
                        livewireComponent.set('objectId', objectId);
                    }
                }
            });

            function updateObjectValue() {
                var totalValue = 0;
                $('input[name="value"]').each(function() {
                    var value = parseInt($(this).val(), 10) || 0;
                    totalValue += value;
                });
                $('#object_value').val(totalValue);
                $('#object_value_input').val(totalValue);
                update_payment();
            }

            // Listen for policyLoaded and objectLoaded events from Livewire (outside jQuery scope)
            document.addEventListener('livewire:init', () => {
                Livewire.on('policyLoaded', (event) => {
                    const objectId = event.objectId || event[0]?.objectId || null;

                    if (objectId) {
                        // Set value and disable the Select2 dropdown
                        $('#object_id').val(objectId).trigger('change');
                        $('#object_id').prop('disabled', true);

                        // Add hidden input to ensure value is submitted
                        if (!$('#object_id_hidden').length) {
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'object_id_hidden',
                                name: 'object_id',
                                value: objectId
                            }).insertAfter('#object_id');
                        }
                    }
                });

                Livewire.on('insurancesLoaded', (event) => {
                    const insurances = event.insurances || event[0]?.insurances || [];
                    console.log('Insurances loaded:', insurances);

                    const container = $('#insurance-list-container');
                    container.empty();

                    if (insurances.length > 0) {
                        insurances.forEach((insurance, index) => {
                            const html = `
                                <div class="row g-2 align-items-center mb-2 insurance-item">
                                    <div class="col-md-5">
                                        <label class="small text-muted mb-0">Asuransi</label>
                                        <input type="text" class="form-control form-control-sm bg-white" 
                                            value="${insurance.insurance_name}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-0">Share</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control text-center bg-white" 
                                                value="${(insurance.percentage * 100).toFixed(2)}" readonly>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-0 fw-bold">Pajak (Input)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control form-control-sm insurance-tax-per-item" 
                                                data-insurance-id="${insurance.id}" 
                                                placeholder="0"
                                                value="0">
                                        </div>
                                    </div>
                                </div>
                                ${index < insurances.length - 1 ? '<hr class="my-2 text-muted">' : ''}
                            `;
                            container.append(html);
                        });
                    } else {
                        container.html('<div class="text-center text-muted small" id="no-insurance-msg">Tidak ada data asuransi terhubung.</div>');
                    }
                });

                Livewire.on('objectLoaded', (event) => {
                    const object = event.object || event[0]?.object || 0;

                    if (object) {
                        $('#object_value').val(object.object_value);
                    }
                    console.log('Object received:', object.object_value);
                    update_payment(); // Trigger calculation when policy is loaded

                });
            });

            function sync_discount(source) {
                var object_value = parseFloat($('#object_value').val()) || 0;
                var premi_percentages = parseFloat($('#premi_percentages').val()) || 0;
                var tjh_value = parseFloat($('#tjh_value').val()) || 0;

                // Hitung Premi Dasar (Sebelum Diskon) untuk basis perhitungan persen
                // Rumus: (Harga Pertanggungan * Tarif) + TJH
                var base_premium = (object_value * (premi_percentages / 100)) + tjh_value;

                if (base_premium > 0) {
                    if (source === 'percentage') {
                        // User isi persen, hitung rupiah
                        var disc_pct = parseFloat($('#discount_percentage').val()) || 0;
                        var disc_val = base_premium * (disc_pct / 100);
                        $('#discount_value').val(disc_val.toFixed(2)); // Update Input Rupiah
                    } else if (source === 'value') {
                        // User isi rupiah, hitung persen
                        var disc_val = parseFloat($('#discount_value').val()) || 0;
                        var disc_pct = (disc_val / base_premium) * 100;
                        $('#discount_percentage').val(disc_pct.toFixed(2)); // Update Input Persen
                    }
                }
            }

            function update_payment() {
                var object_value = parseInt($('#object_value').val()) || 0;
                var premi_percentages = parseFloat($('#premi_percentages').val()) || 0;
                var policy_fee = parseInt($('#policy_fee').val()) || 0;
                var commision_percentages = parseFloat($('#commision_percentages').val()) || 0;
                var discount = parseInt($('#discount_value').val()) || 0;
                var tjh_value = parseInt($('#tjh_value').val()) || 0;
                var discount_agent = parseInt($('#discount_agent').val()) || 0;

                const productionValue = production_value(object_value, premi_percentages / 100, tjh_value, discount);
                const premiValue = premi_value(productionValue, policy_fee);
                const commisionBruto = commision_bruto(productionValue, commision_percentages / 100, discount);
                const taxValue = parseFloat($('#tax').val()) || 0; // Get current tax value from field
                const commisionNetto = commision_netto(commisionBruto, taxValue, discount_agent);
                const clientPay = client_pay(premiValue, discount_agent);
                const insurancePay = insurance_pay(premiValue, commisionNetto);

                console.log('Calculated values:', {
                    productionValue,
                    discount,
                    premiValue,
                    commisionBruto,
                    taxValue,
                    commisionNetto,
                    clientPay,
                    insurancePay
                });

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
                console.log('Total Tax calculated:', totalTax);
                return totalTax; // Return the value for use in update_payment
            }

            // Format number to Rupiah
            function formatRupiah(value) {
                var number = parseFloat(value) || 0;
                return 'Rp' + number.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // --- FUNGSI BARU: VALIDASI PERSEN LANGSUNG ---
            function setupPercentValidation() {
                // Saat mengetik (input/keyup)
                $('.validate-percent').on('input keyup change', function() {
                    var value = parseFloat($(this).val());
                    // Cari pesan error di bawah input ini
                    var alertBox = $(this).closest('div[class^="col-"]').find('.percent-alert');

                    // Reset dulu (hapus merah & sembunyikan pesan)
                    $(this).removeClass('is-invalid border-danger');
                    alertBox.hide();

                    // Validasi
                    if (value < 0) {
                        $(this).val(0); // Auto fix ke 0
                    } else if (value > 100) {
                        // Munculkan merah & pesan error
                        $(this).addClass('is-invalid border-danger');
                        alertBox.slideDown('fast'); 
                    }
                });

                // Saat pindah kolom (Blur) -> Paksa balik ke 100 jika masih salah
                $('.validate-percent').on('blur', function() {
                    var value = parseFloat($(this).val());
                    if (value > 100) {
                        $(this).val(100).trigger('change'); // Paksa 100
                        $(this).removeClass('is-invalid border-danger');
                        $(this).closest('div[class^="col-"]').find('.percent-alert').hide();
                    }
                });
            }

            // Add event listeners to trigger calculation when parameters change
            $(document).ready(function() {
                setupPercentValidation();
                
                // Listen to insurance tax inputs (using event delegation for dynamic elements)
                $(document).on('input change', '.insurance-tax-per-item', function() {
                    calculateTotalTax();
                    update_payment(); // Trigger payment calculation after tax changes
                });

               // 1. Hapus #discount dari list ini karena ID itu salah & butuh logic khusus
                $('#object_value, #policy_fee, #premi_percentages, #commision_percentages, #tjh_value, #discount_agent').on('input change', function() {
                    update_payment();
                });

                // 2. Tambahkan Listener Khusus Diskon Rupiah (#discount_value)
                // Supaya pas ketik Rupiah -> Hitung Persen -> Masuk ke Nilai Produksi
                $('#discount_value').on('input change', function() {
                    sync_discount('value'); // Sinkronisasi: Hitung % dari Rp yang diketik
                    update_payment();       // Hitung ulang semua (Nilai Produksi berubah)
                });

                // 3. Tambahkan Listener Khusus Diskon Persen (#discount_percentage)
                // Supaya pas ketik Persen -> Hitung Rupiah -> Masuk ke Nilai Produksi
                $('#discount_percentage').on('input change', function() {
                    sync_discount('percentage'); // Sinkronisasi: Hitung Rp dari % yang diketik
                    update_payment();            // Hitung ulang semua
                });

                // Initial calculation on page load
                setTimeout(function() {
                    update_payment();
                }, 100);

                // Validate insurance percentage sum before form submission
                $('.needs-validation').on('submit', function(e) {
                    var totalPercentage = 0;
                    var hasInsurances = false;

                    // Calculate sum of all visible insurance percentages
                    $('#new-policy .insurance-percentage').each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            hasInsurances = true;
                            var value = parseFloat($(this).val()) || 0;
                            totalPercentage += value;
                        }
                    });

                    // Check if new policy form is visible and has insurances
                    if ($('#new-policy').is(':visible') && hasInsurances) {
                        if (totalPercentage < 0 || totalPercentage > 100) {
                            e.preventDefault();
                            e.stopPropagation();

                            Swal.fire({
                                title: 'Validasi Gagal',
                                text: 'Total persentase asuransi harus antara 0-100%. Saat ini: ' + totalPercentage.toFixed(2) + '%',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }
                    }
                });

                // Update total percentage display in real-time
                $(document).on('input change', '.insurance-percentage', function() {
                    var totalPercentage = 0;
                    $('.insurance-percentage').each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            var value = parseFloat($(this).val()) || 0;
                            totalPercentage += value;
                        }
                    });
                    $('#total-percentage').text(totalPercentage.toFixed(2));
                    var infoDiv = $('#percentage-info');
                    if (totalPercentage > 100 || totalPercentage < 0) {
                        infoDiv.attr('class', 'alert alert-danger');
                    } else {
                        infoDiv.attr('class', 'alert alert-success');
                    }
                });

                $(document).on('change', '.is-main-checkbox', function() {
                    if (this.checked) {
                        $('.is-main-checkbox').not(this).prop('checked', false);
                    }
                });
            });
        </script>
    @endpush
@endsection
