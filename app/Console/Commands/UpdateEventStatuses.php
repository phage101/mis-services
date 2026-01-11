<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update event statuses based on dates (upcoming, ongoing, completed)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $events = Event::with('dates')
            ->whereNotIn('status', ['cancelled'])
            ->get();

        $updated = 0;

        foreach ($events as $event) {
            $oldStatus = $event->status;
            $event->updateStatusFromDates();

            if ($event->status !== $oldStatus) {
                $updated++;
                $this->info("Updated: {$event->title} ({$oldStatus} → {$event->status})");
            }
        }

        $this->info("Updated {$updated} event(s) status.");
        return 0;
    }
}
