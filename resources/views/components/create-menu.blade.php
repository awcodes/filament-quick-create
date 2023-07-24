<div class="quick-create-component">
@if ($resources)
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger">
            <button
                class="flex flex-shrink-0 w-9 h-9 rounded-full bg-gray-100 items-center justify-center text-primary-500 hover:text-primary-900 dark:bg-gray-800 hover:bg-primary-500 dark:hover:bg-primary-500"
                aria-label="{{ __('filament-quick-create::quick-create.button_label') }}"
            >
                <x-filament::icon
                    alias="filament-quick-create::add"
                    icon="heroicon-o-plus"
                    class="w-5 h-5"
                />
            </button>
        </x-slot>
        <x-filament::dropdown.list>
            @foreach($resources as $resource)
                <x-filament::dropdown.list.item
                    :icon="$resource['icon']"
                    :wire:click="$resource['action']"
                    :href="$resource['url']"
                    :tag="$resource['url'] ? 'a' : 'button'"
                >
                    {{ $resource['label'] }}
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>

    <x-filament-actions::modals />
@endif
</div>
