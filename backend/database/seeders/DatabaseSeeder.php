<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Villa;
use App\Models\Promotion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Villa::updateOrCreate(
            ['slug' => 'the-reef-villa'],
            [
                'name' => 'The Reef Villa',
                'slug' => 'the-reef-villa',
                'status' => 'available',
                'capacity' => 2,
                'price_per_night' => 1450.00,
                'description' => 'A thatched luxury overwater villa standing on its own legs over the reef, with a private sea deck and a ladder straight down into the water.',
                'features' => ['SEA DECK', 'REEF LADDER', 'OUTDOOR BATH', 'PLUNGE POOL'],
                'image_url' => 'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        Promotion::updateOrCreate(
            ['title' => 'Days measured in tides, not hours.'],
            [
                'title' => 'Days measured in tides, not hours.',
                'subtitle' => 'Special Early Bird Ocean Villa Offer — Save 20% on 5-Night Stays',
                'badge_text' => 'BY TERRA ECOSYSTEM',
                'image_url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1600&q=85',
                'is_active' => true
            ]
        );
    }
}
