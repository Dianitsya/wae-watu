<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string',
        ]);

        $inquiry = Inquiry::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Inquiry received. We will get back to you shortly.',
            'data' => $inquiry
        ], 201);
    }
}
