document.addEventListener('DOMContentLoaded', function () {
    var audienceField = document.getElementById('broadcastAudience');
    var courseField = document.getElementById('broadcastCourse');
    var courseTarget = document.querySelector('[data-course-target]');

    if (!audienceField || !courseField || !courseTarget) {
        return;
    }

    function syncCourseField() {
        var isCourseAudience = audienceField.value === 'course_students';

        courseTarget.classList.toggle('is-active', isCourseAudience);
        courseField.required = isCourseAudience;
    }

    audienceField.addEventListener('change', syncCourseField);
    syncCourseField();
});
