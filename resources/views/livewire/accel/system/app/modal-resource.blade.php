<?php

use App\Models\Sys\App;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public ?string $app_id = null;

    #[Validate('required|string', as: 'Nama')]
    public ?string $app_name = null;

    #[Validate('required|string', as: 'Subdomain')]
    public ?string $app_subdomain = null;

    #[Validate('required|integer', as: 'Urutan')]
    public $app_order_number;

    #[On('reset_app')]
    public function reset_app()
    {
        $this->reset(['app_id', 'app_name', 'app_subdomain', 'app_order_number']);
        $this->resetValidation();
    }

    #[On('set_app')]
    public function set_app($app_id)
    {
        $this->app_id = $app_id;
        $app = App::findOrFail($this->app_id);

        $this->app_name = $app->name;
        $this->app_subdomain = $app->subdomain;
        $this->app_order_number = $app->order_number;
    }

    public function save()
    {
        $this->validate();

        if(is_null($this->app_id)) {
            $app = App::create([
                'name' => $this->app_name,
                'subdomain' => $this->app_subdomain,
                'image' => Str::before($this->app_subdomain, '.'),
                'order_number' => $this->app_order_number,
            ]);
        } else {
            $app = App::findOrFail($this->app_id);

            $app->update([
                'name' => $this->app_name,
                'subdomain' => $this->app_subdomain,
                'order_number' => $this->app_order_number,
            ]);

            $this->dispatch("refresh_app_component.{$app->id}");
        }

        $this->dispatch('close_modal_app_resource');

        LivewireAlert::title('')
            ->text('Berhasil '.(is_null($this->app_id) ? 'menambah' : 'mengubah').' Aplikasi')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_app();
    }
}; ?>

<div wire:ignore.self class="modal fade" id="modal_app_resource" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <x-ui::elements.loading text="Mengambil Data" target="set_app, reset_app" />
            <x-ui::elements.loading text="Menyimpan Data" target="save" />

            <div wire:loading.remove wire:target="set_app, reset_app, save" class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div wire:loading.remove wire:target="set_app, reset_app, save" class="modal-body pt-0">
                <div class="text-center mb-4">
                    <h3 class="mb-0">{{ (is_null($app_id) ? 'Tambah' : 'Edit') }} Aplikasi</h3>
                    <p>Di sini, Anda dapat {{ (is_null($app_id) ? 'menambah data' : 'mengubah informasi') }} Aplikasi.</p>
                </div>
                <form wire:submit="save" method="POST">
                    @csrf
                    <x-ui::forms.input
                        wire:model.live="app_name"
                        type="text"
                        label="Nama"
                        placeholder="Nama"
                        container_class="col-12 mb-6"
                    />

                    <x-ui::forms.input
                        wire:model.live="app_subdomain"
                        type="text"
                        label="Subdomain"
                        placeholder="example.waskami-cipta-karya.com"
                        container_class="col-12 mb-6"
                    />

                    <x-ui::forms.input
                        wire:model.live="app_order_number"
                        type="text"
                        label="Urutan"
                        placeholder="1-100"
                        container_class="col-12 mb-6"
                    />

                    <div class="col-12 text-center mt-8">
                        <x-ui::elements.button type="submit" class="btn-primary me-sm-3 me-1">
                            Simpan
                        </x-ui::elements.button>
                        <x-ui::elements.button type="reset" class="btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Batalkan
                        </x-ui::elements.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Livewire.on('close_modal_app_resource', () => {
            var modalElement = document.getElementById('modal_app_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        $(document).ready(function () {
            $(document).on('click', '#btn_app_add', function () {
                $wire.reset_app();
            });

            $(document).on('click', '.btn_app_edit', function () {
                $wire.set_app($(this).attr('value'));
            });

            $(document).on('click', '.btn_app_delete', function () {
                $wire.ask_to_delete_app($(this).attr('value'))
            });
        });
    </script>
@endscript
