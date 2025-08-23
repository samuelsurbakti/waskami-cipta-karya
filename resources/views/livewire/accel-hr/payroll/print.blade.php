<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Hr\Payroll;
use Livewire\Volt\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

new class extends Component {
    public $payroll_print_start_date, $payroll_print_end_date;

    public function set_payroll_print_field($field, $value)
    {
        $this->$field = $value;
        $this->dispatch('re_init_masonry');
    }

    public function print_payroll_report()
    {
        $payrolls = Payroll::where('start_date', $this->payroll_print_start_date)->where('end_date', $this->payroll_print_end_date)->get();

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Instansiasi Dompdf dan panggil view
        $dompdf = new Dompdf($options);
        $html = View::make('accel-hr.payroll.export-batch', ['payrolls' => $payrolls])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response()->stream(function () use ($dompdf) {
            echo $dompdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-gaji-'.$this->payroll_print_start_date.'-'.$this->payroll_print_end_date.'.pdf"',
        ]);
    }
}; ?>

<div class="card mb-6 border-top">
    <div class="card-body">
        <h5 class="card-title text-center">Cetak Laporan</h5>
        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
            <img src="/src/assets/illustrations/print-payroll.svg" class="img-fluid me-n3" alt="Image" width="120px">
        </div>
        <p class="card-text text-center">Mau cetak laporan gaji? Draft atau Final semua bisa dicetak, pilih tanggalnya aja ya!</p>

        <x-ui::forms.input
            id="payroll_print_start_date"
            wire:model="payroll_print_start_date"
            type="text"
            label="Tanggal Awal"
            placeholder="2025-12-30"
            container_class="col-12 mb-6"
        />

        <x-ui::forms.input
            id="payroll_print_end_date"
            wire:model="payroll_print_end_date"
            type="text"
            label="Tanggal Akhir"
            placeholder="2025-12-30"
            container_class="col-12 mb-6"
        />

        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-label-primary" id="btn_payroll_print">
                <span class="icon-base bx bx-printer icon-xs me-2"></span>Cetak
            </button>
        </div>
    </div>
</div>


@script
    <script>
        $(document).ready(function () {
            function init_bootstrap_datepicker() {
                var date = $("#payroll_print_start_date, #payroll_print_end_date").datepicker({
                    todayHighlight: !0,
                    format: "yyyy-mm-dd",
                    language: 'id',
                    orientation: isRtl ? "auto right" : "auto left",
                    autoclose: true
                })
            }

            init_bootstrap_datepicker();

            $(document).on('click', '#btn_payroll_print', function () {
                $wire.print_payroll_report();
            });

            $(document).on('change', '#payroll_print_start_date, #payroll_print_end_date', function () {
                $wire.set_payroll_print_field($(this).attr('id'), $(this).val());
            });
        });
    </script>
@endscript
