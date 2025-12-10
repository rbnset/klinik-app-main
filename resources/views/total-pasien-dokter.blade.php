<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Total Pasien Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">

    <h2 class="text-2xl font-bold mb-4">Daftar Pasien Dokter</h2>

    <table class="table-auto w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">No</th>
                <th class="px-4 py-2 border">Nama Pasien</th>
                <th class="px-4 py-2 border">Tanggal Kunjungan</th>
                <th class="px-4 py-2 border">Poli Tujuan</th>
                <th class="px-4 py-2 border">Jenis Pelayanan</th>
                <th class="px-4 py-2 border">Keluhan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftarans as $key => $p)
            <tr>
                <td class="px-4 py-2 border">{{ $key + 1 }}</td>
                <td class="px-4 py-2 border">{{ $p->pasien->nama_pasien ?? '-' }}</td>
                <td class="px-4 py-2 border">{{ $p->tanggal_kunjungan ?? '-' }}</td>
                <td class="px-4 py-2 border">{{ $p->poli_tujuan ?? '-' }}</td>
                <td class="px-4 py-2 border">{{ $p->jenis_pelayanan ?? '-' }}</td>
                <td class="px-4 py-2 border">{{ $p->keluhan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
