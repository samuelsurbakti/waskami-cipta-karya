<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.vertical')] class extends Component {
    //
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
    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4">
            @can('AccelHr - Pekerja - Menambah Data')
                <div class="card text-center mb-6 border-top">
                    <div class="card-body">
                        <h5 class="card-title">Laporan Baru</h5>
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
                <div class="card">
                    <h5 class="card-header pb-0 pt-2 text-center">Jenis Pekerja</h5>
                    <livewire:accel-hr.worker.types-table />
                </div>
            @endcan
        </div>


    </div>

    @canany(['AccelHr - Pekerja - Menambah Data', 'AccelHr - Pekerja - Mengubah Data', 'AccelHr - Pekerja - Menghapus Data'])
        <livewire:accel-hr.worker.modal-resource />
    @endcanany

    @canany(['AccelHr - Pekerja - Jenis - Menambah Data', 'AccelHr - Pekerja - Jenis - Mengubah Data', 'AccelHr - Pekerja - Jenis - Menghapus Data'])
        <livewire:accel-hr.worker.type.modal-resource />
    @endcanany
</div>
