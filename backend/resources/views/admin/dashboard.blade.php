@extends('admin.layout')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-stone-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="font-serif text-3xl font-normal text-brand-dark">Selamat Datang di Admin Panel Wae Watu</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola ketersediaan kamar, ubah harga per malam, dan atur banner iklan promo resort Anda di sini.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ url('/admin/villas') }}" class="bg-brand-dark hover:bg-brand-teal text-white px-5 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all shadow-md">
                + Ubah Harga & Kamar
            </a>
        </div>
    </div>

    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200 flex flex-col justify-between">
            <span class="text-[10px] tracking-[0.2em] font-bold text-slate-400 uppercase">Total Kamar Terdaftar</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-serif text-4xl text-brand-dark font-semibold">{{ $villasCount }}</span>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">{{ $availableVillasCount }} Dijual</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200 flex flex-col justify-between">
            <span class="text-[10px] tracking-[0.2em] font-bold text-slate-400 uppercase">Total Reservasi</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-serif text-4xl text-brand-dark font-semibold">{{ $bookingsCount }}</span>
                <span class="text-xs font-medium text-slate-500">Tamu Terdaftar</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200 flex flex-col justify-between">
            <span class="text-[10px] tracking-[0.2em] font-bold text-slate-400 uppercase">Pendapatan Terkonfirmasi</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-serif text-3xl text-brand-gold font-semibold">${{ number_format($totalRevenue, 2) }}</span>
                <span class="text-[10px] text-slate-400 uppercase">Confirmed</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200 flex flex-col justify-between">
            <span class="text-[10px] tracking-[0.2em] font-bold text-slate-400 uppercase">Banner Iklan Aktif</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="font-serif text-4xl text-brand-dark font-semibold">{{ $promotions->where('is_active', true)->count() }}</span>
                <a href="{{ url('/admin/promotions') }}" class="text-xs text-brand-teal font-semibold hover:underline">Kelola Banner →</a>
            </div>
        </div>
    </div>

    <!-- Recent Reservations & Quick Room Status Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Bookings Table -->
        <div class="lg:col-span-8 bg-white rounded-2xl p-6 shadow-sm border border-stone-200 space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-stone-100">
                <h3 class="font-serif text-2xl text-brand-dark font-normal">Reservasi Terbaru</h3>
                <a href="{{ url('/admin/bookings') }}" class="text-xs font-semibold text-brand-teal hover:underline uppercase tracking-wider">Lihat Semua →</a>
            </div>

            @if($recentBookings->isEmpty())
                <p class="text-xs text-slate-400 py-8 text-center">Belum ada reservasi masuk. Tamu yang melakukan pemesanan via website akan tampil di sini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-wider text-slate-400 border-b border-stone-200">
                                <th class="pb-3">Kode Booking</th>
                                <th class="pb-3">Nama Tamu</th>
                                <th class="pb-3">Vila</th>
                                <th class="pb-3">Total Harga</th>
                                <th class="pb-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach($recentBookings as $booking)
                                <tr>
                                    <td class="py-3 font-mono font-bold text-brand-dark">{{ $booking->booking_code }}</td>
                                    <td class="py-3 font-medium">{{ $booking->guest_name }}</td>
                                    <td class="py-3 text-slate-600">{{ $booking->villa->name ?? 'Villa' }}</td>
                                    <td class="py-3 font-semibold text-slate-900">${{ number_format($booking->total_price, 2) }}</td>
                                    <td class="py-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Quick Banners & Promos Panel -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm border border-stone-200 space-y-4">
            <h3 class="font-serif text-2xl text-brand-dark font-normal border-b border-stone-100 pb-3">Status Iklan Highlight</h3>
            @foreach($promotions as $promo)
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-bold tracking-widest text-brand-gold uppercase">{{ $promo->badge_text }}</span>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded {{ $promo->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                            {{ $promo->is_active ? 'Tayang' : 'Draft' }}
                        </span>
                    </div>
                    <p class="font-serif italic text-sm text-brand-dark font-medium leading-tight">"{{ $promo->title }}"</p>
                    <a href="{{ url('/admin/promotions') }}" class="text-[10px] font-semibold text-brand-teal hover:underline inline-block pt-1">Edit Teks & Banner →</a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
