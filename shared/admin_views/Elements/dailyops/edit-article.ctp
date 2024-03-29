<script type="text/javascript">

$(document).ready(function() { 


	var ac = $('.text-item').length;

	$("#article-badge").html(ac);

	$('.remove-text-item').click(function() { 

		removeTextItem($(this).val());

	});

	$('.remove-media').click(function() { 

		removeMedia($(this).val());

	});


	$('.attach-media-button').click(function() { 

		var form = $("#article-form");

		var uri = $(this).attr("href");

		form.attr("autosave","autosave");

		form.ajaxForm(function() { 

			document.location.href=uri;

		});

		form.submit();

		return false;

	});

	initTextContentStyles();
	

	$(".text-content-style-select").change(function() { 
	
		initTextContentStyles();

	});

	
});

function removeTextItem($id) {

	var form = $("#article-form");

	form.append($("<input />").attr({

		"type":"hidden",
		"name":"data[DailyopTextItem][RemoveTextItem]",
		"value":$id

	}));

	form.attr("autosave","autosave");
	form.submit();
	form.removeAttr("autosave");
}

function removeMedia($id) {

	var form = $("#article-form");

	form.append($("<input />").attr({

		"type":"hidden",
		"name":"data[DailyopTextItem][RemoveMedia]",
		"value":$id

	}));

	form.attr("autosave","autosave");
	form.submit();
	form.removeAttr("autosave");
	
}


function initTextContentStyles() {

	
	$(".text-content-style-select").each(function() { 
	
		var ind = $(this).attr('data-index');
		var val = $(this).val();
		switch(val) {

			case "interview-two-col":
			case "interview-two-col-right":
				$('textarea[name="data[DailyopTextItem]['+ind+'][text_content_2]"]').show();
				$('textarea[name="data[DailyopTextItem]['+ind+'][text_content_3]"]').hide();
			break;
			case "interview-three-col":
			case "interview-three-col-right":
				$('textarea[name="data[DailyopTextItem]['+ind+'][text_content_2]"]').show();
				$('textarea[name="data[DailyopTextItem]['+ind+'][text_content_3]"]').show();
			break;
			case "interview-one-col-left":
			case "interview-one-col-right":
			default:
				$('textarea[name="data[DailyopTextItem]['+ind+'][text_content_2]"]').hide();
				$('textarea[name="data[DailyopTextItem]['+ind+'][text_content_3]"]').hide();
			break;


		}

	});
	


}



</script>
<?php 

$types = MediaFile::mediaFileTypes();

$sort = array();
for($i=0;$i<=99;$i++) $sort[$i] = $i;

$url = array(
			"action"=>"handle_tab_save"
		);

echo $this->Form->create("Dailyop",array("url"=>$url,"id"=>"article-form"));
echo $this->Form->input("element",array("type"=>"hidden","value"=>"edit-article"));


?>
<?php echo $this->Session->flash(); ?>
<h3>Article Content</h3>
<div class='btn-toolbar'>
	<a href='<?php echo $this->Admin->url(array("action"=>"add_text_item",$this->request->data['Dailyop']['id'])); ?>' class='btn btn-success'><i class='icon icon-plus-sign icon-white'></i> Add New Text Item</a>
</div>
<?php 
foreach($this->request->data['DailyopTextItem'] as $k=>$v):
?>

<div class='row-fluid text-item'>
	
	<div class='well clearfix'>

	<div class='span8' style='margin-bottom:3px;'>
				
		<?php 
			echo $this->Form->input("DailyopTextItem.{$k}.media_gallery");
			$this->Form->formSpan = "span12";
			echo $this->Form->input("DailyopTextItem.{$k}.id");
			echo $this->Form->input("DailyopTextItem.{$k}.display_weight",array("options"=>$sort));
		?>
		
		<div class="row-fluid">
			<div class="span12">
				<?php echo $this->Form->input("DailyopTextItem.{$k}.heading"); ?>
			</div>
		</div>
		<?php
			echo $this->Form->input("DailyopTextItem.{$k}.text_content_style",array("options"=>DailyopTextItem::textContentStyles(),'class'=>'text-content-style-select','data-index'=>$k));
			echo $this->Form->input("DailyopTextItem.{$k}.text_content");
			echo $this->Form->input("DailyopTextItem.{$k}.text_content_2",array("label"=>false));
			echo $this->Form->input("DailyopTextItem.{$k}.text_content_3",array("label"=>false));
			
		?>
		<button class='btn btn-primary'>Update Text</button>
		<button class='btn btn-danger remove-text-item' value='<?php echo $v['id']; ?>' type='button' ><i class='icon icon-white icon-minus-sign'></i> Remove</button>
	</div>
	<div class='span4'>
		<div class='row-fluid'>
			<div class='span12'>
				<?php 
					if(!empty($v['MediaFile']['id'])):
				?>
					<div>
						<span class='label label-info'>
							<?php echo strtoupper($types[$v['MediaFile']['media_type']]); ?>
						</span>
					</div>
					<?php echo $this->Media->mediaThumb(

							array(
								"MediaFile"=>$v['MediaFile'],
								"h"=>120		
							)
							
					); ?>
				<?php 
					else:
				?>
				
				<?php 
					endif;
				?>
			</div>
		</div>
		<div class='row-fluid'>
			<div class='span6'>
				<?php echo $this->Form->input("DailyopTextItem.{$k}.thumb_width"); ?>
			</div>
			<div class='span6'>
				<?php echo $this->Form->input("DailyopTextItem.{$k}.thumb_height"); ?>
			</div>
		</div>
		<div class='row-fluid'>
			<div class='span12'>
				<?php echo $this->Form->input("DailyopTextItem.{$k}.placement",array("options"=>DailyopTextItem::placements())); ?>
			</div>
		</div>
		<div class='row-fluid'>
			<div class='span12'>
				<div class=''>
					<button class='btn btn-primary'><i class='icon icon-white icon-edit'></i> Update</button>
					<a href='<?php echo $this->Admin->url(array("controller"=>"attach_media","action"=>"index","DailyopTextItem","dailyop_id",$v['id'])); ?>/tab:article' class='btn btn-success attach-media-button'><i class='icon icon-white icon-plus-sign'></i> Attach Media</a>
					<?php if(!empty($v['MediaFile']['id'])): ?>
					<button class='btn btn-danger remove-media' value='<?php echo $v['id']; ?>' ><i class='icon icon-white icon-minus-sign'></i> Remove Media</button>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>


<?php 
endforeach;
?>
<?php 
echo $this->Form->end();
?>