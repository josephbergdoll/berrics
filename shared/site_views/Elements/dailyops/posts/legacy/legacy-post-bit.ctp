<?php 

	$d = $dop['Dailyop'];
	$m = $dop['DailyopMediaItem'];
	$f = $m[0]['MediaFile'];
	$s = $dop['DailyopSection'];
	$t = $dop['Tag'];
	
	$url = $this->Berrics->dailyopsPostUrl($dop);
	
	//pr($_SERVER);
	
	if($d['hide_media'] == 1) {
		
		$hide_media = "style='height:0px;'";
		
	} else {
		
		$hide_media = "";
		
	}
	
	//loop thru the tags and find certain strings for extra css classes
	
	$extra_class = '';
	
	foreach($t as $tag) {
		
		switch(strtolower($tag['name'])) {
			
			case "team koston":
				$extra_class = "team-koston";
				break;
			case "team berra":
				$extra_class = "team-berra";
				break;
			
		}
	
	}
	
?>
<div class='d-post-bit <?php echo $extra_class; ?>' dailyop_id='<?php echo $d['id']; ?>' >
	<div class='container'>
		<div class='container-top'>
			<div class='title'>
				<h2><?php 
					
					$name = $d['name'];
					
					if(empty($name)) {
						
						$name = $s['name'];
						
					}
					
					echo $this->Berrics->dailyopsPostLink(strtoupper($name),$dop);

					
					?></h2>
				<div class='sub-title'>
					<?php echo $d['sub_title']; ?>
				</div>
			</div>
				
				<div class='display-media' <?php echo $hide_media; ?> >
					<?php 
						echo $this->Berrics->postMediaDiv($dop);
					?>
				</div>
			
		</div>
		
		<div style='clear:both;'></div>
		
		<hr />
		
		<?php 
				if(!empty($d['text_content'])):
				?>
				<div class='text-content'>
					<div class='top'>
					
					</div>
					<div class='center'>
						<p>
							<?php echo nl2br($d['text_content']); ?>
						</p>
						<div style='clear:both;'></div>
					</div>
					<div class='bot'>
					
					</div>
				</div>
				<?php 
					endif;
				?>
				<?php 
				
					if(!empty($d['html_content'])):
				
				
				?>
				<div class='html-content'>
					<?php echo $d['html_content']; ?>
				</div>
				<?php 
				
					endif;
				
				?>
	</div>
	<div class='bottom'>
		<?php 
		
			echo $this->element("dailyops/posts/legacy/post-footer",array("dop"=>$dop));
		
		?>
	</div>
	
</div>