<?php

use App\Models\Sys\App;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.horizontal')] class extends Component {
    public $apps;

    public function mount()
    {
        $this->apps = App::orderBy('order_number')->get();
    }

    #[On('re_render_apps_container')]
    public function re_render_apps_container()
    {
        $this->mount();
    }
}; ?>

@push('page_styles')
    {{-- Animate --}}
    <link rel="stylesheet" href="/themes/vendor/libs/animate-css/animate.css" />

    {{-- Sweetalert2 --}}
    <link rel="stylesheet" href="/themes/vendor/libs/sweetalert2/sweetalert2.css" />

    {{-- Select2 --}}
    <link rel="stylesheet" href="/themes/vendor/libs/select2/select2.css" />
@endpush

@push('page_scripts')
    {{-- Sweetalert2 --}}
    <script src="/themes/vendor/libs/sweetalert2/sweetalert2.js"></script>

    {{-- Select2 --}}
    <script src="/themes/vendor/libs/select2/select2.js"></script>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-0">Daftar Aplikasi</h4>
    <div class="row g-6">
        @foreach($apps as $app)
            <livewire:accel.system.app.item :$app :key="$app->id" />
        @endforeach


    </div>

    <div class="row mt-12">
        @can('Accel | Sistem | Menu | Melihat Daftar Data')
            <h4 class="fw-bold py-3 mb-0">Daftar Menu</h4>

            <livewire:accel.system.menus-table />
        @endcan
    </div>

    <livewire:accel.system.app.modal-resource />
    <livewire:accel.system.menu.modal-resource />
</div>
