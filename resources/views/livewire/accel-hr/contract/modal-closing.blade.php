<?php

use App\Models\Hr\Contract;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $contract_id;

    public $contract;

    #[Validate('required', as: 'Tgl. Berakhir')]
    public $contract_end_date;

    public function set_closing_contract($contract_id)
    {
        $this->contract_id = $contract_id;

        $this->contract = Contract::findOrFail($this->contract_id);
    }

    public function reset_closing_contract()
    {
        $this->reset(['contract_id', 'contract', 'contract_end_date']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_bootstrap_datepicker');
    }

    public function set_closing_contract_field($field, $value)
    {
        $this->$field = $value;
    }

    public function save()
    {
        $this->validate();

        if($this->contract_id) {
            $contract = Contract::findOrFail($this->contract_id);

            $contract->update([
                'end_date' => $this->contract_end_date,
            ]);

            $this->dispatch("refresh_contract_component.{$contract->id}");

        }

        $this->dispatch('close_modal_contract_closing');

        LivewireAlert::title('')
            ->text('Berhasil mengakhiri kontrak')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_closing_contract();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_contract_closing"
    :title="'Akhiri Kontrak'"
    :description="'Di sini, Anda dapat mengakhiri kontrak ['.($contract_id ? $contract->title : '').'].'"
    :loading-targets="['set_closing_contract', 'reset_closing_contract', 'save']"
>
    @csrf
    <x-ui::forms.input
        wire:model.live="contract_end_date"
        type="text"
        label="Tanggal Berakhir Kontrak"
        placeholder="2025-12-21"
        container_class="col-12 mb-6"
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_contract_closing', () => {
            var modalElement = document.getElementById('modal_contract_closing');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function init_bootstrap_datepicker() {
            var date = $("#contract_end_date").datepicker({
                todayHighlight: !0,
                format: "yyyy-mm-dd",
                language: 'id',
                endDate: new Date(),
                orientation: isRtl ? "auto right" : "auto left",
                autoclose: true
            })
        }

        $(document).ready(function () {
            init_bootstrap_datepicker();

            $(document).on('change', '#contract_end_date', function () {
                $wire.set_closing_contract_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '.btn_closing_contract', function () {
                $wire.set_closing_contract($(this).attr('value'));
            });

            window.Livewire.on('re_init_bootstrap_datepicker', () => {
                setTimeout(init_bootstrap_datepicker, 0)
            })
        });
    </script>
@endscript

