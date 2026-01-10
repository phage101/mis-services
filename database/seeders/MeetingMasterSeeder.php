<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Models\Host;
use Illuminate\Database\Seeder;

class MeetingMasterSeeder extends Seeder
{
    public function run()
    {
        $platforms = ['Zoom', 'MS Teams', 'Google Meet', 'Webex', 'Face-to-Face'];
        foreach ($platforms as $platform) {
            Platform::firstOrCreate(['name' => $platform]);
        }

        $hosts = [
            ['name' => 'Host 1', 'email' => 'host1@example.com'],
            ['name' => 'Host 2', 'email' => 'host2@example.com'],
            ['name' => 'Admin Host', 'email' => 'admin@example.com'],
        ];
        foreach ($hosts as $host) {
            Host::firstOrCreate(['name' => $host['name']], ['email' => $host['email']]);
        }
    }
}
