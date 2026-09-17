@props([
    'title',
    'subtitle' => null
])

<section class="header mb-8">
    <div class="flex flex-col gap-y-4 md:flex-row md:items-center justify-between header-section w-full">
        <div class="title">
            <h1 class="text-2xl text-indigo-950 font-bold mb-1">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-sm text-gray-500 mb-0">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        @if(isset($actions) && $actions->isNotEmpty())
            <div class="flex flex-row gap-x-3 items-center flex-wrap">
                {{ $actions }}
            </div>
        @endif
    </div>
</section>
