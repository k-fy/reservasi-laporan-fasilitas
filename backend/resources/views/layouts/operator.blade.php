<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Chloe - Officer Panel')</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
:root{
  --topbar:#4a4a4a; --sidebar:#dfb4bd; --sidebar-ink:#6b4a50; --canvas:#745a5d;
  --panel:#ebc9cf; --ink:#3d2b2f; --ink-soft:#7c6367; --rose:#8b4a5a; --rose-deep:#6f3948;
  --blush:#f8dcdc; --chip:#e6b8c0; --chip-off:#9b8084; --white:#fff; --dark-card:#454545;
  --ok:#5f8f6b; --warn:#c78a3a; --bad:#b2455a; --info:#cdeefb;
  --serif:'Playfair Display', Georgia, serif; --sans:'Poppins', system-ui, sans-serif;
}
*{box-sizing:border-box}
body{margin:0;background:var(--canvas);color:var(--ink);font-family:var(--sans);font-size:13px}
a{color:inherit;text-decoration:none}
button,input,select,textarea{font:inherit;color:inherit}
button{cursor:pointer}

.app{display:grid;grid-template-rows:auto 1fr;min-height:100vh}
.topbar{background:var(--topbar);display:flex;align-items:center;justify-content:space-between;padding:10px 22px;border-bottom:2px solid var(--blush)}
.brand{display:flex;align-items:center;gap:10px;color:var(--blush)}
.brand svg{width:34px;height:34px}
.brand-logo{height:34px;width:auto;object-fit:contain;display:block}
.brand b{display:block;font-family:var(--serif);font-style:italic;font-weight:500;font-size:20px;line-height:1}
.brand small{display:block;font-family:var(--serif);font-style:italic;font-size:9px;opacity:.85;margin-top:2px}
.user{display:flex;align-items:center;gap:10px;color:var(--blush);text-align:right;font-size:9px;line-height:1.3}
.user u{display:block;font-family:var(--serif);font-style:italic;font-size:13px}
.avatar{width:22px;height:22px;border-radius:50%;background:#8b4a5a}

.shell{display:grid;grid-template-columns:200px 1fr;min-height:0}
.side{background:var(--sidebar);padding:14px 0;display:flex;flex-direction:column;gap:8px}
.side a{display:block;padding:16px 10px;text-align:center;font-weight:600;font-size:14px;color:var(--sidebar-ink)}
.side a:hover{background:rgba(255,255,255,.25)}
.side a[aria-current="page"]{background:var(--canvas);color:var(--blush);border-radius:18px 0 0 18px;margin-left:10px}
.side .count{display:inline-block;min-width:18px;padding:0 5px;margin-left:6px;border-radius:9px;background:var(--rose);color:#fff;font-size:10px;line-height:18px}

main{padding:26px 34px 40px;max-width:1120px;width:100%}
h1{font-family:var(--serif);font-style:italic;font-weight:500;font-size:30px;margin:0;color:var(--blush)}
.sub{margin:4px 0 22px;font-size:11px;color:var(--blush);opacity:.92}

.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.stat{background:var(--white);border-radius:14px;padding:14px 16px;min-height:104px;display:flex;flex-direction:column;justify-content:space-between;text-align:left}
.stat:hover{box-shadow:0 0 0 3px var(--chip)}
.stat span{font-size:11px;font-weight:500}
.stat strong{font-family:var(--serif);font-style:italic;font-weight:500;font-size:38px;line-height:1;color:var(--rose)}

.split{display:grid;grid-template-columns:1.25fr 1fr;gap:22px;margin-top:26px}
.card{background:var(--white);border-radius:18px;padding:18px 20px;min-height:250px}
.card.dark{background:var(--dark-card);border:1px solid var(--blush);color:var(--blush)}
.card h2{margin:0 0 12px;font-size:12px;font-weight:600}
.list{list-style:none;margin:0;padding:0}
.list li{display:flex;justify-content:space-between;gap:10px;padding:9px 0;border-bottom:1px solid #eee;font-size:11.5px}
.list li:last-child{border-bottom:0}
.list small{display:block;color:var(--ink-soft);font-size:10px}
.dark .list li{border-color:#5d5d5d}
.dark .list small{color:#c9b3b6}
.empty{color:var(--ink-soft);font-size:11.5px;padding:18px 0}
.dark .empty{color:#c9b3b6}

.pillrow{display:flex;flex-wrap:wrap;gap:12px;margin-top:26px}
.pill{border:0;border-radius:999px;padding:7px 18px;font-size:10px;font-weight:600;background:var(--chip);color:var(--ink);display:inline-block}
.pill.light{background:var(--blush)}
.pill.grey{background:#7c7c7c;color:#e8e8e8}
.pill:hover{filter:brightness(1.06)}

.toolbar{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:14px}
.chip{border:0;border-radius:999px;padding:4px 14px;font-size:9.5px;font-weight:500;background:var(--chip-off);color:#efe0e2;display:inline-block}
.chip[aria-pressed="true"]{background:var(--chip);color:var(--ink);font-weight:600}
.toolbar .grow{flex:1}
.search{width:min(320px,100%);border:0;border-radius:999px;background:#fff;padding:5px 14px;font-size:11px}
.sel{border:0;border-radius:999px;background:var(--blush);padding:4px 12px;font-size:9.5px;font-weight:500;max-width:150px}
input[type=date].sel{font-family:var(--sans)}

.tablewrap{background:#fff;border-radius:14px;overflow-x:auto;max-width:100%}
table{width:100%;border-collapse:collapse;min-width:700px}
th{text-align:left;font-size:10px;font-weight:600;padding:9px 14px;border-bottom:1px solid #999}
td{padding:12px 14px;font-size:11px;border-bottom:1px solid #999;vertical-align:middle}
tbody tr:last-child td{border-bottom:0}
tbody tr.sel-row{background:#fbeef0}
td small{display:block;color:var(--ink-soft);font-size:10px}
.badge{display:inline-block;border-radius:999px;padding:2px 10px;font-size:10px;font-weight:600}
.b-pending{background:#fbe6c6;color:#7a5314}
.b-approved{background:#d3ecd9;color:#2f6a3f}
.b-rejected,.b-cancelled{background:#f3cdd4;color:#8a2c40}
.b-new{background:var(--info);color:#245a70}
.b-progress{background:#fbe6c6;color:#7a5314}
.b-resolved{background:#d3ecd9;color:#2f6a3f}
.act{display:flex;gap:6px}
.mini{border:0;border-radius:999px;padding:4px 12px;font-size:10px;font-weight:600;background:var(--chip);color:var(--ink)}
.mini.go{background:var(--rose);color:#fff}
.mini.no{background:#fff;color:var(--bad);box-shadow:inset 0 0 0 1.5px var(--bad)}
.mini:hover{filter:brightness(.96)}

.reason{background:var(--panel);border-radius:18px;padding:18px 20px;margin-top:22px}
.reason h3{margin:0 0 10px;font-size:11px;font-weight:600}
.reason .target{font-size:11px;color:var(--rose-deep);margin:-4px 0 10px}
.reason textarea{width:100%;min-height:110px;border:1.5px dashed #6f5559;border-radius:6px;background:#fff;padding:10px 12px;font-size:12px;resize:vertical}
.reason textarea:disabled{background:#f6ecee;cursor:not-allowed}
.btns{display:flex;gap:8px;margin-top:12px}
.btn{border:0;border-radius:999px;padding:6px 16px;font-size:10px;font-weight:600}
.btn.ghost{background:#f4e6e8;color:#9c8589}
.btn.main{background:var(--rose);color:#fff}
.btn:disabled{opacity:.5;cursor:not-allowed}

.cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px}
.rc{background:#fff;border-radius:14px;padding:14px;display:grid;grid-template-columns:78px 1fr;gap:14px;position:relative;cursor:pointer}
.rc.picked{box-shadow:0 0 0 3px var(--rose)}
.rc:hover{box-shadow:0 0 0 3px var(--chip)}
.thumb{width:78px;height:78px;border-radius:12px;background:#d9d9d9;display:grid;place-items:center;color:#8a8a8a;overflow:hidden}
.rc h4{margin:0 70px 2px 0;font-size:12px;font-weight:600}
.rc p{margin:0;font-size:10.5px;color:var(--ink-soft);line-height:1.5}
.rc .badge{position:absolute;top:14px;right:14px}
.rc .foot{margin-top:8px;display:flex;gap:6px;align-items:center;font-size:10px;color:var(--ink-soft)}

.fgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px}
.fc{background:#fff;border-radius:16px;padding:16px 18px;display:flex;flex-direction:column;gap:12px}
.fc header{display:flex;justify-content:space-between;align-items:flex-start;gap:8px}
.fc h4{margin:0;font-size:13px;font-weight:600}
.fc small{color:var(--ink-soft);font-size:10px}
.dot{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:6px}
.seg{display:grid;grid-template-columns:repeat(3,1fr);background:#f2e3e5;border-radius:999px;padding:3px;gap:2px}
.seg form{display:contents}
.seg button{border:0;background:transparent;border-radius:999px;padding:5px 4px;font-size:9.5px;font-weight:500;color:var(--ink-soft);width:100%}
.seg button[aria-pressed="true"]{background:var(--rose);color:#fff;font-weight:600}
.s-active{background:#d3ecd9;color:#2f6a3f}
.s-maintenance{background:#fbe6c6;color:#7a5314}
.s-inactive{background:#e5e5e5;color:#555}
.legend{display:flex;gap:16px;flex-wrap:wrap;margin-bottom:16px;color:var(--blush);font-size:10.5px}
.flash{background:var(--blush);color:var(--ink);padding:8px 16px;border-radius:999px;display:inline-block;font-size:11px;margin-bottom:14px}

[x-cloak]{display:none!important}

/* Dropdown user & logout */
.user{position:relative}
.user-trigger{display:flex;align-items:center;gap:10px;cursor:pointer;background:transparent;border:0;color:inherit;font:inherit;padding:4px 6px;border-radius:10px}
.user-trigger:hover{background:rgba(0,0,0,.06)}
.user-menu{position:absolute;top:calc(100% + 8px);right:0;min-width:170px;background:#fff;border:1px solid rgba(0,0,0,.08);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.12);padding:6px;z-index:50}
.logout-item{width:100%;text-align:left;cursor:pointer;background:transparent;border:0;color:#a03a4a;font:inherit;padding:10px 12px;border-radius:8px}
.logout-item:hover{background:#f6e7ea}

@media (max-width:820px){
  .shell{grid-template-columns:1fr;grid-template-rows:auto 1fr}
  .side{flex-direction:row;overflow-x:auto;padding:8px 8px 0;gap:4px}
  .side a{white-space:nowrap;padding:12px 16px;font-size:12px}
  .side a[aria-current="page"]{margin-left:0;border-radius:14px 14px 0 0}
  main{padding:20px 16px 40px}
  .stats,.split{grid-template-columns:1fr}
  .topbar{padding:10px 14px}
  h1{font-size:26px}
}
</style>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
<div class="app">
  <header class="topbar">
    <div class="brand">
      <img src="{{ asset('images/logoPink.png') }}" alt="Chloe" class="brand-logo">
      <div><b>Chloe</b><small>Campus Hall &amp; Location Online E-booking</small></div>
    </div>

    <div class="user" x-data="{ open: false }" @click.outside="open = false">
      <button type="button" class="user-trigger" @click="open = !open" :aria-expanded="open">
        <div>Logged in as<u>{{ Auth::user()->name }}</u></div>
        <span class="avatar" aria-hidden="true"></span>
      </button>

      <div class="user-menu" x-show="open" x-cloak x-transition>
        <form method="POST" action="{{ route('logout') }}"
              onsubmit="return confirm('Yakin ingin keluar dari sesi ini? Anda harus login kembali untuk mengakses dashboard.')">
          @csrf
          <button type="submit" class="logout-item">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <div class="shell">
    <nav class="side" aria-label="Main">
      <a href="{{ route('dashboard.petugas') }}" @if(request()->routeIs('dashboard.petugas')) aria-current="page" @endif>Dashboard</a>
      <a href="{{ route('petugas.reservations') }}" @if(request()->routeIs('petugas.reservations')) aria-current="page" @endif>
          Reservations
          @if($navPendingCount ?? 0) <span class="count">{{ $navPendingCount }}</span> @endif
      </a>
      <a href="{{ route('petugas.reports') }}" @if(request()->routeIs('petugas.reports')) aria-current="page" @endif>
          Reports
          @if($navNewReportCount ?? 0) <span class="count">{{ $navNewReportCount }}</span> @endif
      </a>
      <a href="{{ route('petugas.facility-status') }}" @if(request()->routeIs('petugas.facility-status')) aria-current="page" @endif>Facility Status</a>
    </nav>
    <main id="view" tabindex="-1">
      @yield('content')
    </main>
  </div>
</div>
</body>
</html>