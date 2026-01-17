<div id="existing-policy">
    {{-- Loading State --}}
    <div wire:loading
        class="alert alert-info"
        role="alert">
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-2"
                role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span>Memuat detail policy...</span>
        </div>
    </div>

    @if ($policy)
        <div wire:loading.remove
            class="border py-2"
            role="alert">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <td style="width: 200px;"><strong>Nomor Polis</strong></td>
                        <td>{{ $policy->policy_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Coverage</strong></td>
                        <td>{{ $policy->coverage->name ?? '-' }} ({{ $policy->coverage->code ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td><strong>Periode Pertangguhan</strong></td>
                        <td>{{ $policy->started_date?->format('d F Y') ?? '-' }} - {{ $policy->ended_date?->format('d F Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Objek</strong></td>
                        <td>{{ $policy->object->name ?? '-' }} ({{ $policy->object->objectOccupation->name ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td><strong>Keterangan</strong></td>
                        <td>{{ $policy->object->keterangan ?? '-' }}</td>
                    </tr>
                    @if ($policy->policyInsurances->count() > 0)
                        <tr>
                            <td><strong>Asuransi</strong></td>
                            <td>
                                @foreach ($policy->policyInsurances as $pi)
                                    <span class="badge badge-info ms-0 me-2">{{ $pi->insurance->name ?? '-' }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @else
        @if (!$notFound || $policyId == '')
            <div wire:loading.remove
                class="alert alert-secondary"
                role="alert">
                Pilih nomor polis untuk melihat detail!
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
