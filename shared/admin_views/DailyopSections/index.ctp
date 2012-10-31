<?php 

$directives = DailyopSection::directives();


?>
<div class='page-header'>
<h1>Dailyops Sections</h1>
</div>
<?php echo $this->Admin->adminPaging(); ?>
<div class="dailyopSections index">
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('featured'); ?></th>
			<th><?php echo $this->Paginator->sort('sort_weight'); ?></th>
			<th>Icons</th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('directive'); ?></th>
			<th><?php echo $this->Paginator->sort("uri"); ?>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($dailyopSections as $dailyopSection):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $dailyopSection['DailyopSection']['id']; ?>&nbsp;</td>
		<td><?php echo $dailyopSection['DailyopSection']['featured']; ?></td>
		<td><?php echo $dailyopSection['DailyopSection']['sort_weight']; ?></td>
		<td>
			<div style=' float:left; background-color:black;'>
			<?php 
			
				if(!empty($dailyopSection['DailyopSection']['icon_light_file'])) {
					
					
					//echo "<img src='http://img.theberrics.com/i.php?src=/berrics-icon/".$dailyopSection['DailyopSection']['icon_light_file']."&w=100&h=100' />";
					echo $this->Media->sectionIcon(array(
					
						"DailyopSection"=>$dailyopSection['DailyopSection'],
						"w"=>100,
						"h"=>100	
					
					));
				}
			?>
			</div>
			<?php 
				if(!empty($dailyopSection['DailyopSection']['icon_dark_file'])) {
					
					
					echo $this->Media->sectionIcon(array(
					
						"DailyopSection"=>$dailyopSection['DailyopSection'],
						"w"=>100,
						"h"=>100,
						"dark"=>true	
					
					));
					
				}
			
			?>
		</td>
		<td><?php echo $this->Time->niceShort($dailyopSection['DailyopSection']['modified']); ?>&nbsp;</td>
		<td><?php echo $dailyopSection['DailyopSection']['name']; ?>&nbsp;</td>
		<td><?php 

			if(!empty($dailyopSection['DailyopSection']['directive'])) {
				
				echo $directives[$dailyopSection['DailyopSection']['directive']];
				
			} else {
				
				echo "Default";
				
			}
				 
			
					
			?></td>
		<td>
			http://theberrics.com/<?php echo $dailyopSection['DailyopSection']['uri']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Admin->link(__('View'), array('action' => 'view', $dailyopSection['DailyopSection']['id'])); ?>
			<?php echo $this->Admin->link(__('Edit'), array('action' => 'edit', $dailyopSection['DailyopSection']['id'])); ?>
			<?php echo $this->Admin->link(__('Delete'), array('action' => 'delete', $dailyopSection['DailyopSection']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $dailyopSection['DailyopSection']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
<?php echo $this->Admin->adminPaging(); ?>