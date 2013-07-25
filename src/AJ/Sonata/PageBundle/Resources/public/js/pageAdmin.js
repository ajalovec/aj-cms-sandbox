(function(){
    // Your base, I'm in it!
    var originalAddClassMethod = jQuery.fn.addClass;
    var originalRemoveClassMethod = jQuery.fn.removeClass;
    
    jQuery.fn.addClass = function(){
        // Execute the original method.
        var result = originalAddClassMethod.apply( this, arguments );

        // trigger a custom event
        jQuery(this).trigger('cssClassChanged');

        // return the original result
        return result;
    }
    
    jQuery.fn.removeClass = function(){
        // Execute the original method.
        var result = originalRemoveClassMethod.apply( this, arguments );

        // trigger a custom event
        jQuery(this).trigger('cssClassChanged');

        // return the original result
        return result;
    }
})();
$(document).ready(function() {
	$(".tabbable .nav-tabs li").on("cssClassChanged", function(){
		if($(this).hasClass('active')){
			$(this).find(".sonata-ba-collapsed-hidden").val("enabled");
		}else{
			$(this).find(".sonata-ba-collapsed-hidden").val("");
		};
	});
});