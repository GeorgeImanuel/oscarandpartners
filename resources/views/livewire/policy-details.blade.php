<div>
    {{-- Loading State --}}
    <div wire:loading class="alert alert-info" role="alert">
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span>Memuat detail policy...</span>
        </div>
    </div>

    @if($policy)
        <div wire:loading.remove class="alert alert-light border" role="alert">
            <div class="row">
                <div class="col-md mb-3">
                    <h6><i data-feather="info"></i> Detail Polis</h6>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            @if($policy->policyInsurances->count() > 0)
                            <tr>
                                <td><strong>Insurances</strong></td>
                                <td>
                                    @foreach($policy->policyInsurances as $pi)
                                        <span class="badge badge-info">{{ $pi->insurance->name ?? '-' }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="width: 200px;"><strong>Nomor Polis</strong></td>
                                <td>{{ $policy->policy_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Coverage</strong></td>
                                <td>{{ $policy->coverage->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Objek</strong></td>
                            <td>{{ $policy->object->name ?? '-' }} ({{ $policy->object->objectType->name ?? '-' }})</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>{{ $policy->object->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Start Date</strong></td>
                                <td>{{ $policy->started_date?->format('d F Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>End Date</strong></td>
                                <td>{{ $policy->ended_date?->format('d F Y') ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div wire:loading.remove class="alert alert-secondary" role="alert">
            Pilih policy untuk melihat detail!
        </div>
    @endif

    @push('scripts')
    <script>
        // Re-initialize feather icons after Livewire updates
        document.addEventListener('livewire:load', function () {
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
