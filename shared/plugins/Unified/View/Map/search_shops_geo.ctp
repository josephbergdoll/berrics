<?php if (!isset($res) || empty($res)): ?>
	<div class="label">
		No Stores Found
	</div>
<?php else: ?>
	<div>
		<?php echo count($res); ?>
	</div>
	<?php 
		foreach ($res as $k => $v): 
		$store = $v['Store'];
	?>
		<div class='shop-result' data-unified-store-id='<?php echo $store['UnifiedStore']['id']; ?>' >
			<div class="name">
				<?php echo $k+1; ?>. <?php echo strtoupper($v['Store']['UnifiedStore']['shop_name']); ?>
			</div>
			
			<div class="address">
				<?php echo $store['UnifiedStore']['address1']; ?>
				<?php if (!empty($store['UnifiedStore']['address2'])): ?>
					<br /> <?php echo $store['UnifiedStore']['address2']; ?>
				<?php endif ?>
				<br />
				<?php echo $store['UnifiedStore']['city'] ?>, <?php echo $store['UnifiedStore']['state']; ?> <?php echo $store['UnifiedStore']['zip'] ?>
				<br />
				<strong>
					P: <?php echo $store['UnifiedStore']['phone'] ?>
				</strong> 
			</div>
			<div class="distance">
				<div class="label label-success">
					<?php echo number_format($v[0]['distance']); ?> MILES
				</div>
			</div>
		</div>
	<?php endforeach ?>
<?php endif ?>