<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card card-outline card-primary w-100" style="max-width: 800px;">
        <div class="card card-outline card-primary p-3">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Detail Penjualan #{{ $penjualan->penjualan_kode ?? 'N/A' }}
                </h4>
                <a href="{{ url('penjualan') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <!-- Transaction Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div>
                            <p class="mb-2"><strong><i class="bi bi-calendar me-2"></i>Tanggal:</strong>
                                {{ $penjualan->penjualan_tanggal ? $penjualan->penjualan_tanggal->format('d-m-Y H:i') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="mb-2"><strong><i class="bi bi-person me-2"></i>Pembeli:</strong>
                                {{ $penjualan->pembeli ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="card-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Barang</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @forelse($penjualan->details ?? [] as $index => $detail)
                                    @php
                                        $subtotal = $detail->harga * $detail->jumlah;
                                        $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->barang->barang_nama ?? 'Barang tidak ditemukan' }}</td>
                                        <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $detail->jumlah }}</td>
                                        <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Tidak ada item penjualan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
                                    <th class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Fallback for Bootstrap Icons -->
{{-- <script>
    if (typeof bootstrap === 'undefined') {
        document.querySelectorAll('i[class^="bi-"]').forEach(icon => {
            const iconName = icon.className.match(/bi-(.+)/)?.[1];
            if (iconName) {
                icon.innerHTML = {
                    'receipt': '📋',
                    'calendar': '📅',
                    'person': '👤',
                    'arrow-left': '←'
                } [iconName] || '';
            }
        });
    }
</script> --}}
