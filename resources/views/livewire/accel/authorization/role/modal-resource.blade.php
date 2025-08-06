<?php

use App\Models\SLP\Role;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public ?string $role_id = null;

    #[Validate('required|string', as: 'Nama')]
    public string $role_name = '';

    #[On('set_role')]
    public function set_role($role_id)
    {
        $this->role_id = $role_id;

        $role = Role::where('uuid', $this->role_id)->firstOrFail();
        $this->role_name = $role->name;
    }

    #[On('reset_role')]
    public function reset_role()
    {
        $this->reset(['role_id', 'role_name']);
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if (is_null($this->role_id)) {
            $role = Role::create([
                'name' => $this->role_name,
                'guard_name' => 'web',
            ]);

            $this->dispatch("re_render_roles_container");
        } else {
            $role = Role::where('uuid', $this->role_id)->firstOrFail();
            $role->update(['name' => $this->role_name]);

            $this->dispatch("refresh_role_component{$role->uuid}");
        }

        $this->dispatch('close_modal_role_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->role_id) ? 'menambah' : 'mengubah') . ' Hak akses')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_role();
    }
}; ?>

<div wire:ignore.self class="modal fade" id="modal_role_resource" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <x-ui::elements.loading text="Mengambil Data" target="set_role, reset_role" />
            <x-ui::elements.loading text="Menyimpan Data" target="save" />

            <div wire:loading.remove wire:target="set_role, reset_role, save" class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div wire:loading.remove wire:target="set_role, reset_role, save" class="modal-body pt-0">
                <div class="text-center mb-4">
                    <h3 class="mb-0">{{ is_null($role_id) ? 'Tambah' : 'Edit' }} Peran</h3>
                    <p>Di sini, Anda dapat {{ is_null($role_id) ? 'menambah data' : 'mengubah informasi' }} Peran.</p>
                </div>

                <form wire:submit="save" method="POST">
                    @csrf
                    <x-ui::forms.input
                        wire:model.live="role_name"
                        type="text"
                        label="Nama"
                        placeholder="Admin"
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
        Livewire.on('close_modal_role_resource', () => {
            var modalElement = document.getElementById('modal_role_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        $(document).ready(function () {
            $(document).on('click', '.btn_role_edit', function () {
                $wire.set_role($(this).attr('value'));
            });
        });
    </script>
@endscript
