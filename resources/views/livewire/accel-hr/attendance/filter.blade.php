<?php

use Livewire\Volt\Component;

new class extends Component {
    public $attendance_filter_date;

    public function set_attendance_filter_field($field, $value)
    {
        $this->$field = $value;

        $filters = [
            'date' => $this->attendance_filter_date,
        ];

        $this->dispatch('apply_filters', filters: $filters);
    }
}; ?>

<div class="card mb-4 border-top">
    <div class="card-body d-flex flex-column flex-fill justify-content-between align-items-center">
        <h5 class="card-title text-center">Filter</h5>

        <x-ui::forms.input
            wire:model="attendance_filter_date"
            type="text"
            label="Tanggal"
            placeholder="2025-12-30"
            container_class="col-12 mb-6"
        />

        <button type="button" id="btn_attendance_filter" class="btn btn-primary">
            <span class="icon-base bx bx-search icon-xs me-2"></span>Cari
        </button>
    </div>
</div>

@script
    <script>
        $(document).ready(function () {
            var filter_date = $("#attendance_filter_date").flatpickr({
                weekNumbers: false,
                enableTime: false,
                mode: "multiple",
                maxDate: "today"
            });

            $(document).on('change', '#attendance_filter_date', function () {
                $wire.set_attendance_filter_field($(this).attr('id'), $(this).val());
            });
        });
    </script>
@endscript
