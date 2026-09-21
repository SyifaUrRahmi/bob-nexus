<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Battle of Brain</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    {{-- Bootrtrap Icon CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="assets/styles/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.css"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        :root {
            --primary-color: #C33F0E;
            --secondary-color: #FDDA09;
        }

        body {
            font-family: 'Poppins', sans-serif !important;
            /* width: 100vh; */
        }

        #rotateWarning {
            display: flex;
            flex-direction: row;
        }

        @media (max-width: 768px) {
            #rotateWarning {
                padding: 15px;
                flex-direction: column;
            }

            .score {
                margin-top: 10px;
            }
        }

        #splash-screen {
            position: fixed;
            width: 100%;
            height: 100vh;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            /* z-index: 9999; */
        }

        .logo-container {
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        $showSplash = Route::currentRouteNamed('entry.form') || Route::currentRouteNamed('login');
    @endphp

    @if ($showSplash)
        <div id="splash-screen">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="500">
                <h3>Battle of Brain</h3>
            </div>
        </div>
    @endif


    {{-- Content section --}}
    @yield('container')

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    {{-- Bootstrap Javascript CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    @if ($showSplash)
        <script>
            window.addEventListener("load", function() {
                setTimeout(function() {
                    const splash = document.getElementById("splash-screen");
                    splash.style.opacity = "0";
                    splash.style.transition = "opacity 1s ease";

                    setTimeout(() => {
                        splash.style.display = "none";
                    }, 1000);
                }, 1000);
            });
        </script>
    @endif

</body>

</html>
