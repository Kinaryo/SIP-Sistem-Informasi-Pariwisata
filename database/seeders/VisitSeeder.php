<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Str;

class VisitSeeder extends Seeder
{
    public function run(): void
    {
        $paths = [
            '/',
        ];

        $methods = ['GET'];

        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',
            'Mozilla/5.0 (Linux; Android 13)',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X)',
        ];

        $referers = [
            'https://google.com',
            'https://facebook.com',
            'https://instagram.com',
            null,
        ];

        for ($i = 0; $i < 500; $i++) {

            $date = Carbon::now()
                ->subDays(rand(0, 30))
                ->subMinutes(rand(0, 1440));

            Visit::create([
                'user_id' => rand(1, 10),
                'session_id' => Str::uuid(),
                'ip_address' => '192.168.' . rand(0, 255) . '.' . rand(0, 255),
                'user_agent' => $userAgents[array_rand($userAgents)],
                'path' => $paths[array_rand($paths)],
                'method' => $methods[array_rand($methods)],
                'referer' => $referers[array_rand($referers)],
                'visited_at' => $date,
            ]);
        }
    }
}