<x-filament-panels::page>
    <style>
        .kqp-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .kqp-scrollbar::-webkit-scrollbar-track {
            background: rgba(24, 24, 27, 0.95);
            border-radius: 9999px;
        }

        .kqp-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.45);
            border-radius: 9999px;
        }

        .kqp-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 175, 55, 0.7);
        }
    </style>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="max-h-[78vh] overflow-y-auto rounded-xl border border-[#D4AF37]/20 bg-black p-3 sm:p-5 shadow-2xl kqp-scrollbar">
                <div class="sticky top-0 z-20 -mx-3 -mt-3 mb-4 border-b border-[#D4AF37]/20 bg-black px-3 pt-3 pb-3 sm:-mx-5 sm:-mt-5 sm:px-5 sm:pt-5">
                    <h2 class="text-2xl font-bold text-[#D4AF37] mb-4">
                        Menu Selection
                    </h2>
                    <div class="flex flex-wrap gap-2 text-sm">
                        @foreach ($this->menuSections as $section)
                            <a href="#{{ $section['id'] }}" class="whitespace-nowrap rounded-full border border-[#D4AF37]/30 bg-zinc-900 px-3 py-1.5 font-medium text-[#E2E8F0] hover:border-[#D4AF37]/70 hover:text-[#D4AF37]">
                                {{ $section['title'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @foreach ($this->menuSections as $section)
                    <section id="{{ $section['id'] }}" class="scroll-mt-20 mb-6">
                        <h3 class="mb-1 text-sm font-semibold uppercase tracking-wide text-[#D4AF37]">{{ $section['title'] }}</h3>
                        @if (!empty($section['subtitle']))
                            <p class="mb-3 text-xs text-slate-300">{{ $section['subtitle'] }}</p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                $products = $section['items'] ?? [];
                            @endphp

                            @forelse ($products as $product)
                                <button
                                    type="button"
                                    wire:click="addItem({{ $product['id'] }})"
                                    class="bg-zinc-900/50 rounded-xl border border-[#D4AF37]/10 p-4 shadow-sm flex flex-col text-left hover:border-[#D4AF37]/50 transition-all"
                                >
                                    <div class="w-full h-40 overflow-hidden rounded-lg mb-3" style="aspect-ratio: 402 / 263.812;">
                                        @if (!empty($product['image_url']))
                                            <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover" loading="lazy" />
                                        @else
                                            <div class="w-full h-full bg-zinc-800"></div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col flex-grow gap-2">
                                        <div class="flex justify-between items-start mb-2 gap-2">
                                            <h3 class="text-lg font-bold text-white">{{ $product['name'] }}</h3>
                                            <span class="text-[#D4AF37] font-black whitespace-nowrap">
                                                ₦{{ number_format((float) $product['price']) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-400 leading-tight line-clamp-2">{{ $product['description'] !== '' ? $product['description'] : 'No recipe instruction available yet.' }}</p>
                                    </div>
                                </button>
                            @empty
                                <p class="col-span-full text-sm text-slate-400">No items found in {{ strtolower($section['title']) }}.</p>
                            @endforelse
                        </div>
                    </section>
                @endforeach
            </div>
        </div>

        <div class="space-y-4 rounded-xl border border-[#D4AF37]/20 bg-black p-4 shadow-2xl">
            <h3 class="text-base font-semibold text-[#D4AF37]">Active Order</h3>

            <div class="space-y-3">
                <x-filament::input.wrapper>
                    <x-filament::input type="text" wire:model.live="tableNumber" placeholder="Table Number (e.g. T-12)" class="text-white" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <textarea wire:model.live="notes" rows="2" class="fi-input block w-full border-0 bg-transparent px-0 py-1.5 text-sm text-slate-100 ring-0 focus:ring-0" placeholder="Optional kitchen note"></textarea>
                </x-filament::input.wrapper>
            </div>

            <div class="max-h-80 space-y-2 overflow-y-auto kqp-scrollbar">
                @forelse ($cartItems as $item)
                    <div class="rounded-lg border border-[#D4AF37]/20 bg-zinc-900 p-3">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium text-white">{{ $item['name'] }}</p>
                            <button type="button" wire:click="removeItem({{ $item['meal_id'] }})" class="text-xs text-danger-600">Remove</button>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="inline-flex items-center gap-2">
                                <button type="button" wire:click="decreaseQty({{ $item['meal_id'] }})" class="h-7 w-7 rounded border border-[#D4AF37]/40 text-sm text-[#E2E8F0]">-</button>
                                <span class="text-sm text-slate-100">{{ $item['quantity'] }}</span>
                                <button type="button" wire:click="increaseQty({{ $item['meal_id'] }})" class="h-7 w-7 rounded border border-[#D4AF37]/40 text-sm text-[#E2E8F0]">+</button>
                            </div>
                            <p class="text-sm font-semibold text-[#D4AF37]">₦{{ number_format((float) $item['subtotal'], 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Tap menu tiles to add items.</p>
                @endforelse
            </div>

            <div class="flex items-center justify-between border-t border-[#D4AF37]/20 pt-3">
                <p class="text-sm text-slate-300">Total</p>
                <p class="text-base font-semibold text-[#D4AF37]">₦{{ number_format($this->getCartTotal(), 2) }}</p>
            </div>

            <x-filament::button color="warning" class="w-full" wire:click="placeOrder">
                Send to Kitchen
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
