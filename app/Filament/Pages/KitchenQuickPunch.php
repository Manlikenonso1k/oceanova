<?php

namespace App\Filament\Pages;

use App\Models\Meal;
use App\Models\Order;
use App\Services\InventoryService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KitchenQuickPunch extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Quick Punch POS';

    protected static ?string $navigationGroup = 'Restaurant';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.kitchen-quick-punch';

    public string $activeCategory = 'main';

    public string $tableNumber = '';

    public string $notes = '';

    /** @var array<int, array{meal_id:int,name:string,price:float,quantity:int,subtotal:float,category:string}> */
    public array $cartItems = [];

    public static function canAccess(array $parameters = []): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['steward', 'general_order_person', 'kitchen_manager', 'admin', 'super_admin']);
    }

    public function setCategory(string $category): void
    {
        if (in_array($category, ['main', 'drinks', 'sides'], true)) {
            $this->activeCategory = $category;
        }
    }

    public function addItem(int $mealId): void
    {
        $meal = Meal::query()->find($mealId);

        if (!$meal) {
            return;
        }

        $price = (float) $meal->price;

        if (isset($this->cartItems[$mealId])) {
            $this->cartItems[$mealId]['quantity']++;
            $this->cartItems[$mealId]['subtotal'] = $this->cartItems[$mealId]['quantity'] * $price;
            return;
        }

        $this->cartItems[$mealId] = [
            'meal_id' => (int) $meal->id,
            'name' => (string) $meal->name,
            'price' => $price,
            'quantity' => 1,
            'subtotal' => $price,
            'category' => $this->resolveCategory($meal),
        ];
    }

    public function increaseQty(int $mealId): void
    {
        if (!isset($this->cartItems[$mealId])) {
            return;
        }

        $this->cartItems[$mealId]['quantity']++;
        $this->cartItems[$mealId]['subtotal'] = $this->cartItems[$mealId]['quantity'] * $this->cartItems[$mealId]['price'];
    }

    public function decreaseQty(int $mealId): void
    {
        if (!isset($this->cartItems[$mealId])) {
            return;
        }

        $this->cartItems[$mealId]['quantity']--;

        if ($this->cartItems[$mealId]['quantity'] <= 0) {
            unset($this->cartItems[$mealId]);
            return;
        }

        $this->cartItems[$mealId]['subtotal'] = $this->cartItems[$mealId]['quantity'] * $this->cartItems[$mealId]['price'];
    }

    public function removeItem(int $mealId): void
    {
        unset($this->cartItems[$mealId]);
    }

    public function placeOrder(): void
    {
        $table = trim($this->tableNumber);

        if ($table === '') {
            Notification::make()->title('Table number is required.')->danger()->send();
            return;
        }

        if ($this->cartItems === []) {
            Notification::make()->title('Add at least one item to the order.')->danger()->send();
            return;
        }

        $total = $this->getCartTotal();

        DB::transaction(function () use ($table, $total): void {
            $items = collect(array_values($this->cartItems))
                ->map(fn (array $item): array => [
                    'meal_id' => (int) $item['meal_id'],
                    'meal_name' => (string) $item['name'],
                    'quantity' => (int) $item['quantity'],
                    'unit_price' => (float) $item['price'],
                    'subtotal' => (float) $item['subtotal'],
                    'total' => (float) $item['subtotal'],
                ])
                ->values()
                ->all();

            $order = Order::query()->create([
                'customer_name' => $table,
                'table_number' => $table,
                'waiter_id' => Auth::id(),
                'status' => 'pending',
                'notes' => trim($this->notes) !== '' ? trim($this->notes) : null,
                'items' => $items,
                'total' => $total,
                'total_price' => $total,
            ]);

            $order->orderItems()->createMany($items);

            app(InventoryService::class)->processOrderStockOut($order, Auth::id());
        });

        $this->cartItems = [];
        $this->notes = '';

        Notification::make()->title('Order sent to kitchen queue.')->success()->send();
    }

    public function getGroupedMealsProperty(): array
    {
        $meals = Meal::query()
            ->with('menuSection:id,title')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $groups = [
            'main' => [],
            'drinks' => [],
            'sides' => [],
        ];

        foreach ($meals as $meal) {
            $group = $this->resolveCategory($meal);
            $groups[$group][] = [
                'id' => (int) $meal->id,
                'name' => (string) $meal->name,
                'price' => (float) $meal->price,
            ];
        }

        return $groups;
    }

    public function getCartTotal(): float
    {
        return (float) collect($this->cartItems)->sum(fn (array $item): float => (float) ($item['subtotal'] ?? 0));
    }

    private function resolveCategory(Meal $meal): string
    {
        $category = strtolower(trim((string) ($meal->category ?? '')));
        $sectionTitle = strtolower(trim((string) ($meal->menuSection?->title ?? '')));
        $source = $category . ' ' . $sectionTitle;

        if (str_contains($source, 'drink') || str_contains($source, 'wine') || str_contains($source, 'cocktail') || str_contains($source, 'mocktail') || str_contains($source, 'beverage')) {
            return 'drinks';
        }

        if (str_contains($source, 'side') || str_contains($source, 'add') || str_contains($source, 'extra')) {
            return 'sides';
        }

        return 'main';
    }
}
