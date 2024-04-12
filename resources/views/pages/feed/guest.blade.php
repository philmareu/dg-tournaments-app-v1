@extends('layouts.default')

@include('partials.mapbox')

@section('title')
    Home
@endsection

@section('meta')
    <meta name="description" content="DG Tournaments provides information on disc golf tournaments from around the world. Our powerful search tools plot disc golf tournaments on a map with the ability to filter search results.">
@endsection

@section('body-class')
    home guest
@endsection

@push('scripts')
    <script>
        let active = 'home';
    </script>
@endpush

@section('content')
    <header>
        @parent
        <div class="uk-container uk-padding uk-text-center">
            <div>
                <p>Welcome to DG Tournaments!</p>
                <h1 class="uk-heading-hero">#discgolf</h1>
                <p class="uk-text-large">We love disc golf tournaments. So we built dgtournaments.com, a new way to list and locate disc golf tournaments.</p>
            </div>
            <a href="{{ route('search') }}" class="uk-button uk-button-primary"><span uk-icon="icon: search;" class="uk-margin-small-right"></span>Search For Tournaments</a>

        </div>
    </header>

    <section class="uk-container uk-margin-large-bottom">
        <div class="uk-grid">
            <div class="uk-width-1-3@s uk-margin-medium">
                <div class="uk-card uk-card-default uk-card-small uk-margin-medium">
                    <div class="uk-card-media-top">
                        <img src="../images/test5.jpg" alt="">
                    </div>
                    <div class="uk-card-header">

                        <h3 class="uk-card-title">About DGT</h3>
                    </div>
                    <div class="uk-card-body">
                        <p>Hi, I'm Phil Mareu (on the left), the creator of dgtournaments.com (DG Tournaments). DG Tournaments is a shiny new project built from the ground up just for disc golf tournaments. This project is a modern platform for searching and managing disc golf tournaments. New features are introduced every month so follow us on social media or check out our
                            <a href="{{ url('blog') }}">blog</a> for periodic updates. Our newsletter is also a great way to stay informed.</p>
                    </div>
                </div>

                <div class="uk-card uk-card-default uk-card-small uk-margin">
                    <h2 class="uk-card-header">Our Newsletter</h2>
                    <div class="uk-card-body">
                        <form action="//dgtournaments.us15.list-manage.com/subscribe/post?u=ede4754794a552aabc331e9de&amp;id=f163d157a4" method="POST" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate uk-form" target="_blank" novalidate>
                            <input type="email" value="" name="EMAIL" class="email uk-input uk-width-1-1" id="mce-EMAIL" placeholder="email address" required>
                            <div class="uk-margin">
                                <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="uk-button uk-button-default">
                            </div>

                            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ede4754794a552aabc331e9de_f163d157a4" tabindex="-1" value=""></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="uk-width-2-3@s">
                <div class="uk-card uk-card-default uk-card-small uk-margin-medium">
                    <div class="uk-card-body uk-text-center">
                        {{--<p class="uk-text-center">Stay informed! Did you know you can follow tournaments and get updates. Try it out. It is an awesome feature that will change the way you wil get tournament updates. <a href="{{ route('login') }}">Sign in</a> to get started.</p>--}}
                        {{--<hr>--}}

                        <div class="uk-heading-hero">{{ number_format($counts['tournaments']) }}</div>
                        <div class="uk-text-large">Upcoming Tournaments</div>
                    </div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-2@s uk-margin-medium">
                        <div class="uk-card uk-card-default uk-card-small">
                            <div class="uk-card-header">
                                <h3 class="uk-card-title">Recent Activity</h3>
                            </div>
                            <div class="uk-card-body">
                                @each('pages.feed.activity', $activities, 'activity', 'pages.feed.empty')
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-2@s">
                        <div class="uk-card uk-card-default uk-card-small">
                            <div class="uk-card-header">
                                <h3 class="uk-card-title">Blog Posts</h3>
                            </div>
                            <div class="uk-card-body">
                                @foreach($recentPosts as $post)
                                    <div class="uk-grid uk-grid-small uk-flex uk-flex-middle">
                                        <div class="uk-width-auto">
                                            <img src="{{ url('images/small/' . $post->image->filename) }}" class="uk-margin" width="40">
                                        </div>
                                        <div class="uk-width-expand">
                                            <div class="details uk-text-muted">
                                                <a href="{{ url($post->path) }}">{{ $post->title }}</a>
                                            </div>
                                            <div class="timestamp">{{ $post->posted_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('footer')
    @include('partials.footer', ['size' => 'large'])
@endsection