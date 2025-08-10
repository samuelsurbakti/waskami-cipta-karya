<?php

use App\Helpers\PageHelper;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.vertical')] class extends Component {
    //
}; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div wire:ignore>
        <x-ui::elements.page-header :info="PageHelper::info()" />
    </div>

    @can('AccelRef - Kecamatan - Melihat Daftar Data')
        <div class="card mb-6">
            <h5 class="card-header pb-0 pt-3 text-start">Daftar Kecamatan</h5>
            <livewire:accel-ref.district-table />
        </div>
    @endcan
</div>

