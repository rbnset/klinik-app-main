<?php

namespace App\Filament\Resources\Pendaftarans\Schemas;

use App\Models\Jadwal;
use Filament\Forms;
use Filament\Forms\Components\{Select, Textarea, TextInput, DatePicker};
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Carbon;

class PendaftaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsGrid::make(2)->schema([

                // 🔹 Nama Pasien
                Select::make('pasien_id')
                    ->label('Nama Pasien')
                    ->relationship('pasien', 'nama_pasien')
                    ->default(fn() => auth()->user()?->role?->name === 'pasien'
                        ? optional(auth()->user()->pasien)->id
                        : null)
                    ->disabled(fn() => auth()->user()?->role?->name === 'pasien')
                    ->dehydrated(true)
                    ->searchable(fn() => auth()->user()?->role?->name !== 'pasien')
                    ->preload()
                    ->required(),

                // 🔹 User ID (Hidden, otomatis untuk semua role saat create)
                Forms\Components\Hidden::make('user_id')
                    ->default(fn() => auth()->id())
                    ->dehydrated(true)
                    ->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),

                // 🔹 Tanggal Kunjungan (wajib, dipakai untuk cek slot kosong)
                DatePicker::make('tanggal_kunjungan')
                    ->label('Tanggal Kunjungan')
                    ->native(false)
                    ->required()
                    ->reactive(),

                // 🔹 Jadwal (cek hari dari tanggal + slot yang masih kosong)
                Select::make('jadwal_id')
                    ->label('Jadwal')
                    ->options(function (Get $get) {
                        $user    = auth()->user();
                        $tanggal = $get('tanggal_kunjungan');

                        if (! $tanggal) {
                            // Tanpa tanggal, jangan tampilkan jadwal
                            return [];
                        }

                        // Ambil hari dari tanggal kunjungan → cocokkan dengan kolom `hari` di `jadwals`
                        $carbon    = Carbon::parse($tanggal);
                        $dayOfWeek = $carbon->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)

                        $mapHari = [
                            1 => 'senin',
                            2 => 'selasa',
                            3 => 'rabu',
                            4 => 'kamis',
                            5 => 'jumat',
                            6 => 'sabtu',
                            7 => 'minggu',
                        ];

                        $hari = $mapHari[$dayOfWeek] ?? null;

                        $q = Jadwal::query()
                            ->with('user')
                            ->when($hari, fn($query) => $query->where('hari', $hari))
                            // Hanya slot yang BELUM dipakai pada tanggal tsb
                            ->whereDoesntHave('pendaftarans', function ($query) use ($tanggal) {
                                $query->whereDate('tanggal_kunjungan', $tanggal)
                                    ->whereNotIn('status', ['batal']);
                            })
                            ->orderBy('hari')
                            ->orderBy('jam_mulai');

                        // Jika yang login dokter → hanya lihat jadwal dirinya
                        if ($user?->role?->name === 'dokter') {
                            $q->where('user_id', $user->id);
                        }

                        return $q->get()->mapWithKeys(fn($j) => [
                            $j->id => ($j->user->name ?? '-') . ' | ' .
                                ucfirst($j->hari) . " {$j->jam_mulai}-{$j->jam_selesai}" .
                                ($j->keterangan ? " | {$j->keterangan}" : ''),
                        ])->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->rule('exists:jadwals,id')
                    ->disabled(fn(Get $get) => ! $get('tanggal_kunjungan')),

                // 🔹 Poli Tujuan
                Select::make('poli_tujuan')
                    ->label('Poli Tujuan')
                    ->options([
                        'Poli Umum'      => 'Poli Umum',
                        'Poli Kandungan' => 'Poli Kandungan',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Kalau poli kandungan → otomatis Bidan, selain itu Dokter
                        $set('tenaga_medis_tujuan', $state === 'Poli Kandungan' ? 'Bidan' : 'Dokter');
                    })
                    ->required(),

                // 🔹 Tenaga Medis (label saja, ikut Poli)
                Select::make('tenaga_medis_tujuan')
                    ->label('Tenaga Medis')
                    ->options([
                        'Dokter' => 'Dokter',
                        'Bidan'  => 'Bidan',
                    ])
                    ->disabled()      // tidak bisa diubah manual
                    ->dehydrated(true)
                    ->required(),

                // 🔹 Jenis Pelayanan
                Select::make('jenis_pelayanan')
                    ->label('Jenis Pelayanan')
                    ->options([
                        'umum'     => 'Umum',
                        'bpjs'     => 'BPJS',
                        'asuransi' => 'Asuransi',
                    ])
                    ->required(),

                // 🔹 Status (hanya untuk admin/staf, bukan pasien)
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'selesai'  => 'Selesai',
                        'batal'    => 'Batal',
                    ])
                    ->default('menunggu')
                    ->visible(fn() => auth()->user()?->role?->name !== 'pasien')
                    ->dehydrated(true)
                    ->required(),
            ]),

            // 🔹 Keluhan
            Textarea::make('keluhan')
                ->label('Keluhan')
                ->columnSpanFull(),

            // 🔹 Catatank
            Textarea::make('catatan')
                ->label('Catatan')
                ->columnSpanFull(),
        ]);
    }
}
