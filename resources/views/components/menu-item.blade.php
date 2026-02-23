@props([
    'name' => null,
    'price' => null,
    'description' => null,
    'image' => null,
    'tier' => null,
    'priceRange' => null,
    'example' => null,
    'tags' => [],
    'id' => null,
])

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $itemName = $name ?? $tier;
    $itemPrice = $price ?? $priceRange;
    $itemDescription = $description ?? $example;

    $fallbackImages = [
        'assets/template/images/breakfast-1.jpg',
        'assets/template/images/lunch-1.jpg',
        'assets/template/images/dinner-1.jpg',
        'assets/template/images/dessert-1.jpg',
        'assets/template/images/drink-1.jpg',
        'assets/template/images/wine-1.jpg',
    ];

    $seed = abs(crc32((string) $itemName));
    $fallback = $fallbackImages[$seed % count($fallbackImages)];
    $itemImage = asset($fallback);

    if (!empty($image)) {
        if (Str::startsWith($image, ['http://', 'https://', '/'])) {
            $itemImage = $image;
        } elseif (Str::startsWith($image, ['assets/', 'images/', 'storage/'])) {
            $itemImage = asset($image);
        } else {
            $itemImage = Storage::disk('public')->url($image);
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-100 shadow-sm p-3 sm:p-5 flex flex-col gap-3 sm:gap-4']) }} @if($id) id="{{ $id }}" @endif>
    <div class="relative w-full overflow-hidden rounded-lg border border-slate-100 aspect-[4/3]">
        <img
            src="{{ $itemImage }}"
            alt="{{ $itemName }} at Oceanova"
            class="h-full w-full object-cover object-center"
            loading="lazy"
        >
    </div>

    <div class="flex items-start justify-between gap-3">
        <div>
            <h3 class="text-lg font-semibold text-white">{{ $itemName }}</h3>
            @if($itemDescription)
                <p class="text-sm text-slate-200">{{ $itemDescription }}</p>
            @endif
        </div>
        @if($itemPrice)
            <span class="shrink-0 rounded-full bg-slate-900 text-white text-xs font-semibold px-3 py-1">
                {{ $itemPrice }}
            </span>
        @endif
    </div>

    @if(!empty($tags))
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
    @endif
</div>
