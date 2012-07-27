(function( $ ){

  var methods = {
		  
		  
		init:function(options) {
		
			$(this).data("BerricsLogin",$.extend(options,{}));
			
		},
		openWindow:function() {
		
			var data = $.data("BerricsLogin");
			
			var div = $("<div id='BerricsLogin'/>").
							append("<div class='wrapper' />").
								append("<div class='content'/>");
			
			$('body').append(div);
			
		},
		handleWindowResize:function() { 
			
			
		},
		closeWindow:function() {}
	
	
		  
  };

  $.fn.BerricsLogin = function( method ) {
    
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on Berrics Login' );
    }    
  
  };

})( jQuery );