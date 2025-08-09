<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Hr\Attendance;
use Illuminate\Support\Number;

new class extends Component {
    public Attendance $attendance;

    #[On('refresh_attendance_component.{attendance.id}')]
    public function refresh_attendance_component()
    {
        $this->dispatch('re_init_masonry');
    }
}; ?>

<div class="col-xl-6 col-lg-6 col-md-6">
	<div class="card">
        <div class="card-header pb-4">
            <div class="d-flex align-items-start">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <h5 class="mb-0">{{ $attendance->contract->relation->name }}</h5>
                        <div class="client-info text-body">
                            <span class="fw-medium">{{ $attendance->contract->title }}</span>
                        </div>
                    </div>
                </div>
                <div class="ms-auto">
                    <div class="dropdown z-2">
                        <button type="button" class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="icon-base bx bx-dots-vertical-rounded icon-md text-body-secondary"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">Rename project</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">View details</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">Add to favorites</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0);">Leave Project</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
		<div class="card-body">
            <div class="d-flex justify-content-around">
				<div class="d-flex flex-column align-items-center gap-2">
                    <h5 class="mb-0 card-title">Check In</h5>
                    <img src="{{ asset('/src/img/attendance/'.$attendance->date->year.'/'.$attendance->date->month.'/'.$attendance->date->day.'/'.$attendance->start_photo) }}" alt="Check In Prove" class="rounded w-px-100 h-px-100">
                    <span class="badge bg-label-info">{{ $attendance->start_time }}</span>
                </div>
				<div class="d-flex flex-column align-items-center gap-2 justify-content-between">
                    <h5 class="mb-0 card-title">Check Out</h5>
                    @if ($attendance->end_time)
                        <img src="{{ asset('/src/img/attendance/'.$attendance->date->year.'/'.$attendance->date->month.'/'.$attendance->date->day.'/'.$attendance->end_photo) }}" alt="Check Out Prove" class="rounded w-px-100 h-px-100">
                        <span class="badge bg-label-info">{{ $attendance->end_time }}</span>
                    @else
                        <x-ui::elements.button
                            class="btn btn-sm btn-label-primary btn_check_out"
                            title="Check Out"
                            value="{{ $attendance->id }}"
                            data-bs-toggle="modal"
                            data-bs-target="#modal_attendance_check_out"
                        >
                            Proses Check Out
                        </x-ui::elements.button>
                    @endif
                </div>
			</div>
		</div>
        @if ($attendance->overtime_rates OR $attendance->docking_pay)
            <div class="card-body border-top d-flex justify-content-center gap-2">
                @if ($attendance->overtime_rates)
                    <span class="badge bg-label-success d-flex align-items-center gap-1"><i class="icon-base bx bx-plus"></i>{{ Number::currency($attendance->overtime_rates, in: 'IDR', locale: 'id', precision: 0) }}</span>
                @endif

                @if ($attendance->docking_pay)
                    <span class="badge bg-label-danger d-flex align-items-center gap-1"><i class="icon-base bx bx-minus"></i>{{ Number::currency($attendance->docking_pay, in: 'IDR', locale: 'id', precision: 0) }}</span>
                @endif
            </div>
        @endif
	</div>
</div>
