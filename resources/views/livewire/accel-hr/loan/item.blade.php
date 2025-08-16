<?php

use Carbon\Carbon;
use App\Models\Hr\Loan;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public Loan $loan;

    #[On('refresh_loan_component.{loan.id}')]
    public function refresh_loan_component()
    {
        $this->dispatch('re_init_masonry');
    }
}; ?>

<div class="col-xl-6 col-lg-6 col-md-6">
	<div class="card h-100">
		<div class="card-header">
			<div class="d-flex align-items-start">
				<div class="d-flex align-items-center">
					<div class="me-2">
						<h5 class="mb-0">
							{{ $loan->loan_number }}
						</h5>
						<div class="client-info text-body">
							<span class="fw-medium">Peminjam: </span>
							<span>{{ $loan->worker->name }}</span>
						</div>
					</div>
				</div>
				<div class="ms-auto">
                    @canany(['AccelHr - Pinjaman - Mengubah Data', 'AccelHr - Pinjaman - Menghapus Data'])
                        <x-ui::elements.dropdown-actions
                            edit-target="modal_loan_resource"
                            :delete-action="$loan->id"
                            :permissions="[
                                'edit' => 'AccelHr - Pinjaman - Mengubah Data',
                                'delete' => 'AccelHr - Pinjaman - Menghapus Data'
                            ]"
                            edit-class="btn_loan_edit"
                            delete-class="btn_loan_delete"
                            variant="action"
                        />
                    @endcanany
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="d-flex align-items-center flex-wrap">
				<div class="bg-lighter px-3 py-2 rounded me-auto mb-4">
					<p class="mb-1">
						<span class="fw-medium text-heading">{{ Number::currency($loan->amount, in: 'IDR', locale: 'id', precision: 0) }}</span>
					</p>
					<span class="text-body">{{ Carbon::parse($loan->loan_date)->isoFormat('DD MMMM YYYY') }}</span>
				</div>
				<div class="text-start mb-4">
					<p class="mb-1">
						<span class="text-heading fw-medium">Diterima Oleh: </span>
					</p>
					<p class="mb-1">
						{{ $loan->receiver->name }}
					</p>
				</div>
			</div>
            <p class="mb-4">{{ $loan->notes }}</p>
            @if ($loan->status == 'ongoing')
                <span class="badge bg-label-info">Berjalan</span>
            @else
                <span class="badge bg-label-success">Lunas</span>
            @endif
		</div>
	</div>
</div>
