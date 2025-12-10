<?php

namespace App\Livewire\Dashboard;

use Filament\Tables\Table;
use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Resources\Pasiens\Tables\PasiensTable;

class PasiensTableView extends Component implements HasTable
{
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        // Ambil konfigurasi tabel dari PasiensTable
        return PasiensTable::configure($table);
    }

    public function render()
    {
        return view('livewire.dashboard.pasiens-table-view');
    }
}
