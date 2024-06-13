@extends('layouts.app_sneat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">DATA TAGIHAN SPP SISWA {{ strtoupper($periode) }}</h5>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td rowspan="3" width="150" class="align-middle">
                            <img src="{{ \Storage::url($siswa->foto) }}" alt="{{ $siswa->nama }}" width="150">
                        </td>
                        <td width="100">NISN</td>
                        <td>: {{ $siswa->nisn }}</td>
                    </tr>
                    <tr>
                        <td>NAMA</td>
                        <td>: {{ $siswa->nama }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-5">
        <div class="card">
            <h5 class="card-header pb-0">DATA TAGIHAN {{ strtoupper($periode) }}</h5>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Tagihan</th>
                            <th>Jumlah Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tagihan->tagihanDetails as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_biaya }}</td>
                            <td>{{ formatRupiah($item->jumlah_biaya) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan='2' class="text-right">Total Pembayaran</td>
                            <td>{{ formatRupiah($tagihan->tagihanDetails->sum('jumlah_biaya')) }}</td>
                        </tr>
                    </tfoot>
                </table>
                <h5 class="card-header pb-0 px-0">DATA PEMBAYARAN</h5>
                <table class="table table-sm table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>TANGGAL</th>
                            <th>JUMLAH</th>
                            <th>METODE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tagihan->pembayaran as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('kwitansipembayaran.show', $item->id) }}" target="blank"><i class="fa fa-print"></i></a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d/m/Y') }}</td>
                                <td>{{ formatRupiah($item->jumlah_dibayar) }}</td>
                                <td>{{ $item->metode_pembayaran }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h5>Status Pembayaran: {{ strtoupper($tagihan->status) }}</h5>
            </div>

            <h5 class="card-header mt-4">FORM PEMBAYARAN</h5>
            <div class="card-body">
                {!! Form::model($model, ['route' => 'pembayaran.store', 'method' => 'POST']) !!}
                {!! Form::hidden('tagihan_id', $tagihan->id) !!}
                <div class="form-group">
                    <label for="tanggal_bayar">Tanggal Pembayaran</label>
                    {!! Form::date('tanggal_bayar', $model->tanggal_bayar ?? \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
                    <span class="text-danger">{{ $errors->first('tanggal_bayar') }}</span>
                </div>
                <div class="form-group mt-3">
                    <label for="jumlah_dibayar">Jumlah Yang Dibayarkan</label>
                    {!! Form::text('jumlah_dibayar', null, ['class' => 'form-control rupiah']) !!}
                    <span class="text-danger">{{ $errors->first('jumlah_dibayar') }}</span>
                </div>
                {!! Form::submit('SIMPAN', ['class' => 'btn btn-primary mt-3']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <h5 class="card-header">KARTU SPP</h5>
            <div class="card-body">
                Kartu SPP
            </div>
        </div>
    </div>
</div>
@endsection
