<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use Illuminate\Support\Facades\View;

new class extends Component {
    public $attendance_print_start_date, $attendance_print_end_date;

    public function set_attendance_print_field($field, $value)
    {
        $this->$field = $value;
        $this->dispatch('re_init_masonry');
    }

    public function print_attendance_report()
    {
        $start_date = \Carbon\Carbon::parse($this->attendance_print_start_date);
        $end_date = \Carbon\Carbon::parse($this->attendance_print_end_date);

        $attendances = Attendance::with(['contract.relation'])
            ->whereBetween('date', [$start_date, $end_date])
            ->get()
            // Kita ubah koleksi menjadi array asosiatif dengan key gabungan pekerja dan tanggal
            // agar lebih mudah diakses di view.
            ->keyBy(function($item) {
                return $item->contract->relation->name . '|' . $item->date->format('Y-m-d');
            });

        $workers = $attendances->pluck('contract.relation.name')->unique();

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Instansiasi Dompdf dan panggil view
        $dompdf = new Dompdf($options);
        $html = View::make('accel-hr.attendance.export', [
            'attendances' => $attendances,
            'workers' => $workers,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response()->stream(function () use ($dompdf) {
            echo $dompdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-kehadiran-'.$this->attendance_print_start_date.'-'.$this->attendance_print_end_date.'.pdf"',
        ]);
    }
}; ?>

<div class="card mb-6 border-top">
    <div class="card-body">
        <h5 class="card-title text-center">Cetak Laporan</h5>
        <div class="d-flex align-items-end justify-content-center mt-sm-0 mt-3">
            <img src="/src/assets/illustrations/print.svg" class="img-fluid me-n3" alt="Image" width="120px">
        </div>
        <p class="card-text text-center">Mau cetak laporan kehadiran? Pilih tanggalnya aja ya!</p>

        <x-ui::forms.input
            id="attendance_print_start_date"
            wire:model="attendance_print_start_date"
            type="text"
            label="Tanggal Awal"
            placeholder="2025-12-30"
            container_class="col-12 mb-6"
        />

        <x-ui::forms.input
            id="attendance_print_end_date"
            wire:model="attendance_print_end_date"
            type="text"
            label="Tanggal Akhir"
            placeholder="2025-12-30"
            container_class="col-12 mb-6"
        />

        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-label-primary" id="btn_attendance_print">
                <span class="icon-base bx bx-printer icon-xs me-2"></span>Cetak
            </button>
        </div>
    </div>
</div>


@script
    <script>
        $(document).ready(function () {
            function init_bootstrap_datepicker() {
                var date = $("#attendance_print_start_date, #attendance_print_end_date").datepicker({
                    todayHighlight: !0,
                    format: "yyyy-mm-dd",
                    language: 'id',
                    orientation: isRtl ? "auto right" : "auto left",
                    autoclose: true
                })
            }

            init_bootstrap_datepicker();

            $(document).on('click', '#btn_attendance_print', function () {
                $wire.print_attendance_report();
            });

            $(document).on('change', '#attendance_print_start_date, #attendance_print_end_date', function () {
                $wire.set_attendance_print_field($(this).attr('id'), $(this).val());
            });
        });
    </script>
@endscript
