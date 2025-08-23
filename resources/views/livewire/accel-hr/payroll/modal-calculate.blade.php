<?php

use Carbon\Carbon;
use App\Models\Hr\Loan;
use App\Models\Hr\Worker;
use App\Models\Hr\Payroll;
use App\Models\Hr\Contract;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use App\Models\Hr\Payroll\Item;
use App\Models\Hr\Loan\Repayment;
use App\Helpers\PayrollCalculatorHelper;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $options_pay_cycle = [], $options_date_range = [];

    public $payroll_pay_cycle;
    public $payroll_date_type;
    public $payroll_start_date;
    public $payroll_end_date;

    public array $payroll_data = [];

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
        $this->dispatch('re_init_bootstrap_datepicker');
    }

    public function set_payroll_field($field, $value)
    {
        $this->$field = $value;

        if($value == 'automatic') {
            [$payroll_start_date, $payroll_end_date] = PayrollCalculatorHelper::generateRange($this->payroll_pay_cycle);
            $this->payroll_start_date = Carbon::parse($payroll_start_date)->isoFormat('YYYY-MM-DD');
            $this->payroll_end_date = Carbon::parse($payroll_end_date)->isoFormat('YYYY-MM-DD');
        }
    }

    public function calculate()
    {
        // Kosongkan data sebelumnya
        $this->payroll_data = [];

        $contracts = Contract::with(['relation', 'relation.loans', 'attendances' => function($query) {
                $query->whereBetween('date', [$this->payroll_start_date, $this->payroll_end_date]);
            }])
            ->where('pay_cycle', $this->payroll_pay_cycle)
            ->whereNull('end_date')
            ->get();

        $processedEntities = collect();

        foreach ($contracts as $contract) {
            $entityId = $contract->relation_id;
            $entityType = $contract->relation_type;
            if ($processedEntities->contains($entityType . '-' . $entityId)) {
                continue;
            }
            $processedEntities->push($entityType . '-' . $entityId);

            $attendances = $contract->attendances;

            $totalSalary = $attendances->sum(function ($attendance) use ($contract) {
                return $contract->rates + $attendance->overtime_rates - $attendance->docking_pay;
            });

            // Periksa jika tidak ada data kehadiran, jangan tambahkan ke list
            if($totalSalary == 0) {
                continue;
            }

            $loans = $contract->relation->loans->where('status', 'ongoing');
            $totalLoan = $loans->sum('remaining_repayments');
            $netSalary = $totalSalary;

            $attendancesWithRates = $attendances->map(function($attendance) use ($contract) {
                $attendance->rates = $contract->rates;
                $attendance->formatted_date = $attendance->date->isoFormat('dddd, DD MMMM YYYY');
                return $attendance;
            })->toArray();

            $this->payroll_data[] = [
                'entity_id' => $entityId,
                'entity_name' => $contract->relation->name ?? 'N/A',
                'contract_id' => $contract->id,
                'total_salary' => $totalSalary,
                'total_loan' => $totalLoan,
                'net_salary' => $netSalary,
                'start_date' => $this->payroll_start_date,
                'end_date' => $this->payroll_end_date,
                'attendances' => $attendancesWithRates,
                // Properti baru untuk pembayaran pinjaman
                'loan_payment' => null,
                'pay_off_loan' => false,
            ];
        }
    }

    public function updated($key, $value)
    {
        if (str_starts_with($key, 'payroll_data.')) {
            // Memecah string key untuk mendapatkan index dan properti yang diubah
            $parts = explode('.', $key);

            // Memastikan key memiliki format yang benar (contoh: 'payroll_data.0.pay_off_loan')
            if (count($parts) < 3) {
                return;
            }

            $index = $parts[1];
            $property = $parts[2];

            // Memastikan index yang diberikan ada dalam array payroll_data
            if (!isset($this->payroll_data[$index])) {
                return;
            }

            if ($property === 'pay_off_loan') {
                // Jika checkbox di-klik, atur loan_payment
                if ($value) {
                    $this->payroll_data[$index]['loan_payment'] = $this->payroll_data[$index]['total_loan'] ?? 0;
                } else {
                    $this->payroll_data[$index]['loan_payment'] = null;
                }
            }

            // Hitung ulang gaji bersih
            // Menggunakan null coalescing operator untuk mencegah error jika key tidak ada
            $totalSalary = $this->payroll_data[$index]['total_salary'] ?? 0;
            $loanPayment = (float) ($this->payroll_data[$index]['loan_payment'] ?? 0);
            $this->payroll_data[$index]['net_salary'] = $totalSalary - $loanPayment;
        }
    }

    public function save()
    {
        // $this->validate();

        foreach ($this->payroll_data as $data) {
            $payroll = Payroll::updateOrCreate(
                [
                    'contract_id' => $data['contract_id'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date']
                ],
                [
                    'status' => 'draft'
                ]
            );

            foreach ($data['attendances'] as $attendance) {
                Item::updateOrCreate(
                    ['payroll_id' => $payroll->id, 'type' => 'addition', 'relation_type' => Attendance::class, 'relation_id' => $attendance['id']],
                    ['amount' => $attendance['rates'] + $attendance['overtime_rates'] - $attendance['docking_pay']]
                );
            }

            if($data['loan_payment']) {
                $contract = Contract::find($data['contract_id']);
                $worker = Worker::find($contract->relation_id);
                $loans = Loan::where('worker_id', $worker->id)->where('status', 'ongoing')->orderBy('loan_date', 'asc')->get();
                $remainingPayment = $data['loan_payment'];

                foreach ($loans as $loan) {
                    if ($remainingPayment <= 0) {
                        break;
                    }

                    $paymentAmount = min($loan->remaining_repayments, $remainingPayment);

                    $repayment = Repayment::create([
                        'loan_id' => $loan->id,
                        'worker_id' => $worker->id,
                        'amount' => $paymentAmount,
                    ]);

                    Item::updateOrCreate(
                        ['payroll_id' => $payroll->id, 'type' => 'subtraction', 'relation_type' => Repayment::class, 'relation_id' => $repayment->id],
                        ['amount' => $paymentAmount]
                    );

                    $remainingPayment -= $paymentAmount;
                }
            }
        }

        $this->dispatch('close_modal_payroll_calculate');

        LivewireAlert::title('')
            ->text('Berhasil membuat draft gaji')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        // $this->reset_loan();
    }

    public function mount()
    {
        $this->options_pay_cycle = [['label' => 'Bulanan', 'value' => 'monthly'], ['label' => 'Mingguan', 'value' => 'weekly']];
        $this->options_date_range = [['label' => 'Otomatis', 'value' => 'automatic'], ['label' => 'Manual', 'value' => 'manual']];
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_payroll_calculate"
    :title="'Hitung Gaji'"
    :description="'Di sini, Anda dapat menghitung gaji pekerja sebelumnya menyimpannya.'"
    :loading-targets="['set_payroll', 'calculate', 'save']"
    size="xl"
    :default_button="false"
>
    @csrf
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <x-ui::forms.select
                wire-model="payroll_pay_cycle"
                label="Siklus Pembayaran"
                placeholder="Pilih Siklus Pembayaran"
                container-class="col-12 mb-6"
                init-select2-class="select2_payroll"
            >
                @foreach ($options_pay_cycle as $option_pay_cycle)
                    <option value="{{ $option_pay_cycle['value'] }}">{{ $option_pay_cycle['label'] }}</option>
                @endforeach
            </x-ui::forms.select>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <x-ui::forms.select
                wire-model="payroll_date_type"
                label="Jenis Periode"
                placeholder="Pilih Jenis Periode"
                container-class="col-12 mb-6"
                init-select2-class="select2_payroll"
            >
                @foreach ($options_date_range as $option_date_range)
                    <option value="{{ $option_date_range['value'] }}">{{ $option_date_range['label'] }}</option>
                @endforeach
            </x-ui::forms.select>
        </div>
    </div>

    @if ($payroll_date_type == 'manual')
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <x-ui::forms.input
                    wire:model="payroll_start_date"
                    type="text"
                    label="Tanggal Awal"
                    placeholder="2025-12-21"
                    container_class="col-12 mb-6"
                />
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <x-ui::forms.input
                    wire:model="payroll_end_date"
                    type="text"
                    label="Tanggal Akhir"
                    placeholder="2025-12-21"
                    container_class="col-12 mb-6"
                />
            </div>
        </div>
    @endif

    @if (!empty($payroll_data))
        <div class="row g-6">
            @foreach ($payroll_data as $index => $data)
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-1">
                        <div class="card-header pb-2">
                            <div class="d-flex align-items-start">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <h5 class="mb-0">
                                            {{ $data['entity_name'] }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-2">Data Kehadiran</h6>

                            <ul class="p-0 m-0">
                                @foreach ($data['attendances'] as $attendance)
                                    <li class="d-flex mb-2 flex-column border-bottom pb-2">
                                        <h6 class="mb-2">{{ $attendance['formatted_date'] }}</h6>
                                        <div class="d-flex justify-content-between flex-column flex-md-row">
                                            <div class="me-2">
                                                <small class="d-block fw-medium text-info">{{ Number::currency($attendance['rates'] ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</small>
                                            </div>
                                            <div class="me-2">
                                                <small class="d-block fw-medium text-success">{{ Number::currency($attendance['overtime_rates'] ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</small>
                                            </div>
                                            <div class="me-2">
                                                <small class="d-block fw-medium text-warning">{{ Number::currency($attendance['docking_pay'] ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</small>
                                            </div>
                                            <div class="me-2">
                                                <small class="d-block fw-medium text-wck">{{ Number::currency($attendance['rates'] + $attendance['overtime_rates'] - $attendance['docking_pay'], in: 'IDR', locale: 'id', precision: 0) }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                <li class="d-flex flex-column">
                                    <div class="d-flex justify-content-between">
                                        <div class="me-2">
                                            <h6 class="text-wck fw-bold">Total</h6>
                                        </div>
                                        <div class="me-2">
                                            <small class="d-block fw-medium text-wck">{{ Number::currency($data['total_salary'], in: 'IDR', locale: 'id', precision: 0) }}</small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        @if ($data['total_loan'] != 0)
                            <div class="card-body border-top">
                                <h6 class="mb-2">Pembayaran Pinjaman</h6>
                                <p class="mb-2">Sisa Pinjaman: <span class="text-danger">{{ Number::currency($data['total_loan'], in: 'IDR', locale: 'id', precision: 0) }}</span></p>
                                @if (!$payroll_data[$index]['pay_off_loan'])
                                    <x-ui::forms.input-group
                                        wire:model.live.debounce.500ms="payroll_data.{{ $index }}.loan_payment"
                                        type="text"
                                        label="Nominal"
                                        placeholder="100000"
                                        container_class="col-12 mb-6"
                                        front="Rp."
                                    />
                                @endif
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" wire:model.live="payroll_data.{{ $index }}.pay_off_loan" id="pay-off-{{ $index }}" />
                                    <label class="form-check-label" for="pay-off-{{ $index }}">Lunasi</label>
                                </div>
                            </div>
                        @endif
                        <div class="card-footer d-flex pt-4 justify-content-between border-top">
                            <h6 class="mb-0 text-wck fw-bold">Gaji Bersih</h6>
                            <h6 class="mb-0 text-wck fw-bold">{{ Number::currency($data['net_salary'], in: 'IDR', locale: 'id', precision: 0) }}</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="col-12 text-center mt-8">
        <x-ui::elements.button type="submit" class="btn-success me-sm-3 me-1">
            Buat Draft
        </x-ui::elements.button>
        <x-ui::elements.button type="reset" class="btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
            Batalkan
        </x-ui::elements.button>
    </div>
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_payroll_calculate', () => {
            var modalElement = document.getElementById('modal_payroll_calculate');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_payroll");
            e_select2.length && e_select2.each(function () {
                var e_select2 = $(this);
                e_select2.wrap('<div class="position-relative"></div>').select2({
                    placeholder: "Select value",
                    allowClear: true,
                    dropdownParent: e_select2.parent()
                })
            })
        }

        function init_bootstrap_datepicker() {
            var date = $("#payroll_start_date, #payroll_end_date").datepicker({
                todayHighlight: !0,
                format: "yyyy-mm-dd",
                language: 'id',
                orientation: isRtl ? "auto right" : "auto left",
                autoclose: true
            })
        }

        $(document).ready(function () {
            initSelect2();
            init_bootstrap_datepicker();

            $(document).on('change', '.select2_payroll, #payroll_start_date, #payroll_end_date', function () {
                $wire.set_payroll_field($(this).attr('id'), $(this).val());
                $wire.calculate();
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })

            window.Livewire.on('re_init_bootstrap_datepicker', () => {
                setTimeout(init_bootstrap_datepicker, 0)
            })
        });
    </script>
@endscript
