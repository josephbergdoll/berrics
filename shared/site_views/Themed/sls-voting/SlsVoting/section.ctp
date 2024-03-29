<?php 

$this->set("title_for_layout","Street League 2012 - The Selection");
$this->Html->script(array("dailyops/post-bit"),array("inline"=>false));
$kw = array();

foreach($entries as $e) {
	
	foreach($e['Tag'] as $t) $kw[$t['id']] = $t['name'];

}

$this->set("meta_k",implode($kw,","));

$this->Html->script(array("jquery.cookie.js","section"),array("inline"=>false));

?>
<div id='sls-voting-section'>
	<div class='inner'>
		<?php 
		if(isset($post)) { 
			
			echo $this->element("video-post",array("dop"=>$post));
			echo "<div id='scroll-chk'></div>";
		}
		?>
		<div class='voting-heading'></div>
		<div class='vote-section'>
			<div class='entries'>
				<div>
					<img border='0' src='/theme/sls-voting/img/selection-heading.png' />
				</div>
				<div class='entry-div-wrapper'>
				<?php foreach($entries as $v): ?>
					<div class='entry-div' style='background-image:url(http://img.theberrics.com/images/<?php echo $v['DailyopMediaItem'][1]['MediaFile']['file']; ?>);' dailyop_id='<?php echo $v['Dailyop']['id']; ?>'>
						<div class='play-button'>
							<a href='/street-league-selection-2012/<?php echo $v['Dailyop']['uri']; ?>'>Play Video</a>
						</div>
						<div class='vote-button'>
							
							 
						</div>
					</div>
					<div class='entry-spacer'></div>
				<?php endforeach; ?>
				</div>
			</div>
			<div class='voting'>
				<div class='rules'>
					<div style='text-align:center;'>
						<img border='0' src='/theme/sls-voting/img/rules-heading.jpg' />
					</div>
					<div class='inner'>
You've heard of the 'sponsor me' tape, right?  Well guess what?  The Selection is that on steroids.  Street League has 5 open slots and these 10 guys each put together an edit of new and unseen footage in hopes to be selected as one of 2012's newest Street League pros.  Watch their videos, and pick your top 5.  <em>Their fate is in your hands and our panel of Street League Pro's</em>				</div>
					<div style='text-align:center;'>
						<img border='0' src='/theme/sls-voting/img/grand-prize-heading.jpg' />
					</div>
					<div class='inner'>
						5 people will win tickets and VIP passes for themselves and 2 friends to a <em>SLs 2012</em> event of their choice.  How?  Correctly vote for the 5 qualifiers of The Selection and you will be instantly entered into the drawing.
					</div>
				</div>
				<div class='voting-form'>
					<div style='text-align:center; margin-bottom:-15px;'>
						<img border='0' src='/theme/sls-voting/img/your-vote-heading.jpg' />
					</div>
					<div class='inner'>
						<div style='font-size:12px; text-align:center;'>Click "Place Vote" on the left</div>
						<?php if($this->Session->check("Auth.User.id")): ?>
						<div style='font-style:italic; font-size:12px; text-align:center;'>Logged in as: <?php echo $this->Session->read("Auth.User.email"); ?> (<a href='/identity/login/logout/<?php echo base64_encode($this->here); ?>'>Logout</a>)</div>
						<?php else: ?>
						<div style='text-align:center;'><img src='/theme/sls-voting/img/facebook-sign-in.png' align='absmiddle' border='0'/> A valid facebook account is required to vote</div>
						<?php endif;?>
						<?php for($i=0;$i<5;$i++): ?>
							<?php 
								if($this->Session->check("Auth.User.id") && isset($votes[$i])): 
									foreach($entries as $v) if($v['SlsEntry']['id'] == $votes[$i]) $e=$v;
							?>
							<div class='vote-result-div' style='background-image:url(http://img.theberrics.com/images/<?php echo $e['DailyopMediaItem'][2]['MediaFile']['file']; ?>);'>
								
							</div>
							<?php else: ?>
								<div class='no-vote'>
								</div>
							<?php endif; ?>
						<?php endfor; ?>
					</div>
				</div>
			</div>
			<div style='clear:both;'></div>
		</div>
	</div>
</div>


<?php if(preg_match('/\/dailyops/',$_SERVER['REQUEST_URI'])): ?>
<div style='width:728px; margin:auto; padding-top:80px;'>
	<div id='paging-menu'>
		<div class='left'>
		<?php 
		
			if($newer_date && !preg_match('/^(\/dailyops)/',$_SERVER['REQUEST_URI'])) {
				$newer_date = strtotime($newer_date);
				echo $this->Html->link("<span> ".date("F jS, Y",$newer_date)."</span>",date("/Y/m/d",$newer_date),array("escape"=>false,"title"=>date("F jS, Y",$newer_date)));
				
			}
			
		?>
		</div>
		<div class='right'>
		<?php 
			
			$older_date = "2012-04-01";
		
			if($older_date) {
				
				$older_date = strtotime($older_date);
				echo $this->Html->link("<span>".date("F jS, Y",$older_date)."</span>",date("/Y/m/d",$older_date),array("escape"=>false,"title"=>date("F jS, Y",$older_date)));
				
			}
			
		?>
		</div>
		<div style='clear:both'></div>
	</div>
<?php 

echo $this->element("dailyops/date-bit");

?>
</div>
<?php endif; ?>