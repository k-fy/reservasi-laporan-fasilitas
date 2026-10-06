@php
    $defaultImages = [
        'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80',
    ];

    $images = !empty($venue->image) ? [asset('storage/' . $venue->image)] : $defaultImages;
    while (count($images) < 5) {
        $images[] = $defaultImages[count($images) % 5];
    }
@endphp

<div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-10">
    <div class="md:col-span-5 h-72 md:h-80 bg-[#814C5B] rounded-3xl overflow-hidden shadow-sm">
        <img src="{{ $images[0] }}" alt="{{ $venue->name ?? 'Venue' }}" class="w-full h-full object-cover">
    </div>

    <div class="md:col-span-5 h-72 md:h-80 bg-[#814C5B] rounded-3xl overflow-hidden shadow-sm">
        <img src="{{ $images[1] }}" alt="{{ $venue->name ?? 'Venue' }}" class="w-full h-full object-cover">
    </div>

    <div class="md:col-span-2 flex flex-col justify-between gap-3 h-72 md:h-80">
        <div class="flex-1 bg-[#814C5B] rounded-2xl overflow-hidden shadow-sm">
            <img src="{{ $images[2] }}" class="w-full h-full object-cover">
        </div>
        <div class="flex-1 bg-[#814C5B] rounded-2xl overflow-hidden shadow-sm">
            <img src="{{ $images[3] }}" class="w-full h-full object-cover">
        </div>
        <div class="flex-1 bg-[#814C5B] rounded-2xl overflow-hidden shadow-sm">
            <img src="{{ $images[4] }}" class="w-full h-full object-cover">
        </div>
    </div>
</div>