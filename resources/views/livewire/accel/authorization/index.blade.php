<?php

use App\Models\SLP\Role;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.horizontal')] class extends Component {
    public $roles;

    public function mount()
    {
        $this->roles = (auth()->user()->getRoleNames()->first() == 'Developer' ? Role::withCount('users')->get() : Role::where('name', '!=', 'Developer')->withCount('users')->get());
    }
}; ?>

@push('page_styles')
    <link rel="stylesheet" href="/themes/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="/themes/vendor/libs/sweetalert2/sweetalert2.css" />
@endpush

@push('page_scripts')
    <script src="/themes/vendor/libs/sweetalert2/sweetalert2.js"></script>

    @include('js.accel.authorization.index')
@endpush

<div class="row">
    <h4 class="fw-bold py-3 mb-2">Daftar Hak Akses</h4>

    @foreach($roles as $role)
        <livewire:accel.authorization.role.item :$role :key="$role->uuid" />
    @endforeach

    @livewire('accel.authorization.role.modal-resource')
</div>
