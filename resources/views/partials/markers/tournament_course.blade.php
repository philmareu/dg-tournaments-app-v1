<div>
    {{ $course->name }}
</div>

<div>
    {{ $course->holes }} Holes
</div>

<div>
    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $course->latitude }},{{ $course->longitude }}" target="_blank">Directions</a>
</div>