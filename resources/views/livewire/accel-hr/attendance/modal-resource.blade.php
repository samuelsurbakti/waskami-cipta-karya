<?php

use Carbon\Carbon;
use App\Models\Hr\Worker;
use App\Helpers\FileHelper;
use App\Models\Hr\Contract;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    use WithFileUploads;

    public $option_worker = [], $option_contract = [];

    public $attendance_id;

    public $attendance_worker_id;

    public $attendance_start_photo_recent, $attendance_end_photo_recent;

    #[Validate('required', as: 'Kontrak')]
    public $attendance_contract_id;

    #[Validate('required', as: 'Tanggal')]
    public $attendance_date;

    #[Validate('required', as: 'Waktu Masuk')]
    public $attendance_start_time;

    #[Validate('nullable', as: 'Foto Masuk')]
    public $attendance_start_photo;

    #[Validate('required', as: 'Waktu Pulang')]
    public $attendance_end_time;

    #[Validate('nullable', as: 'Foto Pulang')]
    public $attendance_end_photo;

    #[Validate('nullable|numeric', as: 'Upah Lembur')]
    public $attendance_overtime_rates;

    #[Validate('nullable|numeric', as: 'Potongan Gaji')]
    public $attendance_docking_pay;

    public function set_attendance($attendance_id)
    {
        $this->attendance_id = $attendance_id;

        $attendance = Attendance::findOrFail($this->attendance_id);

        $this->set_attendance_field('attendance_worker_id', $attendance->contract->relation_id);
        $this->set_attendance_field('attendance_contract_id', $attendance->contract_id);
        $this->set_attendance_field('attendance_date', $attendance->date->isoFormat('YYYY-MM-DD'));
        $this->set_attendance_field('attendance_start_time', Str::beforeLast($attendance->start_time, ':'));
        $this->set_attendance_field('attendance_end_time', Str::beforeLast($attendance->end_time, ':'));

        $this->attendance_start_photo_recent = $attendance->start_photo;
        $this->attendance_end_photo_recent = $attendance->end_photo;
        $this->attendance_overtime_rates = $attendance->overtime_rates;
        $this->attendance_docking_pay = $attendance->docking_pay;
    }

    public function reset_attendance()
    {
        $this->reset(['attendance_id', 'attendance_worker_id', 'attendance_contract_id', 'attendance_date', 'attendance_start_time', 'attendance_start_photo', 'attendance_end_time', 'attendance_end_photo', 'attendance_overtime_rates', 'attendance_docking_pay']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
        $this->dispatch('re_init_flatpickr');
        $this->dispatch('re_init_bootstrap_datepicker');
    }

    public function set_attendance_field($field, $value)
    {
        $this->$field = $value;

        if($field == 'attendance_worker_id') {
            $this->option_contract =  Contract::where('relation_id', $value)
                                    ->whereHas('type', function ($query) {
                                        $query->where('name', 'harian');
                                    })
                                    ->get();
        }
    }

    public function save()
    {
        $this->validate();

        $date = Carbon::parse($this->attendance_date);
        $path = 'img/attendance/' . $date->year . '/' . $date->month . '/' . $date->day;

        if ($this->attendance_start_photo) {
            $attendance_start_photo_filename = FileHelper::storeResizedImage(
                $this->attendance_start_photo,
                $path,
                'ACI',
                1280,
                'src'
            );
        } else {
            $attendance_start_photo_filename = $this->attendance_start_photo_recent ?? null;
        }

        if ($this->attendance_end_photo) {
            $attendance_end_photo_filename = FileHelper::storeResizedImage(
                $this->attendance_end_photo,
                $path,
                'ACO',
                1280,
                'src'
            );
        } else {
            $attendance_end_photo_filename = $this->attendance_end_photo_recent ?? null;
        }

        if(is_null($this->attendance_id)) {
            $attendance = Attendance::create([
                'contract_id' => $this->attendance_contract_id,
                'date' => $this->attendance_date,
                'start_time' => $this->attendance_start_time,
                'start_photo' => $attendance_start_photo_filename,
                'end_time' => $this->attendance_end_time,
                'end_photo' => $attendance_end_photo_filename,
                'overtime_rates' => $this->attendance_overtime_rates,
                'docking_pay' => $this->attendance_docking_pay,
            ]);

            $this->dispatch("re_render_attendances_container");
        } else {
            $attendance = Attendance::findOrFail($this->attendance_id);

            $attendance->update([
                'contract_id' => $this->attendance_contract_id,
                'date' => $this->attendance_date,
                'start_time' => $this->attendance_start_time,
                'start_photo' => $attendance_start_photo_filename,
                'end_time' => $this->attendance_end_time,
                'end_photo' => $attendance_end_photo_filename,
                'overtime_rates' => $this->attendance_overtime_rates,
                'docking_pay' => $this->attendance_docking_pay,
            ]);

            $this->dispatch("refresh_attendance_component.{$attendance->id}");
        }

        $this->dispatch('close_modal_attendance_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->attendance_id) ? 'menambah' : 'mengubah') . ' presensi')
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
                                    });
                                })->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_attendance_resource"
    :title="(is_null($attendance_id) ? 'Tambah' : 'Edit') . ' Presensi'"
    :description="'Di sini, Anda dapat ' . (is_null($attendance_id) ? 'menambah data' : 'mengubah informasi') . ' presensi.'"
    :loading-targets="['set_attendance', 'reset_attendance', 'save']"
    size="xl"
