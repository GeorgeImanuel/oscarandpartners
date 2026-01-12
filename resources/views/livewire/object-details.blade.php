<div id="existing-object">
    {{-- Loading State --}}
    <div wire:loading class="alert alert-info" role="alert">
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span>Memuat detail objek...</span>
        </div>
    </div>

    @if ($object)
        <div wire:loading.remove class="border py-2" role="alert">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <td style="width: 200px;"><strong>Nama Objek</strong></td>
                        <td>{{ $object->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipe Objek</strong></td>
                        <td>{{ $object->objectType->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Harga Pertanggungan</strong></td>
                        <td>Rp {{ number_format($object->object_value ?? 0, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>{{ $object->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kota</strong></td>
                        <td>{{ $object->city ?? '-' }}</td>
                    </tr>
                    @if ($object->objectPart && $object->objectPart->count() > 0)
                        <tr>
                            <td><strong>Parts</strong></td>
                            <td>
                                @foreach ($object->objectPart as $objectPartItem)
                                    <span class="badge badge-info">{{ $objectPartItem->part->name ?? '-' }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @else
        @if (!$notFound || $objectId == "")
            <div wire:loading.remove class="alert alert-secondary" role="alert">
                Pilih objek untuk melihat detail!
            </div>
        @endif
    @endif

    @push('scripts')
        <script>
            // Re-initialize feather icons after Livewire updates
            document.addEventListener('livewire:load', function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });

            Livewire.hook('message.processed', (message, component) => {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        </script>
    @endpush
</div>
