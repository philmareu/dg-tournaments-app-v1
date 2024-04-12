<html>
<head>
    @include('admin.partials.head')
</head>
<body>

@include('admin.partials.topbar')

@yield('content')

<script src="{{ asset('/js/admin.js') }}"></script>
@yield('scripts')
</body>
</html>