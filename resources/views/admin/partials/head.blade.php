<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link href="{{ asset('img/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
@yield('meta')

<title>DG Tournaments | @yield('title')</title>
<script type="text/javascript" charset="utf-8">
    var SITE_URL = "{{ url('/') }}";
    var csrf = "{{ csrf_token() }}";
</script>

<link rel="stylesheet" href="{{ asset('/css/admin.css') }}">

@if(env('APP_ENV') == 'production')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-78661611-1', 'auto');
        ga('send', 'pageview');

    </script>
@else
    <script>
        localStorage.clear();
    </script>
@endif