<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventDateMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = \App\Models\Event::all();
        foreach ($events as $event) {
            // Only migrate if no dates exist yet to avoid duplicates
            if ($event->dates()->count() === 0) {
                // Determine if it's a multi-day event or single day
                $startDate = $event->start_date;
                $endDate = $event->end_date ?: $event->start_date;

                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    \App\Models\EventDate::create([
                        'event_id' => $event->id,
                        'date' => $currentDate->format('Y-m-d'),
                        'start_time' => $event->start_time,
                        'end_time' => $event->end_time,
                    ]);
                    $currentDate->addDay();
                }
            }
        }
    }
}
