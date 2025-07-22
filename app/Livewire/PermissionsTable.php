<?php

namespace App\Livewire;

use App\Models\SLP\Permission;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class PermissionsTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Permission::query()
            ->with(['app', 'menu', 'roles'])
            ->orderBy('app.order_number', 'asc')
            ->orderBy('menu.order_number', 'asc')
            ->orderBy('type', 'asc')
            ->orderBy('number', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('uuid');
        $this->setLoadingPlaceholderEnabled();
        $this->setLoadingPlaceholderContent('Mengambil Data');
        $this->setComponentWrapperAttributes([
            'default' => true,
            'class' => 'card',
        ]);
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table border-top',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Jenis", "type"),
            Column::make("Aplikasi", "app.name"),
            Column::make("Menu", "menu.title"),
            Column::make("Nama", "name"),
            Column::make("Urutan", "number"),
        ];
    }
}
