<!DOCTYPE html>
<html>
<head>
    <title>Kartu Pasien</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .box { border: 1px solid #000; padding: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: left; }
    </style>
</head>
<body>

<div class="header">
    <h3>Praktek Mandiri Bidan Puji Susanti</h3>
    <p>Karongan RT.03 / RW.11 Jogotirto, Kecamatan Berbah, Kabupaten Sleman, Daerah Istimewa Yogyakarta</p>
</div>

<div class="box">
    <p><strong>Nama:</strong> {{ $pasien->nama_pasien }}</p>
    <p><strong>Umur:</strong> {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun</p>
    <p><strong>Alamat:</strong> {{ $pasien->alamat }}</p>
    <p><strong>Alergi Obat:</strong> {{ $pasien->alergi_obat ?? '-' }}</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 20%">Tanggal</th>
            <th style="width: 30%">Pemeriksaan / Diagnosa</th>
            <th style="width: 30%">Terapi</th>
            <th style="width: 20%">Keterangan</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($pasien->rekamMedis as $rm)
        <tr>
            <td>{{ $rm->tanggal }}</td>
            <td>
                <strong>Diagnosa:</strong> {{ $rm->diagnosa?->nama_diagnosa ?? '-' }}<br>
                <strong>Keluhan:</strong> {{ $rm->pemeriksaan?->keluhan_utama ?? '-' }}
            </td>
            <td>{{ $rm->terapi ?? $rm->rencana_terapi ?? '-' }}</td>
            <td>{{ $rm->catatan ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
