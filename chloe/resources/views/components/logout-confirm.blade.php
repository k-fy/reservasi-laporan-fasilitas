{{--
    Pop-up konfirmasi Log Out — dipakai oleh semua aktor (pengguna, petugas, admin).
    Cara kerja: setiap form yang mengarah ke route('logout') akan dicegat,
    lalu pop-up ini muncul. Logout baru dijalankan setelah klik "Ya, keluar".
    Memakai CSS sendiri agar tampil sama di semua layout.
--}}
<style>
    .chloe-logout-overlay {
        position: fixed; inset: 0; z-index: 9999;
        display: none; align-items: center; justify-content: center; padding: 16px;
        background: rgba(30, 24, 25, .55);
        font-family: 'Poppins', system-ui, sans-serif;
    }
    .chloe-logout-overlay.is-open { display: flex; animation: chloeLogoutFade .2s ease-out; }
    .chloe-logout-card {
        width: 100%; max-width: 380px; background: #fff; color: #3d2b2f;
        border-radius: 22px; padding: 28px; box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
        text-align: center;
    }
    .chloe-logout-overlay.is-open .chloe-logout-card { animation: chloeLogoutPop .25s ease-out; }
    .chloe-logout-icon {
        width: 56px; height: 56px; margin: 0 auto 16px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #fbe9ec; color: #b2455a;
    }
    .chloe-logout-icon svg { width: 26px; height: 26px; }
    .chloe-logout-card h3 { margin: 0 0 6px; font-size: 18px; font-weight: 700; color: #3d2b2f; }
    .chloe-logout-card p { margin: 0 0 24px; font-size: 14px; line-height: 1.55; color: #7c6367; }
    .chloe-logout-actions { display: flex; gap: 10px; }
    .chloe-logout-actions button {
        flex: 1; border: 0; border-radius: 999px; padding: 11px 16px;
        font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; transition: background-color .15s ease;
    }
    .chloe-logout-cancel { background: #f1eded; color: #555; }
    .chloe-logout-cancel:hover { background: #e6e0e0; }
    .chloe-logout-confirm { background: #86545e; color: #fff; }
    .chloe-logout-confirm:hover { background: #6f4550; }
    .chloe-logout-confirm:disabled { opacity: .7; cursor: wait; }

    @keyframes chloeLogoutFade { from { opacity: 0; } to { opacity: 1; } }
    @keyframes chloeLogoutPop  { from { opacity: 0; transform: translateY(10px) scale(.97); } to { opacity: 1; transform: none; } }
    @media (prefers-reduced-motion: reduce) {
        .chloe-logout-overlay.is-open, .chloe-logout-overlay.is-open .chloe-logout-card { animation: none; }
    }
    .reduce-motion .chloe-logout-overlay.is-open, .reduce-motion .chloe-logout-overlay.is-open .chloe-logout-card { animation: none; }
</style>

<div id="chloe-logout-modal" class="chloe-logout-overlay" role="dialog" aria-modal="true" aria-labelledby="chloe-logout-title">
    <div class="chloe-logout-card">
        <div class="chloe-logout-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        </div>
        <h3 id="chloe-logout-title">Keluar dari akun?</h3>
        <p>Kamu harus login kembali untuk mengakses akunmu.</p>
        <div class="chloe-logout-actions">
            <button type="button" class="chloe-logout-cancel" data-logout-cancel>Batal</button>
            <button type="button" class="chloe-logout-confirm" data-logout-confirm>Ya, keluar</button>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal      = document.getElementById('chloe-logout-modal');
        const confirmBtn = modal.querySelector('[data-logout-confirm]');
        const cancelBtn  = modal.querySelector('[data-logout-cancel]');
        const logoutUrl  = @json(route('logout'));
        let pendingForm  = null;

        function openModal(form) {
            pendingForm = form;
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Ya, keluar';
            modal.classList.add('is-open');
            cancelBtn.focus();
        }

        function closeModal() {
            modal.classList.remove('is-open');
            pendingForm = null;
        }

        // Cegat semua form logout di halaman ini
        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (form.action !== logoutUrl || form.dataset.logoutConfirmed === '1') return;
            event.preventDefault();
            openModal(form);
        }, true);

        confirmBtn.addEventListener('click', () => {
            if (!pendingForm) return;
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Keluar...';
            pendingForm.dataset.logoutConfirmed = '1';
            pendingForm.submit();
        });

        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (event) => { if (event.target === modal) closeModal(); });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
        });
    })();
</script>