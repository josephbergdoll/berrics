<div class='yn3-video-post'>
	<?php if($this->params['isAjax']): ?>
	<div style='height:90px;'></div>
	<?php endif; ?>
	<?php echo $this->element("dailyops/post-bit",array("dop"=>$post)); ?>
	<?php if($this->params['isAjax']): ?>
	<script>FB.XFBML.parse();</script>
	<div style='width:100%; text-align:center; background-color:#000;'>
		<span onclick='Yn3Video.closeVideo();' style='cursor:pointer;'> <span style='color:white; font-family:"Courier"; padding:5px;'>[X] CLOSE</span></span>
	</div>
	<?php else: ?>
	<script>
	berricsRelatedVideoScreen = function(m,d) { Yn3Video.videoOverScreen(m,d); };
	</script>

	<?php endif; ?>
</div>