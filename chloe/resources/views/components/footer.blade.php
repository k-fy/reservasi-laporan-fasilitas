<footer class="bg-[#D8B4B8] text-neutral-700 py-12">
        <div class="max-w-6xl mx-auto px-6">
            
            <!-- Section: About Chloe -->
            <div class="max-w-xl mb-8">
                <h3 class="text-3xl font-serif italic font mb-3 text-neutral-800">About Chloe</h3>
                <p class="text-sm leading-relaxed text-neutral-700 font-serif">
                    <strong>CHLOE (Campus Hall & Location Online E-booking)</strong> is the premier digital hub for booking campus venues and facilities, designed to make event planning seamless for students and staff.
                </p>
            </div>

            <!-- Section: Fast Links -->
            <div class="mb-10">
                <h3 class="text-3xl font-serif italic font mb-3 text-neutral-800">Fast Links</h3>
                <ul class="space-y-1 text-sm font-medium">
                    <li>
                        <a href="{{ route('dashboard') }}" class="hover:underline text-neutral-800">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('booking.index') }}" class="hover:underline text-neutral-800">Booking</a>
                    </li>
                    <li>
                        <a href="{{ route('reports.create') }}" class="hover:underline text-neutral-800">Reports</a>
                    </li>
                </ul>
            </div>

            <!-- Divider Line -->
            <div class="border-t border-neutral-600/40 w-full mb-6"></div>

            <!-- Copyright -->
            <div class="text-center text-xs text-neutral-700 space-y-1">
                <p>© {{ date('Y') }} Barbie Charm University.</p>
                <p>All rights reserved.</p>
            </div>

        </div>
</footer>