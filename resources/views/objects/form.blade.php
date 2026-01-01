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
                        <label class="form-label" for="object_type_id">Tipe Objek <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <select class="form-select @error('object_type_id') is-invalid @enderror" name="object_type_id" id="object_type_id" required style="width: 100%;">
                                    <option value="">- Pilih Tipe Objek -</option>
                                    @foreach ($objectTypes as $type)
                                        <option value="{{ $type->id }}" {{ isset($object) && $object->object_type_id == $type->id ? 'selected' : (old('object_type_id') == $type->id ? 'selected' : '') }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control @error('object_type_id') is-invalid @enderror" name="object_type_name" id="object_type_name" placeholder="Ketik nama tipe objek baru..." style="display: none; width: 100%;" />
                            </div>
                            <button type="button" class="btn btn-primary" id="toggle-object-type" title="Tambah tipe objek baru">
                                <i class="fa fa-plus"></i> Baru
                            </button>
                        </div>
                        @error('object_type_id')
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

            <!-- Parts Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Komponen Objek (Parts)</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="add-part-btn">
                        <i class="fa fa-plus"></i> Tambah Part
                    </button>
                </div>
                <div class="card-body">
                    <div id="parts-container">
                        @if(isset($object) && $object->objectParts->count() > 0)
                            @foreach($object->objectParts as $index => $objectPart)
                                <div class="part-row mb-3 p-3 border rounded" data-index="{{ $index }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-7 mb-2 mb-md-0">
                                            <label class="form-label">Part {{ $index + 1 }}</label>
                                            <div class="input-group">
                                                <div class="flex-grow-1">
                                                    <select class="form-select part-select" name="part_id[]" style="width: 100%;">
                                                        <option value="">- Pilih Part -</option>
                                                        @foreach($parts as $part)
                                                            <option value="{{ $part->id }}" {{ $objectPart->part_id == $part->id ? 'selected' : '' }}>
                                                                {{ $part->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="form-control part-name" name="part_name[]" placeholder="Ketik nama part baru..." style="display: none; width: 100%;">
                                                </div>
                                                <button class="btn btn-primary toggle-part-input" type="button" title="Tambah part baru">
                                                    <i class="fa fa-plus"></i> Baru
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="form-label">Nilai (Rp)</label>
                                            <input type="number" class="form-control" name="part_value[]" value="{{ $objectPart->value }}" placeholder="0" min="0" step="0.01">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger w-100 remove-part-btn">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div id="empty-state" class="text-center py-4 text-muted" style="{{ (isset($object) && $object->objectParts->count() > 0) ? 'display: none;' : '' }}">
                        <i class="fa fa-info-circle fa-2x mb-2 d-block"></i>
                        <p class="mb-0">Klik tombol <strong class="text-primary">"Tambah Part"</strong> untuk menambahkan komponen objek</p>
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
        let partIndex = {{ isset($object) ? $object->objectParts->count() : 0 }};
        const partsData = @json($parts);

        $(document).ready(function() {
            // Initialize form validation
            initializeFormValidation({
                formSelector: '.needs-validation'
            });

            // Initialize select2
            $('select').not('.part-select').select2({
                placeholder: 'Pilih Item',
                allowClear: true
            });

            // Show error message as SweetAlert if exists (after all initialization)
            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: {!! json_encode(session('error')) !!},
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: {!! json_encode(session('success')) !!},
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif

            // Toggle Object Type: Select <=> Input
            $('#toggle-object-type').on('click', function() {
                const $select = $('#object_type_id');
                const $input = $('#object_type_name');
                const $button = $(this);
                
                if ($input.is(':hidden')) {
                    // Switch to input mode - Tambah Baru
                    $select.next('.select2-container').hide();
                    $select.prop('required', false).prop('disabled', true).val('');
                    $input.show().prop('required', true).prop('disabled', false).focus();
                    $input.attr('placeholder', 'Ketik nama tipe objek baru...');
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
                    $button.attr('title', 'Tambah tipe objek baru');
                }
            });

            // Add Part Button
            $('#add-part-btn').on('click', function() {
                partIndex++;
                const partHtml = createPartRow(partIndex);
                $('#parts-container').append(partHtml);
                $('#empty-state').hide();
                updatePartNumbers();
            });

            // Toggle Part: Select <=> Input
            $(document).on('click', '.toggle-part-input', function() {
                const $btn = $(this);
                const $inputGroup = $btn.closest('.input-group');
                const $select = $inputGroup.find('.part-select');
                const $input = $inputGroup.find('.part-name');
                
                if ($select.is(':visible')) {
                    // Switch to input mode - Tambah Part Baru
                    $select.hide().val('');
                    $input.show().focus();
                    $input.attr('placeholder', 'Ketik nama part baru...');
                    $btn.removeClass('btn-primary').addClass('btn-secondary');
                    $btn.html('<i class="fa fa-list"></i> Pilih');
                    $btn.attr('title', 'Pilih dari daftar yang ada');
                } else {
                    // Switch to select mode - Pilih dari Daftar
                    $input.hide().val('');
                    $select.show();
                    $btn.removeClass('btn-secondary').addClass('btn-primary');
                    $btn.html('<i class="fa fa-plus"></i> Baru');
                    $btn.attr('title', 'Tambah part baru');
                }
            });

            // Remove Part Button
            $(document).on('click', '.remove-part-btn', function() {
                const $partRow = $(this).closest('.part-row');
                
                Swal.fire({
                    title: 'Hapus Part?',
                    text: "Part ini akan dihapus dari daftar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $partRow.remove();
                        updatePartNumbers();
                        
                        if ($('#parts-container .part-row').length === 0) {
                            $('#empty-state').show();
                        }
                    }
                });
            });

            // Form Submit Validation
            $('.needs-validation').on('submit', function(e) {
                const $partRows = $('#parts-container .part-row');
                
                // Check if at least one part exists
                if ($partRows.length === 0) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    Swal.fire({
                        title: 'Error!',
                        text: 'Minimal harus ada 1 komponen objek (part).',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Validate each part
                let isValid = true;
                let errorMsg = '';

                $partRows.each(function(index) {
                    const $row = $(this);
                    const $select = $row.find('.part-select');
                    const $input = $row.find('.part-name');
                    
                    const hasSelectValue = $select.is(':visible') && $select.val();
                    const hasInputValue = $input.is(':visible') && $input.val().trim();
                    
                    if (!hasSelectValue && !hasInputValue) {
                        isValid = false;
                        errorMsg = `Part ${index + 1} harus dipilih atau diisi dengan nama baru.`;
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

        // Create Part Row HTML
        function createPartRow(index) {
            let optionsHtml = '<option value="">- Pilih Part -</option>';
            partsData.forEach(function(part) {
                optionsHtml += `<option value="${part.id}">${part.name}</option>`;
            });

            return `
                <div class="part-row mb-3 p-3 border rounded" data-index="${index}">
                    <div class="row align-items-end">
                        <div class="col-md-7 mb-2 mb-md-0">
                            <label class="form-label">Part ${index}</label>
                            <div class="input-group">
                                <div class="flex-grow-1">
                                    <select class="form-select part-select" name="part_id[]" style="width: 100%;">
                                        ${optionsHtml}
                                    </select>
                                    <input type="text" class="form-control part-name" name="part_name[]" placeholder="Ketik nama part baru..." style="display: none; width: 100%;">
                                </div>
                                <button class="btn btn-primary toggle-part-input" type="button" title="Tambah part baru">
                                    <i class="fa fa-plus"></i> Baru
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="form-label">Nilai (Rp)</label>
                            <input type="number" class="form-control" name="part_value[]" placeholder="0" min="0" step="0.01">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100 remove-part-btn">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Update Part Numbers
        function updatePartNumbers() {
            $('#parts-container .part-row').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('.form-label').first().text(`Part ${index + 1}`);
            });
        }
    </script>
@endpush
