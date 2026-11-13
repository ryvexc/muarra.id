<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Muarra Back Office</title>
        
        <link rel="stylesheet" href="{{ asset('assets/css/muarra-dark.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/core.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.css">

        @yield('css')
    </head>

    <body>
        <div class="layout">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            @include("layout.admin.sidebar")
        
            @yield("content")
        </div>

        <script>
            const openButtons = document.querySelectorAll(".open-modal");

            openButtons.forEach(btn=>{
                btn.addEventListener("click",()=>{
                    const modal=document.getElementById(btn.dataset.modal);
                    modal.classList.add("active");
                });
            });

            document.querySelectorAll(".modal-close, .close-modal").forEach(btn => {
                btn.addEventListener("click", () => {
                    btn.closest(".modal").classList.remove("active");
                });
            });

            document.querySelectorAll(".modal").forEach(modal=>{
                modal.addEventListener("click",(e)=>{
                    if(e.target===modal){
                        modal.classList.remove("active");
                    }
                });
            });

            document.addEventListener("keydown",(e)=>{
                if(e.key==="Escape"){
                    document.querySelectorAll(".modal.active").forEach(m=>{
                        m.classList.remove("active");
                    });
                }
            });
        </script>

        <script>
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("sidebarOverlay");
            const toggle = document.getElementById("sidebarToggle");

            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("open");
                overlay.classList.toggle("active");
            });

            overlay.addEventListener("click", () => {
                sidebar.classList.remove("open");
                overlay.classList.remove("active");
            });

            window.addEventListener("resize", () => {
                if(window.innerWidth > 768){
                    sidebar.classList.remove("open");
                    overlay.classList.remove("active");
                }
            });

            const sidebarMenu = document.getElementById('sidebar-menu');

            if (sidebarMenu) {
                const storageKey = 'sidebar-menu-scroll';

                sidebarMenu.addEventListener('scroll', () => {
                    sessionStorage.setItem(storageKey, sidebarMenu.scrollTop);
                });

                window.addEventListener('load', () => {
                    const savedPosition = sessionStorage.getItem(storageKey);

                    if (savedPosition !== null) {
                        sidebarMenu.scrollTop = Number(savedPosition);
                    }
                });
            }
        </script>

        <script>
            function filter(element) {
                const url = new URL(window.location.href);

                const name = element.name;
                const value = element.value;

                if (value) {
                    url.searchParams.set(name, value);
                } else {
                    url.searchParams.delete(name);
                }

                url.searchParams.delete('page');

                window.location.href = url.toString();
            }
        </script>

        {{-- <script>
            const searchInput = document.getElementById('querySearch');
            const suggestions = document.getElementById('searchSuggestions');
            const preview = document.getElementById('searchPreview');

            const searchFields = {
                cust_name: {
                    label: 'Customer Name',
                    description: 'Search by customer name',
                    values: []
                },

                payment_type: {
                    label: 'Payment Type',
                    description: 'Search by payment method',
                    values: ['cash', 'transfer', 'qris']
                },

                status: {
                    label: 'Order Status',
                    description: 'Search by order status',
                    values: ['pending', 'processing', 'completed', 'cancelled']
                },

                total: {
                    label: 'Total',
                    description: 'Filter by order total',
                    values: []
                }
            };

            let activeIndex = -1;
            let currentSuggestions = [];

            function getCurrentToken() {
                return searchInput.value.split(/\s+/).pop() || '';
            }

            function replaceCurrentToken(value) {
                const tokens = searchInput.value.split(/\s+/);
                tokens[tokens.length - 1] = value;

                searchInput.value = tokens.join(' ') + ' ';
                searchInput.focus();

                renderSuggestions();
                renderPreview();
            }

            function renderSuggestions() {
                const token = getCurrentToken();
                const separator = token.indexOf(':');

                let items = [];

                if (separator === -1) {
                    items = Object.entries(searchFields)
                        .filter(([key]) => key.startsWith(token))
                        .map(([key, field]) => ({
                            value: key + ':',
                            label: key,
                            description: field.description
                        }));
                } else {
                    const key = token.substring(0, separator);
                    const value = token.substring(separator + 1);

                    const field = searchFields[key];

                    if (field && field.values.length) {
                        items = field.values
                            .filter(item => item.startsWith(value))
                            .map(item => ({
                                value: key + ':' + item,
                                label: item,
                                description: field.label
                            }));
                    }
                }

                currentSuggestions = items;
                activeIndex = -1;

                suggestions.innerHTML = '';

                if (!items.length) {
                    suggestions.classList.remove('show');
                    return;
                }

                items.forEach((item, index) => {
                    const button = document.createElement('button');

                    button.type = 'button';
                    button.className = 'search-suggestion';

                    button.innerHTML = `
                        <div>
                            <strong>${item.label}</strong>
                            <div class="suggestion-description">
                                ${item.description}
                            </div>
                        </div>
                    `;

                    button.addEventListener('mousedown', e => {
                        e.preventDefault();
                        replaceCurrentToken(item.value);
                    });

                    suggestions.appendChild(button);
                });

                suggestions.classList.add('show');
            }

            function renderPreview() {
                const matches = searchInput.value.match(
                    /[a-zA-Z_][a-zA-Z0-9_]*:"[^"]+"|[a-zA-Z_][a-zA-Z0-9_]*:\S+/g
                ) || [];

                preview.innerHTML = '';

                matches.forEach(match => {
                    const chip = document.createElement('span');
                    chip.className = 'search-chip';
                    chip.textContent = match;

                    preview.appendChild(chip);
                });
            }

            searchInput.addEventListener('input', () => {
                renderSuggestions();
                renderPreview();
            });

            searchInput.addEventListener('keydown', e => {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = Math.min(
                        activeIndex + 1,
                        currentSuggestions.length - 1
                    );
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = Math.max(activeIndex - 1, 0);
                }

                if (e.key === 'Enter' && activeIndex >= 0) {
                    e.preventDefault();
                    replaceCurrentToken(currentSuggestions[activeIndex].value);
                }

                if (e.key === 'Escape') {
                    suggestions.classList.remove('show');
                }

                document.querySelectorAll('.search-suggestion')
                    .forEach((item, index) => {
                        item.classList.toggle('active', index === activeIndex);
                    });
            });

            document.getElementById('clearSearch').addEventListener('click', () => {
                searchInput.value = '';
                renderPreview();
                renderSuggestions();
                searchInput.focus();
            });

            searchInput.addEventListener('blur', () => {
                setTimeout(() => {
                    suggestions.classList.remove('show');
                }, 150);
            });
        </script> --}}

        @yield("script")
    </body>
</html>