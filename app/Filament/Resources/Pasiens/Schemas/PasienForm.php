<?php

namespace App\Filament\Resources\Pasiens\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid as ComponentsGrid;

class PasienForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsGrid::make(2)->schema([
                    TextInput::make('nik')
                        ->label('NIK')
                        ->maxLength(16)
                        ->unique(ignoreRecord: true)
                        ->nullable(),

                    TextInput::make('nama_pasien')
                        ->label('Nama Pasien')
                        ->maxLength(25)
                       ->nullable(),

                    TextInput::make('tempat_lahir')
                        ->label('Tempat Lahir')
                        ->maxLength(25)
                        ->nullable(),

                    DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->native(false)
                        ->nullable(),

                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                       ->nullable(),

                    Select::make('golongan_darah')
                        ->label('Golongan Darah')
                        ->options([
                            'A' => 'A',
                            'B' => 'B',
                            'AB' => 'AB',
                            'O' => 'O',
                        ])
                        ->nullable(),

                    Select::make('agama')
                        ->label('Agama')
                        ->options([
                            'Islam' => 'Islam',
                            'Kristen Protestan' => 'Kristen Protestan',
                            'Katolik' => 'Katolik',
                            'Hindu' => 'Hindu',
                            'Buddha' => 'Buddha',
                            'Konghucu' => 'Konghucu',
                            'Lainnya' => 'Lainnya',
                        ])
                        ->nullable(),

                    Select::make('status_perkawinan')
                        ->label('Status Perkawinan')
                        ->options([
                            'Belum Kawin' => 'Belum Kawin',
                            'Kawin' => 'Kawin',
                        ])
                        ->nullable(),

                    TextInput::make('no_telp')
                        ->label('No. Telepon')
                        ->maxLength(13)
                        ->tel()
                        ->nullable(),

                    TextInput::make('pekerjaan')
                        ->label('Pekerjaan')
                        ->maxLength(50)
                        ->nullable(),

                    TextInput::make('nama_penanggung_jawab')
                        ->label('Nama Penanggung Jawab')
                        ->maxLength(25)
                        ->nullable(),

                    TextInput::make('no_telp_penanggung_jawab')
                        ->label('No. Telp Penanggung Jawab')
                        ->maxLength(13)
                        ->tel()
                        ->nullable(),
                ]),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull(),
            ]);
            
    }
}
