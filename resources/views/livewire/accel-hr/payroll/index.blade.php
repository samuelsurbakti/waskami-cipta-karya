<?php

use Carbon\Carbon;
use App\Models\Hr\Loan;
use App\Models\Hr\Payroll;
use App\Helpers\PageHelper;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new #[Layout('ui.layouts.vertical')] class extends Component {
    public $payrolls;

    public function mount()
    {
        $this->payrolls = Payroll::orderByDesc('start_date')->get();
    }

    #[On('re_render_payrolls_container')]
    public function re_render_payrolls_container()
    {
        $this->mount();
        $this->dispatch('re_init_masonry');
    }

    public function ask_to_process_payroll_draft()
    {
        $this->dispatch('re_init_masonry');
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan memproses semua draft gaji menjadi final, anda tidak dapat mengembalikan data yang sudah diproses secara massal. Yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('process_payroll_draft')
            ->show();
    }

    public function process_payroll_draft()
    {
        $drafts = Payroll::where('status', 'draft')->get();

        foreach ($drafts as $payroll_draft) {
            $payroll_draft->update([
                'status' => 'final'
            ]);

            foreach ($payroll_draft->repaymentItems as $repaymentItems) {
                $repaymentItems->relation->update([
                    'paid_at' => Carbon::now()
                ]);

                $loan = Loan::find($repaymentItems->relation->loan_id);
                if($loan->remaining_repayments == 0){
                    $loan->update([
                        'status' => 'paid'
                    ]);
                }
            }
        }

        $this->dispatch('re_init_masonry');

        LivewireAlert::title('')
            ->text('Berhasil memproses semua draft gaji')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();
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

            <livewire:accel-hr.payroll.print />

            <div class="card text-center mb-6 border-top">
                <div class="card-body">
                    <h5 class="card-title">Proses Draft Gaji</h5>
                    <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
                        <img src="/src/assets/illustrations/process-payroll.svg" class="img-fluid me-n3" alt="Image" width="120px">
                    </div>
                    <p class="card-text">Malas proses draft gaji satu-satu? Klik aja tombol dibawah ini, semua draft gaji akan langsung diproses!!</p>
                    <button type="button" class="btn btn-label-primary" id="btn_payroll_process_draft">
                        <span class="icon-base bx bx-receipt icon-xs me-2"></span>Proses
                    </button>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="row g-6" data-masonry='{"percentPosition": true }'>
                @can('AccelHr - Pinjaman - Melihat Daftar Data')
                    @foreach($payrolls as $payroll)
                        <livewire:accel-hr.payroll.item :$payroll :key="$payroll->id" />
                    @endforeach
                @endcan
            </div>
        </div>
    </div>

    <livewire:accel-hr.payroll.modal-calculate />
</div>

@script
    <script>
        $(document).ready(function () {
            $(document).on('click', '#btn_payroll_process_draft', function () {
                $wire.ask_to_process_payroll_draft()
            })
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
