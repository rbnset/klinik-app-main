<x-filament-panels::page>
    <style>
        .kartu-body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #111827;
        }

        .kartu-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .kartu-header h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #111827;
        }

        .kartu-header p {
            font-size: 12px;
            margin: 0;
            color: #4b5563;
        }

        .kartu-box {
            border: 1px solid #111827;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            background-color: #f9fafb;
        }

        .kartu-table-wrapper {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #111827;
            background-color: #ffffff;
        }

        .kartu-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kartu-table th,
        .kartu-table td {
            border: 1px solid #111827;
            padding: 6px 8px;
            text-align: left;
            font-size: 12px;
            vertical-align: top;
        }

        .kartu-table thead {
            background-color: #f3f4f6;
        }

        .kartu-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 6px;
            border-radius: 999px;
            font-size: 10px;
            background-color: #e5f3ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .kartu-empty {
            padding: 10px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>

    <div class="kartu-body">
        @if (! $pasien)
        <div class="kartu-box" style="border-color:#b91c1c; background-color:#fef2f2;">
            <p style="color:#b91c1c; font-weight:600; font-size:13px;">Data pasien tidak ditemukan.</p>
        </div>
        @else
        <div class="kartu-body-wrapper" style="max-width: 900px; margin: 0 auto;">

            {{-- HEADER --}}
            <div class="kartu-header">
                <h3>Praktek Mandiri Bidan Puji Susanti</h3>
                <p>Karongan RT.03 / RW.11 Jogotirto, Kec. Berbah, Kab. Sleman, Daerah Istimewa Yogyakarta</p>

                <div
                    style="display:flex; justify-content:space-between; margin-top:4px; font-size:11px; color:#6b7280;">
                    <span>No. Rekam Medis: <strong>{{ $pasien->id }}</strong></span>
                    <span>Dicetak pada: <strong>{{ now()->format('d/m/Y H:i') }}</strong></span>
                </div>
            </div>

            {{-- DATA PASIEN --}}
            <div class="kartu-box">
                <p><strong>Nama</strong>: {{ $pasien->nama_pasien ?? '-' }}</p>
                <p>
                    <strong>Umur</strong>:
                    @if ($pasien->tanggal_lahir)
                    {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun
                    ({{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d/m/Y') }})
                    @else - @endif
                </p>
                <p><strong>Alamat</strong>: {{ $pasien->alamat ?? '-' }}</p>
                <p><strong>Alergi Obat</strong>: {{ $pasien->alergi_obat ?? '-' }}</p>
                <p><strong>No. Telepon</strong>: {{ $pasien->no_telp ?? '-' }}</p>
            </div>

            {{-- REKAM MEDIS --}}
            <div class="kartu-table-wrapper">
                <table class="kartu-table">
                    <thead>
                        <tr>
                            <th style="width: 16%">Tanggal</th>
                            <th style="width: 32%">Pemeriksaan / Diagnosa</th>
                            <th style="width: 32%">Terapi / Detail</th>
                            <th style="width: 20%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pasien->rekamMedis as $rm)
                        <tr>
                            <td>{{ $rm->tanggal ? $rm->tanggal->format('d/m/Y') : '-' }}</td>

                            <td>
                                <div><span class="kartu-badge">Diagnosa</span></div>
                                <div style="margin-top:2px;">{{ $rm->diagnosa?->nama_diagnosa ?? '-' }}</div>

                                <div style="margin-top:6px;">
                                    <span class="kartu-badge"
                                        style="background-color:#ecfdf3; border-color:#bbf7d0; color:#166534;">Keluhan</span>
                                </div>
                                <div style="margin-top:2px;">{{ $rm->pemeriksaan?->keluhan_utama ?? '-' }}</div>

                                {{-- RIWAYAT --}}
                                <div style="margin-top:6px;"><strong>Riwayat Alergi:</strong> {{ $rm->riwayat_alergi ??
                                    '-' }}</div>
                                <div style="margin-top:4px;"><strong>Riwayat Penyakit:</strong> {{ $rm->riwayat_penyakit
                                    ?? '-' }}</div>

                                {{-- TENAGA MEDIS (DITAMBAHKAN) --}}
                                <div style="margin-top:6px;">
                                    <strong>Tenaga Medis:</strong>
                                    {{ $rm->pendaftaran->tenaga_medis_tujuan ?? '-' }}
                                </div>
                            </td>

                            <td>
                                {{-- TERAPI --}}
                                {{ $rm->rencana_terapi ?? '-' }}

                                {{-- DETAIL REKAM MEDIS --}}
                                @foreach ($rm->details as $detail)
                                <div style="margin-top:8px;">
                                    <span class="kartu-badge"
                                        style="background-color:#fee2e2; border-color:#fecaca; color:#b91c1c;">
                                        {{ ucfirst($detail->tipe) }}
                                    </span>
                                    <div style="margin-top:2px;">
                                        {{ $detail->deskripsi }} – {{ $detail->qty }} {{ $detail->satuan }}
                                    </div>
                                </div>
                                @endforeach
                            </td>

                            <td>{{ $rm->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="kartu-empty">Belum ada data rekam medis untuk pasien ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        @endif
    </div>
</x-filament-panels::page>