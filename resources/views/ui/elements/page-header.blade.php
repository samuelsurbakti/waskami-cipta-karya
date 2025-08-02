@props([
    'breadcrumbs' => [],
])

<div class="d-grid d-sm-flex align-items-center justify-content-between">
    <x-ui::elements.page-title />
    <x-ui::elements.breadcrumbs :items="$breadcrumbs" />
</div>
