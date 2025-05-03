<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 4px 3px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }

        .font-bold {
            font-weight: bold;
        }

        .mb-1 {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ asset('polinema-bw.png') }}" class="image">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN
                    TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341)
                    404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA STOK</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">ID Stok</th>
                <th class="text-center">Supplier</th>
                <th class="text-center">Barang</th>
                <th class="text-center">User</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stoks as $stok)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $stok->stok_id }}</td>
                    <td>{{ $stok->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ $stok->barang->barang_nama ?? '-' }}</td>
                    <td>{{ $stok->user->nama ?? '-' }}</td>
                    <td class="text-center">{{ $stok->stok_jumlah }}</td>
                    <td class="text-center">{{ $stok->stok_tanggal->format('d-m-Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
