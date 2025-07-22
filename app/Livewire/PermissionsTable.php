<?php

namespace App\Livewire;

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use App\Helpers\TableHelper;
use App\Models\SLP\Permission;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\ArrayColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectDropdownFilter;

class PermissionsTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Permission::query()
            ->with(['app', 'menu'])
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
        $this->setSearchDisabled();
        $this->setFilterLayoutSlideDown();
        $this->setActionsLeft();
    }

    public function columns(): array
    {
        return [
            Column::make("Jenis", "type"),
            Column::make("Aplikasi", "app.name"),
            Column::make("Menu", "menu.title"),
            Column::make("Nama", "name"),
            Column::make("Urutan", "number"),
            Column::make('Diberikan Kepada', 'uuid')
                ->format(function ($value, $row) {
                    return TableHelper::roles_in_permission($row);
                })
                ->html(),
        ];
    }

    public function filters(): array
    {
        $types = Permission::query()
            ->select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type', 'type') // Ini akan menghasilkan array ['nilai' => 'nilai']
            ->toArray();

        // Tambahkan opsi 'All' di bagian awal array
        $filterOptions = ['' => 'Semua Jenis'] + $types;

        return [
            SelectFilter::make('Jenis')
                ->options($filterOptions) // Menggunakan opsi dinamis dari database
                ->filter(function(Builder $builder, string $value) {
                    // Logika filter Anda. Pastikan $value sesuai dengan data di DB.
                    if (!empty($value)) {
                        $builder->where('type', $value);
                    }
                }),
            MultiSelectFilter::make('Aplikasi')
                ->options(
                    App::query()
                        ->orderBy('order_number')
                        ->get()
                        ->keyBy('id')
                        ->map(fn($app) => $app->name)
                        ->toArray()
                )
                ->filter(function(Builder $builder, array $value) {
                    if (!empty($value)) {
                        $builder->whereIn('app.id', $value);
                    }
                }),
            TextFilter::make('Menu')
                ->config([
                    'placeholder' => 'Cari Menu',
                    'maxlength' => '25',
                ])
                ->setWireBlur()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('menu.name', 'LIKE', '%'.$value.'%');
                    }
                }),

        ];
    }

    public function actions(): array
    {
        return [
            Action::make('View Dashboard')
            ->setRoute('Accel | Gate'),
        ];

    }
}
