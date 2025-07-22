<?php

use App\Models\SLP\Role;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public Role $role;
    public int $roleUsersCount;
    public $users = [];

    public function mount()
    {
        $this->role->loadCount('users');
        $this->role->load('users');

        $this->roleUsersCount = $this->role->users_count;
        $this->users = $this->role->users;
    }

    #[On('refresh_role_component{role.uuid}')]
    public function refreshComponent()
    {
        $this->role->loadCount('users');
        $this->role->load('users');
        $this->roleUsersCount = $this->role->users_count;
        $this->users = $this->role->users;
    }

    public function openModalHakAksesIzin()
    {
        $this->dispatch('role_permission_edit', role_id: $this->role->uuid);
    }
}; ?>

<div class="col-xl-3 col-lg-6 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h6 class="fw-normal">Total {{ $roleUsersCount }} akun</h6>
                @if($roleUsersCount != 0)
                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                    @php $no = 1; @endphp
                    @foreach($users as $user)
                        @if($no <= 5)
                            <li class="avatar avatar-sm pull-up">
                                <img class="rounded-circle" src="{{ asset('src/img/user/'.$user->avatar) }}" alt="{{ $user->name }} Avatar" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $user->name }}">
                            </li>
                        @endif
                        @php $no++; @endphp
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="grid justify-content-between align-items-end">
                <div class="role-heading">
                    <h4 class="mb-1">{{ $role->name }}</h4>
                </div>
                <div class="d-flex justify-content-between">
                    <x-ui::elements.button
                        wire:click="$dispatch('set_role', { role_id: '{{ $role->uuid }}' })"
                        class="bg-primary-subtle text-primary btn_authorization"
                        title="{{ $role->name }}"
                        data-bs-toggle="modal"
                        data-bs-target="#modal_authorization"
                    >
                        <small>Kelola Izin</small>
                    </x-ui::elements.button>
                    <x-ui::elements.button
                        wire:click="$dispatch('set_role', { role_id: '{{ $role->uuid }}' })"
                        class="bg-success-subtle text-success btn_role_edit"
                        title="{{ $role->name }}"
                        data-bs-toggle="modal"
                        data-bs-target="#modal_role_resource"
                    >
                        <small>Edit Hak Akses</small>
                    </x-ui::elements.button>
                </div>
            </div>
        </div>
    </div>
</div>
