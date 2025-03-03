$(document).ready(function () {
    // Initialize the wizard
    $("#wizard").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slide",
        stepsOrientation: "horizontal",
        titleTemplate: '<span class="number">#index#</span>'
    });
});