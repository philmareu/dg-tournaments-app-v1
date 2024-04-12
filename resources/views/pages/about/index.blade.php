@extends('layouts.default')

@section('title')
    Home
@endsection

@section('meta')
    <meta name="description" content="DG Tournaments provides information on disc golf tournaments from around the world. Our powerful search tools plot disc golf tournaments on a map with the ability to filter search results.">
@endsection

@section('body-class')
    about
@endsection

@section('content')
    @parent
    
    <header class="uk-section uk-section-media uk-background-cover">
        <div class="uk-container uk-container-small uk-text-center">
            <p class="uk-text-large uk-text-uppercase slogan">Search &bull; Manage &bull; Sponsor</p>

            <div class="uk-margin-top uk-margin-large-bottom">
                <p class="intro">DG Tournaments is a project built to make disc golf tournament information more accessible. It is a disc golf tournament management platform for players, directors and sponsors.</p>
            </div>
        </div>
    </header>

    <section class="uk-section">
        <div class="uk-container uk-text-center">
            <div class="uk-margin-large-bottom">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/a9AO6i28ip4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen uk-responsive></iframe>
            </div>

            <div class="uk-grid-match uk-child-width-1-3@s" uk-grid>
                <div>
                    <h2>Directors & Staff</h2>

                    <div class="uk-margin">
                        <p class="loud">Help players get the information they need. We have made it super easy for directors and staff to add disc golf tournament information on our beautifully designed and mobile friendly tournament pages.</p>
                    </div>

                    <a href="#staff" uk-scroll>Learn More</a>
                </div>

                <div>
                    <h2 class="uk-text-center">Players</h2>

                    <div class="uk-margin">
                        <p class="loud">Find your next tournament! Search for disc golf tournaments by map, name or city. <a href="{{ url('register') }}">Create an account</a> and then save your tournaments so you can stay organized and informed.</p>
                    </div>

                    <a href="#player" uk-scroll>Learn More</a>
                </div>

                <div>
                    <h2>Sponsors</h2>

                    <div class="uk-margin">
                        <p class="loud">Are you looking for disc golf tournaments to sponsor? Check out our sponsorship map search and easily locate the right sponsorship.</p>
                    </div>

                    <a href="#sponsor" uk-scroll>Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <section class="uk-section uk-section-muted" id="staff">
        <div class="uk-container">
            <h2 class="uk-text-center">Directors & Staff Features</h2>

            <div class="uk-grid uk-grid-match uk-child-width-1-4@s">
                @each('pages.about.feature', $features->where('category', 'staff'), 'feature')
            </div>
        </div>
    </section>

    <section class="uk-section" id="player">
        <div class="uk-container">
            <h2 class="uk-text-center">Player Features</h2>

            <div class="uk-grid uk-grid-match uk-child-width-1-4@s">
                @each('pages.about.feature', $features->where('category', 'player'), 'feature')
            </div>
        </div>
    </section>

    <section class="uk-section uk-section-muted" id="sponsor">
        <div class="uk-container">
            <h2 class="uk-text-center">Sponsor Features</h2>

            <div class="uk-grid uk-grid-match uk-child-width-1-4@s">
                @each('pages.about.feature', $features->where('category', 'sponsor'), 'feature')
            </div>
        </div>
    </section>

@endsection

@section('footer')
    @include('partials.footer', ['size' => 'large'])
@endsection