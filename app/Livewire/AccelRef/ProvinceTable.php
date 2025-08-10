<?php

namespace App\Livewire\AccelRef;

use App\Helpers\TableHelper;
use App\Models\Ref\Province;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;

class ProvinceTable extends DataTableComponent
{
    public string $tableName = 'accel-ref_province';

    public function builder(): Builder
    {
        return Province::query()
            ->with(['regencies', 'regencies.districts', 'regencies.districts.villages'])
            ->orderBy('name', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setAdditionalSelects(['id as identifier']);
        $this->setLoadingPlaceholderEnabled();
        $this->setLoadingPlaceholderContent('Mengambil Data');
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table border-top',
        ]);
        $this->setSearchDisabled();
        $this->setFilterLayoutSlideDown();
        $this->setActionsInToolbarEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "name"),
            CountColumn::make("Banyak Kabupaten/Kota")
            ->setDataSource('regencies'),
            Column::make('Aksi')
                ->setColumnLabelStatusDisabled()
                ->excludeFromColumnSelect()
                ->label(fn ($row, Column $column) => TableHelper::action_buttons(recordId: $row->identifier,
                        permissions: [
                            'edit' => 'AccelRef - Provinsi - Mengubah Data',
                            'delete' => 'AccelRef - Provinsi - Menghapus Data',
                        ],
                        cssClasses: [
                            'edit_btn' => 'btn_province_edit',
                            'delete_btn' => 'btn_province_delete',
                        ],
                        editModalTarget: '#modal_province_resource'))
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            TextFilter::make('Provinsi')
                ->config([
                    'placeholder' => 'Cari Provinsi',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('name', 'LIKE', '%'.$value.'%');
                    }
                }),
        ];
    }

    public function actions(): array
    {
        return array_filter([
            auth()->user()->hasPermissionTo('AccelRef - Provinsi - Menambah Data') ?
                Action::make('Tambah Data')
                    ->setActionAttributes([
                        'id' => 'btn_province_add',
                        'class' => 'btn w-100 btn-label-primary',
                        'data-bs-toggle '=> 'modal',
                        'data-bs-target' => '#modal_province_resource',
                        'default-colors' => false,
                        'default-styling' => false
                    ]) : null,
        ]);
    }
}
