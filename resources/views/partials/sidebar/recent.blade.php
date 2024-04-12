<div id="recent-posts">
    <div class="card">
        <h2 class="card-header">Recent Posts</h2>
        <div class="card-body">
            @foreach($posts as $post)
                <div class="post">
                    <div class="title"><a href="{{ url($post->path) }}">{{ $post->title }}</a></div>
                    <div class="meta">{{ $post->posted_at->format('M jS, Y') }}</div>
                </div>
                @if(! $loop->last)
                    <hr>
                @endif
            @endforeach
        </div>
    </div>
</div>