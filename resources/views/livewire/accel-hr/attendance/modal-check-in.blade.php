<?php

use Carbon\Carbon;
use App\Models\Hr\Worker;
use App\Helpers\FileHelper;
use App\Models\Hr\Contract;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    use WithFileUploads;

    public $option_worker = [], $option_contract = [];

    public $attendance_ci_worker_id;

    #[Validate('required', as: 'Kontrak')]
    public $attendance_ci_contract_id;

    #[Validate('required', as: 'Foto')]
    public $attendance_ci_start_photo;

    public function reset_attendance_check_in()
    {
        $this->reset(['option_worker', 'option_contract', 'attendance_ci_worker_id', 'attendance_ci_contract_id', 'attendance_ci_start_photo']);
        $this->resetValidation();
        $this->option_worker =  Worker::whereHas('contracts', function ($query) {
                                    $query->whereHas('type', function ($query) {
                                        $query->where('name', 'harian');
                                    })->whereNull('end_date')
                                    ->whereDoesntHave('attendances', function ($subQuery) {
                                        $subQuery->whereDate('date', now());
                                    });
                                })->get();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    public function set_attendance_check_in_field($field, $value)
    {
        $this->$field = $value;

        if($field == 'attendance_ci_worker_id') {
            $this->option_contract =  Contract::where('relation_id', $value)
                                    ->whereHas('type', function ($query) {
                                        $query->where('name', 'harian');
                                    })->whereNull('end_date')
                                    ->get();
        }
    }

    public function save()
    {
        $this->validate();

        if($this->attendance_ci_start_photo){
            $path = 'img/attendance/' . date('Y') . '/' . date('n') . '/' . date('j');

            $filename = FileHelper::storeResizedImage(
                $this->attendance_ci_start_photo,
                $path,
                'ACI',
                1280,
                'src'
            );
        } else {
            $filename = null;
        }

        $attendance_check_in = Attendance::create([
            'contract_id' => $this->attendance_ci_contract_id,
            'date' => Carbon::now(),
            'start_time' => Carbon::now(),
            'start_photo' => $filename,
        ]);

        $this->dispatch('close_modal_attendance_check_in');
        $this->dispatch('re_render_attendances_container');

        LivewireAlert::title('')
            ->text('Berhasil check in')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();
    }

    public function mount()
    {
        $this->option_worker =  Worker::whereHas('contracts', function ($query) {
                                    $query->whereHas('type', function ($query) {
                                        $query->where('name', 'harian');
                                    })->whereNull('end_date');
                                })->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_attendance_check_in"
    :title="'Presensi Masuk'"
    :description="'Di sini, Anda dapat menambahkan data presensi masuk pekerja.'"
    :loading-targets="['reset_attendance_check_in', 'save']"
>
    <div class="d-flex align-items-start align-items-sm-center gap-4 mb-3">
        @if ($attendance_ci_start_photo)
            <img src="{{ $attendance_ci_start_photo->temporaryUrl() }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
        @else
            <img src="/src/assets/illustrations/no-avatar.svg" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
        @endif

        <div class="button-wrapper">
            <div wire:loading wire:target="attendance_ci_start_photo">Memeriksa File</div>
            <div wire:loading.remove wire:target="attendance_ci_start_photo">
                <label for="attendance_ci_start_photo" class="btn btn-sm btn-primary me-2 mb-4" tabindex="0">
                    <span class="d-none d-sm-block">Upload foto baru</span>
                    <i class="bx bx-camera d-block d-sm-none"></i>
                    <input type="file" id="attendance_ci_start_photo" wire:model="attendance_ci_start_photo" name="attendance_ci_start_photo" hidden accept="image/*" capture="user" />
                </label>
            </div>

            <p class="text-muted mb-0">File yang diterima JPG atau PNG dengan rasio 1:1.</p>
            @error('attendance_ci_start_photo')
                <div class="fv-plugins-message-container invalid-feedback" style="display: block">
                    <div data-field="attendance_ci_start_photo">{{ $message }}</div>
                </div>
            @enderror
        </div>
    </div>

    <x-ui::forms.select
        wire-model="attendance_ci_worker_id"
        label="Pekerja"
        placeholder="Pilih Pekerja"
        container-class="col-12 mb-6"
        init-select2-class="select2_attendance_ci"
        :options="$option_worker"
        value-field="id"
        text-field="name"
    />

    <x-ui::forms.select
        wire-model="attendance_ci_contract_id"
        label="Kontrak"
        placeholder="Pilih Kontrak"
        container-class="col-12 mb-6"
        init-select2-class="select2_attendance_ci"
        :options="$option_contract"
        value-field="id"
        text-field="title"
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_attendance_check_in', () => {
            var modalElement = document.getElementById('modal_attendance_check_in');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_attendance_ci");
            e_select2.length && e_select2.each(function () {
                var e_select2 = $(this);
                e_select2.wrap('<div class="position-relative"></div>').select2({
                    placeholder: "Select value",
                    allowClear: true,
                    dropdownParent: e_select2.parent()
                })
            })
        }

        $(document).ready(function () {
            initSelect2();

            $(document).on('change', '.select2_attendance_ci', function () {
                $wire.set_attendance_check_in_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_attendance_check_in', function () {
                $wire.reset_attendance_check_in();
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
