{{-- resources/views/reports/reports-page.blade.php --}}
<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-neutral-700 py-10">
        <div class="w-full max-w-xl bg-rose-50 rounded-3xl shadow-xl p-8">

            <h1 class="text-center text-4xl font-serif italic text-neutral-700 mb-2">
                Report Form
            </h1>
            <div class="border-b border-neutral-400 mb-6"></div>

            @if (session('success'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 rounded-lg px-4 py-2">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data"
                  x-data="{
                      wordCount: 0,
                      fileName: '',
                      updateCount(text) {
                          this.wordCount = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
                      }
                  }">
                @csrf

                {{-- Filling as --}}
                <div class="mb-5">
                    <span class="font-semibold text-neutral-700">Filling as</span>
                    <span class="text-rose-400 underline decoration-rose-300">
                        {{ auth()->user()->name }}
                    </span>
                </div>

                {{-- Location --}}
                <div class="mb-5">
                    <label for="facility_id" class="block font-semibold text-neutral-700 mb-1">
                        Location<span class="text-rose-400">*</span>
                    </label>
                    <select name="facility_id" id="facility_id" required
                        class="w-full rounded-xl border border-neutral-400 bg-rose-50 px-4 py-2.5
                               text-neutral-700 focus:outline-none focus:ring-2 focus:ring-rose-300">
                        <option value="" disabled selected>Pilih lokasi</option>
                        @foreach ($facilities as $facility)
                            <option value="{{ $facility->id }}"
                                {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('facility_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Feedback --}}
                <div class="mb-1">
                    <label for="description" class="block font-semibold text-neutral-700 mb-1">
                        Feedback<span class="text-rose-400">*</span>
                    </label>
                    <textarea name="description" id="description" rows="6" required
                        x-on:input="updateCount($event.target.value)"
                        class="w-full rounded-xl border border-neutral-400 bg-rose-50 px-4 py-3
                               text-neutral-700 focus:outline-none focus:ring-2 focus:ring-rose-300 resize-none">{{ old('description') }}</textarea>
                </div>
                <div class="text-right text-sm text-neutral-500 mb-5">
                    <span x-text="wordCount"></span>/500 words
                </div>
                @error('description')
                    <p class="text-sm text-red-500 -mt-4 mb-4">{{ $message }}</p>
                @enderror

                {{-- Upload Photo --}}
                <div class="mb-6">
                    <label class="block font-semibold text-neutral-700 mb-1">Upload a Photo</label>
                    <label for="photo"
                        class="flex flex-col items-center justify-center gap-2 rounded-xl border border-neutral-400
                               bg-rose-50 py-10 cursor-pointer hover:bg-rose-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-neutral-500" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 7.5m0 0L7.5 12m4.5-4.5v13.5" />
                        </svg>
                        <span class="text-sm text-neutral-500" x-text="fileName || 'Klik atau seret foto ke sini'"></span>
                        <input type="file" name="photo" id="photo" accept="image/*" class="hidden"
                               x-on:change="fileName = $event.target.files[0]?.name">
                    </label>
                    @error('photo')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full rounded-full bg-rose-200 hover:bg-rose-300 transition
                           text-neutral-700 font-semibold py-3">
                    Fill Report
                </button>
            </form>
        </div>
    </div>
</x-app-layout>