<?php

namespace App\Livewire\AccelRef;

use App\Models\Ref\Village;
use App\Helpers\TableHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;

class VillageTable extends DataTableComponent
{
    public string $tableName = 'accel-ref_village';

    public function builder(): Builder
    {
        return Village::query()
            ->with(['district'])
            ->orderBy('district_regency_province.name', 'asc')
            ->orderBy('district_regency.name', 'asc')
            ->orderBy('district.name', 'asc')
            ->orderBy('name', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setAdditionalSelects(['ref_villages.id as identifier']);
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
            Column::make("Provinsi", "district.regency.province.name"),
            Column::make("Kabupaten/Kota", "district.regency.name"),
            Column::make("Kecamatan", "district.name"),
            Column::make("Nama", "name"),
            Column::make('Aksi')
                ->setColumnLabelStatusDisabled()
                ->excludeFromColumnSelect()
                ->label(fn ($row, Column $column) => TableHelper::action_buttons(recordId: $row->identifier,
                        permissions: [
                            'edit' => 'AccelRef - Kelurahan/Desa - Mengubah Data',
                            'delete' => 'AccelRef - Kelurahan/Desa - Menghapus Data',
                        ],
                        cssClasses: [
                            'edit_btn' => 'btn_village_edit',
                            'delete_btn' => 'btn_village_delete',
                        ],
                        editModalTarget: '#modal_village_resource'))
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
                        $builder->where('district_regency_province.name', 'LIKE', '%'.$value.'%');
                    }
                }),
            TextFilter::make('Kabupaten/Kota')
                ->config([
                    'placeholder' => 'Cari Kabupaten/Kota',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('district_regency.name', 'LIKE', '%'.$value.'%');
                    }
                }),
            TextFilter::make('Kecamatan')
                ->config([
                    'placeholder' => 'Cari Kecamatan',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('district.name', 'LIKE', '%'.$value.'%');
                    }
                }),
            TextFilter::make('Kelurahan/Desa')
                ->config([
                    'placeholder' => 'Cari Kelurahan/Desa',
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
            auth()->user()->hasPermissionTo('AccelRef - Kelurahan/Desa - Menambah Data') ?
                Action::make('Tambah Data')
                    ->setActionAttributes([
                        'id' => 'btn_village_add',
                        'class' => 'btn w-100 btn-label-primary',
                        'data-bs-toggle '=> 'modal',
                        'data-bs-target' => '#modal_village_resource',
                        'default-colors' => false,
                        'default-styling' => false
                    ]) : null,
        ]);
    }
}
