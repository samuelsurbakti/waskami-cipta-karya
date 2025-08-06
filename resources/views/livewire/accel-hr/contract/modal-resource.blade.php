<?php

use App\Models\Hr\Team;
use App\Models\Hr\Worker;
use Livewire\Volt\Component;
use App\Models\Hr\Contract\Type;
use Livewire\Attributes\Validate;

new class extends Component {
    public $options_type = [], $options_relation;

    public $contract_id;

    #[Validate('required|string', as: 'Judul')]
    public $contract_title;

    #[Validate('required', as: 'Jenis Pemilik Kontrak')]
    public $contract_relation_type;

    #[Validate('required', as: 'Pemilik Kontrak')]
    public $contract_relation_id;

    #[Validate('nullable', as: 'Proyek')]
    public $contract_project_id;

    #[Validate('required', as: 'Jenis Kontrak')]
    public $contract_type_id;

    #[Validate('required|numeric', as: 'Tarif')]
    public $contract_rates;

    #[Validate('required', as: 'Tgl. Mulai')]
    public $contract_start_date;

    public function set_contract()
    {

    }

    public function reset_contract()
    {
        $this->reset(['contract_id', 'contract_title', 'contract_relation_type', 'contract_relation_id', 'contract_project_id', 'contract_type_id', 'contract_rates', 'contract_start_date']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
        $this->dispatch('re_init_bootstrap_datepicker');
    }

    public function set_contract_field($field, $value)
    {
        $this->$field = $value;

        if($field == 'contract_type_id') {
            $contract_type = Type::find($value);
            if($contract_type->name == 'borongan') {
                $this->contract_relation_type = Team::class;
                $this->options_relation = Team::orderBy('name')->get();
            } else {
                $this->contract_relation_type = Worker::class;
                $this->options_relation = Worker::orderBy('name')->get();
            }
        }
    }

    public function save()
    {
        $this->validate();
    }

    public function mount()
    {
        $this->options_type = Type::orderBy('name')->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_contract_resource"
    :title="(is_null($contract_id) ? 'Tambah' : 'Edit') . ' Kontrak'"
    :description="'Di sini, Anda dapat ' . (is_null($contract_id) ? 'menambah data' : 'mengubah informasi') . ' kontrak.'"
    :loading-targets="['set_contract', 'reset_contract', 'save']"
>
    @csrf
    <x-ui::forms.input
        wire:model.live="contract_title"
        type="text"
        label="Judul"
        placeholder="Kontrak Kerja Budi"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.select
        wire-model="contract_type_id"
        label="Jenis"
        placeholder="Pilih Jenis Kontrak"
        container-class="col-12 mb-6"
        init-select2-class="select2_contract"
        :options="$options_type"
        value-field="id"
        text-field="name"
    />

    <x-ui::forms.select
        wire-model="contract_relation_id"
        label="Pemiliki Kontrak"
        placeholder="Pilih Pemilik Kontrak"
        container-class="col-12 mb-6"
        init-select2-class="select2_contract"
        :options="$options_relation"
        value-field="id"
        text-field="name"
    />

    <x-ui::forms.input-group
        wire:model.live="contract_rates"
        type="text"
        label="Tarif"
        placeholder="100000"
        container_class="col-12 mb-6"
        front="Rp."
    />

    <x-ui::forms.input
        wire:model.live="contract_start_date"
        type="text"
        label="Tanggal Mulai Kontrak"
        placeholder="2025-12-21"
        container_class="col-12 mb-6"
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_contract_resource', () => {
            var modalElement = document.getElementById('modal_contract_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_contract");
            e_select2.length && e_select2.each(function () {
                var e_select2 = $(this);
                e_select2.wrap('<div class="position-relative"></div>').select2({
                    placeholder: "Select value",
                    allowClear: true,
                    dropdownParent: e_select2.parent()
                })
            })
        }

        function init_bootstrap_datepicker() {
            var date = $("#contract_start_date").datepicker({
                todayHighlight: !0,
                format: "yyyy-mm-dd",
                language: 'id',
                orientation: isRtl ? "auto right" : "auto left",
                autoclose: true
            })
        }

        $(document).ready(function () {
            initSelect2();
            init_bootstrap_datepicker();

            $(document).on('change', '.select2_contract', function () {
                $wire.set_contract_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_contract_add', function () {
                $wire.reset_contract();
            });

            $(document).on('click', '.btn_contract_edit', function () {
                $wire.set_contract($(this).attr('value'));
            });

            $(document).on('click', '.btn_contract_delete', function () {
                $wire.ask_to_delete_contract($(this).attr('value'));
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })

            window.Livewire.on('re_init_bootstrap_datepicker', () => {
                setTimeout(init_bootstrap_datepicker, 0)
            })
        });
    </script>
@endscript
