@extends('layouts.default')

@section('header')
    ยินดีต้อนรับ
@endsection

@section('content')
    <a href="{{ route('manage.dashboard') }}" class="fixed top-4 right-4 btn btn-primary btn-circle w-14 h-14 shadow-lg z-50">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-gear"
            viewBox="0 0 16 16">
            <path
                d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
        </svg>
    </a>

    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white">
        <!-- Header Section -->
        <div class="container mx-auto px-8 py-12">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-full mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1
                    class="text-5xl font-extrabold mb-4 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                    เลือก Smart TV
                </h1>
                <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                    เลือก Smart TV ที่ต้องการดูเนื้อหา โดยใช้รีโมทคอนโทรลของคุณ
                </p>
            </div>

            @if ($smartTvs->isEmpty())
                <div class="max-w-md mx-auto">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-8 text-center shadow-2xl">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">ไม่พบรายการ Smart TV</h3>
                        <p class="text-white/90">ไม่มีรายการ Smart TV ให้เลือกในระบบ</p>
                    </div>
                </div>
            @else
                <div class="flex flex-row gap-3 justify-center items-center mb-8">
                    <!-- Sort Controls -->
                    <div class="flex justify-center">
                        <div class="bg-slate-800/30 backdrop-blur-sm rounded-2xl p-3 border border-slate-700">
                            <div class="flex items-center space-x-4">
                                <span class="text-slate-300 font-medium">เรียงลำดับ:</span>
                                <select id="sortSelect"
                                    class="bg-slate-700 text-white rounded-lg px-4 py-2 border border-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all duration-200">
                                    <option value="name_asc">ชื่อ A-Z</option>
                                    <option value="name_desc">ชื่อ Z-A</option>
                                    <option value="id_asc">ID น้อยไปมาก</option>
                                    <option value="id_desc">ID มากไปน้อย</option>
                                    <option value="created_new">ใหม่ล่าสุด</option>
                                    <option value="created_old">เก่าที่สุด</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions for Remote Control -->
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center space-x-8 bg-slate-800/30 backdrop-blur-sm rounded-2xl px-8 py-4 border border-slate-700">
                            <div class="flex items-center space-x-2 text-slate-300">
                                <div class="w-8 h-8 bg-slate-600 rounded-lg flex items-center justify-center">
                                    <span class="text-xs font-bold">↑↓</span>
                                </div>
                                <span>เลือก TV</span>
                            </div>
                            <div class="flex items-center space-x-2 text-slate-300">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span class="text-xs font-bold">OK</span>
                                </div>
                                <span>เข้าดูเนื้อหา</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TV Grid -->
                <div id="tvGrid"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
                    @foreach ($smartTvs->sortBy('name') as $index => $tv)
                        <div class="tv-card group cursor-pointer transform transition-all duration-300 hover:scale-105 focus:scale-105 focus:outline-none"
                            tabindex="{{ $index + 1 }}" data-tv-id="{{ $tv->id }}"
                            data-tv-name="{{ strtolower($tv->name) }}" data-created="{{ $tv->created_at }}"
                            onclick="selectTV({{ $tv->id }})" onkeydown="handleKeyPress(event, {{ $tv->id }})">

                            <!-- Card Background with Gradient Border -->
                            <div
                                class="relative h-full bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl overflow-hidden shadow-2xl group-hover:shadow-blue-500/25 group-focus:shadow-blue-500/50 transition-all duration-300">
                                <!-- Gradient Border Effect -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-3xl opacity-0 group-hover:opacity-75 group-focus:opacity-100 transition-opacity duration-300 blur-sm">
                                </div>
                                <div class="absolute inset-[2px] bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl">
                                </div>

                                <!-- Card Content -->
                                <div class="relative h-full p-8 flex flex-col text-center">
                                    <!-- Content Area -->
                                    <div class="flex-1 flex flex-col justify-center">
                                        <!-- TV Icon -->
                                        <div
                                            class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-6 shadow-lg group-hover:shadow-blue-500/50 transition-all duration-300 group-hover:scale-110 mx-auto">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>

                                        <!-- TV Name -->
                                        <h3
                                            class="text-2xl font-bold mb-3 text-white group-hover:text-blue-300 transition-colors duration-300">
                                            {{ $tv->name }}
                                        </h3>

                                        <!-- TV Details -->
                                        <div class="space-y-2 mb-6">
                                            <div
                                                class="inline-flex items-center px-3 py-1 bg-slate-700/50 rounded-full text-sm text-slate-300">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                                ID: {{ $tv->id }}
                                            </div>
                                            @if ($tv->created_at)
                                                <div class="text-xs text-slate-400">
                                                    เพิ่มเมื่อ: {{ $tv->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Action Button - Pinned to Bottom -->
                                    <div class="mt-auto">
                                        <button
                                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-blue-500/50 group-hover:shadow-xl transform group-hover:-translate-y-1">
                                            <span class="flex items-center justify-center space-x-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-7 4h12l-2 5H9l-2-5z" />
                                                </svg>
                                                <span>เลือกดูเนื้อหา</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Focus Ring -->
                                <div
                                    class="absolute inset-0 rounded-3xl ring-4 ring-blue-500 opacity-0 group-focus:opacity-100 transition-opacity duration-200">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        // TV Selection and Navigation
        function selectTV(tvId) {
            // Add loading effect
            const card = document.querySelector(`[data-tv-id="${tvId}"]`);
            const button = card.querySelector('button');
            const originalText = button.innerHTML;

            button.innerHTML = `
        <div class="flex items-center justify-center space-x-2">
            <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            <span>กำลังโหลด...</span>
        </div>
    `;

            // Navigate after short delay for loading effect
            setTimeout(() => {
                window.location.href = `{{ url('/') }}?id=${tvId}`;
            }, 500);
        }

        // Keyboard Navigation for Remote Control
        function handleKeyPress(event, tvId) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                selectTV(tvId);
            }
        }

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(event) {
            const cards = document.querySelectorAll('.tv-card');
            const currentFocus = document.activeElement;
            const currentIndex = Array.from(cards).indexOf(currentFocus);

            let nextIndex = -1;

            switch (event.key) {
                case 'ArrowDown':
                case 'ArrowRight':
                    event.preventDefault();
                    nextIndex = currentIndex < cards.length - 1 ? currentIndex + 1 : 0;
                    break;
                case 'ArrowUp':
                case 'ArrowLeft':
                    event.preventDefault();
                    nextIndex = currentIndex > 0 ? currentIndex - 1 : cards.length - 1;
                    break;
            }

            if (nextIndex >= 0 && cards[nextIndex]) {
                cards[nextIndex].focus();
            }
        });

        // Sort Functionality
        document.getElementById('sortSelect')?.addEventListener('change', function() {
            const sortType = this.value;
            const grid = document.getElementById('tvGrid');
            const cards = Array.from(grid.children);

            cards.sort((a, b) => {
                const aId = parseInt(a.dataset.tvId);
                const bId = parseInt(b.dataset.tvId);
                const aName = a.dataset.tvName;
                const bName = b.dataset.tvName;
                const aCreated = new Date(a.dataset.created);
                const bCreated = new Date(b.dataset.created);

                switch (sortType) {
                    case 'name_asc':
                        return aName.localeCompare(bName, 'th');
                    case 'name_desc':
                        return bName.localeCompare(aName, 'th');
                    case 'id_asc':
                        return aId - bId;
                    case 'id_desc':
                        return bId - aId;
                    case 'created_new':
                        return bCreated - aCreated;
                    case 'created_old':
                        return aCreated - bCreated;
                    default:
                        return 0;
                }
            });

            // Re-append sorted cards
            cards.forEach((card, index) => {
                card.setAttribute('tabindex', index + 1);
                grid.appendChild(card);
            });
        });

        // Auto-focus first card on page load
        document.addEventListener('DOMContentLoaded', function() {
            const firstCard = document.querySelector('.tv-card');
            if (firstCard) {
                firstCard.focus();
            }
        });

        // Responsive grid adjustment
        function adjustGrid() {
            const cards = document.querySelectorAll('.tv-card');
            const screenWidth = window.innerWidth;

            // Add responsive focus management
            cards.forEach(card => {
                card.addEventListener('focus', function() {
                    this.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                        inline: 'center'
                    });
                });
            });
        }

        window.addEventListener('resize', adjustGrid);
        adjustGrid();
    </script>

    <style>
        /* Custom animations and effects */
        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }

            50% {
                box-shadow: 0 0 0 20px rgba(59, 130, 246, 0);
            }
        }

        .tv-card:focus {
            animation: pulse-glow 2s infinite;
        }

        /* Smooth transitions for all interactive elements */
        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Better focus visibility for TV remote */
        .tv-card:focus {
            transform: scale(1.05) translateY(-4px);
        }

        /* Loading animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Gradient text effect */
        .bg-clip-text {
            -webkit-background-clip: text;
            background-clip: text;
        }
    </style>
@endsection
