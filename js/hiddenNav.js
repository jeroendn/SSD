$(document).ready(function () {

    $('body').on('mousemove', (e) => {

        // TODO Check if mouse is not hovering on menu

        if (e.clientX <= 100) {
            $('#hidden-nav').addClass('show');
        }
        else {
            $('#hidden-nav').removeClass('show');
        }
    })

});