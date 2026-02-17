@props([
    'tier',
    'priceRange',
    'example',
    'tags' => [],
    'id' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-100 shadow-sm p-5 flex flex-col gap-4']) }} @if($id) id="{{ $id }}" @endif>
    <div class="flex items-start justify-between gap-3">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">{{ $tier }}</h3>
            <p class="text-sm text-slate-500">{{ $example }}</p>
        </div>
        <span class="shrink-0 rounded-full bg-slate-900 text-white text-xs font-semibold px-3 py-1">
            {{ $priceRange }}
        </span>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
            @php
                $styles = match ($tag) {
                    'V' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                    'L' => 'bg-amber-50 text-amber-700 ring-amber-200',
                    'P' => 'bg-rose-50 text-rose-700 ring-rose-200',
                    'S' => 'bg-sky-50 text-sky-700 ring-sky-200',
                    default => 'bg-slate-50 text-slate-600 ring-slate-200',
                };
            @endphp
            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $styles }}">
                <span class="h-2 w-2 rounded-full bg-current"></span>
                {{ $tag }}
            </span>
        @endforeach
    </div>
</div>
