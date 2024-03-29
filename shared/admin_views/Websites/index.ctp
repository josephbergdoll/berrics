<div class="websites index">
	<h2><?php echo __('Websites'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($websites as $website): ?>
	<tr>
		<td><?php echo h($website['Website']['id']); ?>&nbsp;</td>
		<td><?php echo h($website['Website']['created']); ?>&nbsp;</td>
		<td><?php echo h($website['Website']['modified']); ?>&nbsp;</td>
		<td><?php echo h($website['Website']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $website['Website']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $website['Website']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $website['Website']['id']), null, __('Are you sure you want to delete # %s?', $website['Website']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Website'), array('action' => 'add')); ?></li>
	</ul>
</div>
