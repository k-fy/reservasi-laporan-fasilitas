{{-- resources/views/reports/reports-page.blade.php --}}
<x-app-layout>
    {{-- ================= ANIMASI HALAMAN REPORT ================= --}}
    <style>
        /* Kartu form muncul perlahan dari bawah */
        .report-card { animation: reportCardIn .6s ease-out both; }
        @keyframes reportCardIn {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Isi form muncul bergantian */
        .report-field { animation: reportFieldIn .5s ease-out both; }
        .report-field:nth-child(1) { animation-delay: .15s; }
        .report-field:nth-child(2) { animation-delay: .22s; }
        .report-field:nth-child(3) { animation-delay: .29s; }
        .report-field:nth-child(4) { animation-delay: .36s; }
        .report-field:nth-child(5) { animation-delay: .43s; }
        .report-field:nth-child(6) { animation-delay: .50s; }
        .report-field:nth-child(7) { animation-delay: .57s; }
        .report-field:nth-child(8) { animation-delay: .64s; }
        @keyframes reportFieldIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Kolom input menyala pink saat diklik */
        .report-input { transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease; }
        .report-input:focus { border-color: #fda4af; background-color: #fff; box-shadow: 0 0 0 4px rgba(253, 164, 175, .35); }

        /* Area upload bereaksi saat foto diseret ke atasnya */
        .report-drop { transition: background-color .2s ease, border-color .2s ease, transform .2s ease; }
        .report-drop.is-dragging { background-color: #ffe4e6; border-color: #fb7185; border-style: dashed; transform: scale(1.01); }

        /* Pratinjau foto & pesan sukses muncul halus */
        .report-pop { animation: reportPop .35s ease-out both; }
        @keyframes reportPop {
            from { opacity: 0; transform: scale(.96); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* Tombol sedikit terangkat saat disorot */
        .report-submit { transition: background-color .2s ease, transform .2s ease, box-shadow .2s ease; }
        .report-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0, 0, 0, .15); }

        /* Matikan animasi jika perangkat meminta "kurangi gerakan" atau preferensi Personalization = nonaktif */
        @media (prefers-reduced-motion: reduce) {
            .report-card, .report-field, .report-pop { animation: none; }
            .report-submit:hover:not(:disabled), .report-drop.is-dragging { transform: none; }
        }
    </style>

    <div class="min-h-screen flex items-center justify-center bg-neutral-700 py-10 px-4">
        <div class="report-card w-full max-w-xl bg-rose-50 rounded-3xl shadow-xl p-8"
             x-data="{ category: '{{ old('category', '') }}' }">

            <h1 class="text-center text-4xl font-serif italic text-neutral-700 mb-2">
                Report Form
            </h1>
            <div class="border-b border-neutral-400 mb-6"></div>

            @if (session('success'))
                <div class="report-pop mb-5 flex items-center gap-2 text-sm text-green-700 bg-green-100 border border-green-200 rounded-xl px-4 py-3"
                     x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms>
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button type="button" @click="show = false" class="text-green-700/70 hover:text-green-800" aria-label="Tutup">&times;</button>
                </div>
            @endif

            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data"
                  @submit="submitting = true"
                  x-data="{
                      wordCount: 0,
                      fileName: '',
                      previewUrl: '',
                      photoError: '',
                      dragging: false,
                      submitting: false,
                      updateCount(text) {
                          this.wordCount = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
                      },
                      // Validasi foto di browser + tampilkan pratinjau
                      setPhoto(file) {
                          this.photoError = '';
                          if (!file) return;
                          if (!file.type.startsWith('image/')) {
                              this.photoError = 'File harus berupa gambar.';
                              this.clearPhoto();
                              return;
                          }
                          if (file.size > 2 * 1024 * 1024) {
                              this.photoError = 'Ukuran foto maksimal 2 MB.';
                              this.clearPhoto();
                              return;
                          }
                          this.fileName = file.name;
                          this.previewUrl = URL.createObjectURL(file);
                      },
                      clearPhoto() {
                          this.fileName = '';
                          this.previewUrl = '';
                          this.$refs.photo.value = '';
                      },
                      // Foto diseret lalu dilepas ke area upload
                      dropPhoto(event) {
                          this.dragging = false;
                          const files = event.dataTransfer.files;
                          if (!files.length) return;
                          this.$refs.photo.files = files;
                          this.setPhoto(files[0]);
                      }
                  }"
                  x-init="updateCount($refs.description.value)">
                @csrf

                {{-- Filling as --}}
                <div class="report-field mb-5">
                    <span class="font-semibold text-neutral-700">Filling as</span>
                    <span class="text-rose-400 underline decoration-rose-300">
                        {{ auth()->user()->name }}
                    </span>
                </div>

                {{-- Category --}}
                <div class="report-field mb-5">
                    <label for="category" class="block font-semibold text-neutral-700 mb-1">
                        Category<span class="text-rose-400">*</span>
                    </label>
                    <select name="category" id="category" x-model="category" required
                        class="report-input w-full rounded-xl border border-neutral-400 bg-rose-50 px-4 py-2.5
                               text-neutral-700 focus:outline-none">
                        <option value="" disabled>Pilih kategori</option>
                        <option value="lokasi">Lokasi</option>
                        <option value="peralatan">Peralatan</option>
                    </select>
                    @error('category')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location (muncul kalau category = lokasi) --}}
                <div class="report-field mb-5" x-show="category === 'lokasi'" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <label for="facility_location" class="block font-semibold text-neutral-700 mb-1">
                        Location<span class="text-rose-400">*</span>
                    </label>
                    <select name="facility_id" id="facility_location"
                        :disabled="category !== 'lokasi'"
                        :required="category === 'lokasi'"
                        class="report-input w-full rounded-xl border border-neutral-400 bg-rose-50 px-4 py-2.5
                               text-neutral-700 focus:outline-none">
                        <option value="" disabled selected>Pilih lokasi</option>
                        @foreach ($locations as $facility)
                            <option value="{{ $facility->id }}"
                                {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Equipment (muncul kalau category = peralatan) --}}
                <div class="report-field mb-5" x-show="category === 'peralatan'" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <label for="facility_equipment" class="block font-semibold text-neutral-700 mb-1">
                        Equipment<span class="text-rose-400">*</span>
                    </label>
                    <select name="facility_id" id="facility_equipment"
                        :disabled="category !== 'peralatan'"
                        :required="category === 'peralatan'"
                        class="report-input w-full rounded-xl border border-neutral-400 bg-rose-50 px-4 py-2.5
                               text-neutral-700 focus:outline-none">
                        <option value="" disabled selected>Pilih peralatan</option>
                        @foreach ($equipments as $facility)
                            <option value="{{ $facility->id }}"
                                {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @error('facility_id')
                    <p class="text-sm text-red-500 -mt-4 mb-4">{{ $message }}</p>
                @enderror

                {{-- Description --}}
                <div class="report-field">
                    <label for="description" class="block font-semibold text-neutral-700 mb-1">
                        Description<span class="text-rose-400">*</span>
                    </label>
                    <textarea name="description" id="description" rows="6" required x-ref="description"
                        x-on:input="updateCount($event.target.value)"
                        class="report-input w-full rounded-xl border border-neutral-400 bg-rose-50 px-4 py-3
                               text-neutral-700 focus:outline-none resize-none">{{ old('description') }}</textarea>
                    <div class="text-right text-sm mb-5 mt-1 transition-colors"
                         :class="wordCount > 500 ? 'text-red-500 font-semibold' : 'text-neutral-500'">
                        <span x-text="wordCount"></span>/500 words
                    </div>
                    @error('description')
                        <p class="text-sm text-red-500 -mt-4 mb-4">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Photo --}}
                <div class="report-field mb-6">
                    <label class="block font-semibold text-neutral-700 mb-1">Upload a Photo</label>

                    {{-- Area upload (klik atau seret foto) --}}
                    <label for="photo" x-show="!previewUrl"
                        class="report-drop flex flex-col items-center justify-center gap-2 rounded-xl border border-neutral-400
                               bg-rose-50 py-10 cursor-pointer hover:bg-rose-100"
                        :class="{ 'is-dragging': dragging }"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dropPhoto($event)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-neutral-500 transition-transform duration-200"
                             :class="{ '-translate-y-1': dragging }" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 7.5m0 0L7.5 12m4.5-4.5v13.5" />
                        </svg>
                        <span class="text-sm text-neutral-500" x-text="dragging ? 'Lepaskan foto di sini' : 'Klik atau seret foto ke sini'"></span>
                        <span class="text-xs text-neutral-400">JPG atau PNG, maksimal 2 MB</span>
                    </label>

                    <input type="file" name="photo" id="photo" accept="image/*" class="hidden" x-ref="photo"
                           x-on:change="setPhoto($event.target.files[0])">

                    {{-- Pratinjau foto --}}
                    <template x-if="previewUrl">
                        <div class="report-pop relative rounded-xl overflow-hidden border border-neutral-300 bg-white">
                            <img :src="previewUrl" alt="Pratinjau foto" class="w-full max-h-64 object-cover">
                            <div class="flex items-center justify-between gap-3 px-4 py-2.5 text-sm">
                                <span class="truncate text-neutral-600" x-text="fileName"></span>
                                <button type="button" @click="clearPhoto()"
                                        class="shrink-0 text-rose-500 font-semibold hover:underline">Hapus</button>
                            </div>
                        </div>
                    </template>

                    <p class="text-sm text-red-500 mt-1" x-show="photoError" x-text="photoError"></p>
                    @error('photo')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="report-field">
                    <button type="submit" :disabled="submitting"
                        class="report-submit w-full rounded-full bg-rose-200 hover:bg-rose-300
                               text-neutral-700 font-semibold py-3 disabled:opacity-70 disabled:cursor-wait
                               inline-flex items-center justify-center gap-2">
                        <svg x-show="submitting" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span x-text="submitting ? 'Mengirim...' : 'Fill Report'">Fill Report</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>