jQuery(document).ready(function($) {
			$("a[rel=fancy_gallery]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				/*'titlePosition' 	: 'over',
				'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
				    return '<span id="fancybox-title-over">' + title + '</span>';
				}*/
			});
});