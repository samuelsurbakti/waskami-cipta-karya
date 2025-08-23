<?php

use Carbon\Carbon;
use App\Models\Hr\Payroll;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Number;

new class extends Component {
    public Payroll $payroll;

    #[On('refresh_payroll_component.{payroll.id}')]
    public function refresh_payroll_component()
    {
        $this->dispatch('re_init_masonry');
    }
}; ?>

<div class="col-sm-6 col-md-6 col-lg-6">
    <div class="card h-100 border-1">
        <div class="card-header pb-2">
            <div class="d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <h5 class="mb-0">
                            {{ $payroll->contract->relation->name }}
                        </h5>
                        <p>{{ $payroll->contract->title }}</p>
                    </div>
                </div>
                <div>
                    <span class="badge {{ ($payroll->status == 'draft' ? 'bg-label-success' : 'bg-label-wck') }}">{{ Str::title($payroll->status) }}</span>
                </div>
            </div>
        </div>
        @if ($payroll->attendanceItems->count() != 0)
            <div class="card-body">
                <h6 class="mb-2">Data Kehadiran</h6>

                <ul class="p-0 m-0">
                    @foreach ($payroll->attendanceItems as $attendance)
                        <li class="d-flex mb-2 flex-column border-bottom pb-2">
                            <h6 class="mb-2">{{ Carbon::parse($attendance->relation->date)->isoFormat('DD MMMM YYYY') }}</h6>
                            <div class="d-flex justify-content-between flex-column flex-md-row">
                                <div class="me-2">
                                    <small class="d-block fw-medium text-info">{{ Number::currency($attendance->relation->contract->rates ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</small>
                                </div>
                                <div class="me-2">
                                    <small class="d-block fw-medium text-success">{{ Number::currency($attendance->relation->overtime_rates ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</small>
                                </div>
                                <div class="me-2">
                                    <small class="d-block fw-medium text-warning">{{ Number::currency($attendance->relation->docking_pay ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</small>
                                </div>
                                <div class="me-2">
                                    <small class="d-block fw-medium text-wck">{{ Number::currency($attendance->amount, in: 'IDR', locale: 'id', precision: 0) }}</small>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    <li class="d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <div class="me-2">
                                <h6 class="text-wck fw-bold">Total</h6>
                            </div>
                            <div class="me-2">
                                <small class="d-block fw-medium text-wck">{{ Number::currency($payroll->attendanceItems()->sum('amount'), in: 'IDR', locale: 'id', precision: 0) }}</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        @endif

        @if ($payroll->repaymentItems->count() != 0)
            <div class="card-body border-top">
                <h6 class="mb-2">Pembayaran Pinjaman</h6>

                <ul class="p-0 m-0">
                    @foreach ($payroll->repaymentItems as $repayment)
                        <li class="d-flex mb-2 flex-column border-bottom pb-2">
                            <div class="d-flex justify-content-between flex-column flex-md-row">
                                <div class="me-2">
                                    <small class="d-block fw-medium text-info">{{ $repayment->relation->loan->loan_number }}</small>
                                </div>
                                <div class="me-2">
                                    <small class="d-block fw-medium text-danger">{{ Number::currency($repayment->amount ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</small>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    <li class="d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <div class="me-2">
                                <h6 class="text-danger fw-bold">Total</h6>
                            </div>
                            <div class="me-2">
                                <small class="d-block fw-medium text-danger">{{ Number::currency($payroll->repaymentItems()->sum('amount'), in: 'IDR', locale: 'id', precision: 0) }}</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        @endif

        <div class="card-footer d-flex pt-4 justify-content-between border-top">
            <h6 class="mb-0 text-wck fw-bold">Gaji Bersih</h6>
            <h6 class="mb-0 text-wck fw-bold">{{ Number::currency($payroll->attendanceItems()->sum('amount') - $payroll->repaymentItems()->sum('amount'), in: 'IDR', locale: 'id', precision: 0) }}</h6>
        </div>
    </div>
</div>
