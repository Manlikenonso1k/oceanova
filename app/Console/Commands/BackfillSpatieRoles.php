<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BackfillSpatieRoles extends Command
{
    protected $signature = 'roles:backfill-spatie {--dry-run : Preview changes without writing role assignments}';

    protected $description = 'Backfill Spatie roles for all users using the current users.role value.';

    public function handle(): int
    {
        $roleClass = '\\Spatie\\Permission\\Models\\Role';

        if (! class_exists($roleClass)) {
            $this->error('Spatie role model not found. Install spatie/laravel-permission first.');

            return 1;
        }

        if (! method_exists(User::class, 'syncRoles')) {
            $this->error('User model does not expose syncRoles(). Ensure HasRoles trait is added.');

            return 1;
        }

        $dryRun = (bool) $this->option('dry-run');

        $this->info($dryRun
            ? 'Dry run enabled: no database writes will be made.'
            : 'Backfilling Spatie roles from users.role values...');

        $processed = 0;
        $updated = 0;
        $skipped = 0;

        User::query()
            ->select(['id', 'name', 'email', 'role'])
            ->orderBy('id')
            ->chunkById(200, function ($users) use (&$processed, &$updated, &$skipped, $dryRun, $roleClass): void {
                foreach ($users as $user) {
                    if (! $user instanceof User) {
                        continue;
                    }

                    $processed++;

                    $role = trim((string) $user->role);
                    if ($role === '') {
                        $skipped++;
                        $this->warn("Skipped user #{$user->id} ({$user->email}): users.role is empty.");
                        continue;
                    }

                    if (! $dryRun) {
                        $roleClass::findOrCreate($role);
                        $user->syncRoles([$role]);
                    }

                    $updated++;
                }
            });

        $this->newLine();
        $this->info('Backfill summary');
        $this->line("- Processed: {$processed}");
        $this->line("- Role synced: {$updated}");
        $this->line("- Skipped: {$skipped}");

        return 0;
    }
}
