<?php

use App\Helpers\PageHelper;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.vertical')] class extends Component {
    //
}; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <x-ui::elements.page-header :info="PageHelper::info()" />

    <div class="row">

    </div>
</div>
