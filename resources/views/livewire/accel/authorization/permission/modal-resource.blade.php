<?php

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\SLP\Permission;
use Livewire\Attributes\Validate;

new class extends Component {
    public $types = [], $apps = [], $menus = [];

    public ?string $permission_id = null;

    #[Validate('required|string', as: 'Jenis')]
    public string $permission_type = '';

    #[Validate('required|string', as: 'Aplikasi')]
    public string $permission_app_id = '';

    #[Validate('required|string', as: 'Menu')]
    public string $permission_menu_id = '';

    #[Validate('required|string', as: 'Izin')]
    public string $permission_name = '';

    #[Validate('required|numeric', as: 'Urutan')]
    public ?string $permission_number = null;

    #[On('set_permission')]
    public function set_permission($permission_id)
    {
        $this->reset_permission();
        $this->permission_id = $permission_id;
        $permission = Permission::where('uuid', $this->permission_id)->first();

        $this->set_permission_field('permission_type', $permission->type);
        $this->set_permission_field('permission_app_id', $permission->app_id);
        $this->set_permission_field('permission_menu_id', $permission->menu_id);

        $this->permission_name = $permission->name;
        $this->permission_number = $permission->number;
    }

    #[On('reset_permission')]
    public function reset_permission()
    {
        $this->reset(['permission_id', 'permission_type', 'permission_app_id', 'permission_menu_id', 'permission_name', 'permission_number']);
    }

    #[On('set_permission_field')]
    public function set_permission_field($field, $value)
    {
        $this->$field = $value;

        if($field == 'permission_app_id') {
            $this->menus = Menu::where('app_id', $this->permission_app_id)->orderBy('order_number')->get();
        }

        $this->dispatch('permission_field_updated');
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
            ->text('Berhasil ' . (is_null($this->role_id) ? 'menambah' : 'mengubah') . ' Izin')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset();
    }

    public function mount()
    {
        $this->apps = App::orderBy('order_number')->get();
        $this->types = ['App', 'Menu', 'Izin'];
    }
}; ?>

<div wire:ignore.self class="modal fade" id="modal_permission_resource" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="address-title mb-2">{{ (is_null($permission_id) ? 'Tambah' : 'Edit') }} Izin</h4>
                    <p class="address-subtitle">Di sini, Anda dapat {{ (is_null($permission_id) ? 'menambah data' : 'mengubah informasi') }} Izin.</p>
                </div>
                <form wire:submit="save" method="POST">
                    @csrf
                    <div class="col-12 mb-4">
                        <label class="form-label" for="permission_type">Jenis</label>
                        <select wire:model="permission_type" id="permission_type" class="form-select select2 @error('permission_type') is-invalid @enderror" style="width: 100%;" data-placeholder="Jenis">
                                <option></option>
                                @forelse($types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @empty
                                @endforelse
                            </select>
                        @error('permission_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label" for="permission_app_id">Aplikasi</label>
                        <select wire:model="permission_app_id" id="permission_app_id" class="form-select select2 @error('permission_app_id') is-invalid @enderror" style="width: 100%;" data-placeholder="Aplikasi">
                                <option></option>
                                @forelse($apps as $app)
                                    <option value="{{ $app->id }}">{{ $app->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        @error('permission_app_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label" for="permission_menu_id">Menu</label>
                        <select wire:model="permission_menu_id" id="permission_menu_id" class="form-select select2 @error('permission_menu_id') is-invalid @enderror" style="width: 100%;" data-placeholder="Menu">
                                <option></option>
                                @forelse($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                @empty
                                @endforelse
                            </select>
                        @error('permission_menu_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label" for="permission_name">Izin</label>
                        <input type="text" class="form-control @error('permission_name') is-invalid @enderror" wire:model="permission_name" placeholder="Izin" />
                        @error('permission_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label" for="permission_number">Urutan</label>
                        <input type="text" class="form-control @error('permission_number') is-invalid @enderror" wire:model="permission_number" placeholder="Urutan" />
                        @error('permission_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 text-center mt-8">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Simpan</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Batalkan</button>
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
            var e_select2 = $(".select2");
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

            $(document).on('change', '.select2', function () {
                $wire.dispatch('set_permission_field', { field: $(this).attr('id'), value: $(this).val() });
            });

            window.Livewire.on('permission_field_updated', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
