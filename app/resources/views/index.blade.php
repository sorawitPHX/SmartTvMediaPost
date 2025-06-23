<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Hospital Info Display</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Kanit';
        }

        /* Custom CSS for fade-in effect and pulsating text */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes pulseText {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.03);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 0.8;
            }
        }

        .fade-in-overlay {
            animation: fadeIn 1s ease-out forwards;
        }

        .pulsate-text {
            animation: pulseText 5s ease-in-out infinite;
        }
    </style>
</head>

<body class="text-white h-screen w-screen overflow-hidden m-0 p-0 flex items-center justify-center">
    <div class="absolute top-2 right-2 z-10">
        <button id="toggleFullScreen" onclick="initAppAndFullscreen()" class="btn btn-sm btn-primary hidden">เริ่มต้น /
            Fullscreen</button>
    </div>

    <div id="slide-container" class="carousel w-full h-full relative">
        <div id="initialOverlay"
            class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-700 flex flex-col items-center justify-center text-center p-8 z-20 cursor-pointer fade-in-overlay">
            <h1 class="text-5xl font-bold mb-6 text-success pulsate-text drop-shadow-lg">
                แตะหน้าจอเพื่อเริ่มต้น
            </h1>
            <p class="text-xl text-white opacity-90 max-w-2xl">
                กรุณาแตะที่ใดก็ได้บนจอ เพื่อเริ่มการแสดงผลข้อมูลประชาสัมพันธ์
            </p>
        </div>
    </div>

    <script>
        const defaultDuration = 5000;
        const fetchDuration = 5000;
        let currentSlideIndex = 0;
        let slides = [];
        let slideElements = [];
        let currentDataHash = "";
        let slideTimer = null;
        let appInitialized = false;

        const slideContainer = document.getElementById("slide-container");
        const initialOverlay = document.getElementById("initialOverlay");

        function hashPosts(posts) {
            return JSON.stringify(posts);
        }

        function videoEndedHandler() {
            this.removeEventListener("ended", videoEndedHandler);
            nextSlide();
        }

        function renderSlides(posts) {
            clearTimeout(slideTimer);
            slideContainer.innerHTML = "";
            slides = posts;
            slideElements = [];

            if (slides.length === 0) {
                console.warn("No slides to display.");
                slideContainer.innerHTML = `
                    <div class="flex items-center justify-center w-full h-full text-2xl text-white">
                        <span class="loading loading-spinner loading-lg mr-4"></span>
                        ไม่มีข้อมูลที่จะแสดงผลในขณะนี้
                    </div>`;
                return;
            }

            slides.forEach((post, index) => {
                const carouselItem = document.createElement("div");
                carouselItem.id = `slide-item-${index}`;
                carouselItem.classList.add("carousel-item", "relative", "w-full", "h-full");

                let contentEl;
                if (post.type === "image") {
                    contentEl = document.createElement("img");
                    contentEl.src = `{{ asset('/storage') }}/${post.filename}`;
                    contentEl.classList.add("w-full", "h-full", "object-contain");
                } else if (post.type === "video") {
                    contentEl = document.createElement("video");
                    contentEl.src = `{{ asset('/storage') }}/${post.filename}`;
                    contentEl.muted = post.is_mute;
                    contentEl.loop = false;
                    contentEl.playsInline = true;
                    contentEl.classList.add("w-full", "h-full", "object-contain");
                }
                contentEl.dataset.duration = post.duration * 1000 || defaultDuration;

                carouselItem.appendChild(contentEl);
                slideContainer.appendChild(carouselItem);
                slideElements.push(carouselItem);
            });

            currentSlideIndex = 0;
            showSlide(currentSlideIndex);
        }

        function showSlide(index) {
            if (slideElements.length === 0 || index < 0 || index >= slideElements.length) {
                console.error("Invalid slide index or no slides available.");
                return;
            }

            slideElements.forEach(item => {
                const video = item.querySelector('video');
                if (video) {
                    video.pause();
                    video.currentTime = 0;
                    video.removeEventListener("ended", videoEndedHandler);
                }
            });

            const currentSlideElement = slideElements[index];
            currentSlideElement.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'start'
            });

            const currentPost = slides[index];
            const duration = parseInt(currentPost.duration * 1000) || defaultDuration;

            if (slideTimer) clearTimeout(slideTimer);

            const videoEl = currentSlideElement.querySelector('video');

            if (currentPost.type === "video" && duration === -1000) {
                if (videoEl) {
                    videoEl.addEventListener("ended", videoEndedHandler);
                    videoEl.play().catch(err => {
                        console.warn("Video play error (duration -1000):", err);
                        nextSlide();
                    });
                } else {
                    nextSlide();
                }
            } else {
                if (videoEl) {
                    videoEl.play().catch(err => {
                        console.warn("Video play error (fixed duration):", err);
                    });
                }
                slideTimer = setTimeout(nextSlide, duration);
            }
        }

        function nextSlide() {
            if (slides.length === 0) return;
            currentSlideIndex = (currentSlideIndex + 1) % slides.length;
            showSlide(currentSlideIndex);
        }

        async function pollPosts() {
            try {
                const res = await fetch("{{ url('/api/posts') }}");
                const posts = await res.json();

                const newHash = hashPosts(posts);
                if (newHash !== currentDataHash) {
                    currentDataHash = newHash;
                    renderSlides(posts);
                    console.log("🔁 Slides updated");
                }
            } catch (e) {
                console.error("❌ Polling failed:", e);
                // Optionally show an error message on the screen
                slideContainer.innerHTML = `
                    <div class="flex items-center justify-center w-full h-full text-2xl text-white bg-error p-8">
                        <span class="loading loading-spinner loading-lg mr-4"></span>
                        เกิดข้อผิดพลาดในการโหลดข้อมูล. กรุณาตรวจสอบการเชื่อมต่อ.
                    </div>`;
            }
        }

        function openFullscreen() {
            const elem = document.documentElement;
            const button = document.getElementById('toggleFullScreen');
            if (document.fullscreenElement) {
                document.exitFullscreen();
                button.innerText = 'Fullscreen';
            } else {
                elem.requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
                button.innerText = 'Exit';
            }
        }

        function initApp() {
            if (appInitialized) return;

            appInitialized = true;
            // Add a fade-out animation before hiding
            initialOverlay.classList.remove('fade-in-overlay');
            initialOverlay.classList.add('animate-fadeOut'); // Assuming you have or add fadeOut animation
            setTimeout(() => {
                initialOverlay.style.display = 'none';
            }, 500); // Match fade-out duration

            pollPosts();
            setInterval(pollPosts, fetchDuration);
            console.log("App initialized and polling started.");
        }

        function initAppAndFullscreen() {
            initApp();
            openFullscreen();
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.body.addEventListener('click', initAppAndFullscreen);
        });

        window.openFullscreen = openFullscreen;
        window.initAppAndFullscreen = initAppAndFullscreen;
    </script>
</body>

</html>
