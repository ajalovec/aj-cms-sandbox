
jQuery(function($){


	$('.grid-filmstrip').each(function() {
		var defaultOptions = {
			duration: 1000,
			animationDuration: 1000,
			cycle: true,
			pause: true
		}

		var $this = $(this)
		  , _this = this
		  , container = $this.children('ul')

		this.opt = $.extend(defaultOptions, $this.data())
		
		this.isCycling = false

		this.next = function(e){
			if(_this.isCycling) return
			var li = container.children("li:first")
			  , margin = li.css('marginLeft')
			
			_this.isCycling = true
			li.animate({
					marginLeft: -(li.outerWidth(true))
				}, _this.opt.animationDuration, function() {
				    container.append(li)
				    li.css('marginLeft', margin)
				    _this.isCycling = false
				}
			);
		}

		this.prev = function(e){
			if(_this.isCycling) return
			var li = container.children("li:last")
			  , margin = li.css('marginLeft')
			
			li.css('marginLeft', -(li.outerWidth(true)))
			container.prepend(li)

			_this.isCycling = true
			li.animate({
					marginLeft: margin
				}, _this.opt.animationDuration, function() {
				    li.css('marginLeft', margin)
				    _this.isCycling = false
				}
			);
		}

		this.cycle = function (e) {
			if (!e) _this.paused = false
			_this.opt.duration
				&& !_this.paused
				&& (_this.interval = setInterval($.proxy(_this.next, _this), _this.opt.duration))
			return _this
		}


		//$this.find('.btn.icon-next').click(this.next)
		$this.find('.btn.icon-next').click($.proxy(_this.next, _this))
		$this.find('.btn.icon-prev').click($.proxy(_this.prev, _this))

		if(this.opt.pause)
		{
			$this.mouseenter(function(e){
				clearInterval(_this.interval)
				_this.interval = null
			})

			$this.mouseleave(function(e){
				_this.cycle()
			})
		}

		if(this.opt.cycle) this.cycle()


	})
	
});