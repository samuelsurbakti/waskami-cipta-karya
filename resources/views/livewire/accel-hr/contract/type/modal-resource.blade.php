<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Hr\Contract\Type;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public ?string $type_id = null;

    #[Validate('required|string', as: 'Nama')]
    public string $type_name = '';

    #[On('set_type')]
    public function set_type($type_id)
    {
        $this->type_id = $type_id;

        $type = Type::findOrFail($this->type_id);
        $this->type_name = $type->name;
    }

    #[On('reset_type')]
    public function reset_type()
    {
        $this->reset(['type_id', 'type_name']);
    }

    public function save()
    {
        $this->validate();

        if (is_null($this->type_id)) {
            $type = Type::create([
                'name' => $this->type_name,
            ]);
        } else {
            $type = Type::findOrFail($this->type_id);
            $type->update(['name' => $this->type_name]);
        }

        $this->dispatch('refreshDatatable');
        $this->dispatch('close_modal_type_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->type_id) ? 'menambah' : 'mengubah') . ' Jenis Kontrak')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset(['type_id', 'type_name']);
    }

    #[On('ask_to_delete_type')]
    public function ask_to_delete_type($type_id)
    {
        $this->type_id = $type_id;
        $type = Type::find($this->type_id);
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan menghapus jenis kontrak dengan nama '.$type->name.', Anda yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('delete_type')
            ->show();
    }

    public function delete_type()
    {
        $type = Type::find($this->type_id);

        if($type) {
            $type->delete();

            $this->dispatch('refreshDatatable');

            LivewireAlert::title('')
            ->text('Berhasil menghapus jenis kontrak')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->reset_type();
        }
    }
}; ?>

<div wire:ignore.self class="modal fade" id="modal_type_resource" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <x-ui::elements.loading text="Mengambil Data" target="set_type, reset_type" />
            <x-ui::elements.loading text="Menyimpan Data" target="save" />

            <div wire:loading.remove wire:target="set_type, reset_type, save" class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div wire:loading.remove wire:target="set_type, reset_type, save" class="modal-body pt-0">
                <div class="text-center mb-4">
                    <h3 class="mb-0">{{ is_null($type_id) ? 'Tambah' : 'Edit' }} Jenis Kontrak</h3>
                    <p>Di sini, Anda dapat {{ is_null($type_id) ? 'menambah data' : 'mengubah informasi' }} jenis kontrak.</p>
                </div>

                <form wire:submit="save" method="POST">
                    @csrf
                    <x-ui::forms.input
                        wire:model.live="type_name"
                        type="text"
                        label="Nama"
                        placeholder="Beritahu saya jenisnya"
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
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Livewire.on('close_modal_type_resource', () => {
            var modalElement = document.getElementById('modal_type_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        $(document).ready(function () {
            $(document).on('click', '#btn_type_add', function () {
                $wire.reset_type();
            });

            $(document).on('click', '.btn_type_edit', function () {
                $wire.set_type($(this).attr('value'));
            });

            $(document).on('click', '.btn_type_delete', function () {
                $wire.ask_to_delete_type($(this).attr('value'));
            });
        });
    </script>
@endscript
