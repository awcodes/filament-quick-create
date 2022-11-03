<div>
@if ($resources)
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger" class="ml-4">
            <button  @class([
                'flex flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 items-center justify-center',
                'dark:bg-gray-900' => config('filament.dark_mode'),
            ]) aria-label="{{ __('filament::layout.buttons.user_menu.label') }}">
                @svg('heroicon-o-plus', 'w-4 h-4')
            </button>
        </x-slot>
        <ul @class([
            'py-1 space-y-1 overflow-hidden bg-white shadow rounded-xl',
            'dark:border-gray-600 dark:bg-gray-700' => config('filament.dark_mode'),
        ])>
            @foreach($resources as $resource)
                <x-filament::dropdown.item
                    :color="'secondary'"
                    :icon="$resource['icon']"
                    :wire:click="$resource['action']"
                    :href="$resource['url']"
                    :tag="$resource['url'] ? 'a' : 'button'"
                >
                    {{ $resource['label'] }}
                </x-filament::dropdown.item>
            @endforeach
        </ul>
    </x-filament::dropdown>
@endif
</div>