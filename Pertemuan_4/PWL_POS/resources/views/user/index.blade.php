@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar User</h3>
            <div class="card-tools">
                <a href="{{ url('/user/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-filepdf"></i> Export
                    User</a>
                <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-sm mt-1 btn-info">Import User</button>
                <a href="{{ url('/user/export_excel') }}" class="btn btn-sm mt-1 btn-primary"><i class="fa fa-fileexcel"></i> Export User</a>
                <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="btn btn-sm mt-1 btn-success">Tambah Data
                    (Ajax)</button>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-user">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User ID</th>
                        <th>Level ID</th>
                        <th>Username</th>
                        <th>Nama</th>
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

        var tableUser;
        $(document).ready(function() {
            tableUser = $('#table-user').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('user/list') }}",
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
                        data: 'user_id',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'level_id',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'username',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'nama',
                        className: '',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#table-user_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) {
                    tableUser.search(this.value).draw();
                }
            });
        });
    </script>
@endpush
