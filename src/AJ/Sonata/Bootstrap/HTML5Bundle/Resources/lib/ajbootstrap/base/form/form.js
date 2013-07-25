$(function(){
	
	$('form').append('<input type="hidden" name="jsBot" value="js-inject" />');
	
	$(".jsFocus").live("focus", function() {
		var _this = $(this);
		if(_this.data('searchText') == null)
		{
			_this.data('searchText', _this.val())
		}
		
		if(_this.val() == _this.data('searchText'))
		{
			_this.val("");
		}
	}).live("blur", function() {
		var _this = $(this);
		if(_this.val() == "") _this.val(_this.data('searchText'));
	})
	
	
	
	/** * * * * 
	 * SUBMIT
	 */
	$('form .js-submit').click(function(){
		//console.log($(this).parents('form:first'));
		$(this).parents("form:first").submit();
		
	})
	
	
	//ENTER
	/*
	$('form input').keypress(function(event){	
		$submit = $(this).parents('form').find('.js-submit');	
		if($submit.length){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){//enter
				$(this).parents("form:first").submit();
			};
		}
	});
	*/
	
 	
    
});