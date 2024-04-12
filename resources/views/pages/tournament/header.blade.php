<div uk-grid>
    <div class="uk-width-1-2">
        <action-bar :user="user" v-on:user-updated="updateUser" :tournament="{{ $tournament }}"></action-bar>
    </div>
    <div class="uk-width-1-2">
        @if($tournament->sponsorships->count())
            <display-sponsorships
                    :tournament="{{ $tournament }}"
                    v-on:order-updated="updateOrder"></display-sponsorships>
        @else
            <claim :user="user" :tournament="{{ $tournament->load('managers')->toJson() }}"></claim>
        @endif
    </div>
</div>

@if(auth()->check() && auth()->user()->hasAccessToTournament($tournament->id))
    <div class="uk-margin">
        <a href="{{ route('manage.tournament.index', $tournament->id) }}" class="uk-button uk-button-default uk-button-small uk-width-1-1">Manage</a>
    </div>
@endif

@if(is_null($tournament->poster_id))
    <h1>{{ $tournament->name }}</h1>
    <div>Director: {{ $tournament->director }}</div>
@else
    <div class="uk-grid-small uk-margin" uk-grid>
        <div class="uk-width-1-4">
            <img src="{{ url('images/small/' . $tournament->poster->filename) }}" alt="" uk-toggle="target: #modal-poster">

            <div id="modal-poster" class="uk-flex-top" uk-modal>
                <div class="uk-modal-dialog uk-margin-auto-vertical">
                    <button class="uk-modal-close-outside" type="button" uk-close></button>
                    <img src="{{ url('images/large/' . $tournament->poster->filename) }}" alt="Tournament Poster">
                </div>
            </div>
        </div>
        <div class="uk-width-3-4">
            <h1>{{ $tournament->name }}</h1>
            <div>Director: {{ $tournament->director }}</div>
        </div>
    </div>
@endif

<div class="uk-margin">
    {{ $tournament->dateSpan->formattedDate() }}, {{ $tournament->start->format('Y') }}<br>

    {{ $tournament->location->formatted() }}<br>
</div>
