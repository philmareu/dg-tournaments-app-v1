@extends('laramanager::layouts.default')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}"/>
@endsection

@section('title')
    Edit Video
@endsection

@section('content')

    <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" class="uk-form uk-form-stacked">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">

        @include('laraform::elements.form.text', ['field' => ['name' => 'youtube_video_id', 'value' => $video->youtube_video_id]])

        <div id="event-input-template" class="hide">
            <div class="uk-form-row event-input">
                <label for="events" class="uk-form-label">Event</label>
                <div class="uk-form-controls">
                    <div class="uk-grid">
                        <div class="uk-width-4-5">
                            <input type="text"
                                   name="events[]"
                                   class="typeahead event"
                                   placeholder="Search events">
                        </div>
                        <div class="uk-width-1-5">
                            <button class="uk-button uk-width-1-1 remove" type="button">Remove</button>
                        </div>
                    </div>
                    <input type="hidden" name="" class="event-id-input">
                </div>
            </div>
        </div>

        <div id="course-input-template" class="hide">
            <div class="uk-form-row course-input">
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
            </div>
        </div>

        <h2>Events</h2>
        <div class="events uk-margin-top">
            @each('admin.videos.fields.event', $video->events, 'event')
        </div>

        <div class="uk-margin-large-top uk-margin-large-bottom">
            <a href="#" class="add-event uk-button"><i class="uk-icon-plus-circle"></i> Event</a>
        </div>

        <h2>Courses</h2>
        <div class="courses uk-margin-top">
            @each('admin.videos.fields.course', $video->courses, 'course')
        </div>

        <div class="uk-margin-large-top uk-margin-large-bottom">
            <a href="#" class="add-course uk-button"><i class="uk-icon-plus-circle"></i> Course</a>
        </div>

        <div class="uk-form-row">
            <button type="submit" class="uk-button uk-button-primary uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-6">Save</button>
        </div>

        <div class="uk-hidden">
            <div id="event-suggestions">
                <div>
                    <div class="event">
                        <div class="title">@{{ tournament_name }}</div>
                        <div class="meta">@{{ start_date }}, @{{ city }}, @{{ state_prov }}</div>
                    </div>
                </div>
            </div>
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

@endsection

@section('scripts')

    <script src="{{ asset('js/admin.js') }}"></script>

    <script>

        var eventSuggestion = Handlebars.compile($("#event-suggestions").html());

        var events = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tournament_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            identify: function(obj) { return obj.id; },
            prefetch: {
                url: SITE_URL + '/admin/search/events',
                ttl: 1000
            }
        });

        $('.typeahead.event').typeahead({
                    minLength: 1,
                    highlight: false
                },
                {
                    name: 'events',
                    display: 'tournament_name',
                    source: events,
                    limit: 20,
                    templates: {
                        suggestion: eventSuggestion
                    }
                }
        );

        $('.typeahead.event').on('typeahead:select', function(event, suggestion) {
            console.log('Updated id');

            var element = $(this);
            var idElement = element.parents('.uk-form-controls').find('.event-id-input');
            idElement.val(suggestion.tournament_id);
        });

        $('.add-event').on('click', function(event) {
            event.preventDefault();

            var state = $('.events').css('display');

            if(state == 'none') {
                $('.events').show();
            } else {
                var event = $("#event-input-template .event-input").clone();
                var idElement = event.find('.event-id-input').attr('name', 'event_ids[]');

                var input = event.find('.tt-input').val('');

                $(input).typeahead({
                            minLength: 1,
                            highlight: false
                        },
                        {
                            name: 'events',
                            display: 'tournament_name',
                            source: events,
                            limit: 20,
                            templates: {
                                suggestion: eventSuggestion
                            }
                        }
                );

                $(input).on('typeahead:select', function(event, suggestion) {
                    console.log('Updated id');

                    var element = $(this);
                    var idElement = element.parents('.uk-form-controls').find('.event-id-input');
                    idElement.val(suggestion.tournament_id);
                });

                event.appendTo( ".events" );
            }
        });











        var courseSuggestion = Handlebars.compile($("#course-suggestions").html());

        var courses = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('course_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            identify: function(obj) { return obj.id; },
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

        $('.events').on('click', '.remove', function(event) {
            var button = $(this);
            button.parents('.event-input').remove();
        });

        $('.courses').on('click', '.remove', function(event) {
            var button = $(this);
            button.parents('.course-input').remove();
        });
    </script>

@endsection