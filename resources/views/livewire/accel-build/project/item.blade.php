<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use App\Models\Build\Project;

new class extends Component {
    public Project $project;

    #[On('refresh_project_component.{project.id}')]
    public function refresh_project_component()
    {
        $this->dispatch('re_init_masonry');
    }
}; ?>

<div class="col-xl-6 col-lg-6 col-md-6">
	<div class="card h-100">
		<div class="card-header flex-grow-0">
			<div class="d-flex align-items-center">
				<div class="d-flex w-100 flex-wrap justify-content-between gap-1">
					<div class="me-2">
						<h5 class="mb-1">{{ $project->name }}</h5>
						<small>07 Sep 2020 at 10:30 AM</small>
					</div>
					<div class="dropdown">
						<button class="btn text-body-secondary p-0" type="button" id="oliviaShared" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="icon-base bx bx-dots-vertical-rounded icon-lg"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-end" aria-labelledby="oliviaShared">
							<a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
							<a class="dropdown-item" href="javascript:void(0);">Last Month</a>
							<a class="dropdown-item" href="javascript:void(0);">Last Year</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<img class="object-fit-cover" src="{{ asset('/src/img/project/'.$project->cover) }}" alt="Card image cap" />
		<div class="mt-n8">
			<span class="featured-date d-inline-block ms-6 rounded shadow-lg text-center py-1 px-4">
				<span class="mb-0 h4">{{ Carbon::parse($project->start_date)->isoFormat('DD') }}</span>
				<br />
				<span class="text-primary">{{ Carbon::parse($project->start_date)->isoFormat('MMM') }}</span>
			</span>
		</div>
		<div class="card-body">
			<h5 class="text-truncate mb-2">How To Excel In A Technical Terminology?</h5>
			<div class="d-flex gap-2">
				<span class="badge bg-label-success">Technical</span>
				<span class="badge bg-label-primary">Excel</span>
				<span class="badge bg-label-info">Account</span>
			</div>
			<div class="d-flex my-6">
				<ul class="list-unstyled m-0 d-flex align-items-center avatar-group">
					<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy" class="avatar pull-up">
						<img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar" />
					</li>
					<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Allen Rieske" class="avatar pull-up">
						<img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar" />
					</li>
					<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Julee Rossignol" class="avatar pull-up">
						<img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar" />
					</li>
					<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Darcey Nooner" class="avatar pull-up">
						<img class="rounded-circle" src="../../assets/img/avatars/10.png" alt="Avatar" />
					</li>
				</ul>
				<a href="javascript:;" class="btn btn-primary ms-auto" role="button">Join Now</a>
			</div>
			<div class="d-flex align-items-center justify-content-between">
				<div class="card-actions">
					<a href="javascript:;" class="text-body me-4">
						<i class="icon-base bx bx-heart icon-md me-1"></i> 236 </a>
					<a href="javascript:;" class="text-body">
						<i class="icon-base bx bx-chat icon-md me-1"></i> 12 </a>
				</div>
			</div>
		</div>
	</div>
</div>
