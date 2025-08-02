<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Helpers\BreadcrumbHelper;

new #[Layout('ui.layouts.vertical')] class extends Component {
    //
}; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <x-ui::elements.page-header :breadcrumbs="BreadcrumbHelper::generate_breadcrumbs()" />
</div>
