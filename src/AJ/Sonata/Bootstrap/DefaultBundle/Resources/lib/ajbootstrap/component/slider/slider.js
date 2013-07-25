/* =============================================================
 * =============================================================
 *
<div class="ui-slider " data-length="6" data-value="4" data-range="min">
	<div class="definition" data-icon="default"></div>
	<div class="definition top" data-value='["XS","S","M","L","XL","XXL"]'></div>
	<div class="definition bottom" data-value='["0 €","0 €","6 €","9 €","12 €","15 €"]'></div>
</div>
<div class="ui-slider" data-ajslider='{"length":6, "value":2, "range":"min"}'>
	<div class="definition" data-icon="default"></div>
	<div class="definition top" data-value='["XS","S","M","L","XL","XXL"]'></div>
	<div class="definition bottom" data-value='["0 €","0 €","6 €","9 €","12 €","15 €"]'></div>
	<a class="ui-slider-handle" href="#"></a>
</div>
<div class="ui-slider" data-ajslider='{"length":16, "values":[2,8], "range":true}'>
	<div class="definition" data-icon="default"></div>
	<div class="definition top" data-value="index"></div>
	<div class="definition bottom" data-value="value"></div>
</div>

<div class="ui-slider" data-length="3" data-value="4" data-orientation="vertical">
#= Doda ikone za vsak step - data-icon = ime ikone
	<div class="definition" data-icon="default"></div>
#= Doda text za vsak step - data-value = vsebina
	<div class="definition top" data-value='["XS","S","M","L","XL","XXL"]'></div>
	<div class="definition bottom" data-value='["0 €","0 €","6 €","9 €","12 €","15 €"]'></div>
</div>
 * 
 * 
 * ============================================================ */

!function( $ ){

	"use strict"
	
	var widgetName = 'ajslider'
	
	/* WIDGET CLASS DEFINITION
	* ========================= */
	var WidgetClass = function ( element, options ) {
		this.$element = $(element)
		this.options = options
		
		// če je nastavljen length nastavimo max
		if(this.options.length > 0) {
			--this.options.length
			this.options.max = (this.options.min + this.options.length)
		}
		// v primeru da ni length pogledamo če je max in nastavimo length
		else if(this.options.max) {
			this.options.length = (this.options.max - this.options.min)
		}
		// drugače nastavimo privzeto vrednost 
		else {
			this.options.length = 1
			this.options.max = (this.options.min + 1)
		}
		
		//debug(this.options)
		
		this._create()
	}

	WidgetClass.prototype = {

		constructor: WidgetClass
		
		, _create: function () {
			var that = this	
			
			
			this.$element.find('.definition').each(function() {
				var $this = $(this)
				if($this.data('test')) {
					debug($this.data('test'))
					debug( typeof $this.data('test'))
				}
				
				if($this.data('value')) {
					var dataValue = $this.data('value')
					var startValue = that.options.min
					
					// če je value index ali text
					if(typeof dataValue == 'string')
					{
						for(var i = 0; i<=that.options.length; i++)
						{
							switch(dataValue[0])
							{
								case 'value':
									$this.append('<div>' + startValue + '</div>')
									++startValue
									break;
								default:
									$this.append('<div>' + i + '</div>')
							}
						}
					}
					// če je svoja vsebina
					else if(typeof dataValue == 'object') {
						for(var i = 0; i<dataValue.length; i++)
						{
							$this.append('<div>' + $.trim(dataValue[i]) + '</div>')
						}
					}
				}
				else if($this.data('icon')) {
					var dataValue = ' ' + $.trim($this.data('icon'))
					
					for(var i = 0; i<=that.options.length; i++)
					{
						$this.append('<div><i class="icon' + dataValue + '"></i></div>')
					}
				}
				else {
					for(var i = 0; i<=that.options.length; i++)
					{
						$this.append('<div>' + (i + 1) + '</div>')
					}
				}
				
				
			})
			var sideOrientation = (this.options.orientation == 'vertical' ? 'bottom' : 'left'); 
			
			var span = (100 / that.options.length)
			this.$element.find('.definition').each(function() {
				var current = 0
				var $children = $(this).children()
				var length = ($children.length - 1)
				
				if(length != that.options.length) {
					span = (100 / length)
				}
				
				$children.each(function(){
					var $this = $(this)
					, index = $this.index()
					
					$this.css(sideOrientation, current + '%');
					current = (span + current)
				})
				
			})
			
			
			this.$element.slider(this.options);
			
			
			this.$element.children().wrapAll('<div class="wrapper"/>')
		}
		, value: function (val) {
			if(val)
			{
				this.$element.slider('value', val)
			}
			else {
				return this.$element.slider('value')
			}
				
		}

		, hide: function () {
			var dimension = this.dimension()
			this.reset(this.$element[dimension]())
			this.transition('removeClass', 'hide', 'hidden')
			this.$element[dimension](0)
		}

		, reset: function ( size ) {
			var dimension = this.dimension()
				this.$element
				.removeClass('collapse')
				[dimension](size || 'auto')
				[0].offsetWidth
				this.$element[size ? 'addClass' : 'removeClass']('collapse')
				return this
		}

	}

	/* COLLAPSIBLE PLUGIN DEFINITION
	* ============================== */

	$.fn[widgetName] = function ( option ) {
	
		return this.each(function () {
			var $this = $(this)
			, widget = $this.data(widgetName)
			, options = (typeof option == 'object' && option)
			
			// če ni instance naredi nov objekt z nastavitvami iz data atrributov 
			if(!widget) {
				$this.data(widgetName, (widget = new WidgetClass(this, $.extend({}, $.fn[widgetName].defaults, options, $this.data()))))
			}
			// če je data in ni instanca widgeta naredi novo instanco z nastavitvami iz svojega data attributa  
			else if(typeof widget == 'object' && !(widget instanceof WidgetClass)) {
				$this.data(widgetName, (widget = new WidgetClass(this, $.extend({}, $.fn[widgetName].defaults, options, widget))))
			}
			
			// izvede funkcijo
			if (typeof option == 'string') widget[option]()
		})
	}

	$.fn[widgetName].defaults = {
		step: 1,
		value: 1,
		min: 1,
		length: 0,
		animate: 'fast',
		orientation: 'horizontal'
	}

	$.fn[widgetName].Constructor = WidgetClass


	/* COLLAPSIBLE DATA-API
	 * ==================== */
	
	$(function () {
		$("[data-ajslider], .ui-slider" ).ajslider();
		
		$(document).on('click.ajslider.data-api', '[data-toggle=ajslider]', function (e) {
			var $this = $(this), href, $target
			, target = $this.attr('data-target')
				|| e.preventDefault()
				|| (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') //strip for ie7
		
			if(typeof target == 'string' && target.substr(0, 6) == 'parent')
			{
				if(target.substr(0, 7) == 'parents')
				{
					var selectors = target.substr(7).split('>>', 2)
					$target = $this.parents(selectors[0]).find(selectors[1])
				}
				else {
					$target = $this.parent().find(target.substr(6))
				}
			}
			else {
				$target = $(target)
			}
			//var option = $target.data('ajslider') ? 'toggle' : $this.data()
			if($this.data('value'))	$target.data('ajslider').value($this.data('value'))
			
			//$target.ajslider)
		})
		
	})

}( window.jQuery );