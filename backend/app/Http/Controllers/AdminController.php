<?php

namespace App\Http\Controllers;

use App\Models\Villa;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\Promotion;
use App\Models\SiteContent;
use App\Models\Experience;
use App\Models\DiningItem;
use App\Models\ConservationCard;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Admin Authentication Methods
    public function loginForm()
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            }
        } catch (\Exception $e) {}

        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect('/admin');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                $request->session()->regenerate();
                return redirect('/admin')->with('success', 'Selamat datang kembali, Admin!');
            }
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Akun Anda bukan merupakan akun Admin.');
        }

        return redirect('/admin/login')->with('error', 'Email atau password Admin tidak valid.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Anda telah keluar dari Admin Panel.');
    }

    // Dashboard Overview
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

    // Full CMS Editor for All Website Content
    public function cms()
    {
        $contents = SiteContent::all()->pluck('value', 'key')->toArray();
        $experiences = Experience::orderBy('sort_order', 'asc')->get();
        $diningItems = DiningItem::orderBy('sort_order', 'asc')->get();
        $conservationCards = ConservationCard::orderBy('sort_order', 'asc')->get();
        $galleryPhotos = GalleryPhoto::orderBy('sort_order', 'asc')->get();

        return view('admin.cms', compact(
            'contents',
            'experiences',
            'diningItems',
            'conservationCards',
            'galleryPhotos'
        ));
    }

    private function uploadImage($file, $subfolder)
    {
        $directory = public_path('uploads/' . $subfolder);
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $extension = $file->getClientOriginalExtension();
        $filename = uniqid($subfolder . '_') . '_' . time() . '.' . $extension;
        $file->move($directory, $filename);

        return '/uploads/' . $subfolder . '/' . $filename;
    }

    public function updateCms(Request $request)
    {
        $inputs = $request->except(['_token', 'experiences', 'dining', 'conservation', 'resort_image_file']);

        foreach ($inputs as $key => $value) {
            SiteContent::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'text']
            );
        }

        if ($request->hasFile('resort_image_file')) {
            $file = $request->file('resort_image_file');
            $resortImageUrl = $this->uploadImage($file, 'resort');
            SiteContent::updateOrCreate(
                ['key' => 'resort_image_url'],
                ['value' => $resortImageUrl, 'type' => 'text']
            );
        }

        // Batch Update Experiences
        if ($request->has('experiences')) {
            foreach ($request->input('experiences') as $id => $data) {
                if ($request->hasFile("experiences.{$id}.image_file")) {
                    $file = $request->file("experiences.{$id}.image_file");
                    $data['image_url'] = $this->uploadImage($file, 'experiences');
                }
                unset($data['image_file']);
                Experience::where('id', $id)->update($data);
            }
        }

        // Batch Update Dining
        if ($request->has('dining')) {
            foreach ($request->input('dining') as $id => $data) {
                if ($request->hasFile("dining.{$id}.image_file")) {
                    $file = $request->file("dining.{$id}.image_file");
                    $data['image_url'] = $this->uploadImage($file, 'dining');
                }
                unset($data['image_file']);
                DiningItem::where('id', $id)->update($data);
            }
        }

        // Batch Update Conservation Cards
        if ($request->has('conservation')) {
            foreach ($request->input('conservation') as $id => $data) {
                if ($request->hasFile("conservation.{$id}.image_file")) {
                    $file = $request->file("conservation.{$id}.image_file");
                    $data['image_url'] = $this->uploadImage($file, 'conservation');
                }
                unset($data['image_file']);
                ConservationCard::where('id', $id)->update($data);
            }
        }

        return redirect()->back()->with('success', 'Seluruh konten & foto website berhasil diperbarui!');
    }

    // Room & Pricing Management
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
            'available_units' => 'required|integer|min:0',
            'description' => 'required|string',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_url'] = $this->uploadImage($request->file('image_file'), 'villas');
        }

        unset($validated['image_file']);
        $villa->update($validated);

        return redirect()->back()->with('success', "Updated {$villa->name} details & pricing successfully!");
    }

    // Reservations & Booking Management
    public function bookings()
    {
        $bookings = Booking::with(['villa', 'user'])->latest()->get();
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

    // Advertised Banner CMS
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
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_url'] = $this->uploadImage($request->file('image_file'), 'promotions');
        }

        unset($validated['image_file']);
        $validated['is_active'] = $request->has('is_active');
        $promotion->update($validated);

        return redirect()->back()->with('success', "Advertised banner '{$promotion->title}' updated successfully!");
    }
}
