<?php 

$title_for_layout = "The Berrics Canteen - {$product['CanteenProduct']['name']} - {$product['CanteenProduct']['sub_title']} By: {$product['Brand']['name']}";

$this->set(compact("title_for_layout"));

$p = $product['CanteenProduct'];
$i = $product['CanteenProductImage'];
$b = $product['Brand'];
$o = $product['ChildCanteenProduct'];

$user_currency = CanteenConfig::returnUserCurrencyId($_SERVER['GEOIP_COUNTRY_CODE']);


$price = Set::extract("/CanteenProductPrice[currency_id=USD]",$product);

$price = $price[0]['CanteenProductPrice'];

$in_stock = false;

foreach($product['ChildCanteenProduct'] as $p) {

	$qty = $p['CanteenProductInventory'][0]['CanteenInventoryRecord']['quantity'] - $p['CanteenProductInventory'][0]['CanteenInventoryRecord']['allocated'];

	if($qty>0) $in_stock = true;

}

//check stock

/*
$merch_template = $p['merch_template'];

$this->set(compact("price","user_currency"));

echo $this->element("canteen_product/standard-view");
*/
?>
<div id="canteen-product" class='column-shadow'>
	<div class="row-fluid mobile-title visible-phone">
		<div class="span12">
			<?php echo $this->element("canteen_product/product-title",array("product"=>$product)); ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span5">
			<?php echo $this->Media->productThumb($i[0],array("w"=>485,"h"=>485,"zc"=>1),array("img"=>$i[0]['file_name']));  ?>
		</div>
		<div class="span7">
			<div class="title visible-desktop visible-tablet">
				<?php echo $this->element("canteen_product/product-title",array("product"=>$product)); ?>
			</div>
			<div class="price">
				<?php echo $price['Currency']['symbol']; ?><?php echo $price['price']; ?>
			</div>
			<?php if (!empty($p['description'])): ?>
			<div class="product-description option-box">
				<div class='legend'>
					<div>Description</div>
				</div>
				<p>
					<?php echo $p['description']; ?>
				</p>
			</div>
			<?php endif ?>
			<div class="product-options option-box">
				<div class='legend'>
					<div>Select An Option:</div>
				</div>
				<?php 
					echo $this->Form->create('CanteenOrderItem',array(
						"id"=>'CanteenOrderItemForm',
						"url"=>"/canteen/cart/add"
					));
					echo $this->Form->input("CanteenOrderItem.0.parent_canteen_product_id",array("type"=>"hidden","value"=>$p['id']));
					echo $this->Form->input("CanteenOrderItem.0.quantity",array("type"=>"hidden","value"=>1));
					echo $this->Form->input("CanteenOrderItem.0.currency_id",array("type"=>"hidden","value"=>"USD"));

				?>
					<?php if ($in_stock): ?>
						<select name="data[CanteenOrderItem][0][canteen_product_id]" id="">
						<?php 
							foreach ($product['ChildCanteenProduct'] as $k => $v): 
							$inv = Set::extract("/CanteenProductInventory/CanteenInventoryRecord",$v);
							$inv = $inv[0]['CanteenInventoryRecord']['quantity'] - $inv[0]['CanteenInventoryRecord']['allocated'];
						?>
							<?php if ($inv>0): ?>
							<option value="<?php echo $v['id']; ?>"><?php echo $v['opt_label']; ?> : <?php echo $v['opt_value']; ?></option>	
							<?php endif ?>
						<?php endforeach ?>
						</select>
						<div>
							<button class='btn' type='submit'>Add To Cart</button>
						</div>
					<?php else: ?>
						<button type='submit' disabled='disabled' class='btn'>Out Of Stock</button>
					<?php endif ?>
					
				<?php echo $this->Form->end(); ?>
			</div>
			<?php if (count($product['Meta'])>0): ?>
			<div class="meta-data option-box clearfix">
				<div class='legend'>
					<div>Specs</div>
				</div>
				<?php foreach ($product['Meta'] as $k => $v): ?>
				<div class='meta-item'>
				<strong><?php echo $v['key'] ?></strong>:<strong><?php echo $v['val']; ?></strong>
				</div>
				<?php endforeach ?>
			</div>
			<?php endif ?>
			
			<?php if (count($product['RelatedStyles'])>0): ?>
			<div class='additional-styles option-box'>
				<div class='legend'>
					<div><?php echo (!empty($product['CanteenProduct']['style_code_label'])) ? $product['CanteenProduct']['style_code_label']:"Additional Colors"; ?></div>
				</div>
				
				<?php 
				foreach ($product['RelatedStyles'] as $k => $v): 
					$img = $v['CanteenProduct']['style_code_image'];
				?>
					<div class="related-style">
						<a href="/canteen/item/<?php echo $v['CanteenProduct']['uri']; ?>">
							<img src="//img.theberrics.com/i.php?src=/product-img/<?php echo $v['CanteenProduct']['style_code_image']; ?>&amp;w=50&amp;h=50&amp;zc=1" alt="" border='0' />
						</a>
					</div>
				<?php endforeach ?>
				
			</div>
			<?php endif ?>

			

		</div>
	</div>
	<?php 

		if (CakeSession::read("is_admin") == 1): 

		$prefix = "cp.";

		if(preg_match('//',$_SERVER['HTTP_HOST'])) {

			$prefix = "v3.admin.";

		}

	?>
		<a href="//<?php echo $prefix ?>theberrics.com/canteen_products/edit/<?php echo $product['CanteenProduct']['id']; ?>" class='btn btn-primary' target='_blank'>Admin Edit</a>
	<?php endif ?>
</div>
<?php 
pr($product['ChildCanteenProduct']);
?>