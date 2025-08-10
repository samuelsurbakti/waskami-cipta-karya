<?php

use App\Helpers\PageHelper;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Build\Project;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.vertical')] class extends Component {
    public $projects;

    public function mount()
    {
        $this->projects = Project::orderByDesc('start_date')->get();
    }

    #[On('re_render_projects_container')]
    public function re_render_projects_container()
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

    {{-- Boostrap Datepicker --}}
    <link rel="stylesheet" href="/themes/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css">
@endpush

@push('page_scripts')
    {{-- Sweetalert2 --}}
    <script src="/themes/vendor/libs/sweetalert2/sweetalert2.js"></script>

    {{-- Select2 --}}
    <script src="/themes/vendor/libs/select2/select2.js"></script>

    {{-- Bootstrap Datepicker --}}
    <script src="/themes/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    {{-- Masonry --}}
    <script src="/themes/vendor/libs/masonry/masonry.js"></script>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <div wire:ignore>
        <x-ui::elements.page-header :info="PageHelper::info()" />
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4">
            @can('AccelBuild - Proyek - Menambah Data')
                <div class="card text-center mb-6 border-top">
                    <div class="card-body">
                        <h5 class="card-title">Proyek Baru</h5>
                        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                            <img src="/src/assets/illustrations/add-project.svg" class="img-fluid me-n3" alt="Image" width="120px">
                        </div>
                        <p class="card-text">Punya proyek baru? Klik tombol di bawah untuk tambah proyek!</p>
                        <button type="button" class="btn btn-primary" id="btn_project_add" data-bs-target="#modal_project_resource" data-bs-toggle="modal">
                            <span class="icon-base bx bx-plus icon-xs me-2"></span>Tambahkan
                        </button>
                    </div>
                </div>
            @endcan
        </div>

        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="row g-6" data-masonry='{"percentPosition": true }'>
                @can('AccelBuild - Proyek - Melihat Daftar Data')
                    @foreach($projects as $project)
                        <livewire:accel-build.project.item :$project :key="$project->id" />
                    @endforeach
                @endcan
            </div>
        </div>
    </div>

    @canany(['AccelBuild - Proyek - Menambah Data', 'AccelBuild - Proyek - Mengubah Data', 'AccelBuild - Proyek - Menghapus Data'])
        <livewire:accel-build.project.modal-resource />
    @endcanany
</div>
