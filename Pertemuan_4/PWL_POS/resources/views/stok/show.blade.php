<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card card-outline card-primary w-100" style="max-width: 700px;">
        <div class="card-body">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-box-seam me-2"></i> Detail Stok #{{ $stok->stok_id ?? 'N/A' }}
                </h4>
                <a href="{{ url('stok') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Tutup
                </a>

            </div>

            <!-- Table Content -->
            @empty($stok)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="bi bi-exclamation-triangle-fill me-2"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm mb-0">
                    <tr>
                        <th>Stok ID</th>
                        <td>{{ $stok->stok_id }}</td>
                    </tr>
                    <tr>
                        <th>Supplier ID</th>
                        <td>{{ $stok->supplier_id }}</td>
                    </tr>
                    <tr>
                        <th>Barang ID</th>
                        <td>{{ $stok->barang_id }}</td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td>{{ $stok->user_id }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Stok</th>
                        <td>{{ $stok->stok_jumlah }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Stok</th>
                        <td>{{ $stok->stok_tanggal }}</td>
                    </tr>
                </table>
            @endempty
        </div>
    </div>
</div>
