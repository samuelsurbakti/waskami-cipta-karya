@props([
    'id',                  // ID modal
    'title',               // Judul modal
    'description' => null, // Deskripsi modal
    'loadingTargets' => [], // Array target loading
    'size' => 'lg',        // ukuran modal: sm, md, lg, xl
])

<div wire:ignore.self class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered">
        <div class="modal-content">
            {{-- Loading states --}}
            @foreach ($loadingTargets as $target)
                <x-ui::elements.loading text="Mengambil Data" target="{{ $target }}" />
            @endforeach

            {{-- Header --}}
            <div wire:loading.remove wire:target="{{ implode(', ', $loadingTargets) }}" class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div wire:loading.remove wire:target="{{ implode(', ', $loadingTargets) }}" class="modal-body pt-0">
                <div class="text-center mb-4">
                    <h3 class="mb-0">{{ $title }}</h3>
                    @if($description)
                        <p>{{ $description }}</p>
                    @endif
                </div>

                {{-- Konten/form disediakan oleh slot --}}
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