>
    @csrf
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <x-ui::forms.select
                wire-model="attendance_worker_id"
                label="Pekerja"
                placeholder="Pilih Pekerja"
                container-class="col-12 mb-6"
                init-select2-class="select2_attendance"
                :options="$option_worker"
                value-field="id"
                text-field="name"
            />
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <x-ui::forms.select
                wire-model="attendance_contract_id"
                label="Kontrak"
                placeholder="Pilih Kontrak"
                container-class="col-12 mb-6"
                init-select2-class="select2_attendance"
                :options="$option_contract"
                value-field="id"
                text-field="title"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <label class="form-label">Foto Masuk</label>
            <div class="d-flex align-items-start align-items-sm-center gap-4 mb-3">
                @if ($attendance_start_photo)
                    <img src="{{ $attendance_start_photo->temporaryUrl() }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="file_attendance_start_photo" />
                @elseif ($attendance_start_photo_recent)
                    <img src="{{ asset('/src/img/attendance/'.Carbon::parse($attendance_date)->year.'/'.Carbon::parse($attendance_date)->month.'/'.Carbon::parse($attendance_date)->day.'/'.$attendance_start_photo_recent) }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="file_attendance_start_photo" />
                @else
                    <img src="/src/assets/illustrations/no-avatar.svg" alt="user-avatar" class="d-block rounded" height="100" width="100" id="file_attendance_start_photo" />
                @endif

                <div class="button-wrapper">
                    <div wire:loading wire:target="attendance_start_photo">Memeriksa File</div>
                    <div wire:loading.remove wire:target="attendance_start_photo">
                        <label for="attendance_start_photo" class="btn btn-sm btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload foto baru</span>
                            <i class="bx bx-camera d-block d-sm-none"></i>
                            <input type="file" id="attendance_start_photo" wire:model="attendance_start_photo" name="attendance_start_photo" hidden accept="image/*" capture="user" />
                        </label>
                    </div>

                    <p class="text-muted mb-0">File yang diterima JPG atau PNG dengan rasio 1:1.</p>
                    @error('attendance_start_photo')
                        <div class="fv-plugins-message-container invalid-feedback" style="display: block">
                            <div data-field="attendance_start_photo">{{ $message }}</div>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <label class="form-label">Foto Pulang</label>
            <div class="d-flex align-items-start align-items-sm-center gap-4 mb-3">
                @if ($attendance_end_photo)
                    <img src="{{ $attendance_end_photo->temporaryUrl() }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="file_attendance_end_photo" />
                @elseif ($attendance_end_photo_recent)
                    <img src="{{ asset('/src/img/attendance/'.Carbon::parse($attendance_date)->year.'/'.Carbon::parse($attendance_date)->month.'/'.Carbon::parse($attendance_date)->day.'/'.$attendance_end_photo_recent) }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="file_attendance_end_photo" />
                @else
                    <img src="/src/assets/illustrations/no-avatar.svg" alt="user-avatar" class="d-block rounded" height="100" width="100" id="file_attendance_end_photo" />
                @endif

                <div class="button-wrapper">
                    <div wire:loading wire:target="attendance_end_photo">Memeriksa File</div>
                    <div wire:loading.remove wire:target="attendance_end_photo">
                        <label for="attendance_end_photo" class="btn btn-sm btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload foto baru</span>
                            <i class="bx bx-camera d-block d-sm-none"></i>
                            <input type="file" id="attendance_end_photo" wire:model="attendance_end_photo" name="attendance_end_photo" hidden accept="image/*" capture="user" />
                        </label>
                    </div>

                    <p class="text-muted mb-0">File yang diterima JPG atau PNG dengan rasio 1:1.</p>
                    @error('attendance_end_photo')
                        <div class="fv-plugins-message-container invalid-feedback" style="display: block">
                            <div data-field="attendance_end_photo">{{ $message }}</div>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <x-ui::forms.input
        wire:model.live="attendance_date"
        type="text"
        label="Tanggal"
        placeholder="2025-12-21"
        container_class="col-12 mb-6"
    />

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <x-ui::forms.input
                wire:model="attendance_start_time"
                type="text"
                label="Jam Masuk"
                placeholder="08:30:00"
                container_class="col-12 mb-6 d-grid"
            />
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <x-ui::forms.input
                wire:model="attendance_end_time"
                type="text"
                label="Jam Pulang"
                placeholder="17:00:00"
                container_class="col-12 mb-6 d-grid"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <x-ui::forms.input-group
                wire:model="attendance_overtime_rates"
                type="text"
                label="Upah Lembur"
                placeholder="100000"
                container_class="col-12 mb-6"
                front="Rp."
            />
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <x-ui::forms.input-group
                wire:model="attendance_docking_pay"
                type="text"
                label="Potongan Gaji"
                placeholder="100000"
                container_class="col-12 mb-6"
                front="Rp."
            />
        </div>
    </div>
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_attendance_resource', () => {
            var modalElement = document.getElementById('modal_attendance_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_attendance");
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
            var date = $("#attendance_date").datepicker({
                todayHighlight: !0,
                format: "yyyy-mm-dd",
                language: 'id',
                orientation: isRtl ? "auto right" : "auto left",
                autoclose: true
            })
        }

        function init_flatpickr() {
            var attendance_time = $("#attendance_start_time, #attendance_end_time").flatpickr({
                enableTime: true,
                noCalendar: true,
                time_24hr: true,
                static : true
            });
        }

        $(document).ready(function () {
            initSelect2();
            init_flatpickr();
            // init_bootstrap_datepicker();


            $(document).on('change', '.select2_attendance, #attendance_date, #attendance_start_time, #attendance_end_time', function () {
                $wire.set_attendance_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_attendance_add', function () {
                $wire.reset_attendance();
            });

            $(document).on('click', '.btn_attendance_edit', function () {
                $wire.set_attendance($(this).attr('value'));
            });

            $(document).on('click', '.btn_attendance_delete', function () {
                $wire.ask_to_delete_attendance($(this).attr('value'));
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })

            window.Livewire.on('re_init_flatpickr', () => {
                setTimeout(init_flatpickr, 0)
            })

            window.Livewire.on('re_init_bootstrap_datepicker', () => {
                setTimeout(init_bootstrap_datepicker, 0)
            })
        });
    </script>
@endscript
