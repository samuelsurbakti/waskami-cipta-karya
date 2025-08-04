<?php

use App\Models\Hr\Team;
use Livewire\Volt\Component;
use App\Helpers\StringHelper;

new class extends Component {
    public Team $team;

    #[On('refresh_team_component.{team.id}')]
    public function refresh_team_component()
    {
        $this->dispatch('re_init_masonry');
    }
}; ?>

<div class="col-xl-4 col-lg-4 col-md-4">
	<div class="card">
		<div class="card-body">
            @canany(['AccelHr - Tim - Melihat Data', 'AccelHr - Tim - Mengubah Data', 'AccelHr - Tim - Menghapus Data'])
                <x-ui::elements.dropdown-actions
                    :view-route="route('AccelHr | Team | Show', $team->id)"
                    edit-target="modal_team_resource"
                    :delete-action="$team->id"
                    :permissions="[
                        'view' => 'AccelHr - Pekerja - Melihat Data',
                        'edit' => 'AccelHr - Tim - Mengubah Data',
                        'delete' => 'AccelHr - Tim - Menghapus Data'
                    ]"
                    edit-class="btn_team_edit"
                    delete-class="btn_team_delete"
                />
            @endcanany
			<div class="d-flex align-items-center mb-3 pb-1">
				<div class="me-2 text-heading h5 mb-0">{{ $team->name }}</div>
			</div>
			<div class="d-flex align-items-center">
				<div class="d-flex align-items-center">
					<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        @foreach ($team->members as $member)
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $member->worker->name }}" class="avatar avatar-sm pull-up">
                                <a href="{{ route('AccelHr | Worker | Show', $member->worker_id) }}">
                                    <span class="avatar-initial rounded-circle {{ ($member->worker->type->name == 'pegawai' ? 'bg-label-info' : ($member->worker->type->name == 'tukang' ? 'bg-label-success' : 'bg-label-warning')) }}" style="text-transform: none;">{{ StringHelper::acronym($member->worker->name) }}</span>
                                </a>
                            </li>
                        @endforeach
					</ul>
				</div>
			</div>

            <div class="d-flex text-center align-items-center justify-content-around mt-3">
                <div>
                    <h5 class="mb-0">2</h5>
                    <span>Partisipasi Proyek</span>
                </div>
                <div>
                    <h5 class="mb-0">31</h5>
                    <span>Progres Selesai</span>
                </div>
            </div>
		</div>
	</div>
</div>
