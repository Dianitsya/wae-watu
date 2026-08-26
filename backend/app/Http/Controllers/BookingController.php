<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Villa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $overlapping = Booking::where('villa_id', $validated['villa_id'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('check_in', [$validated['check_in'], $validated['check_out']])
                      ->orWhereBetween('check_out', [$validated['check_in'], $validated['check_out']])
                      ->orWhere(function ($q) use ($validated) {
                          $q->where('check_in', '<=', $validated['check_in'])
                            ->where('check_out', '>=', $validated['check_out']);
                      });
            })->exists();

        return response()->json([
            'available' => !$overlapping,
            'message' => $overlapping ? 'Villa is already reserved for these dates.' : 'Villa is available!'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:50',
            'special_notes' => 'nullable|string',
            'user_id' => 'nullable|integer',
        ]);

        $villa = Villa::findOrFail($validated['villa_id']);

        // Calculate nights and price
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = max(1, $checkIn->diffInDays($checkOut));
        $totalPrice = $nights * $villa->price_per_night;

        // Generate unique booking code e.g. WW-2026-8942
        $bookingCode = 'WW-' . date('Y') . '-' . strtoupper(Str::random(4));

        $userId = $request->input('user_id') ?: (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : null);
        $cleanEmail = strtolower(trim($validated['guest_email']));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $userId,
            'villa_id' => $villa->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guests' => $validated['guests'],
            'total_price' => $totalPrice,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $cleanEmail,
            'guest_phone' => $validated['guest_phone'],
            'special_notes' => $validated['special_notes'] ?? null,
            'status' => 'pending',
        ]);

        $bookingData = $booking->load('villa');
        $snapData = $this->createMidtransSnapToken($bookingData);

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation created successfully!',
            'data' => $bookingData,
            'snap_token' => $snapData['token'] ?? null,
            'snap_url' => $snapData['redirect_url'] ?? null,
            'client_key' => $this->getMidtransClientKey(),
        ], 201);
    }

    public function show($code)
    {
        $booking = Booking::with('villa')->where('booking_code', $code)->firstOrFail();
        return response()->json([
            'status' => 'success',
            'data' => $booking
        ]);
    }

    public function midtransConfig()
    {
        return response()->json([
            'status' => 'success',
            'client_key' => $this->getMidtransClientKey(),
            'is_production' => $this->isMidtransProduction(),
            'snap_js_url' => $this->isMidtransProduction()
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js',
        ]);
    }

    public function midtransNotification(Request $request)
    {
        $serverKey = $this->getMidtransServerKey();

        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        if ($orderId && $statusCode && $grossAmount && $signatureKey) {
            $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            if ($signatureKey !== $mySignature) {
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
            }
        }

        $booking = Booking::where('booking_code', $orderId)->first();
        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
        }

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $booking->update(['status' => 'pending']);
            } else if ($fraudStatus == 'accept') {
                $booking->update(['status' => 'confirmed']);
            }
        } else if ($transactionStatus == 'settlement') {
            $booking->update(['status' => 'confirmed']);
        } else if ($transactionStatus == 'pending') {
            $booking->update(['status' => 'pending']);
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $booking->update(['status' => 'cancelled']);
        }

        return response()->json(['status' => 'success', 'message' => 'Notification processed successfully']);
    }

    private function getMidtransServerKey()
    {
        $cmsKey = \App\Models\SiteContent::where('key', 'midtrans_server_key')->value('value');
        return !empty($cmsKey) ? $cmsKey : config('midtrans.server_key');
    }

    private function getMidtransClientKey()
    {
        $cmsKey = \App\Models\SiteContent::where('key', 'midtrans_client_key')->value('value');
        return !empty($cmsKey) ? $cmsKey : config('midtrans.client_key');
    }

    private function isMidtransProduction()
    {
        $cmsVal = \App\Models\SiteContent::where('key', 'midtrans_is_production')->value('value');
        if ($cmsVal !== null) {
            return $cmsVal == '1';
        }
        return config('midtrans.is_production', false);
    }

    private function createMidtransSnapToken($booking)
    {
        $serverKey = $this->getMidtransServerKey();
        $isProduction = $this->isMidtransProduction();

        $snapApiUrl = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $payload = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) round($booking->total_price),
            ],
            'customer_details' => [
                'first_name' => $booking->guest_name,
                'email' => $booking->guest_email,
                'phone' => $booking->guest_phone,
            ],
            'item_details' => [
                [
                    'id' => 'VILLA-' . $booking->villa_id,
                    'price' => (int) round($booking->total_price),
                    'quantity' => 1,
                    'name' => 'Reservasi ' . ($booking->villa ? $booking->villa->name : 'Wae Watu Villa'),
                ]
            ],
            'credit_card' => [
                'secure' => true,
            ]
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
            ])->post($snapApiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'token' => $data['token'] ?? null,
                    'redirect_url' => $data['redirect_url'] ?? null,
                    'error' => null,
                ];
            } else {
                \Illuminate\Support\Facades\Log::error('Midtrans Snap Error: ' . $response->body());
                return [
                    'token' => null,
                    'redirect_url' => null,
                    'error' => $response->json()['error_messages'][0] ?? $response->body(),
                ];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return [
                'token' => null,
                'redirect_url' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
}
