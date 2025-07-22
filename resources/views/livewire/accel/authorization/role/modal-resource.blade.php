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
    public function resetRole()
    {
        $this->reset(['role_id', 'role_name']);
    }

    public function save()
    {
        $this->validate();

        if (is_null($this->role_id)) {
            $role = Role::create([
                'name' => $this->role_name,
                'guard_name' => 'web',
            ]);
        } else {
            $role = Role::where('uuid', $this->role_id)->firstOrFail();
            $role->update(['name' => $this->role_name]);
        }

        $this->dispatch("refresh_role_component{$role->uuid}");
        $this->dispatch('close_modal_role_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->role_id) ? 'menambah' : 'mengubah') . ' Hak akses')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset(['role_id', 'role_name']);
    }
}; ?>

<div wire:ignore.self class="modal fade" id="modal_role_resource" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="address-title mb-2">{{ is_null($role_id) ? 'Tambah' : 'Edit' }} Peran</h4>
                    <p class="address-subtitle">Di sini, Anda dapat {{ is_null($role_id) ? 'menambah data' : 'mengubah informasi' }} Peran.</p>
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
    </script>
@endscript
