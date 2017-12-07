<!doctype html>
<html lang="{!! app()->getLocale() !!}">
<head>
    <script src="/js/pace.min.js"></script>
    <link href="/css/pace-theme-flash.css" rel="stylesheet"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description"
          content="Chandler Christian Academy is an independent, non-profit, non-denominational Christian preschool through eighth grade in Chandler, AZ.">
    <meta name="author" content="Eric Totten">

    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#b8171c">
    <meta name="theme-color" content="#ffffff">

    @yield('meta')
    {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
    @stack('before-styles')
    <link href="https://fonts.googleapis.com/css?family=PT+Serif:400,400i,700,700i|Vollkorn:600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{!! mix('css/coreui.css') !!}">
    @stack('after-styles')
</head>
<body class="app header-fixed sidebar-fixed aside-menu-off-canvas aside-menu-hidden">
<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button"><i class="fa fa-bars"></i></button>
    <a class="navbar-brand" href="http://www.chandlerchristianacademy.org/"></a>
    <button class="navbar-toggler sidebar-minimizer d-md-down-none" type="button"><i class="fa fa-bars"></i></button>
    <div class="logotext">CCA Lunch Ordering</div>
    <div class="ml-auto"></div>
</header>
<div class="app-body">
    @include('includes.partials.sidebar')
    <main class="main">
        <div class="loader" style="display: none;">
            <div class="ajax-spinner ajax-skeleton"></div>
        </div>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</div>
@include('includes.partials.footer')
@stack('before-scripts')
@prod
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.bundle.min.js"></script>
@else
    {!! Html::script("/js/jquery-3.2.1.min.js") !!}
    {!! Html::script("/js/bootstrap.bundle.min.js") !!}
@endprod
<script src="{!! mix('js/coreui.js') !!}"></script>
@stack('after-scripts')
</body>
</html>
