<div class="uk-grid uk-margin">
    <div class="uk-width-auto uk-margin">
        <img src="{{ url('images/small/' . $tournament->poster->filename) }}" alt="Poster for {{ $tournament->name }}" width="80">
    </div>
    <div class="uk-width-5-6">
        <h3 class="uk-margin-remove-bottom">{{ $tournament->name }}</h3>
        <div>
            {{ $tournament->dateSpan->formattedDateSpan() }}
        </div>
        <div>
            <a href="{{ url($tournament->path) }}" class="uk-button uk-button-small uk-button-default">View</a>
            <a href="{{route('manage.tournament.index', $tournament->id) }}" class="uk-button uk-button-small uk-button-default">Manage</a>
        </div>
    </div>
</div>