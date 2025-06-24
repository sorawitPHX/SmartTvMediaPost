<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>
        @yield('header')
    </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @font-face {
            font-family: 'Kanit';
            font-weight: 100;
            src: url('{{ asset('fonts/Kanit/Kanit-Thin.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Kanit';
            font-weight: 200;
            src: url('{{ asset('fonts/Kanit/Kanit-ExtraLight.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Kanit';
            font-weight: 300;
            src: url('{{ asset('fonts/Kanit/Kanit-Light.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Kanit';
            font-weight: 400;
            src: url('{{ asset('fonts/Kanit/Kanit-Regular.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Kanit';
            font-weight: 500;
            src: url('{{ asset('fonts/Kanit/Kanit-Medium.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Kanit';
            font-weight: 600;
            src: url('{{ asset('fonts/Kanit/Kanit-SemiBold.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Kanit';
            font-weight: 700;
            src: url('{{ asset('fonts/Kanit/Kanit-Bold.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Kanit';
            font-weight: 800;
            src: url('{{ asset('fonts/Kanit/Kanit-ExtraBold.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Kanit';
            font-weight: 900;
            src: url('{{ asset('fonts/Kanit/Kanit-Black.ttf') }}') format('truetype');
        }

        * {
            font-family: 'Kanit', sans-serif;
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

<body class="">
    @yield('content')
</body>

</html>
