<?php

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\SLP\Permission;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $types = [], $apps = [], $menus = [];

    public ?string $permission_id = null;

    #[Validate('required|string', as: 'Jenis')]
    public ?string $permission_type = null;

    #[Validate('required|string', as: 'Aplikasi')]
    public ?string $permission_app_id = null;

    #[Validate('required|string', as: 'Menu')]
    public ?string $permission_menu_id = null;

    #[Validate('required|string', as: 'Izin')]
    public ?string $permission_name = null;

    #[Validate('required|numeric', as: 'Urutan')]
    public ?string $permission_number = null;

    #[On('set_permission')]
    public function set_permission($permission_id)
    {
        $this->reset_permission();
        $this->permission_id = $permission_id;
        $permission = Permission::where('uuid', $this->permission_id)->first();

        $this->permission_type = $permission->type;
        $this->permission_app_id = $permission->app_id;
        $this->permission_menu_id = $permission->menu_id;

        $this->permission_name = $permission->name;
        $this->permission_number = $permission->number;
    }

    #[On('reset_permission')]
    public function reset_permission()
    {
        $this->reset(['permission_id', 'permission_type', 'permission_app_id', 'permission_menu_id', 'permission_name', 'permission_number']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    #[On('set_permission_field')]
    public function set_permission_field($field, $value)
    {
        $this->$field = $value;

        if($field == 'permission_app_id') {
            $this->menus = Menu::where('app_id', $this->permission_app_id)->orderBy('order_number')->get();
        }
    }

    public function save()
    {
        $this->validate();

        if(is_null($this->permission_id)) {
            $menu = Permission::create([
                'type' => $this->permission_type,
                'app_id' => $this->permission_app_id,
                'menu_id' => $this->permission_menu_id,
                'name' => $this->permission_name,
                'guard_name' => 'web',
                'number' => $this->permission_number,
            ]);
        } else {
            $menu = Permission::findOrFail($this->permission_id);

            $menu->update([
                'type' => $this->permission_type,
                'app_id' => $this->permission_app_id,
                'menu_id' => $this->permission_menu_id,
                'name' => $this->permission_name,
                'guard_name' => 'web',
                'number' => $this->permission_number,
            ]);
        }

        $this->dispatch('refreshDatatable');
        $this->dispatch('close_modal_permission_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->permission_id) ? 'menambah' : 'mengubah') . ' Izin')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_permission();
    }

    #[On('ask_to_delete_permission')]
    public function ask_to_delete_permission($permission_id)
    {
        $this->permission_id = $permission_id;
        $permission = Permission::find($this->permission_id);
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan menghapus Izin '.$permission->name.', Anda yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('delete_permission')
            ->show();
    }

    public function delete_permission()
    {
        $permission = Permission::find($this->permission_id);

        if($permission) {
            $permission->delete();

            $this->dispatch('refreshDatatable');

            LivewireAlert::title('')
            ->text('Berhasil menghapus Izin')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->reset_permission();
        }
    }

    public function mount()
    {
        $this->apps = App::orderBy('order_number')->get();
        $this->types = ['App', 'Menu', 'Permission'];
    }
}; ?>

<div wire:ignore.self class="modal fade" id="modal_permission_resource" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content">
            <div wire:loading.flex wire:target="set_permission, reset_permission" class="row h-px-100 justify-content-center align-items-center mb-4">
                <div class="sk-swing w-px-75 h-px-75">
                    <div class="sk-swing-dot"></div>
                    <div class="sk-swing-dot"></div>
                </div>
                <h5 class="text-center">Mengambil Data</h5>
            </div>

            <div class="modal-body p-0" wire:loading.remove wire:target="set_permission, reset_permission">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="address-title mb-2">{{ (is_null($permission_id) ? 'Tambah' : 'Edit') }} Izin</h4>
                    <p class="address-subtitle">Di sini, Anda dapat {{ (is_null($permission_id) ? 'menambah data' : 'mengubah informasi') }} Izin.</p>
                </div>
                <form wire:submit="save" method="POST">
                    @csrf
                    <x-ui::forms.select
                        wire-model="permission_type"
                        label="Jenis"
                        placeholder="Pilih Jenis"
                        container-class="col-12 mb-6"
                        init-select2-class="select2_permission"
                    >
                        @forelse($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @empty
                        @endforelse
                    </x-ui::forms.select>

                    <x-ui::forms.select
                        wire-model="permission_app_id"
                        label="Aplikasi"
                        placeholder="Pilih Aplikasi"
                        container-class="col-12 mb-6"
                        init-select2-class="select2_permission"
                        :options="$apps"
                        value-field="id"
                        text-field="name"
                    />

                    <x-ui::forms.select
                        wire-model="permission_menu_id"
                        label="Menu"
                        placeholder="Pilih Menu"
                        container-class="col-12 mb-6"
                        init-select2-class="select2_permission"
                        :options="$menus"
                        value-field="id"
                        text-field="title"
                    />

                    <x-ui::forms.input
                        wire:model.live="permission_name"
                        type="text"
                        label="Izin"
                        placeholder="Izin"
                        container_class="col-12 mb-6"
                    />

                    <x-ui::forms.input
                        wire:model.live="permission_number"
                        type="text"
                        label="Urutan"
                        placeholder="Urutan"
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
        Livewire.on('close_modal_permission_resource', () => {
            var modalElement = document.getElementById('modal_permission_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_permission");
            e_select2.length && e_select2.each(function () {
                var e_select2 = $(this);
                e_select2.wrap('<div class="position-relative"></div>').select2({
                    placeholder: "Select value",
                    allowClear: true,
                    dropdownParent: e_select2.parent()
                })
            })
        }

        $(document).ready(function () {
            initSelect2();

            $(document).on('change', '.select2_permission', function () {
                $wire.set_permission_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_permission_add', function () {
                $wire.reset_permission();
            });

            $(document).on('click', '.btn_permission_edit', function () {
                $wire.set_permission($(this).attr('value'));
            });

            $(document).on('click', '.btn_permission_delete', function () {
                $wire.ask_to_delete_permission($(this).attr('value'));
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
