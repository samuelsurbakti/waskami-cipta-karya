<?php

use App\Helpers\PageHelper;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.vertical')] class extends Component {

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
            @can('AccelHr - Kontrak - Menambah Data')
                <div class="card text-center mb-6 border-top">
                    <div class="card-body">
                        <h5 class="card-title">Kontrak Baru</h5>
                        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                            <img src="/src/assets/illustrations/add-contract.svg" class="img-fluid me-n3" alt="Image" width="120px">
                        </div>
                        <p class="card-text">Punya kontrak baru? Klik tombol di bawah untuk tambah kontrak!</p>
                        <button type="button" class="btn btn-primary" id="btn_contract_add" data-bs-target="#modal_contract_resource" data-bs-toggle="modal">
                            <span class="icon-base bx bx-plus icon-xs me-2"></span>Tambahkan
                        </button>
                    </div>
                </div>
            @endcan

            @can('AccelHr - Kontrak - Jenis - Melihat Daftar Data')
                <div class="card mb-6">
                    <h5 class="card-header pb-0 pt-3 text-center">Jenis Kontrak</h5>
                    <livewire:accel-hr.contract.types-table />
                </div>
            @endcan
        </div>

        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="row g-6" data-masonry='{"percentPosition": true }'>
                @can('AccelHr - Kontrak - Melihat Daftar Data')
                    @foreach($contracts as $contract)
                        <livewire:accel-hr.contract.item :$contract :key="$contract->id" />
                    @endforeach
                @endcan
            </div>
        </div>
    </div>

    @canany(['AccelHr - Kontrak - Menambah Data', 'AccelHr - Kontrak - Mengubah Data', 'AccelHr - Kontrak - Menghapus Data'])
        <livewire:accel-hr.contract.modal-resource />
    @endcanany

    @canany(['AccelHr - Kontrak - Jenis - Menambah Data', 'AccelHr - Kontrak - Jenis - Mengubah Data', 'AccelHr - Kontrak - Jenis - Menghapus Data'])
        <livewire:accel-hr.contract.type.modal-resource />
    @endcanany
</div>
