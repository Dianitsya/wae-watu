<?php

namespace App\Http\Controllers;

use App\Models\Villa;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\Promotion;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $villasCount = Villa::count();
        $availableVillasCount = Villa::where('status', 'available')->count();
        $bookingsCount = Booking::count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $recentBookings = Booking::with('villa')->latest()->take(5)->get();
        $promotions = Promotion::all();

        return view('admin.dashboard', compact(
            'villasCount',
            'availableVillasCount',
            'bookingsCount',
            'totalRevenue',
            'recentBookings',
            'promotions'
        ));
    }

    public function villas()
    {
        $villas = Villa::all();
        return view('admin.villas', compact('villas'));
    }

    public function updateVilla(Request $request, $id)
    {
        $villa = Villa::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold_out,maintenance',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'image_url' => 'nullable|string',
        ]);

        $villa->update($validated);

        return redirect()->back()->with('success', "Updated {$villa->name} details & pricing successfully!");
    }

    public function bookings()
    {
        $bookings = Booking::with('villa')->latest()->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update($validated);

        return redirect()->back()->with('success', "Booking {$booking->booking_code} status updated to {$booking->status}!");
    }

    public function promotions()
    {
        $promotions = Promotion::all();
        return view('admin.promotions', compact('promotions'));
    }

    public function updatePromotion(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'image_url' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $promotion->update($validated);

        return redirect()->back()->with('success', "Advertised banner '{$promotion->title}' updated successfully!");
    }
}
