@props([
    'title' => null,
    'subtitle' => null,
    'headerAction' => null,
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 ' . $class]) }}>
    @if($title || $headerAction)
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 sm:pb-4 mb-4 border-b border-gray-100 gap-2">
            <div>
                @if($title)
                    <h3 class="text-base sm:text-lg font-bold text-indigo-950 mb-0.5">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-gray-400 mb-0">{{ $subtitle }}</p>
                @endif
            </div>
            @if($headerAction)
                <div class="w-full sm:w-auto">
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif

    <div class="w-full">
        {{ $slot }}
    </div>
</div>
