<!DOCTYPE html>
<html lang="ca" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronos - Tauler</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Prevent flash by applying sidebar state before render
        (function() {
            const sidebarExpanded = localStorage.getItem('sidebarExpanded') === 'true';
            if (sidebarExpanded) {
                document.documentElement.style.setProperty('--sidebar-width', '16rem');
                document.documentElement.style.setProperty('--main-margin', '16rem');
            }
        })();
    </script>
    <style>
        :root {
            --sidebar-width: 5rem;
            --main-margin: 5rem;
        }
        #sidebar.pre-render {
            width: var(--sidebar-width) !important;
        }
        #main-container.pre-render {
            margin-left: var(--main-margin) !important;
        }
    </style>
</head>
<body class="bg-neutral-950">
    <div class="min-h-screen flex">
        <!-- Sidebar Menu -->
        <div id="sidebar" class="pre-render fixed left-0 top-0 h-full w-20 bg-neutral-900 border-r border-neutral-700 transition-all duration-300 ease-in-out z-40 overflow-hidden hover:shadow-2xl">
            <div class="h-full flex flex-col py-4">
                <!-- Menu Toggle Button -->
                <button onclick="toggleSidebar()" class="flex items-center justify-center w-full py-4 text-neutral-400 hover:text-cyan-400 hover:bg-neutral-800 transition-all duration-300">
                    <svg class="w-6 h-6 transition-transform duration-300" id="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="h-px bg-neutral-800 my-2"></div>

                <!-- Menu Items -->
                <nav class="flex-1 px-2 space-y-1">
                    <a href="/" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="Inici">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">Inici</span>
                    </a>

                    <div class="h-px bg-neutral-800 my-2"></div>

                    <a href="/temperature" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="Temperatura">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">Temperatura</span>
                    </a>

                    <a href="/humidity" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="Humitat">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">Humitat</span>
                    </a>

                    <a href="/pressure" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="Pressió">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">Pressió</span>
                    </a>

                    <a href="/brightness" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="Brillantor">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">Brillantor</span>
                    </a>

                    <a href="/co2" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="CO2">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">CO2</span>
                    </a>

                    <a href="/tvoc" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="TVOC">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">TVOC</span>
                    </a>
                </nav>

                <div class="mt-auto px-2">
                    <div class="h-px bg-neutral-800 mb-2"></div>
                    <p class="sidebar-text text-xs text-neutral-500 text-center opacity-0 transition-opacity duration-300 px-2">Cronos TDR &copy; {{ date('Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content Container -->
        <div id="main-container" class="pre-render flex-1 ml-20 transition-all duration-300 ease-in-out flex flex-col">
            <!-- Header - Sticky -->
            <header class="sticky top-0 bg-neutral-900/95 backdrop-blur-sm shadow-lg border-b border-neutral-800 z-30">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-white">Cronos TDR - Tauler de Sensors</h1>
                    <button id="changeDateBtn" onclick="showDateModal(false)" style="display:none;"
                        class="flex items-center gap-2 px-4 py-2 bg-neutral-800/80 border border-neutral-700 rounded-xl text-neutral-300 hover:border-cyan-500/50 hover:text-cyan-400 hover:bg-neutral-800 transition-all duration-300 group cursor-pointer">
                        <svg class="w-4 h-4 text-cyan-400 group-hover:text-cyan-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span id="changeDateBtnText" class="text-sm font-medium whitespace-nowrap"></span>
                        <svg class="w-3.5 h-3.5 text-neutral-500 group-hover:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Main Content -->
            <div class="flex-1">
                @include('partials.dashboard-content')
            </div>
        </div>
    </div>

    <script>
        // ── Date Modal (created dynamically via JS) ──
        let _dateModalEl = null;
        let _dateModalMandatory = false;

        function _createDateModal() {
            if (_dateModalEl) return _dateModalEl;

            const overlay = document.createElement('div');
            overlay.id = 'dateModalOverlay';
            Object.assign(overlay.style, {
                position: 'fixed', inset: '0', zIndex: '9999',
                display: 'none', alignItems: 'center', justifyContent: 'center'
            });

            // Backdrop
            const backdrop = document.createElement('div');
            backdrop.id = 'dateModalBackdrop';
            Object.assign(backdrop.style, {
                position: 'absolute', inset: '0',
                background: 'rgba(0,0,0,0.75)', backdropFilter: 'blur(6px)',
                opacity: '0', transition: 'opacity 0.4s ease'
            });
            overlay.appendChild(backdrop);

            // Card wrapper
            const cardWrap = document.createElement('div');
            cardWrap.id = 'dateModalCard';
            Object.assign(cardWrap.style, {
                position: 'relative', zIndex: '10',
                opacity: '0', transform: 'scale(0.85) translateY(30px)',
                transition: 'opacity 0.5s cubic-bezier(0.34,1.56,0.64,1), transform 0.5s cubic-bezier(0.34,1.56,0.64,1)'
            });

            const card = document.createElement('div');
            card.style.cssText = 'position:relative;background:linear-gradient(135deg,#171717,#262626);border-radius:1rem;border:1px solid #404040;box-shadow:0 25px 50px -12px rgba(6,182,212,0.1);padding:2.5rem;width:420px;max-width:90vw;';

            // Glow
            const glow = document.createElement('div');
            glow.id = 'dateModalGlow';
            glow.style.cssText = 'position:absolute;inset:-2px;background:linear-gradient(90deg,rgba(6,182,212,0.2),rgba(59,130,246,0.2),rgba(168,85,247,0.2));border-radius:1rem;filter:blur(16px);opacity:0;transition:opacity 0.7s;pointer-events:none;';
            card.appendChild(glow);

            // Inner content
            const inner = document.createElement('div');
            inner.style.position = 'relative';

            // Icon
            inner.innerHTML = `
                <div style="display:flex;justify-content:center;margin-bottom:1.5rem;">
                    <div style="position:relative;">
                        <div style="position:absolute;inset:-12px;background:rgba(6,182,212,0.2);border-radius:9999px;filter:blur(16px);animation:pulse 2s ease-in-out infinite;"></div>
                        <div style="position:relative;background:rgba(6,182,212,0.1);border:1px solid rgba(6,182,212,0.3);border-radius:9999px;padding:1rem;">
                            <svg style="width:2.5rem;height:2.5rem;color:#22d3ee;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <h2 style="font-size:1.5rem;font-weight:700;color:#fff;text-align:center;margin-bottom:0.5rem;">Selecciona una data</h2>
                <p style="color:#a3a3a3;text-align:center;font-size:0.875rem;margin-bottom:2rem;">Escull el dia per visualitzar les dades dels sensors</p>
            `;

            // Date input
            const inputWrap = document.createElement('div');
            inputWrap.style.marginBottom = '2rem';
            const input = document.createElement('input');
            input.type = 'date';
            input.id = 'modalDateInput';
            input.style.cssText = 'width:100%;background:rgba(38,38,38,0.8);border:2px solid #525252;color:#f5f5f5;font-size:1.125rem;border-radius:0.75rem;padding:1rem 1.25rem;text-align:center;font-weight:500;cursor:pointer;outline:none;transition:border-color 0.3s,box-shadow 0.3s;color-scheme:dark;box-sizing:border-box;';
            input.addEventListener('focus', function() { this.style.borderColor = '#06b6d4'; this.style.boxShadow = '0 0 0 3px rgba(6,182,212,0.2)'; });
            input.addEventListener('blur', function() { this.style.borderColor = '#525252'; this.style.boxShadow = 'none'; });
            input.addEventListener('change', function() {
                const btn = document.getElementById('dateModalConfirm');
                if (btn) { btn.disabled = !this.value; btn.style.opacity = this.value ? '1' : '0.4'; btn.style.cursor = this.value ? 'pointer' : 'not-allowed'; }
            });
            inputWrap.appendChild(input);
            inner.appendChild(inputWrap);

            // Confirm button
            const confirmBtn = document.createElement('button');
            confirmBtn.id = 'dateModalConfirm';
            confirmBtn.disabled = true;
            confirmBtn.style.cssText = 'width:100%;padding:0.875rem 1.5rem;background:linear-gradient(90deg,#0891b2,#2563eb);color:#fff;font-weight:600;font-size:1rem;border:none;border-radius:0.75rem;cursor:not-allowed;opacity:0.4;transition:all 0.3s;transform:scale(1);';
            confirmBtn.innerHTML = '<span style="display:flex;align-items:center;justify-content:center;gap:0.5rem;"><svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Confirmar</span>';
            confirmBtn.addEventListener('mouseenter', function() { if (!this.disabled) { this.style.background = 'linear-gradient(90deg,#06b6d4,#3b82f6)'; this.style.transform = 'scale(1.02)'; this.style.boxShadow = '0 10px 25px -5px rgba(6,182,212,0.25)'; }});
            confirmBtn.addEventListener('mouseleave', function() { this.style.background = 'linear-gradient(90deg,#0891b2,#2563eb)'; this.style.transform = 'scale(1)'; this.style.boxShadow = 'none'; });
            confirmBtn.addEventListener('mousedown', function() { if (!this.disabled) this.style.transform = 'scale(0.98)'; });
            confirmBtn.addEventListener('mouseup', function() { if (!this.disabled) this.style.transform = 'scale(1.02)'; });
            confirmBtn.addEventListener('click', confirmDateModal);
            inner.appendChild(confirmBtn);

            card.appendChild(inner);
            cardWrap.appendChild(card);
            overlay.appendChild(cardWrap);
            document.body.appendChild(overlay);

            // Pulse keyframe
            if (!document.getElementById('dateModalKeyframes')) {
                const style = document.createElement('style');
                style.id = 'dateModalKeyframes';
                style.textContent = '@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}';
                document.head.appendChild(style);
            }

            _dateModalEl = overlay;
            return overlay;
        }

        function showDateModal(mandatory) {
            if (typeof mandatory === 'undefined') mandatory = !localStorage.getItem('selectedDate');
            _dateModalMandatory = mandatory;

            const overlay = _createDateModal();
            const backdrop = document.getElementById('dateModalBackdrop');
            const card = document.getElementById('dateModalCard');
            const glow = document.getElementById('dateModalGlow');
            const input = document.getElementById('modalDateInput');
            const confirmBtn = document.getElementById('dateModalConfirm');

            // Pre-fill
            const current = localStorage.getItem('selectedDate');
            if (current) input.value = current;
            confirmBtn.disabled = !input.value;
            confirmBtn.style.opacity = input.value ? '1' : '0.4';
            confirmBtn.style.cursor = input.value ? 'pointer' : 'not-allowed';

            overlay.style.display = 'flex';

            // Backdrop click
            if (mandatory) {
                backdrop.style.cursor = 'default';
                backdrop.onclick = null;
            } else {
                backdrop.style.cursor = 'pointer';
                backdrop.onclick = function() { hideDateModal(); };
            }

            // Escape key
            overlay._escHandler = function(e) {
                if (e.key === 'Escape' && !_dateModalMandatory) hideDateModal();
            };
            document.addEventListener('keydown', overlay._escHandler);

            // Animate in
            requestAnimationFrame(function() {
                backdrop.style.opacity = '1';
                card.style.opacity = '1';
                card.style.transform = 'scale(1) translateY(0)';
                setTimeout(function() { glow.style.opacity = '1'; }, 300);
            });
        }

        function hideDateModal() {
            if (_dateModalMandatory && !localStorage.getItem('selectedDate')) return;

            const overlay = document.getElementById('dateModalOverlay');
            if (!overlay || overlay.style.display === 'none') return;
            const backdrop = document.getElementById('dateModalBackdrop');
            const card = document.getElementById('dateModalCard');
            const glow = document.getElementById('dateModalGlow');

            glow.style.opacity = '0';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.85) translateY(30px)';
            backdrop.style.opacity = '0';

            if (overlay._escHandler) {
                document.removeEventListener('keydown', overlay._escHandler);
                overlay._escHandler = null;
            }

            _dateModalMandatory = false;
            setTimeout(function() { overlay.style.display = 'none'; }, 500);
        }

        function confirmDateModal() {
            const input = document.getElementById('modalDateInput');
            const date = input.value;
            if (!date) return;

            localStorage.setItem('selectedDate', date);
            updateChangeDateBtn(date);

            const urlParams = new URLSearchParams(window.location.search);
            const autoLoad = urlParams.get('load');

            hideDateModal();

            if (autoLoad) {
                history.replaceState({ url: '/' + autoLoad }, '', '/' + autoLoad);
                loadPage('/' + autoLoad);
            } else {
                const currentUrl = window.location.pathname;
                loadPage(currentUrl, false);
            }
        }

        // ── Change date button in header ──
        function updateChangeDateBtn(date) {
            const btn = document.getElementById('changeDateBtn');
            const text = document.getElementById('changeDateBtnText');
            if (!btn || !text) return;
            if (date) {
                const d = new Date(date + 'T00:00:00');
                const months = ['gen','feb','mar','abr','mai','jun','jul','ago','set','oct','nov','des'];
                text.textContent = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                btn.style.display = 'flex';
            } else {
                btn.style.display = 'none';
            }
        }

        // ── Init ──
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContainer = document.getElementById('main-container');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const menuIcon = document.getElementById('menu-icon');
            const sidebarExpanded = localStorage.getItem('sidebarExpanded') === 'true';

            if (sidebarExpanded) {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                mainContainer.classList.remove('ml-20');
                mainContainer.classList.add('ml-64');
                sidebarTexts.forEach(function(t) { t.classList.remove('opacity-0'); t.classList.add('opacity-100'); });
                menuIcon.style.transform = 'rotate(90deg)';
            }

            sidebar.classList.remove('pre-render');
            mainContainer.classList.remove('pre-render');

            setupAjaxNavigation();

            const savedDate = localStorage.getItem('selectedDate');
            updateChangeDateBtn(savedDate);

            const urlParams = new URLSearchParams(window.location.search);
            const autoLoad = urlParams.get('load');

            if (!savedDate) {
                showDateModal(true);
            } else if (autoLoad) {
                history.replaceState({ url: '/' + autoLoad }, '', '/' + autoLoad);
                loadPage('/' + autoLoad);
            }
        });

        // ── AJAX Navigation ──
        function setupAjaxNavigation() {
            document.querySelectorAll('nav a[href]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!localStorage.getItem('selectedDate')) { showDateModal(true); return; }
                    loadPage(this.getAttribute('href'));
                });
            });

            window.addEventListener('popstate', function(e) {
                if (e.state && e.state.url) loadPage(e.state.url, false);
            });

            history.replaceState({ url: window.location.pathname }, '', window.location.pathname);
        }

        function loadPage(url, updateHistory) {
            if (typeof updateHistory === 'undefined') updateHistory = true;
            var contentArea = document.querySelector('#main-container .flex-1');

            contentArea.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;min-height:100vh;"><div style="text-align:center;"><div style="display:inline-block;animation:spin 1s linear infinite;border-radius:9999px;height:3rem;width:3rem;border-bottom:2px solid #22d3ee;"></div><p style="margin-top:1rem;color:#a3a3a3;">Carregant...</p></div></div>';

            var globalDate = localStorage.getItem('selectedDate');
            if (globalDate && url !== '/') {
                var cleanUrl = url.replace(/([?&])date=[^&]*(&|$)/, '$1').replace(/[?&]$/, '');
                url = cleanUrl + (cleanUrl.includes('?') ? '&' : '?') + 'date=' + globalDate;
            }

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function(r) { if (!r.ok) throw new Error('HTTP error! status: ' + r.status); return r.json(); })
            .then(function(data) {
                if (data.error) throw new Error(data.error);
                document.querySelector('header h1').textContent = data.title;
                document.title = 'Cronos - ' + data.title.replace('Cronos TDR - ', '').replace('Dades de ', '').replace("Dades d'", '');
                contentArea.innerHTML = data.content;
                executeScripts(contentArea);
                setupPaginationLinks(contentArea);
                updateActiveLink(url);
                if (updateHistory) { var cleanPath = url.split('?')[0]; history.pushState({ url: cleanPath }, '', cleanPath); }
                window.scrollTo(0, 0);
            })
            .catch(function(error) {
                console.error('Error loading page:', error);
                contentArea.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;min-height:100vh;"><div style="text-align:center;background:rgba(127,29,29,0.2);border:1px solid rgba(185,28,28,0.5);border-radius:0.5rem;padding:2rem;max-width:28rem;"><svg style="height:3rem;width:3rem;color:#f87171;margin:0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><h3 style="font-size:1.125rem;font-weight:600;color:#fff;margin-bottom:0.5rem;">Error carregant la p\u00e0gina</h3><p style="color:#d4d4d4;margin-bottom:1rem;">' + error.message + '</p><button onclick="location.reload()" style="padding:0.5rem 1rem;background:#0891b2;color:#fff;border:none;border-radius:0.5rem;cursor:pointer;">Recarregar p\u00e0gina</button></div></div>';
            });
        }

        function executeScripts(container) {
            container.querySelectorAll('script').forEach(function(old) {
                var s = document.createElement('script');
                Array.from(old.attributes).forEach(function(a) { s.setAttribute(a.name, a.value); });
                s.textContent = old.textContent;
                old.parentNode.replaceChild(s, old);
            });
        }

        function updateActiveLink(url) {
            var urlPath = url.split('?')[0];
            document.querySelectorAll('nav a[href]').forEach(function(link) {
                var href = link.getAttribute('href');
                var svg = link.querySelector('svg');
                if (href === urlPath) {
                    link.classList.add('bg-neutral-800', 'text-white', 'border-cyan-500/50');
                    link.classList.remove('text-neutral-300', 'border-transparent');
                    if (svg) { svg.classList.add('text-cyan-300'); svg.classList.remove('text-cyan-400', 'group-hover:text-cyan-300'); }
                } else {
                    link.classList.remove('bg-neutral-800', 'border-cyan-500/50');
                    link.classList.add('text-neutral-300', 'border-transparent');
                    if (svg) { svg.classList.remove('text-cyan-300'); svg.classList.add('text-cyan-400', 'group-hover:text-cyan-300'); }
                }
            });
        }

        function setupPaginationLinks(container) {
            container.querySelectorAll('a[href*="page="]').forEach(function(link) {
                link.addEventListener('click', function(e) { e.preventDefault(); loadPage(this.getAttribute('href')); });
            });
        }

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            var mainContainer = document.getElementById('main-container');
            var sidebarTexts = document.querySelectorAll('.sidebar-text');
            var menuIcon = document.getElementById('menu-icon');

            if (sidebar.classList.contains('w-20')) {
                sidebar.classList.remove('w-20'); sidebar.classList.add('w-64');
                mainContainer.classList.remove('ml-20'); mainContainer.classList.add('ml-64');
                sidebarTexts.forEach(function(t) { t.classList.remove('opacity-0'); t.classList.add('opacity-100'); });
                menuIcon.style.transform = 'rotate(90deg)';
                localStorage.setItem('sidebarExpanded', 'true');
            } else {
                sidebar.classList.remove('w-64'); sidebar.classList.add('w-20');
                mainContainer.classList.remove('ml-64'); mainContainer.classList.add('ml-20');
                sidebarTexts.forEach(function(t) { t.classList.remove('opacity-100'); t.classList.add('opacity-0'); });
                menuIcon.style.transform = 'rotate(0deg)';
                localStorage.setItem('sidebarExpanded', 'false');
            }
        }
    </script>
</body>
</html>
