<!doctype html>
<html lang="en">

<head>
    <link type="image/x-icon" href="{{asset('assets/images/icons/fav.png')}}" rel="icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Complaint Portal | @yield('title')</title>

    <script src="{!! url('assets/js/jquery.min.js') !!}"></script>
    <!-- Bootstrap core CSS -->
    <link href="{!! url('assets/bootstrap/css/font-awesome.min.css') !!}" rel="stylesheet">
    <link href="{!! url('assets/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet" media='all'>
    <link rel="stylesheet" href="{!! url('assets/bootstrap/css/bootstrap-print.min.css') !!}" media="print">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{!! url('assets/css/loginpage.css') !!}" rel="stylesheet">
</head>

<style>
html,
body {
    height: 100%;
    margin: 0;
}
</style>
<style>
.img-container {
    max-height: 100vh;
    overflow: hidden;
}

.img-responsive {
    width: 100%;
    height: auto;
    max-height: 100%;
    object-fit: cover;
}
</style>

<body class="text-center">
    <section class="h-100">
        @yield('content')
    </section>
    <div class="loader"></div>
    <script src="{!! url('assets/js/custom.js') !!}"></script>
    <script src="{!! url('assets/bootstrap/js/bootstrap.min.js') !!}"></script>
    <script src="{!! url('assets/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
</body>

</html>