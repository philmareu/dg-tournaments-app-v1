<div class="uk-card uk-card-small uk-card-body uk-margin bg-light">
    <h2><a href="{{ $post->path }}" class="uk-link-muted">{{ $post->title }}</a></h2>
    <div class="uk-grid uk-grid-medium">
        <div class="uk-width-1-3@s uk-margin">
            <img src="{{ url('images/small/' . $post->image->filename) }}" alt="">
        </div>
        <div class="uk-width-2-3@s">
            <div class="uk-text-muted uk-margin-small">{{ $post->posted_at->diffForHumans() }}</div>
            <div>{{ $post->summary }}</div>
        </div>
    </div>
</div>
