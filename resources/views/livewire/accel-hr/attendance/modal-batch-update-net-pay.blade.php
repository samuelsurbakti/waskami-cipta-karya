<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $attendance_bunp_date = null;

    public $attendance_bunp_id = [];
    public $attendance_bunp_contract = [];
    public $attendance_bunp_worker = [];

    #[Validate(['attendance_bunp_overtime_rates.*' => 'nullable|numeric'])]
    public $attendance_bunp_overtime_rates = [];

    #[Validate(['attendance_bunp_docking_pay.*' => 'nullable|numeric'])]
    public $attendance_bunp_docking_pay = [];

    protected $validationAttributes = [
        'attendance_bunp_overtime_rates.*' => 'Upah Lembur',
        'attendance_bunp_docking_pay.*' => 'Potongan Gaji',
    ];

    public function set_attendance_batch_update_net_pay($attendance_bunp_date)
    {
        $this->reset(['attendance_bunp_date', 'attendance_bunp_id', 'attendance_bunp_overtime_rates', 'attendance_bunp_docking_pay']);
        $this->resetValidation();

        $this->attendance_bunp_date = $attendance_bunp_date;

        $attendance_bunps = Attendance::where('date', $this->attendance_bunp_date)->get();

        foreach ($attendance_bunps as $attendance) {
            $this->attendance_bunp_id[$attendance->id] = $attendance->id;
            $this->attendance_bunp_contract[$attendance->id] = $attendance->contract->title;
            $this->attendance_bunp_worker[$attendance->id] = $attendance->contract->relation->name;
            $this->attendance_bunp_overtime_rates[$attendance->id] = $attendance->overtime_rates;
            $this->attendance_bunp_docking_pay[$attendance->id] = $attendance->docking_pay;
        }
    }

    public function save()
    {
        $this->validate();

        foreach ($this->attendance_bunp_overtime_rates as $id => $value) {
            $this->attendance_bunp_overtime_rates[$id] = trim($value) === '' ? null : $value;
        }

        foreach ($this->attendance_bunp_docking_pay as $id => $value) {
            $this->attendance_bunp_docking_pay[$id] = trim($value) === '' ? null : $value;
        }

        foreach($this->attendance_bunp_id as $id => $value) {
            Attendance::where('id', $id)->update([
                'overtime_rates' => $this->attendance_bunp_overtime_rates[$id] ?? null,
                'docking_pay' => $this->attendance_bunp_docking_pay[$id] ?? null,
            ]);

            $this->dispatch("refresh_attendance_component.{$id}");
        }

        $this->dispatch('close_modal_batch_update_net_pay');

        LivewireAlert::title('')
            ->text('Berhasil update upah lembur dan potong gaji')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_batch_update_net_pay"
    :title="'Presensi Masuk'"
    :description="'Di sini, Anda dapat mengatur upah lembur dan potongan gaji untuk tanggal '.Carbon::parse($attendance_bunp_date)->isoFormat('DD MMMM YYYY').'.'"
    :loading-targets="['set_attendance_batch_update_net_pay', 'save']"
>
    <hr class="mt-12">
    @foreach($attendance_bunp_id as $id => $value)
        <div class="row">
            <div class="col-4 pt-2">
                <h5 class="mb-0">{{ $attendance_bunp_worker[$id] }}</h5>
                <span class="fw-medium">{{ $attendance_bunp_contract[$id] }}</span>
            </div>

            <x-ui::forms.input-group
                wire:model.live="attendance_bunp_overtime_rates.{{ $id }}"
                type="text"
                label="Upah Lembur"
                placeholder="100000"
                container_class="col-4 mb-6"
                front="Rp."
            />

            <x-ui::forms.input-group
                wire:model.live="attendance_bunp_docking_pay.{{ $id }}"
                type="text"
                label="Potongan Gaji"
                placeholder="100000"
                container_class="col-4 mb-6"
                front="Rp."
            />
        </div>
        <hr>
    @endforeach
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_batch_update_net_pay', () => {
            var modalElement = document.getElementById('modal_batch_update_net_pay');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });


        $(document).ready(function () {
            $(document).on('click', '#btn_get_date_for_modal_batch_update_net_pay', function () {
                const bunp_data = $('#attendance_get_date_for_modal_batch_update_net_pay').val();
                $wire.set_attendance_batch_update_net_pay(bunp_data);
            });
        });
    </script>
@endscript
