<x-filament-panels::page>
    @php
    $role = auth()->user()->role?->name;
    @endphp

    {{-- ================== ADMIN ================== --}}
    @if ($role === 'admin')

    {{-- ==== CARD STATISTIK ==== --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 mb-6">

        {{-- Total Pasien --}}
        <div class="bg-white shadow rounded-xl p-5 text-center hover:shadow-md transition">
            <x-heroicon-o-user class="mx-auto text-green-600 mb-3" style="width:32px; height:32px;" />

            <div class="text-3xl font-bold">
                {{ \App\Models\Pasien::count() }}
            </div>

            <div class="text-gray-600 text-sm mt-1">
                Total Pasien
            </div>
        </div>

        {{-- Total Tenaga Medis --}}
        <div class="bg-white shadow rounded-xl p-5 text-center hover:shadow-md transition">
            <x-heroicon-o-user-group class="mx-auto text-blue-600 mb-3" style="width:32px; height:32px;" />

            <div class="text-3xl font-bold">
                {{ \App\Models\User::whereHas('role', fn ($q) =>
                $q->whereIn('name', ['dokter', 'bidan'])
                )->count() }}
            </div>

            <div class="text-gray-600 text-sm mt-1">
                Total Tenaga Medis
            </div>
        </div>

        {{-- Total Pendaftaran --}}
        <div class="bg-white shadow rounded-xl p-5 text-center hover:shadow-md transition">
            <x-heroicon-o-clipboard-document-list class="mx-auto text-orange-500 mb-3"
                style="width:32px; height:32px;" />

            <div class="text-3xl font-bold">
                {{ \App\Models\Pendaftaran::count() }}
            </div>

            <div class="text-gray-600 text-sm mt-1">
                Total Pendaftaran
            </div>
        </div>

        {{-- Total Pemeriksaan --}}
        <div class="bg-white shadow rounded-xl p-5 text-center hover:shadow-md transition">
            <x-heroicon-o-document-check class="mx-auto text-purple-600 mb-3" style="width:32px; height:32px;" />

            <div class="text-3xl font-bold">
                {{ \App\Models\Pemeriksaan::count() }}
            </div>

            <div class="text-gray-600 text-sm mt-1">
                Total Pemeriksaan
            </div>
        </div>

    </div>

    {{-- ==== GRAFIK PENDAFTARAN PER BULAN (ADMIN) ==== --}}
    <x-filament::section class="p-4">
        <h2 class="text-lg font-semibold mb-3 text-right">Grafik Pendaftaran per Bulan</h2>

        <canvas id="chartPendaftaran" height="90"></canvas>

        @php
        $data = \App\Models\Pendaftaran::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        $bulan = $data->pluck('bulan')->map(fn ($b) =>
        DateTime::createFromFormat('!m', $b)->format('F')
        );

        $total = $data->pluck('total');
        @endphp

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                    const ctx = document.getElementById('chartPendaftaran');

                    if (!ctx) return;

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @json($bulan),
                            datasets: [{
                                label: 'Jumlah Pendaftaran',
                                data: @json($total),
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                        }
                    });
                });
        </script>
    </x-filament::section>

    {{-- ================== DOKTER, BIDAN, PEMILIK, PETUGAS ================== --}}
    @elseif (in_array($role, ['pemilik', 'petugas', 'dokter', 'bidan']))

    {{-- Di sini kamu bisa pakai Livewire dashboard khusus --}}
    <livewire:dashboard />

    {{-- ================== PASIEN & ROLE LAIN ================== --}}
    @else
    <div class="text-center py-10">
        <p class="text-gray-600 text-lg">
            Selamat Datang di Praktek Mandiri Bidan Puji Susanti.
        </p>
        <p class="text-gray-500 mt-2 text-sm">
            Silakan gunakan menu di sebelah kiri untuk mendaftar atau melihat riwayat layanan.
        </p>
    </div>
    @endif
</x-filament-panels::page>