<div id="existing-currency">
    {{-- Loading State --}}
    <div wire:loading
        class="alert alert-info"
        role="alert">
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-2"
                role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span>Memuat detail mata uang...</span>
        </div>
    </div>

    @if ($currency)
        <div wire:loading.remove
            class="border py-2"
            role="alert">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <td style="width: 200px;"><strong>Kode</strong></td>
                        <td>{{ $currency->code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $currency->name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        @if (!$notFound || $currencyCode == '')
            <div wire:loading.remove
                class="alert alert-secondary"
                role="alert">
                Pilih mata uang untuk melihat detail!
            </div>
        @endif
    @endif

    @push('scripts')
        <script>
            // Re-initialize feather icons after Livewire updates
            document.addEventListener('livewire:init', function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });

            document.addEventListener('livewire:navigated', () => {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        </script>
    @endpush
</div>
