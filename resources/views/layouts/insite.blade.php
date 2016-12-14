<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <style>
        body {
            background-color: #383938;
            color: #fff;
        }

        .btn-primary {
            background-color: #0b3757;
        }

        .form-control {
            border-radius: 1px;
        }

        .tooltip {
            background: #eee;
            box-shadow: 0 0 5px #999999;
            color: #333;
            display: none;
            font-size: 12px;
            left: 130px;
            padding: 10px;
            position: absolute;
            text-align: center;
            top: 95px;
            width: 80px;
            z-index: 10;
        }

        .legend {
            font-size: 12px;
        }

        rect {
            cursor: pointer;
            /* NEW */
            stroke-width: 2;
        }

        rect.disabled {
            /* NEW */
            fill: transparent !important;
            /* NEW */
        }

        svg{
            width: 100%;
            height: 100%;
        }
        path.slice{
            stroke-width:2px;
        }

        polyline{
            opacity: .3;
            stroke: black;
            stroke-width: 2px;
            fill: none;
        }

        .orenji {
            color: #f47f20;
        }

        .orengi-bg {
            background-color: #f47f20;
        }

        .btn-orange {
            color: #fff;
            font-weight: 500;
            text-transform: uppercase;
            background-color: #f47f20;
            border-color: #d6701c;
        }

        .btn-orange:hover {
            color: #fff;
            background-color: #d9721c;
        }

        .btn-orange:active {
            color: #fff;
            background-color: #c8691a;
        }
    </style>
    @yield('page-css')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div id="app">
    @yield('content')
</div>

<!-- Scripts -->
<script src="/js/app.js"></script>
@yield('page-js')
</body>
</html>
