<?php

use App\Models\Hr\Loan;
use App\Models\Hr\Worker;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use App\Helpers\NumberGeneratorHelper;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $options_worker = [];

    public $loan_id;

    #[Validate('required|string', as: 'Pekerja')]
    public $loan_worker_id;

    #[Validate('required|string', as: 'Penerima')]
    public $loan_receiver_id;

    #[Validate('required|date', as: 'Tanggal')]
    public $loan_loan_date;

    #[Validate('required|numeric', as: 'Jumlah')]
    public $loan_amount;

    #[Validate('nullable|string', as: 'Catatan')]
    public $loan_notes;

    public function set_loan($loan_id)
    {
        $this->loan_id = $loan_id;

        $loan = Loan::findOrFail($this->loan_id);

        $this->loan_worker_id = $loan->title;
        $this->set_loan_field('loan_worker_id', $loan->worker_id);
        $this->set_loan_field('loan_receiver_id', $loan->receiver_id);
        $this->set_loan_field('loan_loan_date', $loan->loan_date);
        $this->loan_amount = $loan->amount;
        $this->loan_notes = $loan->notes;
    }

    public function reset_loan()
    {
        $this->reset(['loan_id', 'loan_worker_id', 'loan_receiver_id', 'loan_loan_date', 'loan_amount', 'loan_notes']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
        $this->dispatch('re_init_bootstrap_datepicker');
    }

    public function set_loan_field($field, $value)
    {
        $this->$field = $value;
    }

    public function save()
    {
        $this->validate();

        if(is_null($this->loan_id)) {
            $loan = Loan::create([
                'loan_number' => NumberGeneratorHelper::loan_number(),
                'worker_id' => $this->loan_worker_id,
                'receiver_id' => $this->loan_receiver_id,
                'loan_date' => $this->loan_loan_date,
                'amount' => $this->loan_amount,
                'notes' => $this->loan_notes,
            ]);

            $this->dispatch("re_render_loans_container");
        } else {
            $loan = Loan::findOrFail($this->loan_id);

            $loan->update([
                'worker_id' => $this->loan_worker_id,
                'receiver_id' => $this->loan_receiver_id,
                'loan_date' => $this->loan_loan_date,
                'amount' => $this->loan_amount,
                'notes' => $this->loan_notes,
            ]);

            $this->dispatch("refresh_loan_component.{$loan->id}");
        }

        $this->dispatch('close_modal_loan_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->loan_id) ? 'menambah' : 'mengubah') . ' pinjaman')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_loan();
    }

    public function ask_to_delete_loan($loan_id)
    {
        $this->loan_id = $loan_id;
        $loan = Loan::find($this->loan_id);
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan menghapus pinjaman dengan nomor '.$loan->loan_number.', Anda yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('delete_loan')
            ->show();
    }

    public function delete_loan()
    {
        $loan = Loan::find($this->loan_id);

        if($loan) {
            $loan->delete();

            $this->dispatch("re_render_loans_container");

            LivewireAlert::title('')
            ->text('Berhasil menghapus pinjaman')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->reset_loan();
        }
    }

    public function mount()
    {
        $this->options_worker = Worker::orderBy('name')->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_loan_resource"
    :title="(is_null($loan_id) ? 'Tambah' : 'Edit') . ' Pinjaman'"
    :description="'Di sini, Anda dapat ' . (is_null($loan_id) ? 'menambah data' : 'mengubah informasi') . ' pinjaman.'"
    :loading-targets="['set_loan', 'reset_loan', 'save']"
>
    @csrf
    <x-ui::forms.select
        wire-model="loan_worker_id"
        label="Pekerja"
        placeholder="Pilih Pekerja"
        container-class="col-12 mb-6"
        init-select2-class="select2_loan"
        :options="$options_worker"
        value-field="id"
        text-field="name"
    />

    <x-ui::forms.select
        wire-model="loan_receiver_id"
        label="Penerima"
        placeholder="Pilih Penerima"
        container-class="col-12 mb-6"
        init-select2-class="select2_loan"
        :options="$options_worker"
        value-field="id"
        text-field="name"
    />

    <x-ui::forms.input
        wire:model.live="loan_loan_date"
        type="text"
        label="Tanggal"
        placeholder="2025-12-21"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input-group
        wire:model.live="loan_amount"
        type="text"
        label="Jumlah"
        placeholder="100000"
        container_class="col-12 mb-6"
        front="Rp."
    />

    <x-ui::forms.textarea
        wire:model.live="loan_notes"
        label="Catatan"
        placeholder="Berikan catatan khusus untuk pinjaman ini jika ada"
        container_class="col-12 mb-6"
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_loan_resource', () => {
            var modalElement = document.getElementById('modal_loan_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_loan");
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
            var date = $("#loan_loan_date").datepicker({
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

            $(document).on('change', '.select2_loan, #loan_loan_date', function () {
                $wire.set_loan_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_loan_add', function () {
                $wire.reset_loan();
            });

            $(document).on('click', '.btn_loan_edit', function () {
                $wire.set_loan($(this).attr('value'));
            });

            $(document).on('click', '.btn_loan_delete', function () {
                $wire.ask_to_delete_loan($(this).attr('value'));
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
