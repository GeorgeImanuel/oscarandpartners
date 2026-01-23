@extends('layouts.master')

@section('title')
    Edit Claim
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3><i class="icon-pencil me-2"></i>Edit Claim</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('claims.index') }}">Claim</a></li>
        <li class="breadcrumb-item active"><i class="icon-pencil me-2"></i>Edit</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <form action="{{ route('claims.update', $claim->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
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
                                        <option value="{{ $policy->id }}" 
                                            {{ (old('policy_id', $claim->policy_id) == $policy->id) ? 'selected' : '' }}>
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
                                       value="{{ old('incident_date', $claim->incident_date->format('Y-m-d')) }}" required>
                                <div class="invalid-feedback">Tanggal kejadian harus diisi.</div>
                            </div>
                        </div>

                        {{-- 3. Tanggal Lapor Asuransi --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lapor Asuransi <span class="text-danger">*</span></label>
                                <input type="date" name="insurance_report_date" class="form-control" 
                                       value="{{ old('insurance_report_date', $claim->insurance_report_date->format('Y-m-d')) }}" required>
                                <div class="invalid-feedback">Tanggal lapor harus diisi.</div>
                            </div>
                        </div>

                        {{-- 4. Kronologi --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Kronologi Kejadian <span class="text-danger">*</span></label>
                                <textarea name="chronology" class="form-control" rows="4" required>{{ old('chronology', $claim->chronology) }}</textarea>
                                <div class="invalid-feedback">Kronologi kejadian harus diisi.</div>
                            </div>
                        </div>

                        {{-- 5. Dokumen --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Dokumen yang Diperlukan <span class="text-danger">*</span></label>
                                <textarea name="docs" class="form-control" rows="3" required>{{ old('docs', $claim->docs) }}</textarea>
                                <div class="invalid-feedback">Dokumen yang diperlukan harus diisi.</div>
                            </div>
                        </div>

                        {{-- 6. Casco --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Claim Casco (Nilai Unit) <span class="text-danger">*</span></label>
                                <input type="number" name="casco" class="form-control" 
                                       value="{{ old('casco', $claim->casco) }}" min="0" required>
                                <div class="invalid-feedback">Claim casco harus diisi.</div>
                            </div>
                        </div>

                        {{-- 7. TJH --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Claim TJH (Pihak Ke-3) <span class="text-danger">*</span></label>
                                <input type="number" name="tjh" class="form-control" 
                                       value="{{ old('tjh', $claim->tjh) }}" min="0" required>
                                <div class="invalid-feedback">Claim TJH harus diisi.</div>
                            </div>
                        </div>

                        {{-- 8. Note --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Keterangan Proses Claim</label>
                                <textarea name="note" class="form-control" rows="3">{{ old('note', $claim->note) }}</textarea>
                            </div>
                        </div>

                        {{-- 9. Payment Date --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Bayar Claim</label>
                                <input type="date" name="payment_date" class="form-control" 
                                       value="{{ old('payment_date', $claim->payment_date ? $claim->payment_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        {{-- 10. Amount (TIDAK WAJIB) --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jumlah Claim</label>
                                <input type="number" name="amount" class="form-control" 
                                       value="{{ old('amount', $claim->amount) }}" min="0">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('claims.show', $claim->id) }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Perubahan
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