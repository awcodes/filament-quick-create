@php
    $keyBindings = collect($keyBindings ?? [])
        ->map(fn (string $keyBinding): string => str_replace('+', '-', $keyBinding))
        ->implode('.');
@endphp

<div class="quick-create-component"
     @if($keyBindings)
         x-data="{
            toggleDropdown() {
                this.$refs.triggerButton.click()
                this.$refs.triggerButton.focus()
            }
        }"
     @endif
>
    @if ($resources && $this->shouldBeHidden() === false)
        <x-filament::dropdown placement="bottom-end" :teleport="true">
            <x-slot name="trigger">
                <button
                    @if($keyBindings)
                        x-ref="triggerButton"
                        x-mousetrap.global.{{ $keyBindings }}="toggleDropdown"
                    @endif
                    @class([
                        'flex flex-shrink-0 bg-gray-100 items-center justify-center text-primary-500 hover:text-primary-900 dark:bg-gray-800 hover:bg-primary-500 dark:hover:bg-primary-500',
                        'rounded-full' => $rounded,
                        'rounded-md' => ! $rounded,
                        'size-8' => ! $label,
                        'py-1 ps-3 pe-4 gap-1' => $label,
                    ])
                    @if($tooltip)
                        x-tooltip="{
                            content: '{{ $tooltip }}'
                        }"
                    @endif
                    aria-label="{{ __('quick-create::quick-create.button_label') }}"
                >
                    <x-filament::icon
                        alias="filament-quick-create::add"
                        icon="heroicon-o-plus"
                        class="size-5"
                    />
                    @if ($label)
                        <span class="">{{ $label }}</span>
                    @endif
                </button>
            </x-slot>

            <x-filament::dropdown.list>
                @foreach($resources as $resource)
                    <x-filament::dropdown.list.item
                        :icon="$hiddenIcons ? null : $resource['icon']"
                        :wire:click="$resource['action']"
                        :href="$resource['url']"
                        :tag="$resource['url'] ? 'a' : 'button'"
                        class="{{ 'quick-create-action-' . str($resource['label'])->slug() }}"
                    >
                        {{ $resource['label'] }}
                    </x-filament::dropdown.list.item>
                @endforeach
            </x-filament::dropdown.list>
        </x-filament::dropdown>

        <x-filament-actions::modals/>
    @endif
</div>
