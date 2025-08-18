<?php

use Carbon\Carbon;
use App\Models\Hr\Contract;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use App\Helpers\PayrollCalculatorHelper;

new class extends Component {
    public $options_pay_cycle = [], $options_date_range = [];

    public $payroll_contract_owners = [];
    public $payroll_active_contracts = [];

    public $payroll_pay_cycle;
    public $payroll_date_type;
    public $payroll_start_date;
    public $payroll_end_date;
    public $payroll_contracts;
    public $payrolls = [];

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
        $this->dispatch('re_init_bootstrap_datepicker');
    }

    public function set_payroll_field($field, $value)
    {
        $this->$field = $value;
    }

    public function calculate_payroll()
    {
        $this->get_contract_owners();
    }

    public function get_active_contracts()
    {
        $this->payroll_active_contracts = Contract::where('pay_cycle', $this->payroll_pay_cycle)->whereNull('end_date')->get();
    }

    public function get_contract_owners()
    {
        $this->payroll_contract_owners = Contract::where('pay_cycle', $this->payroll_pay_cycle)->whereNull('end_date')->pluck(['relation_type', 'relation_id']);
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
    :loading-targets="['set_payroll', 'reset_payroll', 'save']"
    size="xl"
    :default_button="false"
>
    @csrf
    <div class="row">
        <div class="col-6">
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

        <div class="col-6">
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
            <div class="col-6">
                <x-ui::forms.input
                    wire:model="payroll_start_date"
                    type="text"
                    label="Tanggal Awal"
                    placeholder="2025-12-21"
                    container_class="col-12 mb-6"
                />
            </div>

            <div class="col-6">
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

    <div class="row">
        @foreach ($payroll_contract_owners as $contract_owner)
            <div class="col-sm-4">
                <div class="card border">
                    {{ $contract_owner->relation_id }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- @if ($payroll_start_date && $payroll_end_date)
        @php
            $total = 0;
        @endphp
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>/</th>
                        @for ($date = $payroll_start_date->copy(); $date->lte($payroll_end_date->copy()); $date->addDay())
                            <th>{{ Carbon::parse($date)->isoFormat('DD MMMM YYYY') }}</th>
                        @endfor
                        <th>Pinjaman</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payroll_contracts as $payroll_contract)
                        <tr>
                            @php
                                $contract = PayrollCalculatorHelper::get_contract($payroll_contract);
                                $subtotal = 0;
                            @endphp
                            <th>{{ $contract->relation->name }}</th>
                            @for ($date = $payroll_start_date->copy(); $date->lte($payroll_end_date->copy()); $date->addDay())
                                @php
                                    $attendance = PayrollCalculatorHelper::get_attendance_amount_by_contract_date($payroll_contract, Carbon::parse($date)->isoFormat('YYYY-MM-DD'));
                                @endphp

                                <td>
                                    @if ($attendance)
                                        @php
                                            $subtotal += $attendance->contract->rates;
                                        @endphp
                                        <span class="badge bg-label-info d-flex align-items-center gap-1 mb-1"><i class="icon-base bx bx-money"></i>{{ $attendance->contract->rates }}</span><br>

                                        @if($attendance->overtime_rates)
                                            @php
                                                $subtotal += $attendance->overtime_rates;
                                            @endphp
                                            <span class="badge bg-label-success d-flex align-items-center gap-1 mb-1">+ {{ $attendance->overtime_rates }}</span><br>
                                        @endif

                                        @if ($attendance->docking_pay)
                                            @php
                                                $subtotal -= $attendance->docking_pay;
                                            @endphp
                                            <span class="badge bg-label-danger d-flex align-items-center gap-1">- {{ $attendance->docking_pay }}</span>
                                        @endif
                                    @endif
                                </td>
                            @endfor
                            <td>
                                @php
                                    $loan = PayrollCalculatorHelper::get_loan($contract->relation_id);
                                @endphp

                                @if ($loan)
                                    @php
                                        $subtotal -= $loan->amount;
                                        $total += $subtotal;
                                    @endphp
                                    <span class="badge bg-label-warning d-flex align-items-center gap-1 mb-1"><i class="icon-base bx bx-money"></i>{{ $loan->amount }}</span><br>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-wck d-flex align-items-center gap-1 mb-1"><i class="icon-base bx bx-money"></i>{{ $subtotal }}</span><br>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $total }}
    @endif --}}
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

            $(document).on('change', '.select2_payroll, #loan_payroll_date', function () {
                $wire.set_payroll_field($(this).attr('id'), $(this).val());
                $wire.calculate_payroll();
            });

            $(document).on('click', '#btn_payroll_add', function () {
                $wire.reset_payroll();
            });

            $(document).on('click', '.btn_payroll_edit', function () {
                $wire.set_payroll($(this).attr('value'));
            });

            $(document).on('click', '.btn_payroll_delete', function () {
                $wire.ask_to_delete_payroll($(this).attr('value'));
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
