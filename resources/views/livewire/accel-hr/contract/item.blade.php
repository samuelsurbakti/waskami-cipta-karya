<?php

use Carbon\Carbon;
use App\Models\Hr\Contract;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public Contract $contract;

    #[On('refresh_contract_component.{contract.id}')]
    public function refresh_contract_component()
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
							{{ $contract->title }}
						</h5>
						<div class="client-info text-body">
							<span class="fw-medium">Pemilik Kontrak: </span>
							<span>{{ $contract->relation->name }}</span>
						</div>
					</div>
				</div>
				<div class="ms-auto">
                    @canany(['AccelHr - Kontrak - Melihat Data', 'AccelHr - Kontrak - Mengubah Data', 'AccelHr - Kontrak - Menghapus Data'])
                        <x-ui::elements.dropdown-actions
                            :view-route="route('AccelHr | Worker | Show', $contract->id)"
                            edit-target="modal_contract_resource"
                            :delete-action="$contract->id"
                            :permissions="[
                                'view' => 'AccelHr - Kontrak - Melihat Data',
                                'edit' => 'AccelHr - Kontrak - Mengubah Data',
                                'delete' => 'AccelHr - Kontrak - Menghapus Data'
                            ]"
                            edit-class="btn_contract_edit"
                            delete-class="btn_contract_delete"
                            variant="action"
                        />
                    @endcanany
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="d-flex align-items-center flex-wrap">
				<div class="bg-lighter px-3 py-2 rounded me-auto">
					<p class="mb-1">
						<span class="fw-medium text-heading">{{ Number::currency($contract->rates, in: 'IDR', locale: 'id', precision: 0) }}</span>
					</p>
					<span class="text-body">{{ Str::title($contract->type->name) }}</span>
				</div>
				<div class="text-start">
					<p class="mb-1">
						<span class="text-heading fw-medium">Mulai: </span>{{ Carbon::parse($contract->start_date)->isoFormat('DD MMMM YYYY') }}
					</p>
					<p class="mb-1">
						<span class="text-heading fw-medium">Berakhir: </span> @if($contract->end_date) {{ Carbon::parse($contract->end_date)->isoFormat('DD MMMM YYYY') }} @else Masih Berjalan @endif
					</p>
				</div>
			</div>
		</div>
        @if (is_null($contract->end_date))
            <div class="card-body border-top">
                <x-ui::elements.button
                    class="btn btn-sm btn-label-info btn_closing_contract"
                    title="Tutup Kontrak {{ $contract->title }}"
                    value="{{ $contract->id }}"
                    data-bs-toggle="modal"
                    data-bs-target="#modal_contract_closing"
                >
                    Kontrak Berakhir
                </x-ui::elements.button>
            </div>
        @endif
	</div>
</div>
