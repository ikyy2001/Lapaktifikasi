@props([
    'title',
    'value',
    'icon' => 'bi bi-graph-up',
    'iconBg' => 'bg-violet-700',
    'badgeText' => null,
    'badgeClass' => 'text-green-600 bg-green-50',
    'subtitle' => null
])

<div class="item-stat bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
    <div class="flex flex-row mb-6 justify-between items-center">
        <div class="{{ $iconBg }} rounded-full w-fit p-3 text-white flex items-center justify-center">
            @if(str_contains($icon, '<svg') || str_contains($icon, '<path'))
                {!! $icon !!}
            @else
                <i class="{{ $icon }} text-xl"></i>
            @endif
        </div>
        @if($badgeText)
            <div class="flex flex-row gap-x-1 font-semibold items-center text-xs px-2.5 py-1 rounded-full {{ $badgeClass }}">
                {{ $badgeText }}
            </div>
        @endif
    </div>
    <h3 class="text-2xl text-indigo-950 font-bold mb-1">
        {{ $value }}
    </h3>
    <p class="text-xs text-gray-400 font-medium mb-0">
        {{ $title }}
    </p>
    @if($subtitle)
        <span class="text-[11px] text-gray-400 mt-1 block">{{ $subtitle }}</span>
    @endif
</div>
