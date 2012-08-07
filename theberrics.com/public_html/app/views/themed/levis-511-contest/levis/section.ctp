<?php 

$this->Html->script(array("LevisContest","jquery.uploadify-3.1"),array("inline"=>false));

?>
<script>
$(document).ready(function() { 

	var use_base = true;
	
	$('#test-button').click(function() { 

		$.LevisContest('openWindow',{
			'url':"/levis-511/upload_image"
		});

	});

	$("#levis-511-section a[rel!='no-ajax']").click(function() { 

		var ref = $(this).attr("href");

		if(use_base) ref = Base64.encode(ref);
		
		var state = {};

		state['levis'] = ref;

		$.bbq.pushState(state);

		//document.location.hash = "#!"+ref
		
		return false;
		
	});

	$(window).bind('hashchange',function(e) { 

		var levis = $.bbq.getState('levis') || '';

		if(use_base && levis.length>0) levis = Base64.decode(levis);

		if(levis.length>0 && levis.match(/(levis)/)) {
			
			$.LevisContest('openWindow',{
				'url':levis
			});
			
		} else {
			
			$.LevisContest('handleClose');
			$.bbq.removeState("levis");
			
		}

		var login = $.bbq.getState("login") || '';

		if(login.length>0 && login == 1) {

			$.LevisContest('handleClose');
			$.bbq.removeState("levis");

			$.BerricsLogin('openWindow');
			
		}
		
		
	});

	$(window).trigger('hashchange');

	$("#test-login").click(function() { 

		$.BerricsLogin('openWindow',"/identity/login/form");

	});
	
});
</script>
TEST
<input type='button' value='testing' id='test-button' />
<div id='levis-511-section'>

	<div class='profile-column'>
		<div>
			<?php if($this->Session->check("Auth.User.id")): ?>
			
			<?php else: ?>
			<input type='button' value='login' id='test-login' />
			<?php endif; ?>
		</div>
	</div>
		<div class='task-column'>
		<?php foreach($tasks as $task): ?>
		<div class='task'>
			<?php echo $task['MediahuntTask']['name']; ?>
			<div>
				<?php echo $task['MediahuntTask']['details']; ?>
			</div>
			<div>
				<?php 
					
					if($this->Session->check("Auth.User.id")) {
						
						$lnk = "/{$this->params['section']}/tasks/{$task['MediahuntTask']['id']}";
						$rel = "";
						
					} else {
						
						$lnk = "#BerricsLogin=1";
						$rel = "no-ajax";
					}
				
				?>
				<a href='<?php echo $lnk; ?>' rel='<?php echo $rel; ?>'>Add A Photo</a>	
			</div>
		</div>
		<?php endforeach; ?>
		<div style='clear:both;'></div>
	</div>
	<div style='clear:both;'></div>
</div>