@extends('layouts.master')

@section('title', 'Detail Polis - ' . $policy->policy_number)

@section('content')
<div class="container-fluid">
    {{-- HEADER ACTION --}}
    <div>
        <div class="col-12">
            <div>
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Detail Polis: <span>{{ $policy->policy_number }}</span></h4>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('policies.index') }}" class="btn btn-info">
                            <i class="fa fa-arrow-left me-2"></i>Kembali
                        </a>
                        <a href="{{ route('policies.edit', $policy->id) }}" class="btn btn-warning">
                            <i class="fa fa-pencil me-2"></i>Edit Polis
                        </a>
                        @if($policy->productionCard)
                            <a href="{{ route('cards.show', $policy->productionCard->id) }}" class="btn btn-primary">
                                <i class="fa fa-vcard-o me-2"></i>Lihat Kartu
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- CARD 1: INFORMASI UTAMA --}}
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5><i class="fa fa-file-text-o me-2"></i>Informasi Polis</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th width="40%">Nomor Polis</th>
                                <td><strong>{{ $policy->policy_number }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tertanggung (Client)</th>
                                <td>{{ $policy->productionCard->client->id_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Periode Asuransi</th>
                                <td>{{ $policy->started_date->format('d/m/Y') }} - {{ $policy->ended_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Mata Uang</th>
                                <td>{{ $policy->currency_code }} ({{ $policy->currency->name ?? '-' }})</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- CARD: POLICY COVERAGES --}}
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><i class="fa fa-shield me-2"></i>Coverages</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Jenis Jaminan</th>
                                        <th class="text-center">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($policy->policyCoverages as $pc)
                                        <tr>
                                            <td>{{ $pc->coverage->name ?? '-' }}</td>
                                            <td class="text-center">{{ number_format($pc->percentage * 100, 2) }}%</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Tidak ada data coverage.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD: INSURANCES (PENANGGUNG) --}}
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><i class="fa fa-building-o me-2"></i>Insurances</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Perusahaan Asuransi</th>
                                        <th class="text-center">Share</th>
                                        <th class="text-center">Tipe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($policy->policyInsurances as $pi)
                                        <tr>
                                            <td>{{ $pi->insurance->name ?? '-' }}</td>
                                            <td class="text-center">{{ number_format($pi->percentage * 100, 2) }}%</td>
                                            <td class="text-center">
                                                @if($pi->is_main)
                                                    <span class="badge badge-success">Leader</span>
                                                @else
                                                    <span class="badge badge-light text-dark">Co-As</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data asuransi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: RINCIAN OBJEK & TSI --}}
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5><i class="fa fa-cube me-2"></i>Daftar Objek & Nilai Pertanggungan (TSI)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr class="text-center">
                                    {{-- Lebar kolom disesuaikan karena keterangan sudah pindah ke sini --}}
                                    <th width="30%">Nama Objek & Keterangan</th>
                                    <th width="20%">Okupasi</th>
                                    <th width="25%">Interest Pertanggungan</th>
                                    <th width="25%">Nilai Pertanggungan (TSI)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($policy->objects as $object)
                                    @php $rowspan = $object->objectInterests->count(); @endphp
                                    @foreach($object->objectInterests as $index => $interest)
                                    <tr>
                                        @if($index === 0)
                                            <td rowspan="{{ $rowspan }}" class="align-middle">
                                                {{-- Nama Objek Tebal --}}
                                                <strong>{{ $object->name }}</strong>
                                                {{-- Keterangan di bawahnya dengan font lebih kecil dan warna muted --}}
                                                @if($object->keterangan)
                                                    <br>
                                                    <small class="text-muted">{{ $object->keterangan }}</small>
                                                @endif
                                            </td>
                                            <td rowspan="{{ $rowspan }}" class="align-middle text-center">
                                                {{ $object->objectOccupation->name ?? '-' }}
                                            </td>
                                        @endif
                                        <td>{{ $interest->interest->name ?? '-' }}</td>
                                        <td class="text-end font-weight-bold">
                                            {{ number_format($interest->value, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data objek pertanggungan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <th colspan="3" class="text-center">TOTAL KESELURUHAN TSI</th>
                                    <th class="text-end h5 mb-0">
                                        {{ $policy->currency_code }} {{ number_format($policy->getTotalObjectValue(), 2, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection