@extends('layouts.master')

@section('title')
    Tambah Claim
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Tambah Claim</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('claims.index') }}">Claim</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <form action="{{ route('claims.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        {{-- 1. No Polis --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No. Polis <span class="text-danger">*</span></label>
                                <select name="policy_id" class="form-select select2" required>
                                    <option value="">Pilih Polis</option>
                                    @foreach($policies as $policy)
                                        <option value="{{ $policy->id }}" {{ old('policy_id') == $policy->id ? 'selected' : '' }}>
                                            {{ $policy->policy_number }} - {{ $policy->productionCard->client->id_name ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Polis harus dipilih.</div>
                            </div>
                        </div>

                        {{-- 2. Tanggal Kejadian --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                                <input type="date" name="incident_date" class="form-control" 
                                       value="{{ old('incident_date') }}" required>
                                <div class="invalid-feedback">Tanggal kejadian harus diisi.</div>
                            </div>
                        </div>

                        {{-- 3. Tanggal Lapor Asuransi --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lapor Asuransi <span class="text-danger">*</span></label>
                                <input type="date" name="insurance_report_date" class="form-control" 
                                       value="{{ old('insurance_report_date') }}" required>
                                <div class="invalid-feedback">Tanggal lapor harus diisi.</div>
                            </div>
                        </div>

                        {{-- 4. Kronologi --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Kronologi Kejadian <span class="text-danger">*</span></label>
                                <textarea name="chronology" class="form-control" rows="4" required>{{ old('chronology') }}</textarea>
                                <div class="invalid-feedback">Kronologi kejadian harus diisi.</div>
                            </div>
                        </div>

                        {{-- 5. Dokumen --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Dokumen yang Diperlukan <span class="text-danger">*</span></label>
                                <textarea name="docs" class="form-control" rows="3" required>{{ old('docs') }}</textarea>
                                <div class="invalid-feedback">Dokumen yang diperlukan harus diisi.</div>
                            </div>
                        </div>

                        {{-- 6. Casco --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Claim Casco (Nilai Unit) <span class="text-danger">*</span></label>
                                <input type="number" name="casco" class="form-control" 
                                       value="{{ old('casco', 0) }}" min="0" required>
                                <div class="invalid-feedback">Claim casco harus diisi.</div>
                            </div>
                        </div>

                        {{-- 7. TJH --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Claim TJH (Pihak Ke-3) <span class="text-danger">*</span></label>
                                <input type="number" name="tjh" class="form-control" 
                                       value="{{ old('tjh', 0) }}" min="0" required>
                                <div class="invalid-feedback">Claim TJH harus diisi.</div>
                            </div>
                        </div>

                        {{-- 8. Note (Keterangan) --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Keterangan Proses Claim</label>
                                <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                            </div>
                        </div>

                        {{-- 9. Payment Date --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Bayar Claim</label>
                                <input type="date" name="payment_date" class="form-control" 
                                       value="{{ old('payment_date') }}">
                            </div>
                        </div>

                        {{-- 10. Amount (TIDAK WAJIB) --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jumlah Claim</label> {{-- Hapus tanda * --}}
                                <input type="number" name="amount" class="form-control" 
                                       value="{{ old('amount', 0) }}" min="0"> {{-- Hapus required --}}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('claims.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih Polis',
                allowClear: true
            });

            // Form validation
            (function() {
                'use strict';
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        });
    </script>
@endpush
