<div class="quick-create-component">
@if ($resources)
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger" class="ms-4 rtl:me-4 rtl:ms-0">
            <button
                class="flex flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 items-center justify-center dark:bg-gray-900"
                aria-label="{{ __('filament::layout.buttons.user_menu.label') }}"
            >
                @svg('heroicon-o-plus', 'w-4 h-4')
            </button>
        </x-slot>
        <x-filament::dropdown.list>
            @foreach($resources as $resource)
{{--                {{ ($this->createAction)(['model' => $resource['model']]) }}--}}
                <x-filament::dropdown.list.item
                    :color="'secondary'"
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

@push('scripts')
    <style>
        .quick-create-component .filament-dropdown .filament-button {
            border: none;
            justify-content: start;
            font-size: .875rem;
            font-weight: 400;
            color: var(--gray-color-700);
            padding: .5rem;
        }

        .quick-create-component .filament-dropdown .filament-button:hover,
        .quick-create-component .filament-dropdown .filament-button:focus {
            background-color: rgba(var(--gray-color-500), .1);
            z-index: 1;
        }

        .quick-create-component .filament-dropdown .filament-button svg {
            margin-inline-start: 0;
        }

        .dark .quick-create-component .filament-dropdown .filament-button {
            color: var(--gray-color-200);
        }
    </style>
@endpush