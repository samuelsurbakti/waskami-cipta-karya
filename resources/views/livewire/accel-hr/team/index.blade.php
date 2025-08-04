<?php

use App\Models\Hr\Team;
use App\Helpers\PageHelper;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.vertical')] class extends Component {
    public $teams;

    public function mount()
    {
        $this->teams = Team::orderBy('name')->get();
    }

    #[On('re_render_teams_container')]
    public function re_render_teams_container()
    {
        $this->mount();
        $this->dispatch('re_init_masonry');
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

    {{-- Masonry --}}
    <script src="/themes/vendor/libs/masonry/masonry.js"></script>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <x-ui::elements.page-header :info="PageHelper::info()" />

    <div class="row g-6">
        <div class="col-xs-12 col-md-6 col-lg-4">
            @can('AccelHr - Tim - Menambah Data')
                <div class="card text-center mb-6 border-top">
                    <div class="card-body">
                        <h5 class="card-title">Tim Baru</h5>
                        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                            <img src="/src/assets/illustrations/add-team.svg" class="img-fluid me-n3" alt="Image" width="120px">
                        </div>
                        <p class="card-text">Punya tim baru? Klik tombol di bawah untuk tambah tim!</p>
                        <button type="button" class="btn btn-primary" id="btn_team_add" data-bs-target="#modal_team_resource" data-bs-toggle="modal">
                            <span class="icon-base bx bx-plus icon-xs me-2"></span>Tambahkan
                        </button>
                    </div>
                </div>
            @endcan
        </div>

        @can('AccelHr - Tim - Melihat Daftar Data')
            @foreach($teams as $team)
                <livewire:accel-hr.team.item :$team :key="$team->id" />
            @endforeach
        @endcan
    </div>

    @canany(['AccelHr - Tim - Menambah Data', 'AccelHr - Tim - Mengubah Data', 'AccelHr - Tim - Menghapus Data'])
        <livewire:accel-hr.team.modal-resource />
    @endcanany
</div>
