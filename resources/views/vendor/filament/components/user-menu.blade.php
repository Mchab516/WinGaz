<x-filament::dropdown placement="bottom-end" class="ms-auto">
    <x-slot name="trigger">
        <button class="focus:outline-none">
            <div class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold md:w-11 md:h-11 md:text-base">
                {{ Str::limit(auth()->user()->name, 2, '') }}
            </div>

        </button>
    </x-slot>

    <x-filament::dropdown.header>
        {{ auth()->user()->name }}
    </x-filament::dropdown.header>

    <x-filament::dropdown.list>
        @foreach ($items as $item)
        <x-filament::dropdown.list.item
            :icon="$item->getIcon()"
            :url="$item->getUrl()"
            :tag="$item->getTag()"
            :color="$item->getColor()"
            :attributes="$item->getExtraAttributes()"
            :dark-mode="config('filament.dark_mode')">
            {{ $item->getLabel() }}
        </x-filament::dropdown.list.item>
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>