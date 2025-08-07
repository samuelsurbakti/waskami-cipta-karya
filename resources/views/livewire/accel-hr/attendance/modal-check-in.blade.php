<?php

use App\Models\Hr\Worker;
use App\Helpers\FileHelper;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

new class extends Component {
    use WithFileUploads;

    public $option_worker = [], $option_contract = [];

    public $attendance_worker_id;

    #[Validate('required', as: 'Kontrak')]
    public $attendance_contract_id;

    #[Validate('required', as: 'Foto')]
    public $attendance_start_photo;

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    public function save()
    {
        FileHelper::ensure_folder_exists('img/attendance');
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
        @if ($attendance_start_photo)
            <img src="{{ $attendance_start_photo->temporaryUrl() }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
        @else
            <img src="/src/assets/illustrations/no-avatar.svg" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
        @endif

        <div class="button-wrapper">
            <div wire:loading wire:target="attendance_start_photo">Memeriksa File</div>
            <div wire:loading.remove wire:target="attendance_start_photo">
                <label for="upload" class="btn btn-sm btn-primary me-2 mb-4" tabindex="0">
                    <span class="d-none d-sm-block">Upload foto baru</span>
                    <i class="bx bx-camera d-block d-sm-none"></i>
                    <input type="file" id="upload" wire:model="attendance_start_photo" class="account-file-input" name="photo" hidden accept="image/*" capture="user" />
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
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_attendance_check_in', () => {
            var modalElement = document.getElementById('modal_attendance_check_in');
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

        $(document).ready(function () {
            initSelect2();


            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })


        });
    </script>
@endscript
