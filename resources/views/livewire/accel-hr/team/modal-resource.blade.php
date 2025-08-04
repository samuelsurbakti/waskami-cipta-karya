<?php

use App\Models\Hr\Team;
use App\Models\Hr\Worker;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Hr\Team\Member;
use Livewire\Attributes\Validate;

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
        placeholder="Bowo Cokro Aminoto"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.select
        wire-model="team_type_id"
        label="Jenis"
        placeholder="Pilih Jenis Tim"
        container-class="col-12 mb-6"
        init-select2-class="select2_team"
        :options="$options_type"
        value-field="id"
        text-field="name"
        multiple
    />



    <x-ui::forms.input
        wire:model.live="team_phone"
        type="text"
        label="No. Telepon"
        placeholder="081199552244"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input
        wire:model.live="team_whatsapp"
        type="text"
        label="No. Whatsapp"
        placeholder="081199552244"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.textarea
        wire:model.live="team_address"
        label="Alamat"
        placeholder="Jl. Bunga Sedap Malam IX No.1, Sempakata, Kec. Medan Selayang, Kota Medan, Sumatera Utara 20131"
        container_class="col-12 mb-6"
    />
</x-ui::elements.modal-form>
