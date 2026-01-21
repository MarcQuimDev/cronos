<!DOCTYPE html>
<html lang="ca" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronos - TVOC</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11a4 4 0 104 4V6a2 2 0 10-4 0v5zm0 0a2 2 0 110 4m3-10h.01M12 2v1m0 3V5" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">Temperatura</span>
                    </a>

                    <a href="/humidity" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="Humitat">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">Humitat</span>
                    </a>

                    <a href="/pressure" class="flex items-center gap-3 px-3 py-3 rounded-xl text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all duration-300 group border border-transparent hover:border-cyan-500/50" title="Pressió">
                        <svg class="h-6 w-6 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </svg>
                        <span class="sidebar-text font-medium whitespace-nowrap opacity-0 transition-opacity duration-300">CO2</span>
                    </a>

                    <a href="/tvoc" class="flex items-center gap-3 px-3 py-3 rounded-xl bg-neutral-800 text-white transition-all duration-300 group border border-cyan-500/50" title="TVOC">
                        <svg class="h-6 w-6 text-cyan-300 flex-shrink-0 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-white">Dades de TVOC</h1>
                </div>
            </header>

            <!-- Main Content -->
            <div class="flex-1">
                @include('partials.tvoc-content')
            </div>
    </div>

    <script>
        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContainer = document.getElementById('main-container');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const menuIcon = document.getElementById('menu-icon');
            const sidebarExpanded = localStorage.getItem('sidebarExpanded') === 'true';

            if (sidebarExpanded) {
                // Expand sidebar
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                mainContainer.classList.remove('ml-20');
                mainContainer.classList.add('ml-64');

                // Show text labels
                sidebarTexts.forEach(text => {
                    text.classList.remove('opacity-0');
                    text.classList.add('opacity-100');
                });

                menuIcon.style.transform = 'rotate(90deg)';
            }

            // Remove pre-render class to enable transitions
            sidebar.classList.remove('pre-render');
            mainContainer.classList.remove('pre-render');

            // Setup AJAX navigation
            setupAjaxNavigation();
        });

        // AJAX Navigation System
        function setupAjaxNavigation() {
            const navLinks = document.querySelectorAll('nav a[href]');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    loadPage(url);
                });
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(e) {
                if (e.state && e.state.url) {
                    loadPage(e.state.url, false);
                }
            });

            // Save current state
            history.replaceState({ url: window.location.pathname }, '', window.location.pathname);
        }

        function loadPage(url, updateHistory = true) {
            // Show loading state
            const contentArea = document.querySelector('#main-container .flex-1');

            // Fetch content via AJAX
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update page title
                document.querySelector('header h1').textContent = data.title;
                document.title = 'Cronos - ' + data.title.replace('Cronos TDR - ', '').replace('Dades de ', '').replace('Dades d\'', '');

                // Replace content
                contentArea.innerHTML = data.content;

                // Execute scripts in the loaded content
                executeScripts(contentArea);

                // Update active state in sidebar
                updateActiveLink(url);

                // Update browser history
                if (updateHistory) {
                    history.pushState({ url: url }, '', url);
                }

                // Scroll to top
                window.scrollTo(0, 0);
            })
            .catch(error => {
                console.error('Error loading page:', error);
            });
        }

        function executeScripts(container) {
            // Find all script tags in the loaded content
            const scripts = container.querySelectorAll('script');

            scripts.forEach(oldScript => {
                // Create a new script element
                const newScript = document.createElement('script');

                // Copy attributes
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });

                // Copy script content
                newScript.textContent = oldScript.textContent;

                // Replace old script with new one (this executes it)
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
        }

        function updateActiveLink(url) {
            const navLinks = document.querySelectorAll('nav a[href]');

            navLinks.forEach(link => {
                const linkUrl = link.getAttribute('href');
                if (linkUrl === url) {
                    // Add active classes
                    link.classList.add('bg-neutral-800', 'text-white', 'border-cyan-500/50');
                    link.classList.remove('text-neutral-300', 'border-transparent');
                    // Update icon color
                    const svg = link.querySelector('svg');
                    if (svg) {
                        svg.classList.add('text-cyan-300');
                        svg.classList.remove('text-cyan-400', 'group-hover:text-cyan-300');
                    }
                } else {
                    // Remove active classes
                    link.classList.remove('bg-neutral-800', 'border-cyan-500/50');
                    link.classList.add('text-neutral-300', 'border-transparent');
                    // Reset icon color
                    const svg = link.querySelector('svg');
                    if (svg) {
                        svg.classList.remove('text-cyan-300');
                        svg.classList.add('text-cyan-400', 'group-hover:text-cyan-300');
                    }
                }
            });
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContainer = document.getElementById('main-container');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const menuIcon = document.getElementById('menu-icon');

            // Check if sidebar is currently collapsed (w-20)
            if (sidebar.classList.contains('w-20')) {
                // Expand sidebar
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                mainContainer.classList.remove('ml-20');
                mainContainer.classList.add('ml-64');

                // Show text labels
                sidebarTexts.forEach(text => {
                    text.classList.remove('opacity-0');
                    text.classList.add('opacity-100');
                });

                menuIcon.style.transform = 'rotate(90deg)';

                // Save expanded state to localStorage
                localStorage.setItem('sidebarExpanded', 'true');
            } else {
                // Collapse sidebar
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                mainContainer.classList.remove('ml-64');
                mainContainer.classList.add('ml-20');

                // Hide text labels
                sidebarTexts.forEach(text => {
                    text.classList.remove('opacity-100');
                    text.classList.add('opacity-0');
                });

                menuIcon.style.transform = 'rotate(0deg)';

                // Save collapsed state to localStorage
                localStorage.setItem('sidebarExpanded', 'false');
            }
        }
    </script>
</body>
</html>
