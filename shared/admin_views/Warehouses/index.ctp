<div class="warehouses index">
	<h2><?php __('Warehouses');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('active');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($warehouses as $warehouse):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $warehouse['Warehouse']['id']; ?>&nbsp;</td>
		<td><?php echo $warehouse['Warehouse']['created']; ?>&nbsp;</td>
		<td><?php echo $warehouse['Warehouse']['modified']; ?>&nbsp;</td>
		<td><?php echo $warehouse['Warehouse']['name']; ?>&nbsp;</td>
		<td><?php echo $warehouse['Warehouse']['active']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $warehouse['Warehouse']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $warehouse['Warehouse']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $warehouse['Warehouse']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $warehouse['Warehouse']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Warehouse', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Canteen Inventory Records', true), array('controller' => 'canteen_inventory_records', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Canteen Inventory Record', true), array('controller' => 'canteen_inventory_records', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Canteen Shipping Records', true), array('controller' => 'canteen_shipping_records', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Canteen Shipping Record', true), array('controller' => 'canteen_shipping_records', 'action' => 'add')); ?> </li>
	</ul>
</div>