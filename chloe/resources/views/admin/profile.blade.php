<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Admin Chloe</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=2">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#5c4f50] m-0 p-0 text-white min-h-screen flex flex-col overflow-x-hidden">

    @include('admin.components.header')

    <div class="flex flex-1">
        @include('admin.components.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0 px-8 py-8 flex flex-col">
            <div class="mb-6">
                <h1 class="use-radley italic text-4xl text-[#fff5f5]">My Account</h1>
                <p class="text-sm text-[#d1c2c2] mt-1">Update your profile photo, personal data, and password.</p>
            </div>

            <!-- Form Edit Account (dipakai bersama dengan pengguna & petugas) -->
            <div class="flex justify-center">
                @include('profile.partials.account-form', [
                    'user'      => $user,
                    'cancelUrl' => route('admin.accounts'),
                ])
            </div>
        </main>
    </div>

</body>
</html>