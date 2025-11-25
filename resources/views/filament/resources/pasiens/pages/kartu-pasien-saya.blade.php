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

        .kartu-box p {
            margin: 4px 0;
            font-size: 13px;
        }

        .kartu-box strong {
            display: inline-block;
            min-width: 110px;
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

        .kartu-table th {
            font-weight: 600;
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

        .kartu-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 4px;
            font-size: 11px;
            color: #6b7280;
        }

        .kartu-empty {
            padding: 10px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }

        @media print {
            body {
                background-color: #ffffff !important;
            }

            .fi-layout,
            .fi-topbar,
            .fi-sidebar {
                display: none !important;
            }

            .fi-main {
                margin: 0 !important;
                padding: 0 !important;
            }

            .kartu-body-wrapper {
                box-shadow: none !important;
                border: none !important;
            }
        }

        /* =======================
         * DARK MODE STYLES
         * ======================= */
        .dark .kartu-body {
            color: #e5e7eb;
        }

        .dark .kartu-header h3 {
            color: #f9fafb;
        }

        .dark .kartu-header p {
            color: #9ca3af;
        }

        .dark .kartu-box {
            background-color: #020617;
            border-color: #374151;
        }

        .dark .kartu-box p {
            color: #e5e7eb;
        }

        .dark .kartu-table-wrapper {
            background-color: #020617;
            border-color: #374151;
        }

        .dark .kartu-table th,
        .dark .kartu-table td {
            border-color: #374151;
            color: #e5e7eb;
        }

        .dark .kartu-table thead {
            background-color: #111827;
        }

        .dark .kartu-empty {
            color: #9ca3af;
        }

        .dark .kartu-badge {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            color: #e5e7eb;
        }

        .dark .kartu-badge[style*="background-color:#ecfdf3"] {
            background-color: #14532d !important;
            border-color: #16a34a !important;
            color: #bbf7d0 !important;
        }

        .dark .kartu-meta {
            color: #9ca3af;
        }
    </style>

    <div class="kartu-body">
        @if (! $pasien)
        <div class="kartu-box" style="border-color:#b91c1c; background-color:#fef2f2;">
            <p style="color:#b91c1c; font-weight:600; font-size:13px;">
                Data pasien tidak ditemukan.
            </p>
        </div>
        @else
        <div class="kartu-body-wrapper" style="max-width: 900px; margin: 0 auto;">
            {{-- HEADER --}}
            <div class="kartu-header">
                <h3>Praktek Mandiri Bidan Puji Susanti</h3>
                <p>Karongan RT.03 / RW.11 Jogotirto, Kec. Berbah, Kab. Sleman, Daerah Istimewa Yogyakarta</p>

                <div class="kartu-meta">
                    <span>
                        No. Rekam Medis:
                        <strong>{{ $pasien->id }}</strong>
                    </span>
                    <span>
                        Dicetak pada:
                        <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                    </span>
                </div>
            </div>

            {{-- BOX DATA PASIEN --}}
            <div class="kartu-box">
                <p>
                    <strong>Nama</strong>:
                    {{ $pasien->nama_pasien ?? '-' }}
                </p>
                <p>
                    <strong>Umur</strong>:
                    @if ($pasien->tanggal_lahir)
                    {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Tahun
                    ({{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d/m/Y') }})
                    @else
                    -
                    @endif
                </p>
                <p>
                    <strong>Alamat</strong>:
                    {{ $pasien->alamat ?? '-' }}
                </p>
                <p>
                    <strong>Alergi Obat</strong>:
                    {{ $pasien->alergi_obat ?? '-' }}
                </p>
                <p>
                    <strong>No. Telepon</strong>:
                    {{ $pasien->no_telp ?? '-' }}
                </p>
            </div>

            {{-- TABEL REKAM MEDIS --}}
            <div class="kartu-table-wrapper">
                <table class="kartu-table">
                    <thead>
                        <tr>
                            <th style="width: 16%">Tanggal</th>
                            <th style="width: 32%">Pemeriksaan / Diagnosa</th>
                            <th style="width: 32%">Terapi</th>
                            <th style="width: 20%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pasien->rekamMedis as $rm)
                        <tr>
                            <td>
                                {{ $rm->tanggal
                                ? \Carbon\Carbon::parse($rm->tanggal)->format('d/m/Y')
                                : '-' }}
                            </td>
                            <td>
                                <div style="margin-bottom:4px;">
                                    <span class="kartu-badge">
                                        Diagnosa
                                    </span>
                                    <div style="margin-top:2px;">
                                        {{ $rm->diagnosa?->nama_diagnosa ?? '-' }}
                                    </div>
                                </div>

                                <div style="margin-top:6px;">
                                    <span class="kartu-badge"
                                        style="background-color:#ecfdf3; border-color:#bbf7d0; color:#166534;">
                                        Keluhan
                                    </span>
                                    <div style="margin-top:2px;">
                                        {{ $rm->pemeriksaan?->keluhan_utama ?? '-' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $rm->terapi
                                ?? $rm->rencana_terapi
                                ?? '-' }}
                            </td>
                            <td>
                                {{ $rm->catatan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="kartu-empty">
                                Belum ada data rekam medis untuk pasien ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-filament-panels::page>