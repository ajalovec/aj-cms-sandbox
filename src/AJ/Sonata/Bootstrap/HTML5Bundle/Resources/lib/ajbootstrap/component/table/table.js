
jQuery.fn.tablePonudba = function() {
	
	this.children('table').on('mouseover', 'tr:not(.internet-hitrosti) td', function() {
		var _this = $(this)
		var index = (_this.index() + 1)
		var table = _this.parents('table')

			table.find('tr td:nth-child('+index+')').addClass('hovered')
	})
	.on('mouseout', 'tr:not(.internet-hitrosti) td', function() {
		var _this = $(this)
		var index = (_this.index() + 1)
		var table = _this.parents('table')
		
			table.find('tr td:nth-child('+index+')').removeClass('hovered')
	})
}






jQuery(function($) {
	
	$('.table-ponudba').tablePonudba()
	
})

