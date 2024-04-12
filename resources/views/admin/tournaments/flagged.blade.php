@extends('laramanager::layouts.admin.blank')

@section('title')
    Flagged Tournaments ({{ $tournaments->perPage() }} / {{ $tournaments->total() }})
@endsection

@section('breadcrumbs')
    <li><span>@yield('title')</span></li>
@endsection

@section('blank-content')

    <div id="flagged-tournaments">
        @foreach($tournaments as $tournament)

            <div class="uk-card uk-card-default uk-card-small uk-margin">
                <div class="uk-card-header">
                    <h3 class="uk-card-title">
                        {{ $tournament->name }}
                        <a href="{{ route('manage.tournament.basics', $tournament->id) }}" class="uk-float-right uk-button uk-button-small uk-button-primary" target="_blank">Manage</a>
                    </h3>
                </div>

                <div class="uk-card-body">
                    <div class="uk-margin">
                        <a href="{{ route('manage.tournament.registration', $tournament->id) }}" target="_blank">Manage Registration</a>
                    </div>
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
                            @foreach($tournament->activeFlags as $flag)
                                <div>
                                    {{ $flag->title }} - {{ $flag->pivot->notes }} <a href="{{ route('admin.flags.edit', $flag->pivot->id) }}" target="_blank">Edit</a>
                                    / <a href="#" data-resource-id="{{ $flag->pivot->id }}" class="delete-flag">Delete</a>
                                    / <a href="#" data-resource-id="{{ $flag->pivot->id }}" class="postpone-flag">Postpone</a>
                                </div>
                            @endforeach
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
            </div>

        @endforeach
    </div>

@endsection

@push('scripts-last')

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
                            location.reload();
                        }
                    }
                });
            });

            $('#flagged-tournaments').on('click', '.postpone-flag', function(event) {

                event.preventDefault();

                let element = $(this);
                let id = element.attr('data-resource-id');
                let div = element.parent('div');

                $.ajax({
                    url: SITE_URL + '/admin/flags/' + id + '/postpone/7',
                    type: 'POST',
                    data: {_token: csrf},
                    success: function(response) {
                        if(response === 'ok') {
                            location.reload();
                        }
                    }
                });
            });
        })
    </script>

@endpush