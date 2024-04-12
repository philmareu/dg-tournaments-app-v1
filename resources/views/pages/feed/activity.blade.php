@if(view()->exists('partials.activities.types.' . $activity->type))
    @include('partials.activities.types.' . $activity->type)
@else
    <p>Need View!</p>
@endif