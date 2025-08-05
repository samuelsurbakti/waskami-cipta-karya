<?php

namespace App\Livewire\AccelHr\Contract;

use App\Helpers\TableHelper;
use App\Models\Hr\Contract\Type;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;

class TypesTable extends DataTableComponent
{
    public string $tableName = 'accel-hr_contract_types';
    protected $model = Type::class;

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
        $this->setColumnSelectStatus(false);
        $this->setActionsInToolbarEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "name"),
            Column::make('Aksi')
                ->setColumnLabelStatusDisabled()
                ->excludeFromColumnSelect()
                ->label(fn ($row, Column $column) => TableHelper::action_buttons(recordId: $row->identifier,
                        permissions: [
                            'edit' => 'AccelHr - Kontrak - Jenis - Mengubah Data',
                            'delete' => 'AccelHr - Kontrak - Jenis - Menghapus Data',
                        ],
                        cssClasses: [
                            'edit_btn' => 'btn_type_edit',
                            'delete_btn' => 'btn_type_delete',
                        ],
                        editModalTarget: '#modal_type_resource'))
                ->html(),
        ];
    }

    public function actions(): array
    {
        return array_filter([
            auth()->user()->hasPermissionTo('AccelHr - Kontrak - Jenis - Menambah Data') ?
                Action::make('Tambah Data')
                    ->setActionAttributes([
                        'id' => 'btn_type_add',
                        'class' => 'btn w-100 btn-label-primary',
                        'data-bs-toggle '=> 'modal',
                        'data-bs-target' => '#modal_type_resource',
                        'default-colors' => false,
                        'default-styling' => false
                    ]) : null,
        ]);
    }
}
