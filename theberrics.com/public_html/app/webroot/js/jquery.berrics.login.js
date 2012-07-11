(function( $ ){

  var methods = {
		  
		  
		init:function(options) {
		
			$(this).data("BerricsLogin",$.extend(options,{}));
			
		},
		openWindow:function(url) {
				

			var chk = $("#BerricsLogin");
			
			if(chk.length<=0) {
				
				var div = $("<div id='BerricsLogin'/>").
							
							append(
									$("<div class='wrapper' />").
										append(
											"<div class='loading' />"
										).
										append(
											$("<div  class='close-div'/>").append(
													"<div class='close-button'/>"
											)
										).
										append("<div class='content'/>")
									
							);
								
					
					$('body').append(div);
					
					$("#BerricsLogin .close-button").click(function() { methods.closeWindow();  });
					
					div.fadeIn('normal',function() { 
						
						methods.loadAjaxContent(url);
						
					});
					
					methods.handleWindowResize();
					
					
					
			} else {
				
				methods.loadAjaxContent(url);
				
			}

			
		},
		handleWindowResize:function() { 
			
			$('body,html').css({
				
				"overflow":"hidden"
				
			});
			
		},
		closeWindow:function() {
			
			$('#BerricsLogin').fadeOut('normal',function() {
				
				$('#BerricsLogin').remove();
				$('body,html').css({
					
					
					"overflow":"auto"
					
				});
				
			});
			
		},
		loadAjaxContent:function(uri) {
			
			methods.showLoading();
			
			var o = {
					
					"success":function(d) {
						
						methods.hideLoading();
						
						$("#BerricsLogin .content").html(d).fadeIn();
						
						$("#BerricsLogin .content a[rel!=no-ajax]").click(function(e) { 
							
							methods.loadAjaxContent($(e.target).attr("href"));
							
							return false;
							
						});
									
			
						//format all links to use this method
						$("#BerricsLogin .content form[rel!='no-ajax']").each(function(e) { 
							
							methods.initForms(this);
							
						});
						
						
						
					},
					"url":uri
					
				};
			
			$("#BerricsLogin .content").fadeOut("normal",function() { 
				
				$.ajax(o);
				
				
			});
			
			
		},
		showLoading:function() {
			
			$("#BerricsLogin .wrapper").css({
				
				"background-image":"url(/img/layout/ajax-loader.gif)"
				
			});
			
		},
		hideLoading:function() { 
			
			$("#BerricsLogin .wrapper").css({
				
				"background-image":"none"
			});
			
		},
		initForms:function(e) {
			
			$(e).ajaxForm({
				
				"success":function(d) {
					
					methods.hideLoading();
					
					$('#BerricsLogin .content').html(d).fadeIn();
					
					$("#BerricsLogin .content form").each(function(e) { 
						
						methods.initForms(this);
						
					});
					
					$("#BerricsLogin .content a[rel!=no-ajax]").click(function(e) { 
						
						methods.loadAjaxContent({
							
							"url":$(e.target).attr("href")
							
						});
						
						return false;
						
					});
					
					
				},
				'beforeSubmit':function() {
					
					methods.showLoading();
					
					$('#BerricsLogin .content').fadeOut();
					
					return true;
					
				}
				
			});
		}
	
	
		  
  };

  $.BerricsLogin = function( method ) {
    
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on Berrics Login' );
    }    
  
  };

})( jQuery );