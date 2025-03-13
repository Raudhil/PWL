@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Tambah Kategori</h2>
    <form action="{{ url('kategori') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Kode Kategori</label>
            <input type="text" name="kategori_kode" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Kategori</label>
            <input type="text" name="kategori_nama" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ url('kategori') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
