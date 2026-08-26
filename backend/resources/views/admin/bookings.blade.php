@extends('admin.layout')

@section('title', 'Kelola Reservasi Tamu')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="font-serif text-3xl font-normal text-brand-dark">Daftar Reservasi & Pemesanan Tamu</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar lengkap reservasi kamar yang dipesan oleh tamu melalui website Wae Watu Reef Resort.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200">
        @if($bookings->isEmpty())
            <div class="py-12 text-center text-slate-400 space-y-2">
                <p class="text-2xl">📅</p>
                <p class="text-xs">Belum ada reservasi kamar masuk dari tamu.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-wider text-slate-400 border-b border-stone-200">
                            <th class="pb-3 px-2">Kode Booking</th>
                            <th class="pb-3 px-2">Nama Tamu & Kontak</th>
                            <th class="pb-3 px-2">Kamar Vila</th>
                            <th class="pb-3 px-2">Tanggal Stay</th>
                            <th class="pb-3 px-2">Total Harga</th>
                            <th class="pb-3 px-2 text-center">Status Pemesanan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($bookings as $booking)
                            <tr>
                                <td class="py-4 px-2 font-mono font-bold text-brand-dark text-sm">{{ $booking->booking_code }}</td>
                                <td class="py-4 px-2">
                                    <p class="font-semibold text-slate-900">{{ $booking->guest_name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $booking->guest_email }}</p>
                                    <p class="text-[10px] text-brand-teal font-mono">{{ $booking->guest_phone }}</p>
                                </td>
                                <td class="py-4 px-2 font-medium text-slate-800">{{ $booking->villa->name ?? 'Villa' }}</td>
                                <td class="py-4 px-2 text-slate-600">
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }} — {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                                    <span class="block text-[10px] text-slate-400">({{ $booking->guests }} Tamu)</span>
                                </td>
                                <td class="py-4 px-2 font-bold text-slate-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td class="py-4 px-2 text-center">
                                    <form action="{{ url('/admin/bookings/' . $booking->id . '/status') }}" method="POST">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()"
                                            class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border cursor-pointer
                                            {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-rose-100 text-rose-800 border-rose-300') }}">
                                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
