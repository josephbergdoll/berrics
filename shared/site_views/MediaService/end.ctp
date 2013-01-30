<div class="post-related">

	<div class="top">
		<div class="logo">
			<img src="/img/v3/player/related-logo.jpg" alt="" />
		</div>
			
		<div class='heading'>
					JUST WATCHED:
		</div>
			
		<div class='row-fluid'>
			<div class="span8">
				<div class="post-title">
				<?php echo $post['Dailyop']['name']; ?>
				</div>
				<div class="sub-title">
					<?php echo $post['Dailyop']['sub_title'];  ?>&nbsp;
				</div>
			</div>
			<div class="span4">
				<div class="replay-btn">
					<a href="/<?php echo $post['DailyopSection']['uri'] ?>/<?php echo $post['Dailyop']['uri'] ?>"><img src="/img/v3/player/related-replay-btn.jpg" alt="" border='0'></a>
				</div>
			</div>
		</div>
	</div>
	<div class='heading'>
					RELATED:
			</div>
	<div class="thumb-collection clearfix">
		<?php foreach ($posts as $k => $v): ?>
			<?php echo $this->element("dailyops/thumbs/standard-post-thumb",array("post"=>$v)); ?>
		<?php endforeach ?>
	</div>
	<div class="heading">
		TAGS:
	</div>
	<div class="tag-collection">
		<?php echo $this->Berrics->parseTagLinks($post['Tag']); ?>
	</div>
</div>
<script>
jQuery(document).ready(function($) {
	lazyLoad();
initMediaDivs();
});

</script>