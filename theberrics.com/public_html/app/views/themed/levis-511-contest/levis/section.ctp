<?php 

$this->Html->script(array("LevisContest","jquery.uploadify-3.1"),array("inline"=>false));

?>
<script>
$(document).ready(function() { 

	var use_base = true;


	$(".task-unpublished").css({
			opacity:.5
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

			if($("#LevisOverlay").length>0) {

				$.LevisContest('handleClose');
				$.bbq.removeState("levis");
				
			}

		}
		
	});

	$(window).trigger('hashchange');

	
	
});
</script>
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
		<div class='heading'></div>
		<?php if(isset($image) && !empty($image)): ?>
			<div class='image-view'>
				<div class='image-file'>
					<img border='0' src='http://img.theberrics.com/i.php?src=/mediahunt-media/<?php echo $image['MediahuntMediaItem']['file_name']; ?>&w=275' />
				</div>
				<div class='image-user'>
					<div class='image-task'><span>TASK: </span><?php echo $image['MediahuntTask']['name']; ?></div>
					<div class='image-user'><span>Photo By: </span><?php echo $image['User']['first_name']; ?> <?php echo $image['User']['last_name']; ?></div>
					<?php if($image['MediahuntMediaItem']['instagram_id']): ?>
					<div>Instagram: </div>
					<?php endif; ?>
				</div>
				<div style='clear:both;'></div>
			</div>
		<?php endif; ?>
		<?php foreach($tasks as $task): ?>
			<?php echo $this->element("task",array("task"=>$task)); ?>
		<?php endforeach; ?>
		<div style='clear:both;'></div>
		<div class='bottom'></div>
	</div>
	<div style='clear:both;'></div>
</div>
<pre>
<?php 
print_r($tasks);
?>
</pre>