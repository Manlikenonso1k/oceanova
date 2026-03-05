<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-4">
            <div class="flex flex-wrap gap-2">
                <x-filament::button color="gray" wire:click="setCategory('main')" size="sm">Main Meals</x-filament::button>
                <x-filament::button color="info" wire:click="setCategory('drinks')" size="sm">Drinks</x-filament::button>
                <x-filament::button color="warning" wire:click="setCategory('sides')" size="sm">Sides</x-filament::button>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach (($this->groupedMeals[$activeCategory] ?? []) as $meal)
                    <button
                        type="button"
                        wire:click="addItem({{ $meal['id'] }})"
                        class="rounded-xl border border-gray-200 bg-white p-4 text-left shadow-sm hover:border-primary-400 hover:shadow-md transition"
                    >
                        <p class="text-sm font-semibold text-gray-900">{{ $meal['name'] }}</p>
                        <p class="mt-1 text-xs text-gray-500">₦{{ number_format((float) $meal['price'], 2) }}</p>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Active Order</h3>

            <div class="space-y-3">
                <x-filament::input.wrapper>
                    <x-filament::input type="text" wire:model.live="tableNumber" placeholder="Table Number (e.g. T-12)" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <textarea wire:model.live="notes" rows="2" class="fi-input block w-full border-0 bg-transparent px-0 py-1.5 text-sm text-gray-900 ring-0 focus:ring-0" placeholder="Optional kitchen note"></textarea>
                </x-filament::input.wrapper>
            </div>

            <div class="max-h-80 space-y-2 overflow-y-auto">
                @forelse ($cartItems as $item)
                    <div class="rounded-lg border border-gray-100 p-3">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                            <button type="button" wire:click="removeItem({{ $item['meal_id'] }})" class="text-xs text-danger-600">Remove</button>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="inline-flex items-center gap-2">
                                <button type="button" wire:click="decreaseQty({{ $item['meal_id'] }})" class="h-7 w-7 rounded border border-gray-300 text-sm">-</button>
                                <span class="text-sm">{{ $item['quantity'] }}</span>
                                <button type="button" wire:click="increaseQty({{ $item['meal_id'] }})" class="h-7 w-7 rounded border border-gray-300 text-sm">+</button>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">₦{{ number_format((float) $item['subtotal'], 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Tap menu tiles to add items.</p>
                @endforelse
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 pt-3">
                <p class="text-sm text-gray-600">Total</p>
                <p class="text-base font-semibold text-gray-900">₦{{ number_format($this->getCartTotal(), 2) }}</p>
            </div>

            <x-filament::button color="primary" class="w-full" wire:click="placeOrder">
                Send to Kitchen
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
