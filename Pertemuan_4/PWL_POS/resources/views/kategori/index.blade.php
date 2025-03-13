@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_filter" name="kategori_filter" required>
                                <option value="">- semua -</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih Kategori</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endsection

    @push('css')
    @endpush

    @push('js')
        <script>
            $(document).ready(function() {
                var dataKategori = $('#table_kategori').DataTable({
                    serverSide: true,
                    ajax: {
                        "url": "{{ url('kategori/list') }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d) {
                            d.kategori_filter = $('#kategori_filter').val();
                        }
                    },
                    columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }, {
                        data: "kategori_kode",
                        orderable: true,
                        searchable: true
                    }, {
                        data: "kategori_nama",
                        orderable: true,
                        searchable: true
                    }, {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }]
                });

                $('#kategori_filter').on('change', function() {
                    dataKategori.ajax.reload();
                });
            });
        </script>
    @endpush
