<div class='index'>
	<h2>
		Event: <?php echo $mediahuntEvent['MediahuntEvent']['name']; ?>
	</h2>
	<div>
		<fieldset>
			<legend>Actions</legend>
			<ul class='actions'>
			<li>
				<a href='/mediahunt_tasks/add/mediahunt_event_id:<?php echo $mediahuntEvent['MediahuntEvent']['id']; ?>/callback:<?php echo base64_encode($this->here); ?>'>Add New Task</a>
			</li>
		</ul>
		<div style='clear:both;'></div>
		</fieldset>
	</div>
	<div>
		<div style='float:left; width:49%;'>
			<h3>Tasks</h3>
			<div>
				<table cellspacing='0'>
					<tr>
						<th width='1%'>SortOrder</th>
						<th>Name</th>
						<th width='1%'>Active</th>
						<th>-</th>
					</tr>
					<?php foreach($tasks as $task): ?>
					<tr>
						<td align='center'><?php echo $task['MediahuntTask']['sort_order']; ?></td>
						<td><?php echo $task['MediahuntTask']['name']; ?> <span style='font-size:10px; font-style:italic;'>(ID: <?php echo $task['MediahuntTask']['id']; ?>)</span>
						<div style='font-style:italic;'>
							<strong>Details:</strong> <?php echo $task['MediahuntTask']['details']; ?>
						</div>
						</td>
						<td align='center'>
							<?php 
								switch($task['MediahuntTask']['active']) {
									
									case 1:
										echo "<span style='color:green;'>Yes</span>";
									break;
									default:
										echo "<span style='color:red;'>No</span>";
									break;
									
								}
							?>
						</td>
						<td class='actions'>
							<?php 
								echo $this->Html->link("Edit",array("controller"=>"mediahunt_tasks","action"=>"edit",$task['MediahuntTask']['id'],"callback"=>base64_encode($this->here))); 
							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
		<div style='float:left; width:49%;'>	
			<h3>Recently Submitted</h3>
		</div>
	
		<div style='clear:both'></div>
	</div>
</div>