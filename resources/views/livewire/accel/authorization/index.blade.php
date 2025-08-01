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
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    @can('Accel - Otorisasi - Peran - Melihat Daftar Data')
        <h4 class="fw-bold py-3 mb-0">Daftar Peran</h4>
        <div class="row g-6">
            @foreach($roles as $role)
                <livewire:accel.authorization.role.item :$role :key="$role->uuid" />
            @endforeach

            @can('Accel - Otorisasi - Peran - Menambah Data')
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card h-100">
                        <div class="row h-100">
                            <div class="col-sm-4 p-0 ps-2">
                                <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                                    <img src="/src/assets/illustrations/add-role.svg" class="img-fluid h-px-120" alt="Add Role Illustration">
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
            @endcan
        </div>
    @endcan

    @can('Accel - Otorisasi - Izin - Melihat Daftar Data')
        <div class="row mt-12">
            <h4 class="fw-bold py-3 mb-0">Daftar Izin</h4>

            <livewire:accel.authorization.permissions-table />
        </div>
    @endcan

    @canany(['Accel - Otorisasi - Peran - Menambah Data', 'Accel - Otorisasi - Peran - Mengubah Data'])
        <livewire:accel.authorization.role.modal-resource />
    @endcanany

    @canany(['Accel - Otorisasi - Izin - Menambah Data', 'Accel - Otorisasi - Izin - Mengubah Data', 'Accel - Otorisasi - Izin - Menghapus Data'])
        <livewire:accel.authorization.permission.modal-resource />
    @endcanany

    @can('Accel - Otorisasi - Mengelola Otoritas')
        <livewire:accel.authorization.provide.modal-resource />
    @endcan
</div>
