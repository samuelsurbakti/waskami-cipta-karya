<?php

use Livewire\Volt\Component;

new class extends Component {
    public Worker $team;

    #[On('refresh_team_component.{team.id}')]
    public function refresh_team_component()
    {
        $this->dispatch('re_init_masonry');
    }
}; ?>

<div class="col-xl-4 col-lg-4 col-md-4">
	<div class="card h-100">
		<div class="card-body">
			<div class="d-flex align-items-center mb-3 pb-1">
				<div class="me-2 text-heading h5 mb-0">React Developers</div>
				<div class="ms-auto">
					<ul class="list-inline mb-0 d-flex align-items-center">
						<li class="list-inline-item me-0">
							<a href="javascript:void(0);" class="d-flex align-self-center btn btn-icon btn-text-secondary rounded-pill">
								<i class="icon-base bx bx-star icon-md text-body-secondary"></i>
							</a>
						</li>
						<li class="list-inline-item">
							<div class="dropdown">
								<button type="button" class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="icon-base bx bx-dots-vertical-rounded icon-md text-body-secondary"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end">
									<li>
										<a class="dropdown-item" href="javascript:void(0);">Rename Team</a>
									</li>
									<li>
										<a class="dropdown-item" href="javascript:void(0);">View Details</a>
									</li>
									<li>
										<a class="dropdown-item" href="javascript:void(0);">Add to favorites</a>
									</li>
									<li>
										<hr class="dropdown-divider" />
									</li>
									<li>
										<a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<p class="mb-3 pb-1">We don’t make assumptions about the rest of your technology stack, so you can develop new features...</p>
			<div class="d-flex align-items-center">
				<div class="d-flex align-items-center">
					<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
						<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy" class="avatar avatar-sm pull-up">
							<img class="rounded-circle" src="../../assets/img/avatars/1.png" alt="Avatar" />
						</li>
						<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Allen Rieske" class="avatar avatar-sm pull-up">
							<img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar" />
						</li>
						<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Julee Rossignol" class="avatar avatar-sm pull-up">
							<img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar" />
						</li>
						<li class="avatar avatar-sm">
							<span class="avatar-initial rounded-circle pull-up" data-bs-toggle="tooltip" data-bs-placement="top" title="9 more">+9</span>
						</li>
					</ul>
				</div>
				<div class="ms-auto">
					<a href="javascript:;" class="me-1">
						<span class="badge bg-label-primary">React</span>
					</a>
					<a href="javascript:;">
						<span class="badge bg-label-info">MUI</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
