@extends('admin.layout')

@section('title', 'Kelola Seluruh Konten Website (CMS)')

@section('content')
    <form action="{{ url('/admin/cms/update') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
        @csrf

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-serif text-3xl font-normal text-brand-dark">Kelola Seluruh Konten & Foto Website (CMS Editor)
                </h2>
                <p class="text-xs text-slate-500 mt-1">Seluruh teks headline, foto aktivitas, foto makanan, kutipan
                    konservasi, dan kontak footer dapat Anda ubah di halaman ini.</p>
            </div>
            <button type="submit"
                class="bg-brand-dark hover:bg-brand-teal text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-lg flex items-center space-x-2">
                <span>💾 SIMPAN SEMUA PERUBAHAN CMS</span>
            </button>
        </div>

        <!-- Section 1: Hero Header & Quotes -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-stone-200 space-y-6">
            <h3
                class="font-serif text-2xl text-brand-dark font-medium border-b border-stone-200 pb-3 flex items-center space-x-2">
                <span>🌄 Section 1 — Hero Banner Header & Teks Utama</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Judul Headline
                        Utama Hero</label>
                    <input type="text" name="hero_title" value="{{ $contents['hero_title'] ?? '' }}" required
                        class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-900 focus:ring-2 focus:ring-brand-gold">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Sub-Judul Hero
                        Header</label>
                    <input type="text" name="hero_subtitle" value="{{ $contents['hero_subtitle'] ?? '' }}"
                        class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Teks Kutipan
                    Filosofi Konservasi (Section 4 Quote)</label>
                <textarea name="conservation_quote" rows="3"
                    class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-light text-slate-800 leading-relaxed">{{ $contents['conservation_quote'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- Section THE RESORT (Section 6) -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-stone-200 space-y-6">
            <h3 class="font-serif text-2xl text-brand-dark font-medium border-b border-stone-200 pb-3 flex items-center space-x-2">
                <span>🏖️ Section — THE RESORT (Header & Sub-Headline)</span>
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Judul Headline THE RESORT ("A village above the water, built to touch it lightly.")
                    </label>
                    <textarea name="resort_title" rows="2" required
                        class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-serif font-bold text-slate-900 leading-snug">{{ $contents['resort_title'] ?? "A village above the water,\nbuilt to touch it lightly." }}</textarea>
                    <p class="text-[10px] text-slate-500 mt-1">* Gunakan enter/baris baru untuk memisahkan kalimat atas & bawah.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi Singkat Boardwalk / Resort
                    </label>
                    <textarea name="resort_description" rows="3"
                        class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-light text-slate-800 leading-relaxed">{{ $contents['resort_description'] ?? 'A kilometre of weathered timber curls across the shallows, past young mangroves planted by our own team. Every villa, table and jetty is reached on foot, above the tide.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: 9 Experience Cards -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-stone-200 space-y-6">
            <h3
                class="font-serif text-2xl text-brand-dark font-medium border-b border-stone-200 pb-3 flex items-center space-x-2">
                <span>🏄 Section 2 — 9 Kartu Aktivitas Experiences ("Days measured in tides")</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($experiences as $exp)
                    <div class="p-5 rounded-xl border border-stone-200 bg-stone-50/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-serif italic font-bold text-brand-gold text-lg">Card
                                {{ $exp->number_code }}</span>
                        </div>

                        <div class="aspect-[4/3] rounded-lg overflow-hidden bg-stone-200 border border-stone-300">
                            <img id="preview-exp-{{ $exp->id }}" src="{{ $exp->image_url }}" alt="{{ $exp->title }}"
                                class="w-full h-full object-cover">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-600 uppercase mb-1">Judul
                                Aktivitas</label>
                            <input type="text" name="experiences[{{ $exp->id }}][title]" value="{{ $exp->title }}"
                                required
                                class="w-full bg-white border border-stone-300 rounded-lg px-3 py-1.5 text-xs font-medium">
                        </div>

                        <div class="space-y-1 bg-white p-2.5 rounded-lg border border-stone-200">
                            <label class="block text-[10px] font-semibold text-slate-600 uppercase">📤 Upload Foto
                                Aktivitas</label>
                            <input type="file" name="experiences[{{ $exp->id }}][image_file]" accept="image/*"
                                onchange="previewImage(this, 'preview-exp-{{ $exp->id }}')"
                                class="block w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-brand-dark file:text-white hover:file:bg-brand-teal cursor-pointer">

                            <details class="text-[10px] text-slate-500 pt-0.5">
                                <summary class="cursor-pointer font-medium">URL eksternal...</summary>
                                <input type="text" name="experiences[{{ $exp->id }}][image_url]"
                                    value="{{ $exp->image_url }}"
                                    class="w-full bg-stone-50 border border-stone-300 rounded px-2 py-1 text-[10px] font-mono text-slate-700 mt-1">
                            </details>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-600 uppercase mb-1">Deskripsi
                                Singkat</label>

                            <textarea name="experiences[{{ $exp->id }}][description]" rows="2"
                                class="w-full bg-white border border-stone-300 rounded-lg px-3 py-1.5 text-xs font-light text-slate-700 leading-tight">{{ $exp->description }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 3: 4 Dining Cards -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-stone-200 space-y-6">
            <h3
                class="font-serif text-2xl text-brand-dark font-medium border-b border-stone-200 pb-3 flex items-center space-x-2">
                <span>🍽️ Section 3 — 4 Kartu Kuliner Dining</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($diningItems as $dining)
                    <div class="p-5 rounded-xl border border-stone-200 bg-stone-50 space-y-3">
                        <div class="aspect-[16/9] rounded-lg overflow-hidden bg-stone-200 border border-stone-300">
                            <img id="preview-dining-{{ $dining->id }}" src="{{ $dining->image_url }}"
                                alt="{{ $dining->title }}" class="w-full h-full object-cover">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-600 uppercase mb-1">Judul Menu /
                                Dining</label>
                            <input type="text" name="dining[{{ $dining->id }}][title]" value="{{ $dining->title }}"
                                required
                                class="w-full bg-white border border-stone-300 rounded-lg px-3 py-1.5 text-xs font-medium">
                        </div>

                        <div class="space-y-1 bg-white p-2.5 rounded-lg border border-stone-200">
                            <label class="block text-[10px] font-semibold text-slate-600 uppercase">📤 Upload Foto
                                Dining</label>
                            <input type="file" name="dining[{{ $dining->id }}][image_file]" accept="image/*"
                                onchange="previewImage(this, 'preview-dining-{{ $dining->id }}')"
                                class="block w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-brand-dark file:text-white hover:file:bg-brand-teal cursor-pointer">

                            <details class="text-[10px] text-slate-500 pt-0.5">
                                <summary class="cursor-pointer font-medium">URL eksternal...</summary>
                                <input type="text" name="dining[{{ $dining->id }}][image_url]"
                                    value="{{ $dining->image_url }}"
                                    class="w-full bg-stone-50 border border-stone-300 rounded px-2 py-1 text-[10px] font-mono text-slate-700 mt-1">
                            </details>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 4: 3 Conservation Cards & Photographer Credit -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-stone-200 space-y-6">
            <h3
                class="font-serif text-2xl text-brand-dark font-medium border-b border-stone-200 pb-3 flex items-center space-x-2">
                <span>🪸 Section 4 — 3 Kartu Reef Conservation & Credit Badge</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($conservationCards as $card)
                    <div class="p-5 rounded-xl border border-stone-200 bg-stone-50 space-y-3">
                        <div class="aspect-[4/3] rounded-lg overflow-hidden bg-stone-200 border border-stone-300">
                            <img id="preview-cons-{{ $card->id }}" src="{{ $card->image_url }}"
                                alt="{{ $card->title }}" class="w-full h-full object-cover">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-600 uppercase mb-1">Judul Kartu
                                Konservasi</label>
                            <input type="text" name="conservation[{{ $card->id }}][title]"
                                value="{{ $card->title }}" required
                                class="w-full bg-white border border-stone-300 rounded-lg px-3 py-1.5 text-xs font-medium">
                        </div>

                        <div class="space-y-1 bg-white p-2.5 rounded-lg border border-stone-200">
                            <label class="block text-[10px] font-semibold text-slate-600 uppercase">📤 Upload Foto
                                Conservation</label>
                            <input type="file" name="conservation[{{ $card->id }}][image_file]" accept="image/*"
                                onchange="previewImage(this, 'preview-cons-{{ $card->id }}')"
                                class="block w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-brand-dark file:text-white hover:file:bg-brand-teal cursor-pointer">

                            <details class="text-[10px] text-slate-500 pt-0.5">
                                <summary class="cursor-pointer font-medium">URL eksternal...</summary>
                                <input type="text" name="conservation[{{ $card->id }}][image_url]"
                                    value="{{ $card->image_url }}"
                                    class="w-full bg-stone-50 border border-stone-300 rounded px-2 py-1 text-[10px] font-mono text-slate-700 mt-1">
                            </details>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-600 uppercase mb-1">Teks Credit Badge
                                Fotografer</label>
                            <input type="text" name="conservation[{{ $card->id }}][photographer_credit]"
                                value="{{ $card->photographer_credit }}" required
                                class="w-full bg-white border border-stone-300 rounded-lg px-3 py-1.5 text-xs font-mono text-brand-gold">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 5: Contact Info & Footer -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-stone-200 space-y-6">
            <h3
                class="font-serif text-2xl text-brand-dark font-medium border-b border-stone-200 pb-3 flex items-center space-x-2">
                <span>📞 Footer — Informasi Kontak & WhatsApp</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor WhatsApp
                        Resort</label>
                    <input type="text" name="footer_whatsapp" value="{{ $contents['footer_whatsapp'] ?? '' }}"
                        class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor Telepon
                        Resort</label>
                    <input type="text" name="footer_phone" value="{{ $contents['footer_phone'] ?? '' }}"
                        class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email
                        Reservasi</label>
                    <input type="email" name="footer_email" value="{{ $contents['footer_email'] ?? '' }}"
                        class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-900">
                </div>
            </div>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit"
                class="bg-brand-dark hover:bg-brand-teal text-white px-10 py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-xl">
                💾 SIMPAN SEMUA PERUBAHAN KONTEN WEBSITE
            </button>
        </div>
    </form>

    <script>
        function previewImage(input, targetId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(targetId);
                    if (img) {
                        img.src = e.target.result;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
