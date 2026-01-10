<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventUuidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = \App\Models\Event::whereNull('uuid')->get();
        foreach ($events as $event) {
            $event->uuid = (string) \Illuminate\Support\Str::uuid();
            $event->save();
        }
    }
}
