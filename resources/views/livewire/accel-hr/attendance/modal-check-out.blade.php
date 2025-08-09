<?php

use Carbon\Carbon;
use App\Helpers\FileHelper;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    use WithFileUploads;

    public $attendance_co_id, $attendance_co;

    #[Validate('required', as: 'Foto')]
    public $attendance_co_end_photo;

    #[Validate('nullable|numeric', as: 'Upah Lembur')]
    public $attendance_co_overtime_rates;

    #[Validate('nullable|numeric', as: 'Potongan Gaji')]
    public $attendance_co_docking_pay;

    public function set_attendance_check_out($attendance_co_id)
    {
        $this->reset(['attendance_co_id', 'attendance_co', 'attendance_co_end_photo', 'attendance_co_overtime_rates', 'attendance_co_docking_pay']);
        $this->resetValidation();

        $this->attendance_co_id = $attendance_co_id;

        $this->attendance_co = Attendance::findOrFail($this->attendance_co_id);
    }

    public function save()
    {
        $this->validate();

        if($this->attendance_co_end_photo){
            $path = 'img/attendance/' . date('Y') . '/' . date('n') . '/' . date('j');

            $filename = FileHelper::storeResizedImage(
                $this->attendance_co_end_photo,
                $path,
                'ACO',
                1280,
                'src'
            );
        } else {
            $filename = null;
        }

        $this->attendance_co->update([
            'end_photo' => $filename,
            'end_time' => Carbon::now(),
            'overtime_rates' => $this->attendance_co_overtime_rates,
            'docking_pay' => $this->attendance_co_docking_pay,
        ]);

        $this->dispatch('close_modal_attendance_check_out');
        $this->dispatch("refresh_attendance_component.{$this->attendance_co->id}");

        LivewireAlert::title('')
            ->text('Berhasil check out')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_attendance_check_out"
    :title="'Presensi Pulang'"
    :description="'Anda akan proses presensi pulang '.($attendance_co ? $attendance_co->contract->relation->name : '').''"
    :loading-targets="['set_attendance_check_out', 'save']"
>
    <div class="d-flex align-items-start align-items-sm-center gap-4 mb-3">
        @if ($attendance_co_end_photo)
            <img src="{{ $attendance_co_end_photo->temporaryUrl() }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
        @else
            <img src="/src/assets/illustrations/no-avatar.svg" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
        @endif

        <div class="button-wrapper">
            <div wire:loading wire:target="attendance_co_end_photo">Memeriksa File</div>
            <div wire:loading.remove wire:target="attendance_co_end_photo">
                <label for="attendance_co_end_photo" class="btn btn-sm btn-primary me-2 mb-4" tabindex="0">
                    <span class="d-none d-sm-block">Upload foto baru</span>
                    <i class="bx bx-camera d-block d-sm-none"></i>
                    <input type="file" id="attendance_co_end_photo" wire:model="attendance_co_end_photo" name="attendance_co_end_photo" hidden accept="image/*" capture="user" />
                </label>
            </div>

            <p class="text-muted mb-0">File yang diterima JPG atau PNG dengan rasio 1:1.</p>
            @error('attendance_co_end_photo')
                <div class="fv-plugins-message-container invalid-feedback" style="display: block">
                    <div data-field="attendance_co_end_photo">{{ $message }}</div>
                </div>
            @enderror
        </div>
    </div>

    <x-ui::forms.input-group
        wire:model="attendance_co_overtime_rates"
        type="text"
        label="Upah Lembur"
        placeholder="100000"
        container_class="col-12 mb-6"
        front="Rp."
    />

    <x-ui::forms.input-group
        wire:model="attendance_co_docking_pay"
        type="text"
        label="Potongan Gaji"
        placeholder="100000"
        container_class="col-12 mb-6"
        front="Rp."
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_attendance_check_out', () => {
            var modalElement = document.getElementById('modal_attendance_check_out');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        $(document).ready(function () {
            $(document).on('click', '.btn_check_out', function () {
                $wire.set_attendance_check_out($(this).attr('value'));
            });
        });
    </script>
@endscript
