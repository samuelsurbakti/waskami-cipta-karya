<?php

use App\Models\Sys\App;
use App\Models\SLP\Role;
use App\Models\Sys\Menu;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\SLP\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $role, $apps;

    #[On('set_role_for_provide')]
    public function set_role_for_provide($role_id)
    {
        $this->role = Role::where('uuid', $role_id)->first();

        $this->apps = App::orderBy('order_number', 'asc')
                         ->with(['menus' => function($query) {
                             $query->orderBy('order_number', 'asc')
                                   ->with(['action_permissions' => function($query) {
                                       $query->where('type', 'Permission')->orderBy('number', 'asc');
                                   }]);
                         }])
                         ->get();
    }

    #[On('app_switch')]
    public function app_switch($app_id)
    {
        $app = App::find($app_id);
        $role = Role::find($this->role->uuid);
        $permission = Permission::where('app_id', $app_id)->where('type', 'App')->first();

        $status = $role->hasPermissionTo($permission);

        if($status) {
            $role->revokePermissionTo($permission->name);
        } else {
            $role->givePermissionTo($permission->name);
        }

        LivewireAlert::title('')
            ->text('Berhasil '.($status ? 'menarik' : 'memberikan').' akses App '.$app->name.' kepada '.$this->role->name)
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();
    }

    #[On('menu_switch')]
    public function menu_switch($menu_id)
    {
        $menu = Menu::find($menu_id);
        $role = Role::find($this->role->uuid);
        $permission = Permission::where('menu_id', $menu_id)->where('type', 'Menu')->first();

        $status = $role->hasPermissionTo($permission);

        if($status) {
            $role->revokePermissionTo($permission->name);
        } else {
            $role->givePermissionTo($permission->name);
        }

        LivewireAlert::title('')
            ->text('Berhasil '.($status ? 'menarik' : 'memberikan').' akses Menu '.$menu->title.' kepada '.$this->role->name)
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();
    }

    #[On('permission_switch')]
    public function permission_switch($permission)
    {
        $role = Role::find($this->role->uuid);

        $status = ($role->hasPermissionTo($permission) ? true : false);

        if($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }

        if($status) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }

        LivewireAlert::title('')
            ->text('Berhasil '.($status ? 'menarik' : 'memberikan').' akses Izin '.$permission.' kepada '.$this->role->name)
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();
    }

    public function mount()
    {
        $this->role = [];
        $this->apps = App::orderBy('order_number', 'asc')
                         ->with(['menus' => function($query) {
                             $query->orderBy('order_number', 'asc')
                                   ->with(['action_permissions' => function($query) {
                                       // Memuat izin bertipe 'Permission' untuk setiap menu
                                       $query->where('type', 'Permission')->orderBy('number', 'asc');
                                   }]);
                         }])
                         ->get();
    }
}; ?>

<div wire:ignore.self class="modal fade" id="modal_provide_permission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div wire:loading.flex wire:target="set_role_for_provide" class="row h-px-100 justify-content-center align-items-center my-4">
                <div class="sk-swing w-px-75 h-px-75">
                    <div class="sk-swing-dot"></div>
                    <div class="sk-swing-dot"></div>
                </div>
                <h5 class="text-center">Mengambil Data</h5>
            </div>

            <div wire:loading.remove wire:target="set_role_for_provide" class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div wire:loading.remove wire:target="set_role_for_provide" class="modal-body pt-0">
                <div class="text-center mb-4">
                    <h3 class="mb-0">Berikan Izin</h3>
                    <p>Di sini, Anda dapat mengelola Izin untuk Peran {{ ($role ? $role->name : '') }}</p>
                </div>
                <form wire:submit="save" method="POST">
                    @csrf
                    @if ($role)
                        @foreach($apps as $app)
                            <div class="d-flex my-3">
                                <div class="flex-shrink-0">
                                    <img src="/src/assets/illustrations/app/{{ $app->image }}.svg" class="h-100 me-3" alt="{{ $app->name }} App Image">
                                </div>
                                <div class="flex-grow-1 row">
                                    <div class="col-9 mb-sm-0 mb-2">
                                        <h6 class="mb-0">{{ $app->name }}</h6>
                                        <small class="text-muted">{{ $app->subdomain }}</small>
                                    </div>
                                    <div class="col-3 d-flex justify-content-end align-items-end">
                                        <label class="switch switch-square me-0">
                                            <input id="app_{{ $app->id }}" wire:click="'app_switch('{{ $app->id }}')" type="checkbox" class="switch-input" {{ ($role->hasPermissionTo($app->app_permission->name) ? 'checked' : '') }}>
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="icon-base bx bx-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                    <i class="icon-base bx bx-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="menu_app_{{ $app->id }}" {{ ($role->hasPermissionTo($app->app_permission->name) ? '' : 'hidden') }}>
                                @foreach ($app->menus as $menu)
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0 px-4">
                                        </div>
                                        <div class="flex-grow-1 row">
                                            <hr class="my-2 text-primary">
                                            <div class="col-9 mb-sm-0 mb-2">
                                                <h6 class="mb-0">{{ $menu->title }}</h6>
                                            </div>
                                            <div class="col-3 d-flex justify-content-end align-items-end">
                                                <label class="switch switch-square me-0">
                                                    <input id="menu_{{ $menu->id }}" wire:click="menu_switch('{{ $menu->id }}')" type="checkbox" class="switch-input" {{ ($role->hasPermissionTo($menu->menu_permission->name) ? 'checked' : '') }}>
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on">
                                                            <i class="icon-base bx bx-check"></i>
                                                        </span>
                                                        <span class="switch-off">
                                                            <i class="icon-base bx bx-x"></i>
                                                        </span>
                                                    </span>
                                                    <span class="switch-label"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="permission_menu_{{ $menu->id }}" {{ ($role->hasPermissionTo($menu->menu_permission->name) ? '' : 'hidden') }}>
                                        @foreach ($menu->action_permissions as $permission)
                                            <div class="d-flex mb-3">
                                                <div class="flex-shrink-0 px-5">
                                                </div>
                                                <div class="flex-grow-1 row">
                                                    <hr class="my-2 text-primary">
                                                    <div class="col-9 mb-sm-0 mb-2">
                                                        <h6 class="mb-0">{{ Str::after(Str::after($permission->name, ' | '), ' | '); }}</h6>
                                                    </div>
                                                    <div class="col-3 d-flex justify-content-end align-items-end">
                                                        <label class="switch switch-square me-0">
                                                            <input id="permission_{{ Str::replace(' ', '', $permission->name) }}" wire:click="permission_switch('{{ $permission->name }}')" type="checkbox" class="switch-input" {{ ($role->hasPermissionTo($permission->name) ? 'checked' : '') }}>
                                                            <span class="switch-toggle-slider">
                                                                <span class="switch-on">
                                                                    <i class="icon-base bx bx-check"></i>
                                                                </span>
                                                                <span class="switch-off">
                                                                    <i class="icon-base bx bx-x"></i>
                                                                </span>
                                                            </span>
                                                            <span class="switch-label"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <hr class="m-0 text-primary">
                        @endforeach
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $(document).ready(function () {
            $(document).on('click', '.btn_provide', function () {
                $wire.set_role_for_provide($(this).attr('value'));
            });
        });
    </script>
@endscript
