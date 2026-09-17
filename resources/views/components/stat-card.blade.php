@props([
    'title',
    'value',
    'icon' => 'bi bi-graph-up',
    'iconBg' => 'bg-violet-700',
    'badgeText' => null,
    'badgeClass' => 'text-green-600 bg-green-50',
    'subtitle' => null
])

<div class="item-stat bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
    <div class="flex flex-row mb-4 sm:mb-6 justify-between items-center">
        <div class="{{ $iconBg }} rounded-full w-fit p-2.5 sm:p-3 text-white flex items-center justify-center">
            @if(str_contains($icon, '<svg') || str_contains($icon, '<path'))
                {!! $icon !!}
            @else
                <i class="{{ $icon }} text-lg sm:text-xl"></i>
            @endif
        </div>
        @if($badgeText)
            <div class="flex flex-row gap-x-1 font-semibold items-center text-[11px] sm:text-xs px-2.5 py-0.5 sm:py-1 rounded-full {{ $badgeClass }}">
                {{ $badgeText }}
            </div>
        @endif
    </div>
    <h3 class="text-xl sm:text-2xl text-indigo-950 font-bold mb-1 truncate">
        {{ $value }}
    </h3>
    <p class="text-xs text-gray-400 font-medium mb-0 truncate">
        {{ $title }}
    </p>
    @if($subtitle)
        <span class="text-[10px] sm:text-[11px] text-gray-400 mt-1 block truncate">{{ $subtitle }}</span>
    @endif
</div>
