<html>
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DG Tournaments | @yield('title', 'Best place to find and manage disc golf tournament information')</title>
    <meta name="description" content="@yield('page-description')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')

    <!-- Links -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Muli:400,700&display=swap" rel="stylesheet">
    @yield('links')

    <!-- CSS -->
    @stack('css')
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    @yield('css-after')

    <!-- Scripts -->
    @include('partials.js-vars')
    @include('partials.bugsnag')
    @include('partials.analytics')

    @if(env('LOAD_STRIPE', true) && env('ONLINE', true))
        <script src="https://js.stripe.com/v3/"></script>
    @endif

    @stack('scripts')

</head>
<body class="@yield('body-class')">
<div id="app" class="uk-offcanvas-content">
    @section('content')

        <primary-navigation :user="user"
                            :order="order"
                            v-on:order-updated="updateOrder"
                            :has-new-notifications="hasNewNotifications"
                            v-on:viewed-notifications="markNotificationsAsViewed"
        ></primary-navigation>

    @show

    @yield('footer')

    <login v-on:user-authenticated="updateUser"></login>
</div>
<script src="{{ mix('js/scripts.js') }}"></script>
@yield('bottom-body')
</body>
</html>