<div class="uk-form-row event-input">
    <label for="events" class="uk-form-label">Event</label>
    <div class="uk-form-controls">
        <div class="uk-grid">
            <div class="uk-width-4-5">
                <input type="text"
                       name="events[]"
                       class="typeahead event"
                       placeholder="Search events"
                       value="{{ $event->tournament_name }}">
            </div>
            <div class="uk-width-1-5">
                <button class="uk-button uk-width-1-1 remove" type="button">Remove</button>
            </div>
        </div>
        <input type="hidden" name="event_ids[]" value="{{ $event->tournament_id }}" class="event-id-input">
    </div>
</div>