<script>
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    SITE_URL = "{{ url('/') }}";
    ENV = "{{ config('app.env') }}";
    LOAD_GOOGLE_ANALYTICS = "{{ env('LOAD_GOOGLE_ANALYTICS', true) && env('ONLINE', true) }}";
    algoliaAppId = "{{ config('services.algolia.appId') }}";
    algoliaSearchKey = "{{ config('services.algolia.searchKey') }}";
    stripePublishableKey = "{{ config('services.stripe.key') }}";
    mapboxToken = "{{ config('services.mapbox.token') }}"
</script>