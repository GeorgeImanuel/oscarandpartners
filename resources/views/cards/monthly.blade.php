@extends('layouts.master')

@section('title')
    Kartu Produksi - {{ $month }} {{ $year }}
@endsection

@push('css')
    <link rel="stylesheet"
        type="text/css"
        href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Kartu Produksi {{ $month }} {{ $year }}</h3>
        @endslot
        <li class="breadcrumb-item">Kartu Produksi</li>
        <li class="breadcrumb-item active">{{ $month }} {{ $year }}</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Kartu Produksi Jatuh Tempo - {{ $month }} {{ $year }}</h5>
                            <div>
                                <a href="{{ route('cards.index') }}"
                                    class="btn btn-secondary">
                                    <i class="fa fa-list"></i> Semua Kartu
                                </a>
                            </div>
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
            <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        @endpush
    @endsection
