<?php

namespace App\Livewire\AccelRef;

use App\Models\Ref\Regency;
use App\Helpers\TableHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;

class RegencyTable extends DataTableComponent
{
    public string $tableName = 'accel-ref_regency';

    public function builder(): Builder
    {
        return Regency::query()
            ->with(['province', 'districts'])
            ->orderBy('name', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setAdditionalSelects(['ref_regencies.id as identifier']);
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
            Column::make("Provinsi", "province.name"),
            Column::make("Nama", "name"),
            CountColumn::make("Banyak Kecamatan")
            ->setDataSource('districts'),
            Column::make('Aksi')
                ->setColumnLabelStatusDisabled()
                ->excludeFromColumnSelect()
                ->label(fn ($row, Column $column) => TableHelper::action_buttons(recordId: $row->id,
                        permissions: [
                            'edit' => 'AccelRef - Kabupaten/Kota - Mengubah Data',
                            'delete' => 'AccelRef - Kabupaten/Kota - Menghapus Data',
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
                        $builder->where('province.name', 'LIKE', '%'.$value.'%');
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
                        $builder->where('name', 'LIKE', '%'.$value.'%');
                    }
                }),
        ];
    }

    public function actions(): array
    {
        return array_filter([
            auth()->user()->hasPermissionTo('AccelRef - Kabupaten/Kota - Menambah Data') ?
                Action::make('Tambah Data')
                    ->setActionAttributes([
                        'id' => 'btn_regency_add',
                        'class' => 'btn w-100 btn-label-primary',
                        'data-bs-toggle '=> 'modal',
                        'data-bs-target' => '#modal_regency_resource',
                        'default-colors' => false,
                        'default-styling' => false
                    ]) : null,
        ]);
    }
}
