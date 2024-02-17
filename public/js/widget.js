camelize = s => s.replace(/-./g, x => x[1].toUpperCase()); // Short function to camelize kebab strings

/**
 * Refreshes the widget on the given interval.
 * @param integration
 * @param widgetName Widget name is kebab-case.
 * @param refreshRate Refresh rate in milliseconds.
 * @returns {Promise<void>}
 */
async function autoRefreshWidget(integration, widgetName, refreshRate) {
  if (refreshRate !== undefined) {

    setTimeout(function () {
      let widgetNameCamelized = camelize(widgetName);

      $.ajax({
        url: '/widgets/ajaxWidget?huts=HUTS&integration=' + integration + '&widget=' + widgetNameCamelized,
      }).done(function (data) {
        if (data.error !== undefined) {
          console.log(data.error);
          return;
        }

        $('#widget-' + integration + '-' + widgetName).replaceWith(data);
        console.log('Refreshed widget: ' + widgetNameCamelized); // For debugging
      });

      autoRefreshWidget(integration, widgetName, refreshRate);
    }, refreshRate);

  }
}

$(document).ready(function () {

  let widgets = $('.widget[refresh-rate]');

  widgets.each(function () {
    let id = $(this).attr('id');

    let platform = id.match('\-([a-z]*)\-')[1];
    let widgetName = id.match('\-[a-z]*\-(.*)')[1];
    let refreshRate = $(this).attr('refresh-rate');

    autoRefreshWidget(platform, widgetName, refreshRate);
  });

});