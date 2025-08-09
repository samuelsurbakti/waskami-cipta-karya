<?php

use Carbon\Carbon;
use App\Helpers\PageHelper;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.vertical')] class extends Component {
    public $attendances;

    public function mount()
    {
        $this->attendances = Attendance::where('date', Carbon::now()->toDateString())->get();
    }

    #[On('re_render_attendances_container')]
    public function re_render_attendances_container()
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
            @can('AccelHr - Presensi - Check In')
                <div class="card text-center mb-6 border-top">
                    <div class="card-body">
                        <h5 class="card-title">Check In</h5>
                        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                            <img src="/src/assets/illustrations/attendance-check-in.svg" class="img-fluid me-n3" alt="Image" width="120px">
                        </div>
                        <p class="card-text">Hari baru, presensi baru? Klik tombol di bawah untuk check in!</p>
                        <button type="button" class="btn btn-primary" id="btn_attendance_check_in" data-bs-target="#modal_attendance_check_in" data-bs-toggle="modal">
                            <span class="icon-base bx bx-time icon-xs me-2"></span>Check In
                        </button>
                    </div>
                </div>
            @endcan

            @can('AccelHr - Presensi - Menambah Data')
                <div class="card text-center mb-6 border-top">
                    <div class="card-body">
                        <h5 class="card-title">Presensi Baru</h5>
                        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                            <img src="/src/assets/illustrations/add-attendance.svg" class="img-fluid me-n3" alt="Image" width="120px">
                        </div>
                        <p class="card-text">Lupa check in? Tenang data presensi bisa kamu tambahkan melalui tombol di bawah ini!</p>
                        <button type="button" class="btn btn-primary" id="btn_attendance_add" data-bs-target="#modal_attendance_resource" data-bs-toggle="modal">
                            <span class="icon-base bx bx-plus icon-xs me-2"></span>Tambahkan
                        </button>
                    </div>
                </div>
            @endcan
        </div>

        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="row g-6" data-masonry='{"percentPosition": true }'>
                @can('AccelHr - Presensi - Melihat Daftar Data')
                    @foreach($attendances as $attendance)
                        <livewire:accel-hr.attendance.item :$attendance :key="$attendance->id" />
                    @endforeach
                @endcan
            </div>
        </div>
    </div>

    @can('AccelHr - Presensi - Check In')
        <livewire:accel-hr.attendance.modal-check-in />
    @endcan

    @can('AccelHr - Presensi - Check Out')
        <livewire:accel-hr.attendance.modal-check-out />
    @endcan
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
