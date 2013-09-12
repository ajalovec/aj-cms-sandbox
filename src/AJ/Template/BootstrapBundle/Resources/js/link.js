

!function ($) {

	"use strict"; // jshint ;_;

	var widgetName = 'link'

 /* LINK PLUGIN DEFINITION
	* ======================== */
	
	$(document).on('click.link.data-api', '[data-href]', function (e) {
		var $btn = $(e.currentTarget)
		var href = $btn.data('href')
		
		location.href = href
		e.stopPropagation()
	})

}(window.jQuery);

