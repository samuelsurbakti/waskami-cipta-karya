<?php

use App\Models\Hr\Worker;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Helpers\BreadcrumbHelper;

new #[Layout('ui.layouts.vertical')] class extends Component {
    public $workers;

    public function mount()
    {
        $this->workers = Worker::orderBy('name')->get();
    }

    #[On('re_render_workers_container')]
    public function re_render_workers_container()
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
    <x-ui::elements.page-header :breadcrumbs="BreadcrumbHelper::generate_breadcrumbs([null])" />

    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4">
            @can('AccelHr - Pekerja - Menambah Data')
                <div class="card text-center mb-6 border-top">
                    <div class="card-body">
                        <h5 class="card-title">Pekerja Baru</h5>
                        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                            <img src="/src/assets/illustrations/add-worker.svg" class="img-fluid me-n3" alt="Image" width="120px">
                        </div>
                        <p class="card-text">Punya pekerja baru? Klik tombol di bawah untuk tambah pekerja!</p>
                        <button type="button" class="btn btn-primary" id="btn_worker_add" data-bs-target="#modal_worker_resource" data-bs-toggle="modal">
                            <span class="icon-base bx bx-plus icon-xs me-2"></span>Tambahkan
                        </button>
                    </div>
                </div>
            @endcan

            @can('AccelHr - Pekerja - Jenis - Melihat Daftar Data')
                <div class="card mb-6">
                    <h5 class="card-header pb-0 pt-3 text-center">Jenis Pekerja</h5>
                    <livewire:accel-hr.worker.types-table />
                </div>
            @endcan
        </div>

        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="row g-6" data-masonry='{"percentPosition": true }'>
                @can('AccelHr - Pekerja - Melihat Daftar Data')
                    @foreach($workers as $worker)
                        <livewire:accel-hr.worker.item :$worker :key="$worker->id" />
                    @endforeach
                @endcan
            </div>
        </div>
    </div>

    @canany(['AccelHr - Pekerja - Menambah Data', 'AccelHr - Pekerja - Mengubah Data', 'AccelHr - Pekerja - Menghapus Data'])
        <livewire:accel-hr.worker.modal-resource />
    @endcanany

    @canany(['AccelHr - Pekerja - Jenis - Menambah Data', 'AccelHr - Pekerja - Jenis - Mengubah Data', 'AccelHr - Pekerja - Jenis - Menghapus Data'])
        <livewire:accel-hr.worker.type.modal-resource />
    @endcanany
</div>

@script
    <script>
        $(document).ready(function () {
            function initMasonry() {
                let grid = document.querySelector('[data-masonry]');
                if (grid) {
                    // Re-init Masonry (dengan asumsi kamu pakai Masonry v4)
                    new Masonry(grid, JSON.parse(grid.dataset.masonry || '{}'));
                }
            }

            window.Livewire.on('re_init_masonry', () => {
                setTimeout(initMasonry, 0)
            })
        });
    </script>
@endscript
