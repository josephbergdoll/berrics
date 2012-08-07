<?php 
	echo $this->Html->css(array("uploadify.css"),"stylesheet");
?>
<script type='text/javascript'>
$(document).ready(function() { 

	
	$("#MediahuntMediaItemImageFile").uploadify({
			
				'swf'      : '/swf/uploadify.swf',
	        	'uploader' : '/levis-511/handle_upload?xid=<?php echo $this->Session->id(); ?>',
	        	"fileTypeExts":"*.gif; *.jpg; *.jpeg; *.png",
	        	"debug":true,
	        	"buttonImage":"/theme/levis-511-contest/img/select-an-image.jpg",
	        	"height":"100",
	        	"width":"100",
	        	"onUploadSuccess":function(f,d,r) {

	        		var data = eval("("+d+")");

					$("#MediahuntMediaItemFileName").val(data.file_name);



					showTmpThumb(data.file_name);
					removeFormOverlay();
		        		
	        	},
	        	'onUploadStart':function() {
					showFormOverlay("test");
	        	},
	        	"queueID":"fileQueue"
	            // Put your options here
		            
	});



	$("#reset-form").click(function() { 

		document.location.reload(true);

	});

	$('.instagram-thumb').click(function() { 

		var id = $(this).attr("instagram_id");

		alert(id);

	});
	
});

function showTmpThumb(file) {

	var url = "http://img.theberrics.com/i.php?src=/tmp/"+file+"&w=100&h=100&zc=1";

	var img = $("<img />").attr({
			"src":url,
			"border":"0"
		});

	$("#SwfUpload").html(img);
	
}

function showFormOverlay(msg) {

	var div = $("<div class='levis-upload-overlay'/>").append("<div class='form-msg'/>");

	$("#levis-upload-overlay .form-msg").html(msg);
	
	$("#levis-task").append(div);
	
}

function removeFormOverlay() {

	$('.levis-upload-overlay').remove();
	
}
</script>
<style>

#LevisOverlay .wrapper {
	
	width:650px;
	margin:auto;

}

#levis-task {

	background-color:#333;
	padding:5px;
	position:relative;
}
.instagram-thumb {

	float:left;
	margin:20px;

}
#fileQueue {
	
	displauy:none;
	height:0px;
	width:0px;
	overflow:hidden;
}

.levis-upload-overlay {

	position:absolute;
	height:100%;
	width:100%;
	background-color:red;
	top:0px;
	left:0px;
	
}
#SwfUpload {

	width:100px;
	height:100px;
	

}
.uploadify-button {
        background-color: transparent;
        border: none;
        padding: 0;
        height:100px;
        width:100px;
    }
.img-thumb {

	float:left;
	width:120px;
	height:100px;

}
.form-fields {

	float:right;
	width:300px;

}
</style>
<div id='levis-task'>
<?php echo $this->Session->flash(); ?>
<div class='task-heading'>
	<strong> TASK: </strong> <?php echo $task['MediahuntTask']['name']; ?>
</div>
<div id='upload-form-div'>
	STANDARD UPLOADING STUFF GOES HERE
	<?php if(isset($task['MediahuntMediaItem']['id'])): ?>
		MEDIA ITEM ELEMENT
	<?php else: ?>
		<?php 
		
			echo $this->Form->create("MediahuntMediaItem",array("url"=>array("action"=>"handle_submit","controller"=>$this->params['section']),"rel"=>"no-ajax")); 
		?>
		<div class='img-thumb'>
			<?php echo $this->Form->input("image_file",array("type"=>"file","div"=>array("id"=>"SwfUpload"),"label"=>false)); ?>
		</div>
		<div class='form-fields'>
			<?php 
			
			
				
				echo $this->Form->input("file_name",array("type"=>"hidden"));
				echo $this->Form->input("mediahunt_task_id",array("value"=>$task['MediahuntTask']['id'],"type"=>"hidden"));
				echo $this->Form->input("instagram_id",array("type"=>"hidden"));
			?>
		</div>
		<div style='clear:both;'></div>
		<div>
			<?php 
				echo $this->Form->submit("Submit Entry");
				echo $this->Form->button("Reset Form",array("id"=>"reset-form")); 
			?>
		</div>
		<?php
			echo $this->Form->end();
			
		?>
		
	<?php endif; ?>
	<div id='fileQueue'></div>
</div>
<div>
<?php 
		echo $this->Html->link("Instagram Connect",array(
					"plugin"=>"identity",
					"controller"=>"login",
					"action"=>"send_to_instagram",
					base64_encode($this->here)
				),array("rel"=>"no-ajax"));
	?>
	<?php if(isset($instagram_images)): ?>
	<div class='instagram-heading'>Instagram: <strong>@<?php echo $this->Session->read("Auth.User.instagram_handle"); ?></strong></div>
	<div class='instagram-thumbs'>
		<?php foreach($instagram_images->data as $img): ?>
		<div style='float:left' class='instagram-thumb' instagram_id='<?php echo $img->id; ?>'>
			<img src='<?php echo $img->images->thumbnail->url; ?>' border='0' height='75' width='75' />
		</div>
		<?php endforeach; ?>
		<div style='clear:both;'></div>
		<?php //print_r($instagram_images); ?>
	</div>
	<?php else: ?>
	<?php 
		echo $this->Html->link("Instagram Connect",array(
					"plugin"=>"identity",
					"controller"=>"login",
					"action"=>"send_to_instagram",
					base64_encode($this->here)
				),array("rel"=>"no-ajax"));
	?>
	<?php endif; ?>
</div>
</div>
<?php 
print_r($task);
print_r($mediaItem);
print_r($instagram_images);
?>