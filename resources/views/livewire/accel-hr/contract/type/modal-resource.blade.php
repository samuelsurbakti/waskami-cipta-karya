<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Validation\Rule;
use App\Models\Hr\Contract\Type;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public ?string $type_id = null;

    #[Validate(as: 'Nama')]
    public string $type_name = '';

    public function rules(): array
    {
        return [
            'type_name' => [
                'required',
                'string',
                Rule::unique('hr_contract_types', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($this->type_id),
            ],
        ];
    }

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
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $trashed_type = Type::onlyTrashed()->where('name', $this->type_name)->first();

        if($trashed_type) {
            $this->ask_to_restore_type($trashed_type->id);
        } else {
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

            $this->reset_type();
        }
    }

    public function ask_to_restore_type($type_id)
    {
        $this->type_id = $type_id;

        LivewireAlert::title('Peringatan')
            ->text('Jenis kontrak yang kamu masukkan sudah ada sebelumnya dan sudah dihapus, apakah kamu ingin mengembalikan data tersebut?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('restore_type')
            ->show();
    }

    public function restore_type()
    {
        $trashedType = Type::onlyTrashed()->findOrFail($this->type_id);
        $trashedType->restore();

        LivewireAlert::title('')
            ->text('Berhasil mengembalikan data jenis kontrak')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->dispatch('refreshDatatable');
            $this->dispatch('close_modal_type_resource');
            $this->reset_type();
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


<x-ui::elements.modal-form
    id="modal_type_resource"
    :title="(is_null($type_id) ? 'Tambah' : 'Edit') . ' Jenis Kontrak'"
    :description="'Di sini, Anda dapat ' . (is_null($type_id) ? 'menambah data' : 'mengubah informasi') . ' jenis kontrak.'"
    :loading-targets="['set_type', 'reset_type', 'save']"
>
    @csrf
    <x-ui::forms.input
        wire:model.live="type_name"
        type="text"
        label="Nama"
        placeholder="Beritahu saya jenisnya"
        container_class="col-12 mb-6"
    />
</x-ui::elements.modal-form>

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
