<div class="uk-form-row course-input">
    <label for="courses" class="uk-form-label">Course</label>
    <div class="uk-form-controls">
        <div class="uk-grid">
            <div class="uk-width-4-5">
                <input type="text"
                       name="courses[]"
                       class="typeahead course"
                       placeholder="Search courses"
                       value="{{ $course->course_name }}">
            </div>
            <div class="uk-width-1-5">
                <button class="uk-button uk-width-1-1 remove" type="button">Remove</button>
            </div>
        </div>
        <input type="hidden" name="course_ids[]" value="{{ $course->course_id }}" class="course-id-input">
    </div>
</div>