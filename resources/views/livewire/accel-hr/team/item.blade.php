<?php

use App\Models\Hr\Team;
use Livewire\Attributes\On;
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
    <div class="card card-action">
        <div class="card-header align-items-center">
            <h5 class="card-action-title mb-0">{{ $team->name }}</h5>
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
                    variant="action"
                />
            @endcanany
        </div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                @foreach ($team->members as $member)
                    <li class="mb-4">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded-circle {{ ($member->worker->type->name == 'pegawai' ? 'bg-label-info' : ($member->worker->type->name == 'tukang' ? 'bg-label-success' : 'bg-label-warning')) }}" style="text-transform: none;">{{ StringHelper::acronym($member->worker->name) }}</span>
                                </div>
                                <div class="me-2">
                                    <h6 class="mb-0">{{ $member->worker->name }}</h6>
                                    <small>{{ Str::title($member->worker->type->name) }}</small>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('AccelHr | Worker | Show', $member->worker_id) }}" class="btn btn-label-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Profil Pekerja">
                                    <i class="icon-base bx bx-user-check icon-md"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="d-flex text-center align-items-center justify-content-around mt-6">
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
