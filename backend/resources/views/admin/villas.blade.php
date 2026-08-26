@extends('admin.layout')

@section('title', 'Kelola Kamar & Harga')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="font-serif text-3xl font-normal text-brand-dark">Kelola Kamar Vila & Harga Per Malam</h2>
            <p class="text-xs text-slate-500 mt-1">Ubah tarif harga kamar, status penjualan (Available / Sold Out / Maintenance), dan foto deskripsi vila di bawah ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        @foreach($villas as $villa)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Villa Image Preview -->
                <div class="lg:col-span-4 space-y-3">
                    <div class="aspect-[4/3] rounded-xl overflow-hidden bg-stone-200 shadow-sm border border-stone-300">
                        <img src="{{ $villa->image_url }}" alt="{{ $villa->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-medium text-slate-500">Status Penjualan:</span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $villa->status === 'available' ? 'bg-emerald-100 text-emerald-800' : ($villa->status === 'sold_out' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $villa->status === 'available' ? 'Dijual (Available)' : ($villa->status === 'sold_out' ? 'Habis (Sold Out)' : 'Perbaikan (Maintenance)') }}
                        </span>
                    </div>
                </div>

                <!-- Update Form -->
                <div class="lg:col-span-8">
                    <form action="{{ url('/admin/villas/' . $villa->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Kamar Vila</label>
                                <input type="text" name="name" value="{{ old('name', $villa->name) }}" required
                                    class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-brand-gold focus:border-brand-gold">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Harga Per Malam ($ USD)</label>
                                <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night', $villa->price_per_night) }}" required
                                    class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-bold text-brand-dark focus:ring-2 focus:ring-brand-gold">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Status Ketersediaan Kamar</label>
                                <select name="status" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 focus:ring-2 focus:ring-brand-gold">
                                    <option value="available" {{ $villa->status === 'available' ? 'selected' : '' }}>🟢 Dijual (Available)</option>
                                    <option value="sold_out" {{ $villa->status === 'sold_out' ? 'selected' : '' }}>🔴 Habis (Sold Out)</option>
                                    <option value="maintenance" {{ $villa->status === 'maintenance' ? 'selected' : '' }}>🟡 Perbaikan (Maintenance)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kapasitas Maksimal Tamu</label>
                                <input type="number" name="capacity" value="{{ old('capacity', $villa->capacity) }}" required
                                    class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Tautan Foto Kamar (URL Foto HD)</label>
                            <input type="text" name="image_url" value="{{ old('image_url', $villa->image_url) }}"
                                class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-mono text-slate-700">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Kamar Vila</label>
                            <textarea name="description" rows="3" required
                                class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-light text-slate-800 leading-relaxed">{{ old('description', $villa->description) }}</textarea>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="bg-brand-dark hover:bg-brand-teal text-white px-6 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all shadow-md">
                                💾 Simpan Perubahan Kamar & Harga
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
