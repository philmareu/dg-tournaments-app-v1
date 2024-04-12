@extends('laramanager::layouts.admin.default')

@section('title')
    {{ $tournament->name }}
@endsection

@section('breadcrumbs')
    <li><span>@yield('title')</span></li>
@endsection

@section('actions')
    <a href="{{ route('manage.tournament.basics', $tournament->id) }}" class="uk-button uk-button-primary uk-button-small" target="_blank">Manage</a>
@endsection

@section('default-content')

    <div id="flagged-tournaments">
        <div>
            {{ $tournament->director }}<br>
            {{ $tournament->dateSpan->formattedDateSpan() }}<br>

            <a href="mailto:{{ $tournament->authorization_email }}?subject={{ $tournament->name }}&body=I am the creator of dgtournaments.com, a new disc golf tournament search and management platform. I would like to update your listing ({{ url($tournament->path) }}).">{{ $tournament->authorization_email }}</a><br>

            @if($tournament->phone !== '' && ! is_null($tournament->phone))
                {{ $tournament->phone }}<br>
            @endif

            @if($tournament->sanctionedByPdga)
                <a href="https://pdga.com/tour/event/{{ $tournament->data_source_tournament_id }}" target="_blank">PDGA Page</a>
            @endif
        </div>

        <div class="uk-grid uk-margin">
            <div class="uk-width-1-2">
                <h3>Flags</h3>

                @foreach($tournament->flags as $flag)
                    <div class="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $flag->pivot->review_on)->isPast() ? 'uk-text-danger' : '' }}">{{ $flag->pivot->review_on }} - {{ $flag->title }} - {{ $flag->pivot->notes }} <a href="{{ route('admin.flags.edit', $flag->pivot->id) }}" target="_blank">Edit</a> / <a href="{{ route('admin.flags.show', $flag->pivot->id) }}" data-resource-id="{{ $flag->pivot->id }}" class="delete-flag">Delete</a></div>
                @endforeach

                <a href="{{ route('admin.flags.create') }}" target="_blank">Add</a>
            </div>
            <div class="uk-width-1-2">
                <h3>Related</h3>
                @foreach($tournament->relatedByEmail as $related)

                    @unless($tournament->id === $related->id)
                        <div>
                            <a href="{{ route('manage.tournament.basics', $related->id) }}" target="_blank">{{ $related->name }}</a> - {{ $related->dateSpan->formattedDateSpan() }}
                            @foreach($related->activeFlags as $flag)
                                <div>{{ $flag->title }} - {{ $flag->pivot->notes }} <a href="{{ route('admin.flags.edit', $flag->pivot->id) }}" target="_blank">Edit</a> / <a href="{{ route('admin.flags.show', $flag->pivot->id) }}" data-resource-id="{{ $flag->pivot->id }}" class="delete-flag">Delete</a></div>
                            @endforeach
                        </div>
                    @endunless

                @endforeach
            </div>
        </div>

        <div class="uk-margin">
            <h3>Activity</h3>

            @foreach($tournament->activities as $activity)
                <div>{{ $activity->type }} - {{ $activity->created_at }}</div>
            @endforeach
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(function() {
            $('#flagged-tournaments').on('click', '.delete-flag', function(event) {

                event.preventDefault();

                let element = $(this);
                let id = element.attr('data-resource-id');
                let div = element.parent('div');

                $.ajax({
                    url: SITE_URL + '/admin/flags/' + id,
                    type: 'POST',
                    data: {_method: 'DELETE', _token: csrf},
                    success: function(response) {
                        if(response.status === 'ok') {
                            div.addClass('uk-text-muted');
                        }
                    }
                });
            });
        })
    </script>

@endsection