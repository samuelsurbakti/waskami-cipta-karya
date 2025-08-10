<?php

namespace App\Livewire\AccelRef;

use App\Helpers\TableHelper;
use App\Models\Ref\District;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;

class DistrictTable extends DataTableComponent
{
    public string $tableName = 'accel-ref_district';

    public function builder(): Builder
    {
        return District::query()
            ->with(['regency', 'villages', 'regency.province'])
            ->orderBy('regency_province.name', 'asc')
            ->orderBy('regency.name', 'asc')
            ->orderBy('name', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setAdditionalSelects(['ref_districts.id as identifier']);
        $this->setEagerLoadAllRelationsStatus(true);
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
            Column::make("Provinsi", "regency.province.name"),
            Column::make("Kabupaten/Kota", "regency.name"),
            Column::make("Nama", "name"),
            CountColumn::make("Banyak Kelurahan/Desa")
            ->setDataSource('villages'),
            Column::make('Aksi')
                ->setColumnLabelStatusDisabled()
                ->excludeFromColumnSelect()
                ->label(fn ($row, Column $column) => TableHelper::action_buttons(recordId: $row->id,
                        permissions: [
                            'edit' => 'AccelRef - Kecamatan - Mengubah Data',
                            'delete' => 'AccelRef - Kecamatan - Menghapus Data',
                        ],
                        cssClasses: [
                            'edit_btn' => 'btn_regency_edit',
                            'delete_btn' => 'btn_regency_delete',
                        ],
                        editModalTarget: '#modal_regency_resource'))
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
                        $builder->where('regency_province.name', 'LIKE', '%'.$value.'%');
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
                        $builder->where('regency.name', 'LIKE', '%'.$value.'%');
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
                        $builder->where('name', 'LIKE', '%'.$value.'%');
                    }
                }),
        ];
    }

    public function actions(): array
    {
        return array_filter([
            auth()->user()->hasPermissionTo('AccelRef - Kecamatan - Menambah Data') ?
                Action::make('Tambah Data')
                    ->setActionAttributes([
                        'id' => 'btn_district_add',
                        'class' => 'btn w-100 btn-label-primary',
                        'data-bs-toggle '=> 'modal',
                        'data-bs-target' => '#modal_district_resource',
                        'default-colors' => false,
                        'default-styling' => false
                    ]) : null,
        ]);
    }
}
