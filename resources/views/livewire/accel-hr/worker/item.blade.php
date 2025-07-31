<?php

use App\Models\Hr\Worker;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Helpers\StringHelper;

new class extends Component {
    public Worker $worker;

    #[On('refresh_worker_component.{worker.id}')]
    public function refresh_worker_component()
    {
        //
    }
}; ?>

<div class="col-xl-6 col-lg-6 col-md-6">
	<div class="card">
		<div class="card-body text-center">
			<div class="dropdown btn-pinned">
				<button type="button" class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="icon-base bx bx-dots-vertical-rounded icon-md text-body-secondary"></i>
				</button>
				<ul class="dropdown-menu dropdown-menu-end">
                    @can('AccelHr - Pekerja - Melihat Data')
                        <li>
                            <a class="dropdown-item text-primary d-flex align-items-center gap-1" href="{{ route('AccelHr | Worker | Show', $worker->id) }}"><i class="bx bx-show"></i> Lihat</a>
                        </li>
                    @endcan
                    @can('AccelHr - Pekerja - Mengubah Data')
                        <li>
                            <button class="dropdown-item text-success d-flex align-items-center gap-1 btn_worker_edit" data-bs-toggle="modal" data-bs-target="#modal_worker_resource" value="{{ $worker->id }}"><i class="bx bx-edit"></i> Edit</button>
                        </li>
                    @endcan
	                @can('AccelHr - Pekerja - Menghapus Data')
                        <li>
                            <button class="dropdown-item text-danger d-flex align-items-center gap-1 btn_worker_delete" value="{{ $worker->id }}"><i class="bx bx-trash"></i> Hapus</button>
                        </li>
                    @endcan
				</ul>
			</div>
			<div class="mb-3 d-flex justify-content-center">
				<div class="avatar avatar-xl">
                    <span class="avatar-initial rounded-circle {{ ($worker->type->name == 'pegawai' ? 'bg-label-info' : ($worker->type->name == 'tukang' ? 'bg-label-success' : 'bg-label-warning')) }}" style="text-transform: none;">{{ StringHelper::acronym($worker->name) }}</span>
                </div>
			</div>
			<h5 class="mb-0 card-title">{{ $worker->name }}</h5>
			<span>{{ Str::title($worker->type->name) }}</span>
			<ul class="list-unstyled mt-3 mb-0 py-1">
                <li class="d-flex mb-4">
                    <span class="fw-medium mx-2">No. Telepon:</span>
                    <span class="text-start"><a href="tel:{{ $worker->phone }}" target="_blank">{{ $worker->phone }}</a></span>
                </li>
                <li class="d-flex mb-4">
                    <span class="fw-medium mx-2">No. Whatsapp:</span>
                    <span class="text-start"><a href="https://wa.me/{{ Str::startsWith($worker->phone, '0') ? '62' . substr($worker->phone, 1) : $worker->phone }}" target="_blank">{{ $worker->whatsapp }}</a></span>
                </li>
                <li class="d-flex mb-4">
                    <span class="fw-medium mx-2">Alamat:</span>
                    <span class="text-start">{{ $worker->address }}</span>
                </li>
            </ul>
			{{-- <div class="d-flex align-items-center justify-content-around mb-8">
				<div>
					<h5 class="mb-0">18</h5>
					<span>Projects</span>
				</div>
				<div>
					<h5 class="mb-0">834</h5>
					<span>Tasks</span>
				</div>
				<div>
					<h5 class="mb-0">129</h5>
					<span>Connections</span>
				</div>
			</div> --}}
		</div>
	</div>
</div>
