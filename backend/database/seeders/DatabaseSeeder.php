<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Villa;
use App\Models\Promotion;
use App\Models\Experience;
use App\Models\DiningItem;
use App\Models\ConservationCard;
use App\Models\SiteContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Admin & Guest User
        User::updateOrCreate(
            ['email' => 'admin@waewatu.com'],
            [
                'name' => 'Wae Watu Admin',
                'email' => 'admin@waewatu.com',
                'password' => Hash::make('adminpassword123'),
                'phone' => '+6281234567890',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'guest@waewatu.com'],
            [
                'name' => 'Guest Wae Watu',
                'email' => 'guest@waewatu.com',
                'password' => Hash::make('guestpassword123'),
                'phone' => '+6289876543210',
            ]
        );

        // 2. Create Initial Villa Record
        Villa::updateOrCreate(
            ['slug' => 'the-reef-villa'],
            [
                'name' => 'The Good Villa',
                'slug' => 'the-reef-villa',
                'status' => 'available',
                'capacity' => 1,
                'price_per_night' => 10000000.00,
                'description' => 'A thatched luxury overwater villa standing on its own legs over the reef, with a private sea deck and a ladder straight down into the water.',
                'features' => ['SEA DECK', 'REEF LADDER', 'OUTDOOR BATH', 'PLUNGE POOL'],
                'image_url' => 'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        // 3. Create Initial Promotion Record
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

        // 4. Create 9 Experiences Cards
        $experiences = [
            ['01', 'Snorkel the house reef', 'Straight off your villa ladder — guided at slack tide, or alone at dawn.', 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=900&q=80', 1],
            ['02', 'Paddleboard at dawn', 'Mirror-flat water before breakfast, turtles surfacing beside you.', 'https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?auto=format&fit=crop&w=900&q=80', 2],
            ['03', 'Kayak the mangroves', 'Paddle green corridors at high tide, where herons fish and the nursery reef begins.', 'https://images.unsplash.com/photo-1544551763-77ef2d0cfc6c?auto=format&fit=crop&w=900&q=80', 3],
            ['04', 'Sunrise yoga', 'On the lagoon deck as the tide turns, before the day chooses its heat.', 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=900&q=80', 4],
            ['05', 'Wellness & spa', 'Sea-salt scrubs and island botanicals in the shore gardens, under old canopy.', 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=900&q=80', 5],
            ['06', 'Ride the sandbar', 'Horseback at low tide along the exposed sand, hooves in an inch of sea.', 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?auto=format&fit=crop&w=900&q=80', 6],
            ['07', 'Sunset sail', 'An evening under sail on our twin-mast outrigger, glass in hand.', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80', 7],
            ['08', 'Dive the drop-off', 'The reef wall falls into blue fifty metres from the last villa.', 'https://images.unsplash.com/photo-1582967788606-a171c1080cb0?auto=format&fit=crop&w=900&q=80', 8],
            ['09', 'Island exploration', 'Hilltop lookouts, hidden coves and fishing villages across the strait.', 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=900&q=80', 9],
        ];

        foreach ($experiences as $exp) {
            Experience::updateOrCreate(
                ['number_code' => $exp[0]],
                [
                    'number_code' => $exp[0],
                    'title' => $exp[1],
                    'description' => $exp[2],
                    'image_url' => $exp[3],
                    'aspect_ratio' => 'aspect-square',
                    'sort_order' => $exp[4],
                ]
            );
        }

        // 5. Create 4 Dining Items
        $dinings = [
            ['A TABLE FOR TWO', 'Sunset dinner table set for two by the ocean', 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=800&q=80', 1],
            ['THE INDONESIAN KITCHEN', 'Authentic Indonesian culinary feast spread', 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=800&q=80', 2],
            ['LOBSTER & CRAB OFF THE COALS', 'Seafood feast on overwater sea deck', 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?auto=format&fit=crop&w=800&q=80', 3],
            ['STEAK OVER THE FIRE', 'Beachside grill fire overlooking sunset ocean', 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80', 4],
        ];

        foreach ($dinings as $d) {
            DiningItem::updateOrCreate(
                ['title' => $d[0]],
                [
                    'title' => $d[0],
                    'description' => $d[1],
                    'image_url' => $d[2],
                    'sort_order' => $d[3]
                ]
            );
        }

        // 6. Create 3 Conservation Cards
        $cards = [
            ['THE CORAL NURSERY', 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=900&q=80', 'Photo by Francesco Ungaro on Unsplash', 1],
            ['THE HOUSE REEF TODAY', 'https://images.unsplash.com/photo-1544551763-77ef2d0cfc6c?auto=format&fit=crop&w=900&q=80', 'Photo by NEOM on Unsplash', 2],
            ['THE RETURNING LIFE', 'https://images.unsplash.com/photo-1546026423-cc4642628d2b?auto=format&fit=crop&w=900&q=80', 'Photo by Hiroko Yoshii on Unsplash', 3],
        ];

        foreach ($cards as $c) {
            ConservationCard::updateOrCreate(
                ['title' => $c[0]],
                [
                    'title' => $c[0],
                    'image_url' => $c[1],
                    'photographer_credit' => $c[2],
                    'sort_order' => $c[3]
                ]
            );
        }

        // 7. Create SiteContent Key-Values
        $contents = [
            'hero_title' => 'Days measured in tides, not hours.',
            'hero_subtitle' => 'WAE WATU / REEF RESORT',
            'hero_badge' => 'BY TERRA ECOSYSTEM',
            'resort_title' => "A village above the water,\nbuilt to touch it lightly.",
            'resort_description' => 'A kilometre of weathered timber curls across the shallows, past young mangroves planted by our own team. Every villa, table and jetty is reached on foot, above the tide.',
            'resort_image_url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1600&q=85',
            'conservation_quote' => '“Luxury should not only preserve nature — it should help restore it. Every guest becomes part of a larger story: protecting our oceans for generations to come.”',
            'footer_phone' => '+62 385 892 104',
            'footer_whatsapp' => '+62 812 3456 7890',
            'footer_email' => 'reservations@waewatu.com',
            'tax_enabled' => '0',
            'tax_percentage' => '10',
            'tax_label' => 'Pajak & Layanan',
        ];

        foreach ($contents as $key => $val) {
            SiteContent::updateOrCreate(
                ['key' => $key],
                ['value' => $val, 'type' => 'text']
            );
        }
    }
}
