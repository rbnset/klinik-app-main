<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Total Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">

    <h2 class="text-2xl font-bold mb-4">Daftar Dokter</h2>

    <table class="table-auto w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">No</th>
                <th class="px-4 py-2 border">Nama Dokter</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dokter as $key => $d)
            <tr>
                <td class="px-4 py-2 border">{{ $key + 1 }}</td>
                <td class="px-4 py-2 border">{{ $d->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
