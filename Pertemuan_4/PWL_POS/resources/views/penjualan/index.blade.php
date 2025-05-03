@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Penjualan</h3>
            <div class="card-tools">
                <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i>
                    Export Penjualan</a>
                <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-sm mt-1 btn-info">Import
                    Penjualan</button>
                <a href="{{ url('/penjualan/export_excel') }}" class="btn btn-sm mt-1 btn-primary"><i
                        class="fa fa-file-excel"></i> Export Penjualan</a>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-penjualan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Penjualan ID</th>
                        <th>User ID</th>
                        <th>Pembeli</th>
                        <th>Kode Penjualan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var tablePenjualan;
        $(document).ready(function() {
            tablePenjualan = $('#table-penjualan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('penjualan/list') }}",
                    dataType: "json",
                    type: "POST"
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'penjualan_id',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'user_id',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'pembeli',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'penjualan_kode',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'penjualan_tanggal',
                        className: '',
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            return formatDate(data);
                        }
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#table-penjualan_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) {
                    tablePenjualan.search(this.value).draw();
                }
            });

            function formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleString('id-ID');
            }
        });
    </script>
@endpush
