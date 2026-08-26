<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;
use App\Models\Experience;
use App\Models\DiningItem;
use App\Models\ConservationCard;
use App\Models\GalleryPhoto;
use App\Models\Villa;
use App\Models\Promotion;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('site_contents')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            }
        } catch (\Exception $e) {}

        $siteContentKeys = SiteContent::all()->pluck('value', 'key')->toArray();
        $experiences = Experience::orderBy('sort_order', 'asc')->get();
        $dinings = DiningItem::orderBy('sort_order', 'asc')->get();
        $conservationCards = ConservationCard::orderBy('sort_order', 'asc')->get();
        $galleryPhotos = GalleryPhoto::orderBy('sort_order', 'asc')->get();
        $villas = Villa::all();
        $promotions = Promotion::where('is_active', true)->get();

        return response()->json([
            'status' => 'success',
            'contents' => $siteContentKeys,
            'experiences' => $experiences,
            'dining_items' => $dinings,
            'conservation_cards' => $conservationCards,
            'gallery_photos' => $galleryPhotos,
            'villas' => $villas,
            'promotions' => $promotions,
        ]);
    }
}
