/* ==========================================================
 * bootstrap-carousel.js v2.2.1
 * http://twitter.github.com/bootstrap/javascript.html#carousel
 * ==========================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */

!function ($) {

	"use strict"; // jshint ;_;
	
	var widgetName = 'filmstrip';

	/* WIDGET CLASS DEFINITION
	* ========================= */
	var WidgetClass = function (element, options) {
		this.$element = $(element)
		this.options = options
		
		this.options.slide && this.slide(this.options.slide)
		this.options.pause == 'hover' && this.$element
			.on('mouseenter', $.proxy(this.pause, this))
			.on('mouseleave', $.proxy(this.cycle, this))
			
		NavUpdate({currentTarget:element})
		this.$element.on('slid', NavUpdate)
		
	}
	var NavUpdate = function(e) {
		var $this = $(e.currentTarget)
			, index = $this.find('.item.active').index()
			, $nav =  $this.find('.carousel-nav')
		//$this.data('carousel').slide()
			
		var current = $nav.find('.btn:eq('+index+')')
		$nav.find('.btn.active').removeClass('active')
		//$('[data-slide=to][data-target]')
		current.addClass('active')
		
	}

	WidgetClass.prototype = {

		cycle: function (e) {
			if (!e) this.paused = false
			this.options.interval
				&& !this.paused
				&& (this.interval = setInterval($.proxy(this.next, this), this.options.interval))
			return this
		}

	, next: function (pos) {
			var $active = this.$element.find('.item.active')
				, children = $active.parent().children()
				, activePos = children.index($active)
				, that = this

			
		}

	}


	/* CAROUSEL PLUGIN DEFINITION
	* ========================== */
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
			
			var action = typeof option == 'string' ? option : options.slide
			// izvede funkcijo
			if (typeof option == 'number') widget.to(option)
			else if (action) widget[action]()
			else if (widget.options.interval) widget.cycle()
			
		})
	}

	$.fn[widgetName].defaults = {
		interval: 5000
		, pause: 'hover'
	}

	$.fn[widgetName].Constructor = WidgetClass


	/* CAROUSEL DATA-API
	* ================= */
	
	$(function () {
		$(document).on('click.carousel.data-api', '[data-slide]', function (e) {
			var $this = $(this), href
				, $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
				, options = $.extend({}, $target.data(), $this.data())
				
			//debug(options)
			if(options.slide == 'to') {
				$target.carousel(options.value)
			}
			else {
				$target.carousel(options)
			}
			
			e.preventDefault()
		})
		
		
		$("[data-carousel], .carousel" )[widgetName]();
		
	})
	

}(window.jQuery);