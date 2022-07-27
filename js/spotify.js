/**
 * @param selector
 */
function updatePlayerCurrentTime(selector) {
  let refreshRate = 1000;

  setTimeout(function () {
    let currentTime = parseInt($(selector).attr('duration'));
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

    updatePlayerCurrentTime(selector);
  }, refreshRate);
}

$(document).ready(function () {

  updatePlayerCurrentTime('#widget-spotify-player .current-duration[duration]'); // Will only work if the player is loaded on page load directly.

});