<?php 
$tag_array = Set::extract("/Tag/name",$this->data);

$tag_str = implode(",",$tag_array);
?>
<div class='form index'>
	<h2>Media File Inspector</h2>
	<?php echo $this->Form->create("MediaFile",array("url"=>$this->here))?>
	<fieldset>
		<legend>Details</legend>
		<div>
			<label>ID</label>
			<div><?php echo $this->data['MediaFile']['id']; ?></div>
		</div>
		<div>
			<label>Date Created</label>
			<div><?php echo $this->Time->niceShort($this->data['MediaFile']['created']); ?></div>
		</div>
		<div>
			<label>Date Modified</label>
			<div><?php echo $this->Time->niceShort($this->data['MediaFile']['modified']); ?></div>
		</div>
		<?php 
			
			echo $this->Form->input("MediaFile.id");
			echo $this->Form->input("MediaFile.name");
			echo $this->Form->input("MediaFile.caption");
			echo $this->Form->input("tags",array("value"=>$tag_str));
			echo $this->Form->submit("Update");
			
		?>
	</fieldset>
	
	<?php 
		
		switch($this->data['MediaFile']['media_type']) {
	
			case "bcove":
				echo $this->element("media_files/inspect_video_still");
				echo $this->element("media_files/inspect_limelight");
				break;
			case "img":
				echo $this->element("media_files/inspect_image");
				echo $this->element("media_files/inspect_image_link");
				break;
			
		}
	?>
	<fieldset>
		<legend>Advertising Tags</legend>
		<p>These properties only apply to video</p>
		<?php 
			
			echo $this->Form->input("MediaFile.preroll_label",array("options"=>Arr::adLabels(),"empty"=>true));
			echo $this->Form->input("MediaFile.preroll_tags");
			echo $this->Form->input("MediaFile.preroll_label_override");
			echo $this->Form->input("MediaFile.postroll_label",array("options"=>Arr::adLabels(),"empty"=>true));
			echo $this->Form->input("MediaFile.postroll_tags");
			echo $this->Form->input("MediaFile.postroll_label_override");
			echo $this->Form->submit("Update");
			
		?>
	</fieldset>
	<?php 
	
		echo $this->Form->end("Update");
	
	?>
</div>