@php
    $venueId = is_object($venue) ? $venue->id : ($venue['id'] ?? null);
    
    $timeSlots = [];
    for ($h = 7; $h <= 20; $h++) {
        $timeSlots[] = sprintf('%02d:00', $h);
        if ($h < 20) {
            $timeSlots[] = sprintf('%02d:30', $h);
        }
    }
@endphp

<style>
    #booking_date::-webkit-calendar-picker-indicator {
        display: none !important;
        -webkit-appearance: none !important;
    }
</style>

<div id="scheduler" class="bg-[#FCF1F0] text-neutral-800 rounded-[32px] p-6 md:p-8 shadow-sm border border-[#EDD3D6]/50">
    
    <!-- Header Controls -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <label for="booking_date" onclick="document.getElementById('booking_date').showPicker()">
            <div class="flex items-center bg-white text-[#814C5B] rounded-xl overflow-hidden shadow-sm border border-[#EDD3D6]">
                <div class="px-3 py-2 bg.fcf1f0 bg-[#FCF1F0] text-[#814C5B] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                    </svg>
                </div>
                <input type="date" id="booking_date" min="{{ now()->toDateString() }}" value="{{ request('date', now()->toDateString()) }}"
                    class="bg-white border-0 text-[#814C5B] font-semibold text-sm px-3 focus:ring-0 cursor-pointer">
            </div>
        </div>

        <div id="scheduler_summary" class="text-sm font-semibold text-[#814C5B]">
            Pick a date to see available time slots.
        </div>
    </div>

    <!-- Timeline -->
    <div class="relative bg-[#FFF9F9] border border-[#EDD3D6] rounded-2xl p-4 overflow-x-auto select-none 
                [scrollbar-width:thin] [scrollbar-color:#814C5B_#FCF1F0]
                [&::-webkit-scrollbar]:h-1.5 
                [&::-webkit-scrollbar-track]:bg-[#FCF1F0] [&::-webkit-scrollbar-track]:rounded-full
                [&::-webkit-scrollbar-thumb]:bg-[#814C5B]/40 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-[#814C5B]">
        
        <div class="min-w-[1200px]">
            
            <!-- Area Grid Interactive Slots -->
            <div id="time_slots_grid" class="flex py-2 min-h-[200px]">
                @foreach($timeSlots as $time)
                    <div data-time="{{ $time }}" 
                        class="slot-btn flex-1 min-w-[70px] border-r border-dashed border-[#EDD3D6] last:border-r-0 h-44 rounded-xl transition-all cursor-pointer hover:bg-[#814C5B]/20 flex flex-col justify-between p-2 m-0.5">
                        <span class="text-xs font-semibold text-neutral-500 font-mono">{{ $time }}</span>
                        <div class="slot-status-indicator text-xs text-center font-bold"></div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- Form Submit -->
    <form method="POST" action="{{ route('booking.store') }}" id="scheduler_form" class="mt-6 flex flex-wrap justify-between items-center gap-4">
        @csrf
        <input type="hidden" name="facility_id" value="{{ $venueId }}">
        <input type="hidden" name="reservation_date" id="input_date" value="{{ request('date', now()->toDateString()) }}">
        <input type="hidden" name="start_time" id="input_start_time">
        <input type="hidden" name="end_time" id="input_end_time">

        <div class="text-xs text-neutral-500">
            * Klik slot awal dan slot akhir untuk menentukan durasi sewa.
        </div>

        <a id="reserve-btn" href="#"
                class="bg-[#814C5B] hover:bg-[#6b3e4b] text-white px-8 py-3 rounded-full font-bold text-sm shadow-md transition disabled:pointer-events-none disabled:opacity-40 disabled:cursor-not-allowed">
            Request a Booking
        </a>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookedSlots = JSON.parse('@json($bookedSlots ?? [])');
    const dateInput = document.getElementById('booking_date');
    const inputDate = document.getElementById('input_date');
    const inputStart = document.getElementById('input_start_time');
    const inputEnd = document.getElementById('input_end_time');
    const reserveBtn = document.getElementById('reserve-btn');
    const summaryText = document.getElementById('scheduler_summary');
    const slotBtns = document.querySelectorAll('.slot-btn');

    let selectedStart = null;
    let selectedEnd = null;

    function renderBookedSlots() {
        slotBtns.forEach(slot => {
            const slotTime = slot.dataset.time;
            let isBooked = false;

            bookedSlots.forEach(b => {
                if (slotTime >= b.start_time && slotTime < b.end_time) {
                    isBooked = true;
                }
            });

            if (isBooked) {
                slot.classList.add('bg-[#814C5B]', 'text-white', 'pointer-events-none', 'opacity-80');
                slot.classList.remove('hover:bg-[#814C5B]/20', 'cursor-pointer');
                slot.querySelector('.slot-status-indicator').innerText = 'Booked';
            }
        });
    }

    renderBookedSlots();

    slotBtns.forEach(slot => {
        slot.addEventListener('click', function () {
            if (this.classList.contains('pointer-events-none')) return;

            const time = this.dataset.time;

            if (!selectedStart || (selectedStart && selectedEnd)) {
                selectedStart = time;
                selectedEnd = null;
            } else if (selectedStart && !selectedEnd) {
                if (time > selectedStart) {
                    selectedEnd = time;
                } else {
                    selectedStart = time;
                    selectedEnd = null;
                }
            }

            updateSelectionUI();
        });
    });

    function updateSelectionUI() {
        slotBtns.forEach(slot => {
            if (slot.classList.contains('pointer-events-none')) return;

            const time = slot.dataset.time;
            slot.classList.remove('bg-[#814C5B]', 'text-white', 'ring-2', 'ring-[#6b3e4b]');

            if (selectedStart && !selectedEnd && time === selectedStart) {
                slot.classList.add('bg-[#814C5B]', 'text-white', 'ring-2', 'ring-[#6b3e4b]');
            } else if (selectedStart && selectedEnd && time >= selectedStart && time <= selectedEnd) {
                slot.classList.add('bg-[#814C5B]', 'text-white');
            }
        });

        if (selectedStart && selectedEnd) {
            inputStart.value = selectedStart;
            inputEnd.value = selectedEnd;
            inputDate.value = dateInput.value;
            reserveBtn.disabled = false;
            summaryText.innerText = `Terpilih: ${selectedStart} WIB - ${selectedEnd} WIB`;

            const url = "{{ route('booking.reserve', $facility) }}"
            + "?date=" + dateInput.value
            + "&start_time=" + selectedStart
            + "&end_time=" + selectedEnd;

            reserveBtn.href = url;
            reserveBtn.classList.remove('bg-[#814C5B]/40', 'pointer-events-none', 'cursor-not-allowed');
            reserveBtn.classList.add('bg-[#814C5B]', 'hover:bg-[#6b3e4b]', 'cursor-pointer')

            updateReserveBtn(dateInput.value, selectedStart, selectedEnd);
        } else if (selectedStart) {
            reserveBtn.disabled = true;
            summaryText.innerText = `Mulai: ${selectedStart} WIB (Pilih jam selesai)`;

            reserveBtn.href = '#';
            reserveBtn.classList.add('bg-[#814C5B]/40', 'pointer-events-none', 'cursor-not-allowed');
            reserveBtn.classList.remove('bg-[#814C5B]', 'hover:bg-[#6b3e4b]', 'cursor-pointer');
        }
    }

    dateInput.addEventListener('change', function () {
        selectedStart = null;
        selectedEnd   = null;
        summaryText.innerText = 'Pick a date to see available time slots.';
        reserveBtn.href = '#';
        reserveBtn.classList.add('bg-[#814C5B]/40', 'pointer-events-none', 'cursor-not-allowed');
        reserveBtn.classList.remove('bg-[#814C5B]', 'hover:bg-[#6b3e4b]', 'cursor-pointer');

        fetch(`/booking/{{ $facility->id }}/booked-slots?date=${this.value}`)
            .then(r => r.json())
            .then(data => {
                slotBtns.forEach(slot => {
                    slot.classList.remove('bg-[#814C5B]', 'text-white', 'pointer-events-none', 'opacity-80', 'ring-2', 'ring-[#6b3e4b]');
                    slot.classList.add('hover:bg-[#814C5B]/20', 'cursor-pointer');
                    slot.querySelector('.slot-status-indicator').innerText = '';
                });

                data.forEach(b => {
                    slotBtns.forEach(slot => {
                        const t = slot.dataset.time;
                        if (t >= b.start_time && t < b.end_time) {
                            slot.classList.add('bg-[#814C5B]', 'text-white', 'pointer-events-none', 'opacity-80');
                            slot.classList.remove('hover:bg-[#814C5B]/20', 'cursor-pointer');
                            slot.querySelector('.slot-status-indicator').innerText = 'Booked';
                        }
                    });
                });
            });
    });

});
</script>