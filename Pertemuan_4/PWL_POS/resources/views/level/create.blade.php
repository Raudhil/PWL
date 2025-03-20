@extends('layouts.template')

@section('content')
    <div class="container">
        <h2>Tambah Level</h2>
        <form action="{{ url('level') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Kode Level</label>
                <input type="text" name="level_kode" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama Level</label>
                <input type="text" name="level_nama" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ url('level') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
