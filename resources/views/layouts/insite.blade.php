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
            background-color: #fbfbfb;
        }

        .btn-primary {
            background-color: #0b3757;
        }

        .form-control {
            border-radius: 1px;
        }
    </style>
    @yield('page-css');

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
@yield('page-js');
</body>
</html>
