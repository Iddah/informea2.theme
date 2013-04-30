$(document).ready(function() {
    $('#results').infinitescroll({
        navSelector: "#results .paginator",
        nextSelector: "#results .paginator a:last",
        itemSelector: "#results .items",

        finishedMsg: '<p>No more highlights found</p>'
    });
});