<?php 

$TrendingPost = ClassRegistry::init("TrendingPost");

$trending_posts = $TrendingPost->currentTrending('weekly');

$trending_news = $TrendingPost->currentTrending('featured-news');
//die(pr($trending_news));
?>
<div id="standard-right-column" style='clearfix'>
	<!--Trending Content!-->
	<div id="trending-content">
		<h2>HOT! HOT! HOT!</h2>
		<div class="tab-row clearfix">
			<div class="tab active" data-section='weekly'>The Week</div>
			<div class="tab" data-section='monthly'>This Month</div>
			<div class="tab" data-section='yearly'>This Year</div>
		</div>
		<div class='content'>
			<table cellspacing='0'>
				<tbody class="content">
					<?php foreach ($trending_posts as $k => $v): ?>
					<?php echo $this->element("layout/v3/trending-tr",array("post"=>$v)) ?>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	<hr class='hr1' />
	<?php echo $this->element("banners/300x250") ?>
	<div id="trending-news">
		<h2>LATEST NEWS:</h2>
		<div class="content">
			<table cellspacing="0">
				<tbody class="content">
					<?php 
						foreach ($trending_news as $k => $v): 
						$link = "/".$v['Dailyop']['DailyopSection']['uri']."/".$v['Dailyop']['uri'];
						$t = $v['Dailyop']['DailyopTextItem'][0];
					?>
					<tr>
						<td width='100'>
							<a href='<?php echo $link; ?>'>
							<?php 
								$media_file = $v['Dailyop']['DailyopTextItem'][0]['MediaFile'];
								echo $this->Media->mediaThumb(array(
									"MediaFile"=>$media_file,
									"w"=>90
								));
							?>
							</a>
						</td>
						<td>
							<a href='<?php echo $link; ?>'><?php echo $this->Text->truncate($v['Dailyop']['name'],26); ?></a>
							<div>
								<small>
									<a href='<?php echo $link; ?>'><?php echo $this->Text->truncate($v['Dailyop']['sub_title'],36); ?></a>
								</small>
							</div>
							<p>
								<a href='<?php echo $link; ?>'><?php echo $this->Text->truncate($t['text_content'],60); ?></a>
							</p>
						</td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	<hr class='hr1' />
	<?php echo $this->element("banners/300x250") ?>
	<hr class='hr1' />
	<h2>CONTENT CALENDAR</h2>
	<div id="calendar-widget">
		<?php echo $this->element("dailyops/calendar-widget"); ?>
	</div>
</div>