<?php

use App\Models\Hr\Worker;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Hr\Worker\Type;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $options_type = [];

    public $worker_id;

    #[Validate('required', as: 'Jenis')]
    public $worker_type_id;

    #[Validate('required|string', as: 'Nama')]
    public $worker_name;

    #[Validate('nullable|string', as: 'No. Telepon')]
    public $worker_phone;

    #[Validate('nullable|string', as: 'No. Whatsapp')]
    public $worker_whatsapp;

    #[Validate('nullable|string', as: 'Alamat')]
    public $worker_address;

    #[On('set_worker')]
    public function set_worker($worker_id)
    {
        $this->worker_id = $worker_id;

        $worker = Worker::findOrFail($this->worker_id);

        $this->worker_type_id = $worker->type_id;
        $this->worker_name = $worker->name;
        $this->worker_phone = $worker->phone;
        $this->worker_whatsapp = $worker->whatsapp;
        $this->worker_address = $worker->address;
    }

    #[On('reset_worker')]
    public function reset_worker()
    {
        $this->reset(['worker_id', 'worker_type_id', 'worker_name', 'worker_phone', 'worker_whatsapp', 'worker_address']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    #[On('set_worker_field')]
    public function set_worker_field($field, $value)
    {
        $this->$field = $value;
    }

    public function save()
    {
        $this->validate();

        if(is_null($this->worker_id)) {
            $worker = Worker::create([
                'type_id' => $this->worker_type_id,
                'name' => $this->worker_name,
                'phone' => $this->worker_phone,
                'whatsapp' => $this->worker_whatsapp,
                'address' => $this->worker_address,
            ]);

            $this->dispatch("re_render_workers_container");
        } else {
            $worker = Worker::findOrFail($this->worker_id);

            $worker->update([
                'type_id' => $this->worker_type_id,
                'name' => $this->worker_name,
                'phone' => $this->worker_phone,
                'whatsapp' => $this->worker_whatsapp,
                'address' => $this->worker_address,
            ]);

            $this->dispatch("refresh_worker_component.{$worker->id}");
        }

        $this->dispatch('close_modal_worker_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->worker_id) ? 'menambah' : 'mengubah') . ' pekerja')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_worker();
    }

    #[On('ask_to_delete_worker')]
    public function ask_to_delete_worker($worker_id)
    {
        $this->worker_id = $worker_id;
        $worker = Worker::find($this->worker_id);
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan menghapus pekerja dengan nama '.$worker->name.', Anda yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('delete_worker')
            ->show();
    }

    public function delete_worker()
    {
        $worker = Worker::find($this->worker_id);

        if($worker) {
            $worker->delete();

            $this->dispatch("re_render_workers_container");

            LivewireAlert::title('')
            ->text('Berhasil menghapus pekerja')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->reset_worker();
        }
    }

    public function mount()
    {
        $this->options_type = Type::orderBy('name')->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_worker_resource"
    :title="(is_null($worker_id) ? 'Tambah' : 'Edit') . ' Pekerja'"
    :description="'Di sini, Anda dapat ' . (is_null($worker_id) ? 'menambah data' : 'mengubah informasi') . ' pekerja.'"
    :loading-targets="['set_worker', 'reset_worker', 'save']"
>
    <form wire:submit="save" method="POST">
        @csrf
        <x-ui::forms.select
            wire-model="worker_type_id"
            label="Jenis"
            placeholder="Pilih Jenis Pekerja"
            container-class="col-12 mb-6"
            init-select2-class="select2_worker"
            :options="$options_type"
            value-field="id"
            text-field="name"
        />

        <x-ui::forms.input
            wire:model.live="worker_name"
            type="text"
            label="Nama"
            placeholder="Bowo Cokro Aminoto"
            container_class="col-12 mb-6"
        />

        <x-ui::forms.input
            wire:model.live="worker_phone"
            type="text"
            label="No. Telepon"
            placeholder="081199552244"
            container_class="col-12 mb-6"
        />

        <x-ui::forms.input
            wire:model.live="worker_whatsapp"
            type="text"
            label="No. Whatsapp"
            placeholder="081199552244"
            container_class="col-12 mb-6"
        />

        <x-ui::forms.textarea
            wire:model.live="worker_address"
            label="Alamat"
            placeholder="Jl. Bunga Sedap Malam IX No.1, Sempakata, Kec. Medan Selayang, Kota Medan, Sumatera Utara 20131"
            container_class="col-12 mb-6"
        />

        <div class="col-12 text-center mt-8">
            <x-ui::elements.button type="submit" class="btn-primary me-sm-3 me-1">
                Simpan
            </x-ui::elements.button>
            <x-ui::elements.button type="reset" class="btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                Batalkan
            </x-ui::elements.button>
        </div>
    </form>
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_worker_resource', () => {
            var modalElement = document.getElementById('modal_worker_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_worker");
            e_select2.length && e_select2.each(function () {
                var e_select2 = $(this);
                e_select2.wrap('<div class="position-relative"></div>').select2({
                    placeholder: "Select value",
                    allowClear: true,
                    dropdownParent: e_select2.parent()
                })
            })
        }

        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', () => {
                let grid = document.querySelector('[data-masonry]');
                if (grid) {
                    // Re-init Masonry (dengan asumsi kamu pakai Masonry v4)
                    new Masonry(grid, JSON.parse(grid.dataset.masonry || '{}'));
                }
            });
        });

        $(document).ready(function () {
            initSelect2();

            $(document).on('change', '.select2_worker', function () {
                $wire.set_worker_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_worker_add', function () {
                $wire.reset_worker();
            });

            $(document).on('click', '.btn_worker_edit', function () {
                $wire.set_worker($(this).attr('value'));
            });

            $(document).on('click', '.btn_worker_delete', function () {
                $wire.ask_to_delete_worker($(this).attr('value'));
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
