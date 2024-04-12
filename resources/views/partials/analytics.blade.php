@if(\Illuminate\Support\Facades\App::environment('production'))
    @unless(auth()->check() && auth()->user()->isAdmin())
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-78661611-1', 'auto');
            ga('send', 'pageview');

        </script>
    @endunless
@else
    @if(config('dev.load.google_analytics') && config('dev.online'))
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics_debug.js','ga');

            window.ga_debug = {trace: false};
            ga('create', 'UA-78661611-1', 'auto');
            ga('set', 'sendHitTask', null);
            ga('send', 'pageview');

        </script>
    @endif
@endif