<?php

namespace App\Console\Commands;

use App\Mail\NewsletterDigest;
use App\Models\NewsletterSubscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendNewsletterDigest extends Command
{
    protected $signature = 'newsletter:send';

    protected $description = 'Send the Oceanova newsletter to subscribers who are due.';

    public function handle(): int
    {
        $cutoff = now()->subDays(3);
        $sent = 0;

        NewsletterSubscriber::query()
            ->where(function ($query) use ($cutoff) {
                $query->whereNull('last_sent_at')
                    ->orWhere('last_sent_at', '<=', $cutoff);
            })
            ->orderBy('id')
            ->chunkById(200, function ($subscribers) use (&$sent) {
                foreach ($subscribers as $subscriber) {
                    try {
                        Mail::to($subscriber->email)->send(new NewsletterDigest());
                        $subscriber->forceFill(['last_sent_at' => now()])->save();
                        $sent++;
                    } catch (Throwable $exception) {
                        report($exception);
                    }
                }
            });

        $this->info("Newsletter sent to {$sent} subscribers.");

        return self::SUCCESS;
    }
}
