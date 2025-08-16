<?php

use App\Helpers\PageHelper;
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
            <div class="card text-center mb-6 border-top">
                <div class="card-body">
                    <h5 class="card-title">Hitung Gaji</h5>
                    <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                        <img src="/src/assets/illustrations/calculate-payroll.svg" class="img-fluid me-n3" alt="Image" width="120px">
                    </div>
                    <p class="card-text">Mau hitung gaji pekerja tapi malas ribet? Gampang, klik aja tombol dibawah ini!</p>
                    <button type="button" class="btn btn-label-primary" id="btn_loan_add" data-bs-target="#modal_payroll_calculate" data-bs-toggle="modal">
                        <span class="icon-base bx bx-calculator icon-xs me-2"></span>Hitung
                    </button>
                </div>
            </div>
        </div>
    </div>

    <livewire:accel-hr.payroll.modal-calculate />
</div>
