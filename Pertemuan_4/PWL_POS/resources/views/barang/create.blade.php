@extends('layouts.template')

@section('content')
    <div class="container">
        <h2>Tambah Barang</h2>
        <form action="{{ url('barang') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="">- Pilih Kategori -</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Kode Barang</label>
                <input type="text" name="barang_kode" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama Barang</label>
                <input type="text" name="barang_nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Harga Beli</label>
                <input type="number" name="harga_beli" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ url('barang') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
