<?php
		$this->Html->script(array(
		
			"http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js",
			"http://connect.facebook.net/en_US/all.js#xfbml=1",
			"http://platform.twitter.com/widgets.js",
			"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js",
		"https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js",
			"/js/jquery.scrollTo",
			"jquery.swfobject",
			"jquery.client",
			"/js/main.js"
		
		),array("inline"=>false));
		$this->Html->css(array(
			"main",
			"layout",
			"layout_override",
			"vader/jquery-ui-1.8.11.custom"
		
		),"stylesheet",array("inline"=>false));
		$this->set("title_for_layout","The Berrics - The Most Award Winning Skateboarding Site In The World");
?>
<script><!--

var swfPlayer = "/swf/BerricsPlayer.swf";

$(document).ready(function() { 
/*

	setTimeout(function() { 

		$("#post,#enter").fadeIn("slow");

	},2200);
*/
	
});

--></script>
<style>
body {

	background-image:url(/img/splash/element-am-video/bg.jpg);
	
	background-position:top center;
	
}
#post {

	width:728px;
	margin:auto;
	margin-top:10px;
	background-image:none;
	
	padding-top:2px;
}
#enter {

	text-align:center;
	

}

#enter a {

	color:#f0f0f0;
	font-size:22px;
	font-family:'Times New Roman';
}

.d-post-bit {

	margin-bottom:2px;

}

.d-post-bit .container,.d-post-bit .container-top,.d-post-bit .bottom  {

	background-image:none;
	background-repeat:repeat;
	min-height:10px;
}

.d-post-bit .container-top .display-media {

	margin:auto;

}

.d-post-bit .container-top .title h2 {

	display:none;

}
.d-post-bit .tags {

display:none;

}

.d-post-bit .container hr {

	display:none;

}

.d-post-bit .bottom .date-info {

	display:none;
}

.video-heading {

	height:249px;
	background-image:url(/img/splash/element-am-video/title-bg.jpg);
	background-repeat:no-repeat;
	background-position: center center;
	min-width:930px;
}

</style>


<div id='gc-trailer'>
	
	<div id='post'>
		<?php echo $this->element("dailyops/post-bit",array("dop"=>$post)); ?>
	</div>
	<div class='video-heading'></div>
	<div id='enter'>
		<a href='/dailyops' title='The DailyOps'><img src='/img/splash/element-am-video/enter.jpg' border='0' /></a>
	</div>
</div>
