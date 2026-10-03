<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Chloe - Officer Panel')</title>
<link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=2">
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Font: Poppins (teks) & Radley (logo + judul), sama dengan halaman admin -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Radley:ital@0;1&display=swap" rel="stylesheet">

<style>
:root{
  /* Warna disamakan dengan panel admin */
  --topbar:#42393a; --topbar-line:#e2b8bc;
  --sidebar:#e2b8bc; --sidebar-ink:#4b3839;
  --canvas:#5c4f50;
  --panel:#f7eced; --ink:#3d2b2f; --ink-soft:#7c6367;
  --rose:#86545e; --rose-deep:#6f4550;
  --blush:#fff5f5; --blush-soft:#d1c2c2;
  --chip:#e2b8bc; --chip-off:#f2e6e6; --white:#fff; --dark-card:#454545;
  --line:#f2e6e6;
  --ok:#2f6a3f; --warn:#7a5314; --bad:#b2455a; --info:#cdeefb;
  --serif:'Radley', Georgia, serif; --sans:'Poppins', system-ui, sans-serif;
}
*{box-sizing:border-box}
body{margin:0;background:var(--canvas);color:var(--ink);font-family:var(--sans);font-size:15px;-webkit-font-smoothing:antialiased;overflow-x:hidden}
a{color:inherit;text-decoration:none}
button,input,select,textarea{font:inherit;color:inherit}
button{cursor:pointer}
svg{flex-shrink:0}

.app{display:grid;grid-template-rows:auto 1fr;min-height:100vh}

