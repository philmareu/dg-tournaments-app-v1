@extends('laramanager::layouts.default')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('title')
    Edit Event
@endsection

@section('content')

    <h2>{{ $tournament->tournament_name }}</h2>
    <div class="uk-grid">
        <div class="uk-width-1-2">
            <ul class="uk-list">
                <li>{{ $tournament->dates }}</li>
                <li>{{ $tournament->city }}, {{ $tournament->state_prov }}</li>
                <li>{{ $tournament->tierLabel }}, {{ $tournament->classLabel }}, {{ $tournament->format->title }}</li>
            </ul>
        </div>
        <div class="uk-width-1-2">
            <ul class="uk-list">
                <li>Director: <a href="{{ route('directors.show', $tournament->director->first()->pdga_number) }}">{{ $tournament->director->first()->name }}</a></li>
                <li>Email: <a href="mailto:{{ $tournament->communication->email }}">{{ $tournament->communication->email }}</a></li>
                <li>Phone: {{ $tournament->communication->phone }}</li>
            </ul>
        </div>
    </div>

    <div class="uk-button-group uk-margin">

        <a href="{{ url($tournament->path) }}" class="uk-button" target="_blank">View <i class="uk-icon-external-link"></i></a>

        @if($tournament->urls->pdga_page != "")
            <a href="{{ $tournament->urls->pdga_page }}" class="uk-button" target="_blank">PDGA Page <i class="uk-icon-external-link"></i></a>
        @endif

        @if($tournament->urls->website != "")
            <a href="{{ $tournament->urls->website }}" class="uk-button" target="_blank">Website <i class="uk-icon-external-link"></i></a>
        @endif

        @if($tournament->urls->registration != "")
            <a href="{{ $tournament->urls->registration }}" class="uk-button" target="_blank">Registration <i class="uk-icon-external-link"></i></a>
        @endif
    </div>

    <form action="{{ route('tournaments.update', $tournament->tournament_id) }}" method="POST" class="uk-form uk-form-stacked" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">

        <div class="uk-grid">
            <div class="uk-width-medium-1-2">
                @include('laraform::elements.form.image', ['field' => ['name' => 'image', 'value' => $tournament->meta->poster]])
            </div>
            <div class="uk-width-medium-1-2">
                @include('laraform::elements.form.checkbox', ['field' => ['name' => 'womens', 'checked' => $tournament->specialEvents->where('id', 1)->count()]])
            </div>
        </div>

        <div class="uk-grid uk-margin">
            <div class="uk-width-1-3">
                @include('laraform::elements.form.text', ['field' => ['name' => 'hashtag', 'value' => $tournament->meta->hashtag]])
            </div>
            <div class="uk-width-1-3">
                @include('laraform::elements.form.text', ['field' => ['name' => 'latitude', 'value' => $tournament->latitude]])
            </div>
            <div class="uk-width-1-3">
                @include('laraform::elements.form.text', ['field' => ['name' => 'longitude', 'value' => $tournament->longitude]])
            </div>
        </div>

        @include('laraform::elements.form.textarea', ['field' => ['name' => 'additional_course_information', 'value' => $tournament->meta->additional_course_information]])

        <div id="course-input-template" class="hide">
            <ul class="uk-list uk-form-row course-input uk-sortable" data-uk-sortable="{handleClass:'uk-sortable-handle'}">
                <li>
                    <div class="uk-sortable-handle"></div>
                    <label for="courses" class="uk-form-label">Course</label>
                    <div class="uk-form-controls">
                        <div class="uk-grid">
                            <div class="uk-width-4-5">
                                <input type="text"
                                       name="courses[]"
                                       class="typeahead course"
                                       placeholder="Search courses">
                            </div>
                            <div class="uk-width-1-5">
                                <button class="uk-button uk-width-1-1 remove" type="button">Remove</button>
                            </div>
                        </div>
                        <input type="hidden" name="" class="course-id-input">
                    </div>
                </li>
            </ul>
        </div>

        <div class="courses uk-margin-top">
            @each('admin.videos.fields.course', $tournament->courses, 'course')
        </div>

        <div class="uk-margin-large-top uk-margin-large-bottom">
            <a href="#" class="add-course uk-button"><i class="uk-icon-plus-circle"></i> Course</a>
        </div>

        <div class="uk-form-row">
            <button type="submit" class="uk-button uk-button-primary uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-6">Save</button>
        </div>

        <div class="uk-hidden">
            <div id="course-suggestions">
                <div>
                    <div class="event">
                        <div class="title">@{{ course_name }}</div>
                        <div class="meta">@{{ city }}, @{{ state_province }} (@{{ country }})</div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <h2>Issues</h2>

    <h3>Open</h3>

    @foreach($tournament->issues as $issue)

        <div>
            {{ $issue->issue }} by {{ $issue->user->name }}
            <a href="{{ route('issues.edit', $issue->id) }}">Edit</a>
        </div>

    @endforeach

    <form action="{{ route('issues.store', $tournament->tournament_id) }}" method="POST" class="uk-form uk-form-stacked uk-width-1-3">
        {{ csrf_field() }}

        @include('laraform::elements.form.textarea', ['field' => ['name' => 'issue']])

        <div class="uk-form-row">
            @include('laraform::elements.form.submit')
        </div>
    </form>

@endsection

@section('scripts')

    <script src="{{ asset('js/admin.js') }}"></script>

    <script>
        var courseSuggestion = Handlebars.compile($("#course-suggestions").html());

        var courses = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('course_name', 'city'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            identify: function(obj) { return obj.course_id; },
            prefetch: {
                url: SITE_URL + '/admin/search/courses',
                ttl: 1000
            }
        });

        $('.typeahead.course').typeahead({
                    minLength: 1,
                    highlight: false
                },
                {
                    name: 'courses',
                    display: 'course_name',
                    source: courses,
                    limit: 50,
                    templates: {
                        suggestion: courseSuggestion
                    }
                }
        );

        $('.typeahead.course').on('typeahead:select', function(event, suggestion) {
            console.log('Updated id');
            var element = $(this);
            var idElement = element.parents('.uk-form-controls').find('.course-id-input');
            idElement.val(suggestion.course_id);
        });

        $('.add-course').on('click', function(event) {
            event.preventDefault();

            var state = $('.courses').css('display');

            if(state == 'none') {
                $('.courses').show();
            } else {
                var course = $("#course-input-template .course-input").clone();
                var idElement = course.find('.course-id-input').attr('name', 'course_ids[]');

                var input = course.find('.tt-input').val('');

                $(input).typeahead({
                            minLength: 1,
                            highlight: false
                        },
                        {
                            name: 'courses',
                            display: 'course_name',
                            source: courses,
                            limit: 50,
                            templates: {
                                suggestion: courseSuggestion
                            }
                        }
                );

                $(input).on('typeahead:select', function(event, suggestion) {
                    console.log('Updated id');

                    var element = $(this);
                    var idElement = element.parents('.uk-form-controls').find('.course-id-input');
                    idElement.val(suggestion.course_id);
                });

                course.appendTo(".courses");
            }
        });

        $('.courses').on('click', '.remove', function(event) {
            var button = $(this);
            button.parents('.course-input').remove();
        });
    </script>

@endsection