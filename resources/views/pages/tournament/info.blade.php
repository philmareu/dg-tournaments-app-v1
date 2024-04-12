<div class="uk-margin">
    @if($tournament->email !== '' && ! is_null($tournament->email))
        <div class="uk-text-truncate">
            <a href="mailto:{{ $tournament->email }}?subject={{ $tournament->name }}">{{ $tournament->email }}</a><br>
        </div>
    @endif

    @if($tournament->phone !== '' && ! is_null($tournament->phone))
        {{ $tournament->phone }}<br>
    @endif
</div>

<div class="uk-margin uk-margin">
    @if($tournament->pdgaTiers->count())
        PDGA - {{ $tournament->pdgaTiers->pluck('title')->implode(',') }}<br>
    @endif

    {{ $tournament->classes->pluck('title')->implode(', ') }}<br>

    @if($tournament->specialEventTypes->count())
        {{ $tournament->specialEventTypes->pluck('title')->implode(', ') }}
    @endif
</div>

@if(! is_null($tournament->description) && $tournament->description !== '')
    <div class="uk-margin-large-bottom">
        <h2>Description</h2>
        <div>{!! nl2br(e($tournament->description)) !!}</div>
    </div>
@endif

<div class="uk-margin-large-bottom">
    <h2>Weather</h2>
    <div class="weather uk-child-width-1-{{ count($weather) }}" uk-grid>
        @foreach($weather as $day)
            <div class="daily-weather uk-text-center">
                {{ \Carbon\Carbon::createFromTimestamp($day['date'])->format('D') }}<br>
                <i class="wi {{ $weatherIcons[$day['icon']] }}"></i><br>
                {{ round($day['temperatureMax']) }}&deg; F<br>
                {{ round(($day['temperatureMax'] - 32) / 1.8) }}&deg; C
            </div>
        @endforeach
    </div>
    <a href="https://darksky.net/poweredby/" class="uk-link-muted">Powered by Dark Sky</a>
</div>

<div class="uk-margin-large-bottom">
    <h2>Links & Media</h2>

    @if($tournament->pdgaTiers->count())
        <span uk-icon="icon: link" class="uk-margin-small-right"></span><a href="https://pdga.com/tour/event/{{ $tournament->data_source_tournament_id }}" target="_blank">PDGA Event Page</a>
        <br>
    @endif

    @foreach($tournament->links as $link)
        <span uk-icon="icon: link" class="uk-margin-small-right"></span><a href="{{ $link->url }}" target="_blank">{{ $link->title }}</a>
        <br>
    @endforeach

    @foreach($tournament->media as $media)
        <span uk-icon="icon: file" class="uk-margin-small-right"></span><a href="{{ url('storage/' . $media->filename) }}" target="_blank">{{ $media->title }}</a>
        <br>
    @endforeach
</div>

@if(! is_null($tournament->registration->id))
    <div class="uk-margin-large-bottom">
        <h2>Registration</h2>
        <display-registration :registration="{{ $tournament->registration->toJson() }}"></display-registration>

        <div class="uk-text-muted uk-margin">(Updated: {{ $tournament->registration->updated_at->diffForHumans() }})</div>
    </div>
@endif

@if($tournament->scheduleByDay->count())
    <div class="uk-margin-large-bottom">
        <h2>Schedule</h2>
        @foreach($tournament->scheduleByDay as $day => $items)
            <h3>{{ $day }}</h3>
            <table class="uk-table uk-table-small uk-table-striped">
                <thead>
                <tr>
                    <th>Time</th>
                    <th>Event</th>
                    <th>Location</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item['time_span'] }}</td>
                        <td>{{ $item['summary'] }}</td>
                        <td>{{ $item['location'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach

        <div class="uk-text-muted uk-margin">(Updated: {{ $tournament->schedule->sortByDesc('updated_at')->first()->updated_at->diffForHumans() }})</div>
    </div>
@endif

@if($tournament->courses->count())
    <div class="uk-margin-large-bottom">
    <h2>Courses</h2>
        @foreach($tournament->courses as $course)
            <h3 class="uk-margin-remove-bottom">{{ $course->name }}</h3>
            <div class="uk-margin">
                {{ $course->holes }} Holes <br>
                {{ $course->address }} <br>
                {{ $course->city }}, {{ $course->state_province }} ({{ $course->country }}) <br>
                <a href="#" onclick="moveMap({{ $course->latitude }}, {{ $course->longitude }})">Move Map</a><br>
                @if($course->holeNotes->count() && $course->holeNotes->where('notes', null)->count() < $course->holes)
                    <a href="#modal-hole-information-{{ $course->id }}" uk-toggle>View Details</a>
                @endif
            </div>

            <div class="uk-text-muted">(Updated: {{ $course->updated_at->diffForHumans() }})</div>

            <div id="modal-hole-information-{{ $course->id }}" class="uk-flex-top" uk-modal>
                <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

                    <button class="uk-modal-close-default" type="button" uk-close></button>

                    <h3>Directions</h3>
                    <p>{{ $course->directions }}</p>

                    <h3>Notes</h3>
                    <p>{{ $course->notes }}</p>

                    <hr>

                    @foreach($course->holeNotes as $holeInformation)
                        <h4>Hole {{ $holeInformation->hole }}</h4>
                        <p>{{ $holeInformation->notes }}</p>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($tournament->playerPacks->count())
    <div class="uk-margin-large-bottom">
        <h2>Player Pack(s)</h2>

        @foreach($tournament->playerPacks as $playerPack)
            <h3>{{ $playerPack->title }}</h3>
            <p>{{ $playerPack->description }}</p>
            <table class="uk-table uk-table-small uk-table-striped">
                <thead>
                <tr>
                    <th>Item</th>
                </tr>
                </thead>
                <tbody>
                @foreach($playerPack->items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
@endif

<h2>Followers</h2>

@if($tournament->followers->count())
    @foreach($tournament->followers as $follow)
        <article class="uk-comment">
            <header class="uk-comment-header uk-grid-medium uk-flex-middle" uk-grid>
                <div class="uk-width-auto">
                    <img class="uk-comment-avatar uk-border-circle" src="{{ url('images/poster-small/' . $follow->user->image->filename) }}" width="40" alt="">
                </div>
                <div class="uk-width-expand">
                    <h4 class="uk-comment-title uk-margin-remove">{{ $follow->user->name }}</h4>
                    <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top uk-text-truncate">
                        <li>{{ $follow->user->location }}</li>
                    </ul>
                </div>
            </header>
        </article>
    @endforeach
@else
    <p>No one is following this tournament yet. You could be the first.</p>
    <action-bar :user="user" v-on:user-updated="updateUser" :tournament="{{ $tournament }}"></action-bar>
@endif