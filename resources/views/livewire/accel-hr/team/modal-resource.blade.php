<?php

use App\Models\Hr\Team;
use App\Models\Hr\Worker;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Hr\Team\Member;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $options_worker = [];

    public $team_id;

    #[Validate('required', as: 'Nama')]
    public $team_name;

    #[Validate('required|array|min:1', as: 'Anggota')]
    public $team_member;

    #[On('set_team')]
    public function set_team($team_id)
    {
        $this->team_id = $team_id;

        $team = Team::findOrFail($this->team_id);

        $this->team_name = $team->name;
        $this->team_member = [];

        foreach ($team->members as $member) {
            array_push($this->team_member, $member->worker_id);
        }
    }

    #[On('reset_team')]
    public function reset_team()
    {
        $this->reset(['team_id', 'team_name', 'team_member']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    #[On('set_team_field')]
    public function set_team_field($field, $value)
    {
        $this->$field = $value;
    }

    public function save()
    {
        $this->validate();

        if(is_null($this->team_id)) {
            $team = Team::create([
                'name' => $this->team_name,
            ]);

            foreach ($this->team_member as $member) {
                Member::create([
                    'team_id' => $team->id,
                    'worker_id' => $member,
                ]);
            }

            $this->dispatch("re_render_teams_container");
        } else {
            $team = Team::findOrFail($this->team_id);

            $team->update([
                'name' => $this->team_name,
            ]);

            Member::where('team_id', $team->id)->whereNotIn('worker_id', $this->team_member)->delete();

            foreach ($this->team_member as $member) {
                Member::updateOrCreate(
                    ['team_id' => $team->id, 'worker_id' => $member]
                );
            }

            $this->dispatch("refresh_team_component.{$team->id}");
        }

        $this->dispatch('close_modal_team_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->team_id) ? 'menambah' : 'mengubah') . ' tim')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_team();
    }

    #[On('ask_to_delete_team')]
    public function ask_to_delete_team($team_id)
    {
        $this->team_id = $team_id;
        $team = Team::find($this->team_id);
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan menghapus tim dengan nama '.$team->name.', Anda yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('delete_team')
            ->show();
    }

    public function delete_team()
    {
        $team = Team::find($this->team_id);

        if($team) {
            $team->delete();

            $this->dispatch("re_render_teams_container");

            LivewireAlert::title('')
            ->text('Berhasil menghapus tim')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->reset_team();
        }
    }

    public function mount()
    {
        $this->options_worker = Worker::orderBy('name')->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_team_resource"
    :title="(is_null($team_id) ? 'Tambah' : 'Edit') . ' Tim'"
    :description="'Di sini, Anda dapat ' . (is_null($team_id) ? 'menambah data' : 'mengubah informasi') . ' tim.'"
    :loading-targets="['set_team', 'reset_team', 'save']"
>
    @csrf
    <x-ui::forms.input
        wire:model.live="team_name"
        type="text"
        label="Nama"
        placeholder="Tim Trobos"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.select
        wire-model="team_member"
        label="Anggota"
        placeholder="Pilih Anggota Tim"
        container-class="col-12 mb-6"
        init-select2-class="select2_team"
        :options="$options_worker"
        value-field="id"
        text-field="name"
        multiple
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_team_resource', () => {
            var modalElement = document.getElementById('modal_team_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_team");
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

            $(document).on('change', '.select2_team', function () {
                $wire.set_team_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_team_add', function () {
                $wire.reset_team();
            });

            $(document).on('click', '.btn_team_edit', function () {
                $wire.set_team($(this).attr('value'));
            });

            $(document).on('click', '.btn_team_delete', function () {
                $wire.ask_to_delete_team($(this).attr('value'));
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
