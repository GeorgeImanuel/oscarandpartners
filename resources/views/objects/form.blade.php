@extends('layouts.master')

@section('title', 'Object')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
    @slot('breadcrumb_title')
    <h3>{{ isset($object) ? 'Edit' : 'Tambah'  }} Object</h3>
    @endslot
    <li class="breadcrumb-item"><a href="{{ route('objects.index') }}">Object</a></li>
    <li class="breadcrumb-item active">{{ isset($object) ? 'Edit' : 'Tambah'  }}</li>
    @endcomponent

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error Validasi!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($object) ? route('objects.update', $object->id) : route('objects.store') }}">
            @csrf
            @if(isset($object))
                @method('PUT')
            @endif

            <div class="card">
                <div class="card-header">
                    <h5>{{ isset($object) ? 'Edit' : 'Tambah'  }} Object</h5>
                </div>
                <div class="card-body row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="object_occupation_id">Objek Occupation<span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <select class="form-select @error('object_occupation_id') is-invalid @enderror" name="object_occupation_id" id="object_occupation_id" required style="width: 100%;">
                                    <option value="">- Pilih Objek Occupation -</option>
                                    @foreach ($objectOccupations as $occupation)
                                        <option value="{{ $occupation->id }}" {{ isset($object) && $object->object_occupation_id == $occupation->id ? 'selected' : (old('object_occupation_id') == $occupation->id ? 'selected' : '') }}>
                                            {{ $occupation->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control @error('object_occupation_id') is-invalid @enderror" name="object_occupation_name" id="object_occupation_name" placeholder="Ketik nama objek occupation baru..." style="display: none; width: 100%;" />
                            </div>
                            <button type="button" class="btn btn-primary" id="toggle-object-occupation" title="Tambah objek occupation baru">
                                <i class="fa fa-plus"></i> Baru
                            </button>
                        </div>
                        @error('object_occupation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="name">Nama Objek <span class="text-danger">*</span></label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ isset($object) ? $object->name : old('name', '') }}" placeholder="Contoh: Suzuki Ertiga" required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="address">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Contoh: Jl. Buntu no.12, Jakarta Selatan" required>{{ isset($object) ? $object->address : old('address', '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Interests Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Komponen Objek (Interests)</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="add-interest-btn">
                        <i class="fa fa-plus"></i> Tambah Interest
                    </button>
                </div>
                <div class="card-body">
                    <div id="interests-container">
                        @if(isset($object) && $object->objectInterests->count() > 0)
                            @foreach($object->objectInterests as $index => $objectInterest)
                                <div class="interest-row mb-3 p-3 border rounded" data-index="{{ $index }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-7 mb-2 mb-md-0">
                                            <label class="form-label">Interest {{ $index + 1 }}</label>
                                            <div class="input-group">
                                                <div class="flex-grow-1">
                                                    <select class="form-select interest-select" name="interest_id[]" style="width: 100%;">
                                                        <option value="">- Pilih Interest -</option>
                                                        @foreach($Interests as $Interest)
                                                            <option value="{{ $Interest->id }}" {{ $objectInterest->interest_id == $Interest->id ? 'selected' : '' }}>
                                                                {{ $Interest->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="form-control interest-name" name="interest_name[]" placeholder="Ketik nama interest baru..." style="display: none; width: 100%;">
                                                </div>
                                                <button class="btn btn-primary toggle-interest-input" type="button" title="Tambah interest baru">
                                                    <i class="fa fa-plus"></i> Baru
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="form-label">Nilai (Rp)</label>
                                            <input type="number" class="form-control" name="interest_value[]" value="{{ $objectInterest->value }}" placeholder="0" min="0" step="0.00001">
                                        </div>
                                        <div class="col-md-1 pt-3">
                                            <button type="button" class="btn btn-danger btn-xs remove-interest-btn" style="{{ $index == 0 ? 'display:none' : '' }}">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-footer text-end">
                    <a href="{{ route('objects.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Batal
                    </a>
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        let interestIndex = {{ isset($object) ? $object->objectInterests->count() : 0 }};
        const interestsData = @json($Interests);

        $(document).ready(function() {
            // Add default Interest if no Interests exist
            if ($('#interests-container .interest-row').length === 0) {
                interestIndex = 1;
                const interestHtml = createInterestRow(1);
                $('#interests-container').append(interestHtml);
                updateInterestVisibility();
            }

            // Initialize form validation
            initializeFormValidation({
                formSelector: '.needs-validation'
            });

            // Initialize select2
            $('select').not('.interest-select').select2({
                placeholder: 'Pilih Item',
                allowClear: true
            });

            // Toggle Object Occupation: Select <=> Input
            $('#toggle-object-occupation').on('click', function() {
                const $select = $('#object_occupation_id');
                const $input = $('#object_occupation_name');
                const $button = $(this);
                
                if ($input.is(':hidden')) {
                    // Switch to input mode - Tambah Baru
                    $select.next('.select2-container').hide();
                    $select.prop('required', false).prop('disabled', true).val('');
                    $input.show().prop('required', true).prop('disabled', false).focus();
                    $input.attr('placeholder', 'Ketik nama objek occupationbaru...');
                    $button.removeClass('btn-primary').addClass('btn-secondary');
                    $button.html('<i class="fa fa-list"></i> Pilih');
                    $button.attr('title', 'Pilih dari daftar yang ada');
                } else {
                    // Switch to select mode - Pilih dari Daftar
                    $input.hide().prop('required', false).prop('disabled', true).val('');
                    $select.prop('required', true).prop('disabled', false);
                    $select.next('.select2-container').show();
                    $button.removeClass('btn-secondary').addClass('btn-primary');
                    $button.html('<i class="fa fa-plus"></i> Baru');
                    $button.attr('title', 'Tambah objek occupation baru');
                }
            });

            // Add Interest Button
            $('#add-interest-btn').on('click', function() {
                interestIndex++;
                const interestHtml = createInterestRow(interestIndex);
                $('#interests-container').append(interestHtml);
                $('#empty-state').hide();
                updateInterestNumbers();
                updateInterestVisibility();
            });

            // Toggle Interest: Select <=> Input
            $(document).on('click', '.toggle-interest-input', function() {
                const $btn = $(this);
                const $inputGroup = $btn.closest('.input-group');
                const $select = $inputGroup.find('.interest-select');
                const $input = $inputGroup.find('.interest-name');
                
                if ($select.is(':visible')) {
                    // Switch to input mode - Tambah Interest Baru
                    $select.hide().val('');
                    $input.show().focus();
                    $input.attr('placeholder', 'Ketik nama interest baru...');
                    $btn.removeClass('btn-primary').addClass('btn-secondary');
                    $btn.html('<i class="fa fa-list"></i> Pilih');
                    $btn.attr('title', 'Pilih dari daftar yang ada');
                } else {
                    // Switch to select mode - Pilih dari Daftar
                    $input.hide().val('');
                    $select.show();
                    $btn.removeClass('btn-secondary').addClass('btn-primary');
                    $btn.html('<i class="fa fa-plus"></i> Baru');
                    $btn.attr('title', 'Tambah interest baru');
                }
            });

            // Remove Interest Button
            $(document).on('click', '.remove-interest-btn', function() {
                const $interestRows = $('#interests-container .interest-row');
                
                // Prevent removal if only 1 interest left
                if ($interestRows.length <= 1) {
                    Swal.fire({
                        title: 'Tidak dapat menghapus!',
                        text: 'Minimal harus ada 1 interest.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const $interestRow = $(this).closest('.interest-row');
                
                Swal.fire({
                    title: 'Hapus Interest?',
                    text: "Interest ini akan dihapus dari daftar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $interestRow.remove();
                        updateInterestNumbers();
                        updateInterestVisibility();
                    }
                });
            });

            // Form Submit Validation
            $('.needs-validation').on('submit', function(e) {
                const $interestRows = $('#interests-container .interest-row');

                // Validate each interest
                let isValid = true;
                let errorMsg = '';

                $interestRows.each(function(index) {
                    const $row = $(this);
                    const $select = $row.find('.interest-select');
                    const $input = $row.find('.interest-name');

                    const hasSelectValue = $select.is(':visible') && $select.val();
                    const hasInputValue = $input.is(':visible') && $input.val().trim();
                    
                    if (!hasSelectValue && !hasInputValue) {
                        isValid = false;
                        errorMsg = `Interest ${index + 1} harus dipilih atau diisi dengan nama baru.`;
                        return false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    Swal.fire({
                        title: 'Terdapat input tidak valid!',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            });
        });

        // Create Interest Row HTML
        function createInterestRow(index) {
            let optionsHtml = '<option value="">- Pilih Interest -</option>';
            interestsData.forEach(function(interest) {
                optionsHtml += `<option value="${interest.id}">${interest.name}</option>`;
            });

            return `
                <div class="interest-row mb-3 p-3 border rounded" data-index="${index}">
                    <div class="row align-items-end">
                        <div class="col-md-7 mb-2 mb-md-0">
                            <label class="form-label">Interest ${index}</label>
                            <div class="input-group">
                                <div class="flex-grow-1">
                                    <select class="form-select interest-select" name="interest_id[]" style="width: 100%;">
                                        ${optionsHtml}
                                    </select>
                                    <input type="text" class="form-control interest-name" name="interest_name[]" placeholder="Ketik nama interest baru..." style="display: none; width: 100%;">
                                </div>
                                <button class="btn btn-primary toggle-interest-input" type="button" title="Tambah interest baru">
                                    <i class="fa fa-plus"></i> Baru
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="form-label">Nilai (Rp)</label>
                            <input type="number" class="form-control" name="interest_value[]" placeholder="0" min="0" step="0.00001">
                        </div>
                        <div class="col-md-1 pt-3">
                            <button type="button" class="btn btn-danger btn-xs remove-interest-btn">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Update Interest Numbers
        function updateInterestNumbers() {
            $('#interests-container .interest-row').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('.form-label').first().text(`Interest ${index + 1}`);
            });
        }

        // Update Interest Visibility - Hide first interest delete button
        function updateInterestVisibility() {
            const $interestRows = $('#interests-container .interest-row');
            $interestRows.each(function(index) {
                const $deleteBtn = $(this).find('.remove-interest-btn');
                if (index === 0) {
                    $deleteBtn.hide();
                } else {
                    $deleteBtn.show();
                }
            });
        }
    </script>
@endpush
