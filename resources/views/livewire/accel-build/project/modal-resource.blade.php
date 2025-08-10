<?php

use App\Helpers\FileHelper;
use App\Models\Ref\Regency;
use App\Models\Ref\Village;
use App\Models\Ref\District;
use App\Models\Ref\Province;
use Livewire\Volt\Component;
use App\Models\Build\Project;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    use WithFileUploads;

    public $options_province = [], $options_regency = [], $options_district = [], $options_village = [], $options_type = [];

    public $project_id;

    #[Validate('required|string', as: 'Nama')]
    public $project_name;

    #[Validate('required|string', as: 'Jenis')]
    public $project_type;

    #[Validate('required|image', as: 'Cover')]
    public $project_cover;

    #[Validate('required|string', as: 'Provinsi')]
    public $project_province_id;

    #[Validate('required|string', as: 'Kabupaten/Kota')]
    public $project_regency_id;

    #[Validate('required|string', as: 'Kecamatan')]
    public $project_district_id;

    #[Validate('required|string', as: 'Kelurahan/Desa')]
    public $project_village_id;

    #[Validate('required|string', as: 'Alamat')]
    public $project_address;

    #[Validate('nullable', as: 'Klien')]
    public $project_client_id;

    #[Validate('required|string', as: 'Tanggal Mulai')]
    public $project_start_date;

    #[Validate('nullable', as: 'Tanggal Selesai')]
    public $project_end_date;

    #[Validate('nullable', as: 'Keterangan')]
    public $project_description;

    public function set_project($project_id)
    {
        $this->project_id = $project_id;

        $project = Project::findOrFail($this->project_id);

        $this->project_title = $project->title;
        $this->set_project_field('project_type_id', $project->type_id);
        $this->project_relation_type = $project->relation_type;
        $this->project_relation_id = $project->relation_id;
        $this->project_project_id = $project->project_id;
        $this->project_rates = $project->rates;
        $this->project_start_date = $project->start_date;
    }

    public function reset_project()
    {
        $this->reset(['project_id', 'project_name', 'project_type', 'project_cover', 'project_province_id', 'project_regency_id', 'project_district_id', 'project_village_id', 'project_address', 'project_client_id', 'project_start_date', 'project_end_date', 'project_description']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
        $this->dispatch('re_init_bootstrap_datepicker');
    }

    public function set_project_field($field, $value)
    {
        $this->$field = $value;

        if($field == 'project_province_id') {
            $this->options_regency = Regency::where('province_id', $this->project_province_id)->get();
        }

        if($field == 'project_regency_id') {
            $this->options_district = District::where('regency_id', $this->project_regency_id)->get();
        }

        if($field == 'project_district_id') {
            $this->options_village = Village::where('district_id', $this->project_district_id)->get();
        }
    }

    public function save()
    {
        $this->validate();

        if($this->project_cover){
            $path = 'img/project';

            $filename = FileHelper::storeResizedImage(
                $this->project_cover,
                $path,
                'PC',
                1280,
                'src'
            );
        } else {
            $filename = null;
        }

        if(is_null($this->project_id)) {
            $project = Project::create([
                'name' => $this->project_name,
                'type' => $this->project_type,
                'cover' => $filename,
                'village_id' => $this->project_village_id,
                'address' => $this->project_address,
                'client_id' => $this->project_client_id,
                'start_date' => $this->project_start_date,
                'end_date' => $this->project_end_date,
                'description' => $this->project_description,
            ]);

            $this->dispatch("re_render_projects_container");
        } else {
            $project = Project::findOrFail($this->project_id);

            $project->update([
                'name' => $this->project_name,
                'type' => $this->project_type,
                'cover' => $filename,
                'village_id' => $this->project_village_id,
                'address' => $this->project_address,
                'client_id' => $this->project_client_id,
                'start_date' => $this->project_start_date,
                'end_date' => $this->project_end_date,
                'description' => $this->project_description,
            ]);

            $this->dispatch("refresh_project_component.{$project->id}");
        }

        $this->dispatch('close_modal_project_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->project_id) ? 'menambah' : 'mengubah') . ' proyek')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_project();
    }

    public function ask_to_delete_project($project_id)
    {
        $this->project_id = $project_id;
        $project = Project::find($this->project_id);
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan menghapus proyek dengan nama '.$project->name.', Anda yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('delete_project')
            ->show();
    }

    public function delete_project()
    {
        $project = Project::find($this->project_id);

        if($project) {
            $project->delete();

            $this->dispatch("re_render_projects_container");

            LivewireAlert::title('')
            ->text('Berhasil menghapus proyek')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->reset_project();
        }
    }

    public function mount()
    {
        $this->options_type = [['label' => 'Perumahan', 'value' => 'housing'], ['label' => 'Bukan Perumahan', 'value' => 'non_housing']];
        $this->options_province = Province::orderBy('name')->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_project_resource"
    :title="(is_null($project_id) ? 'Tambah' : 'Edit') . ' Kontrak'"
    :description="'Di sini, Anda dapat ' . (is_null($project_id) ? 'menambah data' : 'mengubah informasi') . ' kontrak.'"
    :loading-targets="['set_project', 'reset_project', 'save']"
>
    @csrf
    <div class="col-12">
        <h6>Informasi Proyek</h6>
        <hr class="mt-0">
    </div>

    <div class="d-flex align-items-start align-items-sm-center gap-4 mb-3">
        @if ($project_cover)
            <img src="{{ $project_cover->temporaryUrl() }}" alt="Project Cover" class="d-block rounded" width="100" id="upload_project_cover" />
        @else
            <img src="/src/assets/illustrations/no-file.svg" alt="Project Cover" class="d-block rounded" width="100" id="upload_project_cover" />
        @endif

        <div class="button-wrapper">
            <div wire:loading wire:target="project_cover">Memeriksa File</div>
            <div wire:loading.remove wire:target="project_cover">
                <label for="project_cover" class="btn btn-sm btn-primary me-2 mb-4" tabindex="0">
                    <span class="d-none d-sm-block">Upload foto baru</span>
                    <i class="bx bx-camera d-block d-sm-none"></i>
                    <input type="file" id="project_cover" wire:model="project_cover" name="project_cover" hidden accept="image/*" capture="user" />
                </label>
            </div>

            <p class="text-muted mb-0">File yang diterima JPG atau PNG.</p>
            @error('project_cover')
                <div class="fv-plugins-message-container invalid-feedback" style="display: block">
                    <div data-field="project_cover">{{ $message }}</div>
                </div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <x-ui::forms.input
                wire:model="project_name"
                type="text"
                label="Nama"
                placeholder="Perumahan Bagus"
                container_class="col-12 mb-6"
            />
        </div>

        <div class="col-6">
            <x-ui::forms.select
                wire-model="project_type"
                label="Jenis"
                placeholder="Pilih Jenis Proyek"
                container-class="col-12 mb-6"
                init-select2-class="select2_project"
            >
                @foreach ($options_type as $option_type)
                    <option value="{{ $option_type['value'] }}">{{ $option_type['label'] }}</option>
                @endforeach
            </x-ui::forms.select>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <x-ui::forms.input
                wire:model.live="project_start_date"
                type="text"
                label="Tanggal Mulai Proyek"
                placeholder="2025-12-21"
                container_class="col-12 mb-6"
            />
        </div>

        <div class="col-6">
            <x-ui::forms.input
                wire:model.live="project_end_date"
                type="text"
                label="Tanggal Selesai Proyek"
                placeholder="2026-12-21"
                container_class="col-12 mb-6"
            />
        </div>
    </div>

    <x-ui::forms.textarea
        wire:model="project_description"
        label="Keterangan"
        placeholder="Proyek ini dikerjakan dengan sangat hati-hati, sehingga menghasilkan kualitas yang baik"
        container_class="col-12 mb-6"
    />

    <div class="col-12 mt-12">
        <h6>Lokasi Proyek</h6>
        <hr class="mt-0">
    </div>

    <div class="row">
        <div class="col-6">
            <x-ui::forms.select
                wire-model="project_province_id"
                label="Provinsi"
                placeholder="Pilih Provinsi"
                container-class="col-12 mb-6"
                init-select2-class="select2_project"
                :options="$options_province"
                value-field="id"
                text-field="name"
            />
        </div>

        <div class="col-6">
            <x-ui::forms.select
                wire-model="project_regency_id"
                label="Kabupaten/Kota"
                placeholder="Pilih Kabupaten/Kota"
                container-class="col-12 mb-6"
                init-select2-class="select2_project"
                :options="$options_regency"
                value-field="id"
                text-field="name"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <x-ui::forms.select
                wire-model="project_district_id"
                label="Kecamatan"
                placeholder="Pilih Kecamatan"
                container-class="col-12 mb-6"
                init-select2-class="select2_project"
                :options="$options_district"
                value-field="id"
                text-field="name"
            />
        </div>

        <div class="col-6">
            <x-ui::forms.select
                wire-model="project_village_id"
                label="Kelurahan/Desa"
                placeholder="Pilih Kelurahan/Desa"
                container-class="col-12 mb-6"
                init-select2-class="select2_project"
                :options="$options_village"
                value-field="id"
                text-field="name"
            />
        </div>
    </div>

    <x-ui::forms.textarea
        wire:model="project_address"
        label="Alamat"
        placeholder="Jl. Bunga Sedap Malam IX No.1"
        container_class="col-12 mb-6"
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_project_resource', () => {
            var modalElement = document.getElementById('modal_project_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_project");
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
            var date = $("#project_start_date, #project_end_date").datepicker({
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

            $(document).on('change', '.select2_project, #project_start_date, #project_end_date', function () {
                $wire.set_project_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_project_add', function () {
                $wire.reset_project();
            });

            $(document).on('click', '.btn_project_edit', function () {
                $wire.set_project($(this).attr('value'));
            });

            $(document).on('click', '.btn_project_delete', function () {
                $wire.ask_to_delete_project($(this).attr('value'));
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
