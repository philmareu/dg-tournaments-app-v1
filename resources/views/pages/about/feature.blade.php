<div class="uk-margin-large-bottom">
    <div class="uk-card uk-card-default uk-card-small">
        <div class="uk-card-media-top">
            <div uk-lightbox>
                <a href="{{ url('images/original/' . $feature->image->filename) }}"><img src="{{ url('images/small/' . $feature->image->filename) }}" alt=""></a>
            </div>
        </div>
        <div class="uk-card-body">
            <h3 class="uk-card-title">{{ $feature->title }}</h3>
            <p>{{ $feature->description }}</p>
        </div>
    </div>
</div>