<!doctype html>
<html lang="en">

<head>
    <link type="image/x-icon" href="{{asset('assets/images/icons/fav.png')}}" rel="icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>@yield('title')</title>
    <!-- Bootstrap core CSS -->
    <!-- <link href="{!! url('assets/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet"> -->
    <!-- Custom styles for this template -->
    <!-- <link href="{!! url('assets/css/app.css') !!}" rel="stylesheet"> -->
    <!-- <link href="{!! url('assets/bootstrap/css/font-awesome.min.css') !!}" rel="stylesheet"> -->
    <!-- <link href="{!! url('assets/css/extended.css') !!}?v={{config('constants.css_version')}}" rel="stylesheet"> -->
    <!-- <script src="{!! url('assets/js/jquery.min.js') !!}"></script> -->
</head>

<body>
    @yield('content')

    <!-- <script src="{!! url('assets/bootstrap/js/bootstrap.bundle.min.js') !!}"></script> -->
    <!-- <script src="{!! url('assets/bootstrap/js/bootstrap.min.js') !!}"></script> -->
</body>

</html>