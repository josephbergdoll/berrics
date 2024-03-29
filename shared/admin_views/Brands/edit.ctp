<?php 

$verb = "Edit Brand";

if($this->params['action'] == 'add') {
	
	$verb = "Add New Brand";
	
}

$tag_str = '';

foreach($this->data['Tag'] as $tag) $tag_str .= $tag['name'].",";

$this->data['Brand']['tags'] = $tag_str;

?>
<script>

$(document).ready(function() { 


	$("#BrandEstDate").datepicker({
		"dateFormat":"yy-mm-dd"
	});

	
});


</script>
<div class="brands form">
<?php echo $this->Form->create('Brand',array("enctype"=>"multipart/form-data"));?>
	<fieldset>
 		<legend><?php echo $verb; ?></legend>
	<?php
	
		echo $this->Form->input('id');
		echo $this->Form->input('active');
		echo $this->Form->input('featured');
		echo $this->Form->input('est_date');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('website_url');
		echo $this->Form->input("tags");
		
		
	?>
	</fieldset>
	<fieldset>
		<legend>Canteen Logo</legend>
		<?php if(!empty($this->data['Brand']['canteen_logo'])): ?>
		<?php echo $this->Media->brandLogoThumb(array(
				"Brand"=>$this->data['Brand'],
				"h"=>100,
				"canteen"=>true
			));?>
		<?php else: ?>
		No Logo Uploaded
		<?php endif; ?>
		<?php 
			echo $this->Form->input("new_canteen_logo",array("type"=>"file"));
			echo $this->Form->submit("Update Canteen Logo",array("name"=>"data[CanteenLogo]"));
		?>
	</fieldset>
	<fieldset>
		<legend>Logo</legend>
		<?php if(!empty($this->data['Brand']['image_logo'])): ?>
			<?php echo $this->Media->brandLogoThumb(array(
				"Brand"=>$this->data['Brand'],
				"h"=>100
			));?>
		<?php else: ?>
		No Logo Uploaded
		<?php endif; ?>
		<?php 
		echo $this->Form->input('image_logo',array('type'=>'file','label'=>'Logo Image'));
		?>
	</fieldset>
	
<?php echo $this->Form->end(__('Submit', true));?>
</div>
