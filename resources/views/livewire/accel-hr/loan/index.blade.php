<?php

use App\Models\Hr\Loan;
use App\Helpers\PageHelper;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.vertical')] class extends Component {
    public $loans;

    public function mount()
    {
        $this->loans = Loan::orderByDesc('loan_date')->get();
    }

    #[On('re_render_loans_container')]
    public function re_render_loans_container()
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
            @can('AccelHr - Pinjaman - Menambah Data')
                <div class="card text-center mb-6 border-top">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Pinjaman</h5>
                        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                            <img src="/src/assets/illustrations/add-loan.svg" class="img-fluid me-n3" alt="Image" width="120px">
                        </div>
                        <p class="card-text">Pekerja butuh pinjaman? Kamu bisa tambahkan data pinjamannya melalui tombol di bawah ini!</p>
                        <button type="button" class="btn btn-label-primary" id="btn_loan_add" data-bs-target="#modal_loan_resource" data-bs-toggle="modal">
                            <span class="icon-base bx bx-plus icon-xs me-2"></span>Tambahkan
                        </button>
                    </div>
                    <div class="card-body border-top">
                        <p class="card-text">Atau beberapa pekerja yang butuh pinjaman? Tapi penanggung jawabnya 1 orang? Klik tombol dibawah ini!</p>
                        <button type="button" class="btn btn-primary" id="btn_attendance_check_in" data-bs-target="#modal_attendance_check_in" data-bs-toggle="modal">
                            <span class="icon-base bx bx-plus icon-xs me-2"></span>Tambahkan
                        </button>
                    </div>
                </div>
            @endcan
        </div>

        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="row g-6" data-masonry='{"percentPosition": true }'>
                @can('AccelHr - Pinjaman - Melihat Daftar Data')
                    @foreach($loans as $loan)
                        <livewire:accel-hr.loan.item :$loan :key="$loan->id" />
                    @endforeach
                @endcan
            </div>
        </div>
    </div>

    @canany(['AccelHr - Pinjaman - Menambah Data', 'AccelHr - Pinjaman - Mengubah Data', 'AccelHr - Pinjaman - Menghapus Data'])
        <livewire:accel-hr.loan.modal-resource />
    @endcanany
</div>

@script
    <script>
        $(document).ready(function () {
            function initMasonry() {
                let grid = document.querySelector('[data-masonry]');
                if (grid) {
                    new Masonry(grid, JSON.parse(grid.dataset.masonry || '{}'));
                }
            }

            window.Livewire.on('re_init_masonry', () => {
                setTimeout(initMasonry, 0)
            })
        });
    </script>
@endscript
