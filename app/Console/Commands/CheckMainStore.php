<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\DepartmentStock;
use App\Models\Ingredient;
use Illuminate\Console\Command;

class CheckMainStore extends Command
{
    protected $signature = 'inventory:check-main-store {ingredient_id?}';

    protected $description = 'Check Main Store existence and optionally show stock for an ingredient';

    public function handle(): int
    {
        $main = Department::where('is_main', true)->first();

        if (! $main) {
            $this->error('No Main Store department found.');
            return 1;
        }

        $this->info(sprintf('Main Store: ID %d - %s (code: %s)', $main->id, $main->name, $main->code));

        $ingredientId = $this->argument('ingredient_id');
        if ($ingredientId) {
            $ingredient = Ingredient::find($ingredientId);
            if (! $ingredient) {
                $this->error(sprintf('Ingredient with ID %s not found.', $ingredientId));
                return 2;
            }

            $stock = DepartmentStock::where('department_id', $main->id)
                ->where('ingredient_id', $ingredientId)
                ->first();

            $qty = $stock ? $stock->quantity : 0;
            $this->info(sprintf('Ingredient %s (ID %d) stock in Main Store: %s', $ingredient->name, $ingredient->id, $qty));
        } else {
            $this->line('To check a specific ingredient, pass `ingredient_id`.');
            $this->line('Example: php artisan inventory:check-main-store 5');
        }

        return 0;
    }
}
