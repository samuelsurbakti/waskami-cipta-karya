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
						<small>{{ ($project->type == 'housing' ? $project->properties->count().' Properti' : '') }}</small>
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
				<span class="mb-0 h4">{{ Carbon::parse($project->start_date)->isoFormat('MMM') }}</span>
				<br />
				<span class="text-primary">{{ Carbon::parse($project->start_date)->isoFormat('YYYY') }}</span>
			</span>
		</div>
		<div class="card-body">
			<p class="mb-0">{{ $project->description }}</p>
		</div>
		<div class="card-body border-top">
			<h6 class="text-heading fw-medium mb-4">Progres</h6>
			<div class="d-flex justify-content-between align-items-center mb-2">
				<small class="text-body">Tugas: 101/201</small>
				<small class="text-body">51% Completed</small>
			</div>
			<div class="progress mb-4 rounded" style="height: 8px;">
				<div class="progress-bar rounded" role="progressbar" style="width: 42%;" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
			<div class="d-flex align-items-center">
				<div class="d-flex align-items-center">
					<ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
						<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Kaith D'souza" class="avatar avatar-sm pull-up">
							<img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar" />
						</li>
						<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="John Doe" class="avatar avatar-sm pull-up">
							<img class="rounded-circle" src="../../assets/img/avatars/1.png" alt="Avatar" />
						</li>
						<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Alan Walker" class="avatar avatar-sm pull-up me-3">
							<img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar" />
						</li>
						<li>
							<small class="text-body-secondary">1.1k Members</small>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
