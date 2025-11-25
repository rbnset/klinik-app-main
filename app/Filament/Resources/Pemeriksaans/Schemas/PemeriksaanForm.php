<?php

namespace App\Filament\Resources\Pemeriksaans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{
    Grid,
    Select,
    DateTimePicker,
    Textarea,
    TextInput,
    Hidden
};
use App\Models\Pendaftaran;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Illuminate\Support\Facades\Auth;

class PemeriksaanForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = Auth::user();
        $role = $user->role?->name ?? null;

        // Hanya dokter & bidan yang boleh mengisi pemeriksaan
        if (!in_array($role, ['dokter', 'bidan'])) {
            // Untuk admin/pasien/petugas: form kosong (tidak bisa create)
            return $schema->components([]);
        }

        return $schema->components([

            // ============================================
            // GRID: PENDAFTARAN - PASIEN - TENAGA MEDIS
            // ============================================
            ComponentsGrid::make(3)->schema([

                // --------------------------
                // SELECT PENDAFTARAN (difilter menurut role/poli)
                // --------------------------
                Select::make('pendaftaran_id')
                    ->label('Pendaftaran')
                    ->options(function () use ($role) {
                        $query = Pendaftaran::with('pasien');

                        if ($role === 'dokter') {
                            $query->where('poli_tujuan', 'Poli Umum');
                        } elseif ($role === 'bidan') {
                            $query->where('poli_tujuan', 'Poli Kandungan');
                        }

                        return $query->get()->mapWithKeys(function ($pendaftaran) {
                            $label = $pendaftaran->pasien->nama_pasien ?? 'Pasien Tidak Ditemukan';
                            return [$pendaftaran->id => "ID: {$pendaftaran->id} | {$label}"];
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) use ($role) {
                        $pendaftaran = Pendaftaran::find($state);

                        if ($pendaftaran) {
                            // Auto set pasien
                            $set('pasien_id', $pendaftaran->pasien_id);

                            // Otomatis set dokter_id ke user login (dokter/bidan) 
                            // hanya jika pendaftaran sesuai poli dan user role cocok
                            if ($pendaftaran->poli_tujuan === 'Poli Umum' && $role === 'dokter') {
                                $set('dokter_id', Auth::id());
                            } elseif ($pendaftaran->poli_tujuan === 'Poli Kandungan' && $role === 'bidan') {
                                $set('dokter_id', Auth::id());
                            }
                        }
                    }),

                // ---------------------------
                // SELECT PASIEN
                // ---------------------------
                Select::make('pasien_id')
                    ->relationship('pasien', 'nama_pasien')
                    ->searchable()
                    ->preload()
                    ->required(),

                // ---------------------------
                // SELECT TENAGA MEDIS (DOKTER/BIDAN) -- disabled, auto default
                // ---------------------------
                Select::make('dokter_id')
                    ->relationship('dokter', 'name')
                    ->label('Tenaga Medis')
                    ->searchable()
                    ->preload()
                    ->default(fn () => Auth::id())
                    ->disabled()        // user tidak boleh ganti
                    ->dehydrated()      // wajib agar dikirim ke DB
                    ->required()
                    ->afterStateHydrated(function ($state, callable $set) {
                        if (!$state) {
                            $set('dokter_id', Auth::id());
                        }
                    }),

                // (opsional Hidden field untuk memastikan selalu terkirim, tidak wajib jika Select diatas sudah dehydrated)
                Hidden::make('auto_set_marker')->default(true)->dehydrated(false),
            ]),

            // ============================
            //    GRID: TANGGAL & STATUS
            // ============================
            ComponentsGrid::make(2)->schema([
                DateTimePicker::make('tanggal_periksa')
                    ->native(false)
                    ->default(now())
                    ->required(),

                Select::make('status')
                    ->options([
                        'proses'  => 'Proses',
                        'selesai' => 'Selesai',
                        'dirujuk' => 'Dirujuk',
                    ])
                    ->default('proses')
                    ->required(),
            ]),

            // ============================
            //    KELUHAN UTAMA
            // ============================
            Textarea::make('keluhan_utama')
                ->label('Keluhan Utama')
                ->required()
                ->columnSpanFull(),

            // ============================
            //    PEMERIKSAAN FISIK
            // ============================
            ComponentsGrid::make(6)->schema([
                TextInput::make('tinggi_badan')->numeric()->label('TB (cm)'),
                TextInput::make('berat_badan')->numeric()->label('BB (kg)'),
                TextInput::make('tekanan_darah')->label('TD (mmHg)'),
                TextInput::make('suhu')->numeric()->label('Suhu (°C)'),
                TextInput::make('nadi')->numeric()->label('Nadi'),
                TextInput::make('respirasi')->numeric()->label('RR'),
            ]),
        ]);
    }
}
