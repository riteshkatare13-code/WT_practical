$(document).ready(function () {

    // Fade effects
    $("#fadeIn").click(function () {
        $("#message").fadeIn(1000);
    });

    $("#fadeOut").click(function () {
        $("#message").fadeOut(1000);
    });

    // Sliding effects
    $("#slideUp").click(function () {
        $("#message").slideUp(1000);
    });

    $("#slideDown").click(function () {
        $("#message").slideDown(1000);
    });

    // Immediate hide and show
    $("#hide").click(function () {
        $("#message").hide();
    });

    $("#show").click(function () {
        $("#message").show();
    });

    // Mouse events
    $("#message").mouseover(function () {
        $(this).css({
            "background-color": "lightblue",
            "color": "black"
        });
    });

    $("#message").mouseout(function () {
        $(this).css({
            "background-color": "#eeeeee",
            "color": "black"
        });
    });

    // Click event
    $("#message").click(function () {
        $("#info").text("The paragraph was clicked.");
    });

});