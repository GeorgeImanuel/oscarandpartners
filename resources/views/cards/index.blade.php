@extends('layouts.master')

@section('title')
    Kartu Produksi
@endsection

@push('css')
    <link rel="stylesheet"
        type="text/css"
        href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Daftar Kartu Produksi</h3>
        @endslot
        <li class="breadcrumb-item">Kartu Produksi</li>
        <li class="breadcrumb-item active">Daftar</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (auth()->user()->hasRole(['owner', 'admin']))
                                <div class="d-flex gap-2 align-items-center">
                                    <label for="clientFilter"
                                        class="mb-0 me-2">Filter Client:</label>
                                    <select id="clientFilter"
                                        class="form-select"
                                        style="width: 250px;">
                                        <option value="">Semua Client</option>
                                        @forelse($clients as $client)
                                            <option value="{{ $client->id }}"
                                                {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->id_name ?? $client->user->name }}
                                            </option>
                                        @empty
                                            <option value=""
                                                disabled>Tidak ada data client</option>
                                        @endforelse
                                    </select>
                                </div>
                            @endif
                            <div class="d-flex gap-2 align-items-center">
                                <label for="statusFilter"
                                    class="mb-0 me-2">Filter Status:</label>
                                <select id="statusFilter"
                                    class="form-select"
                                    style="width: 250px;">
                                    <option value="">Semua Status</option>
                                    @forelse($cardStatuses as $status)
                                        <option value="{{ $status->id }}"
                                            {{ request('card_status_id') == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @empty
                                        <option value=""
                                            disabled>Tidak ada data status</option>
                                    @endforelse
                                </select>
                            </div>
                            <a href="{{ route('cards.create') }}"
                                class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Tambah Kartu Produksi
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show"
                                role="alert">
                                {{ session('success') }}
                                <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show"
                                role="alert">
                                {{ session('error') }}
                                <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="display"
                                id="basic-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Client</th>
                                        <th>Polis</th>
                                        <th>Periode</th>
                                        <th>Premi Bruto</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cards as $index => $card)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $card->client->id_name ?? $card->client->user->name }}</td>
                                            <td>{{ $card->policy->policy_number ?? '-' }}</td>
                                            <td>{{ $card->policy->started_date && $card->policy->started_date ? $card->policy->started_date->format('d M Y') . ' - ' . $card->policy->ended_date->format('d M Y') : '-' }}</td>
                                            <td>{{ numberFormat($card->object_value) }}</td>
                                            <td>
                                                <span class="badge"
                                                    style="background-color: {{ $card->cardType->color }}">
                                                    {{ $card->cardType->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge"
                                                    style="background-color: {{ $card->cardStatus->color }}">
                                                    {{ $card->cardStatus->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <div style="white-space: nowrap;">
                                                    <a href="{{ route('cards.show', $card->id) }}"
                                                        class="btn btn-info btn-sm me-1"
                                                        title="Detail">
                                                        <i class="fa fa-eye"></i> Detail
                                                    </a>
                                                    {{-- <a href="{{ route('cards.edit', $card->id) }}"
                                                        class="btn btn-warning btn-sm me-1"
                                                        title="Edit">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a> --}}
                                                    <form action="{{ route('cards.destroy', $card->id) }}"
                                                        method="POST"
                                                        style="display: inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm"
                                                            onClick="confirmDelete(this)">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9"
                                                class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script>
            $(document).ready(function() {
				$('#basic-1').DataTable({
					columnDefs: [
						{ targets: [4], className: 'dt-right' },
						{ targets: [5, 6], className: 'dt-center' }
					]
				});

                function buildFilterUrl() {
                    var params = [];
                    var clientId = $('#clientFilter').val();
                    var statusId = $('#statusFilter').val();

                    if (clientId) {
                        params.push('client_id=' + clientId);
                    }
                    if (statusId) {
                        params.push('card_status_id=' + statusId);
                    }

                    var url = "{{ route('cards.index') }}";
                    if (params.length > 0) {
                        url += '?' + params.join('&');
                    }
                    return url;
                }

                $('#clientFilter').on('change', function() {
                    window.location.href = buildFilterUrl();
                });

                $('#statusFilter').on('change', function() {
                    window.location.href = buildFilterUrl();
                });
            });
        </script>
    @endpush

@endsection
