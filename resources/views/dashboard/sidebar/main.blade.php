<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Gamification App</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    {{-- Bootrtrap Icon CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="assets/styles/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        :root {
            /* --primary-color: #C33F0E; */
            --primary-color: #5a70f9;
            --secondary-color: #FDDA09;
        }

        body {
            font-family: 'Poppins', sans-serif !important;
        }

        #sidebar-wrapper {
            width: 400px;
            display: block;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }

        .students-list {
            width: 400px;
            left: 0;
        }

        .sidebar-list:hover,
        .sidebar-list.active {
            background-color: var(--secondary-color);
            color: white;
        }

        .sidebar-list:hover i {
            color: white;
        }

        .nav {
            --bs-nav-link-color: #000000;
            --bs-nav-link-hover-color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        .page-link {
            color: var(--primary-color);
        }

        .pagination {
            --bs-pagination-hover-color: var(--primary-color);
            --bs-pagination-focus-color: var(--primary-color);
            --bs-pagination-focus-box-shadow: var(--primary-color);
            --bs-pagination-active-bg: var(--primary-color);
            --bs-pagination-active-border-color: var(--primary-color);

        }

        .btn-link {
            --bs-btn-color: #fff;
            --bs-btn-bg: #198754;
            --bs-btn-border-color: #198754;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #254f3b;
            --bs-btn-hover-border-color: #254f3b;
            --bs-btn-focus-shadow-rgb: 60, 153, 110;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #146c43;
            --bs-btn-active-border-color: #13653f;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #198754;
            --bs-btn-disabled-border-color: #198754;
        }
        #sidebar-wrapper {
    width: 400px;
    min-width: 200px;
    flex-shrink: 0;
}

.content-wrapper {
    flex: 1;
    min-width: 0;
    overflow-x: auto;
}
#answerContainer td {
    vertical-align: middle;
}

#answerContainer input[type=file] {
    font-size: 11px;
}
    </style>
</head>

<body>
   <div class="d-flex">
    @include('dashboard.sidebar.sidebar')

    <div class="content-wrapper flex-grow-1">
        @yield('container')
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    {{-- Bootstrap Javascript CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
</body>

</html>
