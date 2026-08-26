@extends('admin.layout')

@section('title', 'Kelola Banner Iklan & Promo')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="font-serif text-3xl font-normal text-brand-dark">Kelola Tampilan Iklan Highlight & Banner Promo</h2>
            <p class="text-xs text-slate-500 mt-1">Ubah judul headline promo, deskripsi singkat, badge teks, dan foto banner utama yang diiklankan di website.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        @foreach($promotions as $promo)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Promo Image Preview -->
                <div class="lg:col-span-5 space-y-3">
                    <div class="aspect-[16/9] rounded-xl overflow-hidden bg-stone-200 shadow-sm border border-stone-300">
                        <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-medium text-slate-500">Status Tayang Iklan:</span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $promo->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                            {{ $promo->is_active ? 'Aktif Tayang' : 'Non-Aktif (Draft)' }}
                        </span>
                    </div>
                </div>

                <!-- Update Promo Form -->
                <div class="lg:col-span-7">
                    <form action="{{ url('/admin/promotions/' . $promo->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Judul Headline Utama (Promotional Title)</label>
                            <input type="text" name="title" value="{{ old('title', $promo->title) }}" required
                                class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-brand-gold">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Sub-Deskripsi Iklan Promo</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $promo->subtitle) }}"
                                class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-light text-slate-800">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Teks Badge Emblem (e.g. BY TERRA ECOSYSTEM)</label>
                                <input type="text" name="badge_text" value="{{ old('badge_text', $promo->badge_text) }}"
                                    class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-mono uppercase text-brand-gold">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Tautan Foto Banner HD (URL Image)</label>
                                <input type="text" name="image_url" value="{{ old('image_url', $promo->image_url) }}"
                                    class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs font-mono text-slate-700">
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 pt-2">
                            <input type="checkbox" id="is_active_{{ $promo->id }}" name="is_active" value="1" {{ $promo->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-dark focus:ring-brand-gold">
                            <label for="is_active_{{ $promo->id }}" class="text-xs font-semibold text-slate-700 cursor-pointer">Tayangkan banner iklan ini secara live di website</label>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="bg-brand-dark hover:bg-brand-teal text-white px-6 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all shadow-md">
                                💾 Simpan Banner Iklan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
