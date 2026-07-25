/**
 * @param selector
 */
function updatePlayerCurrentTime(selector) {
    let refreshRate = 1000; // 1 second

    setTimeout(function () {
        let currentTime = parseInt($(selector).attr('duration'));
        let totalTime   = parseInt($(selector).attr('total-duration'))

        if (currentTime < totalTime) { // Do not exceed total time of the track. Should be changed with a pause state on the widget.

            let updatedTime = currentTime + refreshRate;

            let totalSeconds = updatedTime / 1000;
            let totalMinutes = totalSeconds / 60;

            let minutes = Math.floor(totalMinutes);
            let seconds = Math.floor(totalSeconds - (minutes * 60));

            if (seconds < 10) {
                seconds = '0' + seconds;
            }

            $(selector).attr('duration', updatedTime).html(minutes + ':' + seconds);
            $(selector).parent().parent().find('progress').val(updatedTime);

        } else {

        }

        updatePlayerCurrentTime(selector);
    }, refreshRate);
}

$(document).ready(function () {

    updatePlayerCurrentTime('#widget-spotify-player .current-duration[duration]'); // Will only work if the player is loaded on page load directly.

});