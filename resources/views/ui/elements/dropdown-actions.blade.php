@props([
    'viewRoute' => null,
    'editTarget' => null,
    'deleteAction' => null,
    'permissions' => [],
    'editClass' => 'btn_edit',
    'deleteClass' => 'btn_delete',
])

<div class="dropdown btn-pinned">
    <button type="button" class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="icon-base bx bx-dots-vertical-rounded icon-md text-body-secondary"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        {{-- Tombol Lihat --}}
        @if(isset($permissions['view']) && Gate::check($permissions['view']))
            <li>
                <a class="dropdown-item text-primary d-flex align-items-center gap-1" href="{{ $viewRoute }}">
                    <i class="bx bx-show"></i> Lihat
                </a>
            </li>
        @endif

        {{-- Tombol Edit --}}
        @if(isset($permissions['edit']) && Gate::check($permissions['edit']))
            <li>
                <button
                    class="dropdown-item text-success d-flex align-items-center gap-1 {{ $editClass }}"
                    data-bs-toggle="modal"
                    data-bs-target="#{{ $editTarget }}"
                    value="{{ $deleteAction }}">
                    <i class="bx bx-edit"></i> Edit
                </button>
            </li>
        @endif

        {{-- Tombol Hapus --}}
        @if(isset($permissions['delete']) && Gate::check($permissions['delete']))
            <li>
                <button
                    class="dropdown-item text-danger d-flex align-items-center gap-1 {{ $deleteClass }}"
                    value="{{ $deleteAction }}">
                    <i class="bx bx-trash"></i> Hapus
                </button>
            </li>
        @endif
    </ul>
</div>
