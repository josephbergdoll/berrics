<div class="articleTypes index">
	<h2><?php echo __('Article Types');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('sort_weight');?></th>
			<th><?php echo $this->Paginator->sort('active');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($articleTypes as $articleType):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $articleType['ArticleType']['id']; ?>&nbsp;</td>
		<td><?php echo $articleType['ArticleType']['created']; ?>&nbsp;</td>
		<td><?php echo $articleType['ArticleType']['modified']; ?>&nbsp;</td>
		<td><?php echo $articleType['ArticleType']['name']; ?>&nbsp;</td>
		<td><?php echo $articleType['ArticleType']['sort_weight']; ?>&nbsp;</td>
		<td><?php echo $articleType['ArticleType']['active']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Admin->link(__('View'), array('action' => 'view', $articleType['ArticleType']['id'])); ?>
			<?php echo $this->Admin->link(__('Edit'), array('action' => 'edit', $articleType['ArticleType']['id'])); ?>
			<?php echo $this->Admin->link(__('Delete'), array('action' => 'delete', $articleType['ArticleType']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $articleType['ArticleType']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous'), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next') . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Admin->link(__('New Article Type'), array('action' => 'add')); ?></li>
	</ul>
</div>