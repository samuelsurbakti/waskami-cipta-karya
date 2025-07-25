<?php

use App\Models\SLP\Role;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.horizontal')] class extends Component {
    public $roles;

    public function mount()
    {
        $this->roles = (auth()->user()->getRoleNames()->first() == 'Developer' ? Role::withCount('users')->get() : Role::where('name', '!=', 'Developer')->withCount('users')->get());
    }

    #[On('re_render_roles_container')]
    public function reRenderRolesContainer()
    {
        $this->mount();
    }
}; ?>

@push('page_styles')
    <link rel="stylesheet" href="/themes/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="/themes/vendor/libs/sweetalert2/sweetalert2.css" />

    {{-- Select2 --}}
    <link rel="stylesheet" href="/themes/vendor/libs/select2/select2.css" />
@endpush

@push('page_scripts')
    <script src="/themes/vendor/libs/sweetalert2/sweetalert2.js"></script>

    {{-- Select2 --}}
    <script src="/themes/vendor/libs/select2/select2.js"></script>

    @include('js.accel.authorization.index')
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-0">Daftar Peran</h4>
    <div class="row g-4 mb-4">
        @foreach($roles as $role)
            <livewire:accel.authorization.role.item :$role :key="$role->uuid" />
        @endforeach

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="row h-100">
                    <div class="col-sm-4 p-0 ps-2">
                        <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                            <img src="/src/assets/illustrations/add-role.svg" class="img-fluid me-n3" alt="Add Role Illustration">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="card-body text-sm-end text-center ps-sm-0 p-4">
                            <p class="mb-0">Butuh peran tambahan? Klik tombol dibawah ini untuk menambah peran.</p>
                            <x-ui::elements.button
                                wire:click="$dispatch('reset_role')"
                                class="btn btn-sm btn-primary mt-3 text-nowrap"
                                title="{{ $role->name }}"
                                data-bs-toggle="modal"
                                data-bs-target="#modal_role_resource"
                            >
                                Tentu
                            </x-ui::elements.button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <h4 class="fw-bold py-3 mb-0">Daftar Izin</h4>

        <livewire:accel.authorization.permissions-table />
    </div>

    <livewire:accel.authorization.role.modal-resource />
    <livewire:accel.authorization.permission.modal-resource />
    <livewire:accel.authorization.provide.modal-resource />
</div>
