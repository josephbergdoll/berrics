<div class='order-history-div'>
	<div class='heading'>CANTEEN ORDER HISTORY</div>
	<div class='order-table'>
		<?php if(count($orders)>0): ?>
		<table cellspacing='0' class='canteen-table-items'>
			<tr>
				<th>Order Date</th>
				<th>Last Updated</th>
				<th>Order #</th>
				<th>Actions</th>
			</tr>
			<?php foreach($orders as $o): ?>
			<tr>
				<td align='center'><?php echo $this->Time->niceShort($o['CanteenOrder']['created']); ?></td>
				<td align='center'><?php echo $this->Time->niceShort($o['CanteenOrder']['modified']); ?></td>
				<td align='center'><?php echo $o['CanteenOrder']['id']; ?></td>
				<td class='actions'><a href='/account/canteen/status/<?php echo $o['CanteenOrder']['hash']; ?>'>Check Status</a> | <a href='/canteen/printable/receipt/<?php echo $o['CanteenOrder']['hash']; ?>' target='_blank'>Print Receipt</a></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php else: ?>
		<div style='text-align:center; padding:10px; color:#333;'>No order were found</div>
		<?php endif; ?>
	</div>	
</div>