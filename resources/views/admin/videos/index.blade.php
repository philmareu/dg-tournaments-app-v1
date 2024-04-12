@extends('laramanager::layouts.default')

@section('head')
    <link href="{{ asset("vendor/laramanager/css/datatables.css") }}" rel="stylesheet" media="screen">
@endsection

@section('actions')
    <a href="{{ route('admin.videos.create') }}" class="uk-float-right"><i class="uk-icon-plus"></i> Add</a>
@endsection

@section('title')
    Videos
@endsection

@section('content')
    <div class="uk-overflow-container">
        <table id="data-table" class="stripe row-border">
            <thead>
            <tr>
                <td>YouTube Id</td>
                <td>Events</td>
                <td>Courses</td>
                <td>&nbsp;</td>
            </tr>
            </thead>

            <tbody>
            @foreach($videos as $video)
                <tr>
                    <td>{{ $video->youtube_video_id }}</td>
                    <td>{{ $video->events->count() }}</td>
                    <td>{{ $video->courses->count() }}</td>
                    <td>
                        <div class="uk-grid uk-grid-medium">
                            <div class="uk-width-1-2">
                                <a href="{{ route('admin.videos.edit', $video->id) }}"><i class="uk-icon-pencil"></i></a>
                            </div>
                            <div class="uk-width-1-2">
                                <a href="#" class="uk-text-danger delete" data-video-id="{{ $video->id }}"><i class="uk-icon-trash"></i></a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('scripts')

    <script src="{{ asset('vendor/laramanager/js/datatables.js') }}"></script>

    <script>
        $(function() {
            $('#data-table').DataTable({
                "pageLength": 50
            });
        });

        $('table').on('click', '.delete', function(event) {
            var r = confirm("Are you sure? This will remove any event and course attachments.");

            event.preventDefault();

            if (r == true) {
                var element = $(this);
                var id = element.attr('data-video-id');
                var td = element.parents('td');
                var row = element.parents('tr');

                $.ajax({
                    url: SITE_URL + '/admin/videos/' + id,
                    type: 'POST',
                    data: {_method: 'DELETE', _token: csrf},
                    success: function(response) {
                        if(response.status == 'ok') {
                            row.addClass('uk-text-muted');
                            td.html('Deleted');
                        }
                    }
                });
            }
        });
    </script>

@endsection