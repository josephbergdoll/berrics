<div id='top-banner-container'>
	<div style='text-align:center;'>
		<a href='/news/<?php echo str_replace("-","/",$this->params['date_in']); ?>'>
			<img alt='Aberrican Times' border='0' src='/img/layout/newsv2/header.jpg' />
		</a>
	</div>
	<div class='bottom'>
		<div class='inner'>
			<div class='issue-date'><?php echo strtoupper(date("l F jS, Y",strtotime($this->params['date_in']))); ?></div>
			<img src='/img/layout/newsv2/header-bottom.png' />
		</div>
	</div>
</div>