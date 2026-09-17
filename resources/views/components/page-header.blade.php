@props([
    'title',
    'subtitle' => null
])

<section class="header mb-6 sm:mb-8">
    <div class="flex flex-col gap-y-3 sm:flex-row sm:items-center justify-between header-section w-full">
        <div class="title">
            <h1 class="text-xl sm:text-2xl text-indigo-950 font-bold mb-1 tracking-tight">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-xs sm:text-sm text-gray-500 mb-0 leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        @if(isset($actions) && $actions->isNotEmpty())
            <div class="flex flex-row gap-2 sm:gap-3 items-center flex-wrap w-full sm:w-auto">
                {{ $actions }}
            </div>
        @endif
    </div>
</section>
