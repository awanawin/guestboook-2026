<?php

namespace database\seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        Theme::create([
            'name' => 'Royal Indigo',
            'slug' => 'royal-indigo',
            'primary_color' => '#4f46e5',
            'background_gradient' => 'linear-gradient(to bottom right, #1e1b4b, #0f172a)'
        ]);

        Theme::create([
            'name' => 'Emerald Luxury',
            'slug' => 'emerald-luxury',
            'primary_color' => '#059669',
            'background_gradient' => 'linear-gradient(to bottom right, #064e3b, #022c22)'
        ]);

        Theme::create([
            'name' => 'Rose Gold Valentine',
            'slug' => 'rose-gold-valentine',
            'primary_color' => '#db2777',
            'background_gradient' => 'linear-gradient(to bottom right, #500724, #0f172a)'
        ]);
    }
}