/* ================= HEADER (sama dengan admin) ================= */
.topbar{background:var(--topbar);display:flex;align-items:center;justify-content:space-between;gap:16px;width:100%;padding:10px 32px;border-bottom:2px solid var(--topbar-line)}
.brand{display:flex;align-items:center;gap:12px}
.brand-logo{height:40px;width:auto;object-fit:contain;display:block}
.brand b{display:block;font-family:var(--serif);font-style:italic;font-weight:400;font-size:30px;line-height:1;color:#ffdcdc}
.brand small{display:block;font-size:12px;color:var(--blush-soft);margin-top:4px}

.user{position:relative}
.user-trigger{display:flex;align-items:center;gap:10px;background:var(--chip);color:var(--topbar);border:0;border-radius:999px;padding:6px 16px 6px 6px;font-size:15px;font-weight:600;box-shadow:0 2px 6px rgba(0,0,0,.2);transition:background .15s}
.user-trigger:hover{background:#ffdcdc}
.user-trigger .name{max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-trigger .chev{width:16px;height:16px;transition:transform .2s}
.user-trigger[aria-expanded="true"] .chev{transform:rotate(180deg)}
.avatar{width:36px;height:36px;border-radius:50%;object-fit:cover;display:flex;align-items:center;justify-content:center;background:var(--topbar);color:var(--chip);font-size:14px;font-weight:600;flex-shrink:0}
.avatar.lg{width:40px;height:40px}

.user-menu{position:absolute;top:calc(100% + 10px);right:0;width:230px;background:#fff;color:var(--ink);border:1px solid var(--chip);border-radius:18px;box-shadow:0 12px 30px rgba(0,0,0,.18);padding:8px 0;z-index:50}
.user-info{display:flex;align-items:center;gap:12px;padding:8px 16px}
.user-info .avatar{background:var(--topbar);color:var(--chip)}
.user-info p{margin:0}
.user-info .label{font-size:12px;color:#888}
.user-info .who{font-size:14px;font-weight:600;color:#2f2a2b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px}
.menu-sep{border-top:1px solid #f1eded;margin:6px 0}
.menu-item{display:flex;align-items:center;gap:10px;width:100%;text-align:left;background:transparent;border:0;padding:9px 16px;font-size:14px;font-weight:500;color:#444}
.menu-item:hover{background:#fcf7f7}
.menu-item svg{width:16px;height:16px;color:#888}
.menu-item.danger{color:#e11d48;font-weight:600}
.menu-item.danger svg{color:#f43f5e}
.menu-item.danger:hover{background:#fff1f2}

/* ================= SIDEBAR (sama dengan admin) ================= */
.shell{display:grid;grid-template-columns:256px minmax(0,1fr);min-height:0}
.side{background:var(--sidebar);box-shadow:2px 0 6px rgba(0,0,0,.08)}
.side nav{position:sticky;top:0;display:flex;flex-direction:column;gap:6px;padding:24px 16px}
.side .menu-label{padding:0 16px;margin:0 0 8px;font-size:12px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--rose)}
.side a{display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:12px;font-size:16px;font-weight:500;color:var(--sidebar-ink);transition:background .15s}
.side a svg{width:20px;height:20px}
.side a:hover{background:rgba(255,255,255,.5)}
.side a[aria-current="page"]{background:var(--canvas);color:#fff;font-weight:600;box-shadow:0 2px 6px rgba(0,0,0,.2)}
.side .count{margin-left:auto;min-width:22px;padding:0 7px;border-radius:999px;background:var(--rose);color:#fff;font-size:12px;font-weight:600;line-height:22px;text-align:center}
.side a[aria-current="page"] .count{background:var(--chip);color:var(--topbar)}

/* ================= KONTEN ================= */
main{padding:32px 40px 48px;max-width:1240px;width:100%;min-width:0}
h1{font-family:var(--serif);font-style:italic;font-weight:400;font-size:40px;line-height:1.1;margin:0;color:var(--blush)}
.sub{margin:6px 0 26px;font-size:14px;color:var(--blush-soft)}

/* Kartu statistik */
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.stat{background:var(--white);border-radius:18px;padding:20px 22px;min-height:120px;display:flex;flex-direction:column;justify-content:space-between;text-align:left;box-shadow:0 6px 16px rgba(0,0,0,.12);transition:box-shadow .15s}
.stat:hover{box-shadow:0 0 0 3px var(--chip),0 6px 16px rgba(0,0,0,.12)}
.stat span{font-size:14px;font-weight:500;color:var(--ink-soft)}
.stat strong{font-family:var(--sans);font-weight:700;font-size:38px;line-height:1;color:var(--rose)}

/* Kartu daftar */
.split{display:grid;grid-template-columns:1.25fr 1fr;gap:20px;margin-top:24px}
.card{background:var(--white);border-radius:18px;padding:22px 24px;min-height:250px;box-shadow:0 6px 16px rgba(0,0,0,.12)}
.card.dark{background:var(--dark-card);border:1px solid var(--chip);color:var(--blush)}
.card h2{margin:0 0 14px;font-size:16px;font-weight:600}
.list{list-style:none;margin:0;padding:0}
.list li{display:flex;justify-content:space-between;gap:12px;padding:12px 0;border-bottom:1px solid var(--line);font-size:14px}
.list li:last-child{border-bottom:0}
.list small{display:block;color:var(--ink-soft);font-size:12.5px;margin-top:2px}
.dark .list li{border-color:#5d5d5d}
.dark .list small{color:#c9b3b6}
.empty{color:var(--ink-soft);font-size:14px;padding:24px 0;text-align:center}
.dark .empty{color:#c9b3b6}

/* Tombol pil */
.pillrow{display:flex;flex-wrap:wrap;gap:12px;margin-top:26px}
.pill{border:0;border-radius:999px;padding:10px 22px;font-size:14px;font-weight:600;background:var(--chip);color:var(--topbar);display:inline-block;transition:filter .15s}
.pill.light{background:var(--blush)}
.pill.grey{background:#7c7c7c;color:#f1f1f1}
.pill:hover{filter:brightness(1.06)}

/* Toolbar: filter & pencarian */
.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:18px}
.chip{border:0;border-radius:999px;padding:7px 16px;font-size:13px;font-weight:500;background:rgba(255,255,255,.15);color:var(--blush);display:inline-block;transition:background .15s}
.chip:hover{background:rgba(255,255,255,.25)}
.chip[aria-pressed="true"]{background:var(--chip);color:var(--topbar);font-weight:600}
.toolbar .grow{flex:1}
.search{width:min(340px,100%);border:0;border-radius:12px;background:#fff;padding:10px 16px;font-size:14px;outline:none}
.search:focus{box-shadow:0 0 0 3px rgba(226,184,188,.7)}
.sel{border:0;border-radius:12px;background:#fff;padding:9px 14px;font-size:14px;font-weight:500;max-width:200px;outline:none;cursor:pointer}
.sel:focus{box-shadow:0 0 0 3px rgba(226,184,188,.7)}
input[type=date].sel{font-family:var(--sans)}

/* Tabel */
.tablewrap{background:#fff;border-radius:18px;overflow-x:auto;max-width:100%;box-shadow:0 6px 16px rgba(0,0,0,.12)}
table{width:100%;border-collapse:collapse;min-width:720px}
thead{background:var(--panel)}
th{text-align:left;font-size:12px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--rose);padding:14px 18px;border-bottom:1px solid var(--line)}
td{padding:16px 18px;font-size:14px;border-bottom:1px solid var(--line);vertical-align:middle}
tbody tr{transition:background .15s}
tbody tr:hover{background:#fdf8f8}
tbody tr:last-child td{border-bottom:0}
tbody tr.sel-row{background:#fbeef0}
td small{display:block;color:var(--ink-soft);font-size:12.5px;margin-top:2px}

/* Badge status */
.badge{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:4px 12px;font-size:12px;font-weight:600;white-space:nowrap}
.badge::before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor;opacity:.7}
.b-pending{background:#fff4e0;color:#8a5a12}
.b-approved{background:#e3f4e8;color:#2f6a3f}
.b-rejected,.b-cancelled{background:#fbe4e8;color:#9b2f45}
.b-new{background:#e1f3fb;color:#245a70}
.b-progress{background:#fff4e0;color:#8a5a12}
.b-resolved{background:#e3f4e8;color:#2f6a3f}

/* Tombol aksi kecil */
.act{display:flex;gap:8px;flex-wrap:wrap}
.mini{border:0;border-radius:999px;padding:6px 14px;font-size:12.5px;font-weight:600;background:var(--chip);color:var(--topbar);transition:filter .15s}
.mini.go{background:var(--rose);color:#fff}
.mini.no{background:#fff;color:var(--bad);box-shadow:inset 0 0 0 1.5px #f2b8c2}
.mini.no:hover{background:#fff1f3}
.mini:hover{filter:brightness(.96)}

/* Panel alasan / catatan */
.reason{background:var(--white);border-radius:18px;padding:22px 24px;margin-top:22px;box-shadow:0 6px 16px rgba(0,0,0,.12)}
.reason h3{margin:0 0 10px;font-size:16px;font-weight:600}
.reason .target{font-size:14px;color:var(--rose);margin:-4px 0 12px}
.reason textarea{width:100%;min-height:120px;border:1.5px solid var(--line);border-radius:12px;background:#fcf7f7;padding:12px 14px;font-size:14px;resize:vertical;outline:none}
.reason textarea:focus{background:#fff;border-color:var(--chip);box-shadow:0 0 0 3px rgba(226,184,188,.5)}
.reason textarea:disabled{background:#f6ecee;cursor:not-allowed}
.btns{display:flex;gap:10px;margin-top:14px}
.btn{border:0;border-radius:999px;padding:10px 22px;font-size:14px;font-weight:600;transition:filter .15s}
.btn.ghost{background:#f1eded;color:#666}
.btn.main{background:var(--rose);color:#fff}
.btn:hover:not(:disabled){filter:brightness(.95)}
.btn:disabled{opacity:.5;cursor:not-allowed}

/* Kartu laporan */
.cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px}
.rc{background:#fff;border-radius:18px;padding:16px;display:grid;grid-template-columns:88px 1fr;gap:16px;position:relative;cursor:pointer;box-shadow:0 6px 16px rgba(0,0,0,.12);transition:box-shadow .15s}
.rc.picked{box-shadow:0 0 0 3px var(--rose)}
.rc:hover{box-shadow:0 0 0 3px var(--chip),0 6px 16px rgba(0,0,0,.12)}
.thumb{width:88px;height:88px;border-radius:14px;background:var(--chip-off);display:grid;place-items:center;color:#b89b9e;overflow:hidden}
.thumb img{width:100%;height:100%;object-fit:cover}
.rc h4{margin:0 90px 4px 0;font-size:15px;font-weight:600}
.rc p{margin:0;font-size:13px;color:var(--ink-soft);line-height:1.55}
.rc .badge{position:absolute;top:16px;right:16px}
.rc .foot{margin-top:10px;display:flex;gap:8px;align-items:center;font-size:12.5px;color:var(--ink-soft)}

/* Kartu status fasilitas */
.fgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px}
.fc{background:#fff;border-radius:18px;padding:20px 22px;display:flex;flex-direction:column;gap:16px;box-shadow:0 6px 16px rgba(0,0,0,.12)}
.fc header{display:flex;justify-content:space-between;align-items:flex-start;gap:10px}
.fc h4{margin:0 0 2px;font-size:16px;font-weight:600}
.fc small{color:var(--ink-soft);font-size:13px}
.dot{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:6px}
.seg{display:grid;grid-template-columns:repeat(3,1fr);background:var(--panel);border-radius:999px;padding:4px;gap:3px}
.seg form{display:contents}
.seg button{border:0;background:transparent;border-radius:999px;padding:8px 6px;font-size:13px;font-weight:500;color:var(--ink-soft);width:100%;transition:background .15s}
.seg button:hover{background:rgba(255,255,255,.7)}
.seg button[aria-pressed="true"]{background:var(--rose);color:#fff;font-weight:600}
.s-active{background:#e3f4e8;color:#2f6a3f}
.s-maintenance{background:#fff4e0;color:#8a5a12}
.s-inactive{background:#ececec;color:#555}

/* Ringkasan & pesan */
.legend{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px}
.legend span{background:rgba(255,255,255,.12);color:var(--blush);padding:7px 16px;border-radius:999px;font-size:13.5px;font-weight:500}
.flash{display:flex;align-items:center;gap:8px;background:#ecfdf3;color:#166534;border:1px solid #bbf7d0;padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:18px}

[x-cloak]{display:none!important}

/* Sembunyikan scrollbar (halaman tetap bisa di-scroll) */
html{scrollbar-width:none}
::-webkit-scrollbar{display:none}

@media (max-width:820px){
  .shell{grid-template-columns:1fr;grid-template-rows:auto 1fr}
  .side nav{position:static;flex-direction:row;overflow-x:auto;padding:10px;gap:6px}
  .side .menu-label{display:none}
  .side a{white-space:nowrap;padding:10px 14px;font-size:14px}
  main{padding:22px 16px 40px}
  .stats,.split{grid-template-columns:1fr}
  .topbar{padding:10px 14px}
  .brand small{display:none}
  .user-trigger .name{max-width:90px}
  h1{font-size:32px}
}
</style>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
@php
    $authUser = Auth::user();
    $userName = $authUser->name ?? 'Petugas';
    $photoUrl = !empty($authUser->photo) ? asset('storage/' . $authUser->photo) : null;
    $initial  = strtoupper(substr($userName, 0, 1));
@endphp

<div class="app">
  <!-- ================= HEADER ================= -->
  <header class="topbar">
    <div class="brand">
      <img src="{{ asset('images/logoPink.png') }}" alt="Chloe" class="brand-logo">
      <div><b>Chloe</b><small>Campus Hall &amp; Location Online E-booking</small></div>
    </div>

    <div class="user" x-data="{ open: false }" @click.outside="open = false">
      <button type="button" class="user-trigger" @click="open = !open" :aria-expanded="open.toString()">
        @if ($photoUrl)
          {{-- Jika file foto gagal dimuat, tampilkan inisial sebagai cadangan --}}
          <img src="{{ $photoUrl }}" alt="Foto profil {{ $userName }}" class="avatar"
               onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
          <span class="avatar" aria-hidden="true" style="display:none">{{ $initial }}</span>
        @else
          <span class="avatar" aria-hidden="true">{{ $initial }}</span>
        @endif
        <span class="name">{{ $userName }}</span>
        <svg class="chev" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
      </button>

      <div class="user-menu" x-show="open" x-cloak x-transition>
        <div class="user-info">
          @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="Foto profil {{ $userName }}" class="avatar lg"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <span class="avatar lg" aria-hidden="true" style="display:none">{{ $initial }}</span>
          @else
            <span class="avatar lg" aria-hidden="true">{{ $initial }}</span>
          @endif
          <div>
            <p class="label">Signed in as</p>
            <p class="who">{{ $userName }}</p>
          </div>
        </div>

        <div class="menu-sep"></div>

        <a href="{{ route('profile.edit') }}" class="menu-item">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Edit Account
        </a>

        <div class="menu-sep"></div>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="menu-item danger">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Log Out
          </button>
        </form>
      </div>
    </div>
  </header>

  <div class="shell">
    <!-- ================= SIDEBAR ================= -->
    <aside class="side">
      <nav aria-label="Main">
        <p class="menu-label">Menu</p>

        <a href="{{ route('dashboard.petugas') }}" @if(request()->routeIs('dashboard.petugas')) aria-current="page" @endif>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Dashboard
        </a>

        <a href="{{ route('petugas.reservations') }}" @if(request()->routeIs('petugas.reservations*')) aria-current="page" @endif>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Reservations
          @if($navPendingCount ?? 0) <span class="count">{{ $navPendingCount }}</span> @endif
        </a>

        <a href="{{ route('petugas.reports') }}" @if(request()->routeIs('petugas.reports*')) aria-current="page" @endif>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
          Reports
          @if($navNewReportCount ?? 0) <span class="count">{{ $navNewReportCount }}</span> @endif
        </a>

        <a href="{{ route('petugas.facility-status') }}" @if(request()->routeIs('petugas.facility-status*')) aria-current="page" @endif>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          Facility Status
        </a>
      </nav>
    </aside>

    <main id="view" tabindex="-1">
      @yield('content')
    </main>
  </div>
</div>

<!-- Konfirmasi Log Out -->
<x-logout-confirm />
</body>
</html>