<?php 

class CanteenProduct extends AppModel {
	
	
	public $hasMany = array(
	
		"ChildCanteenProduct"=>array(
			"className"=>"CanteenProduct",
			"foreignKey"=>"parent_canteen_product_id",
			"order"=>array("ChildCanteenProduct.display_weight"=>"ASC")
		),
		
		"CanteenProductPrice",
		"CanteenProductImage",
		"CanteenProductInventory",
	
	);
	
	public $belongsTo = array(
		"CanteenCategory",
		"Brand",
		"ParentCanteenProduct"=>array(
			"className"=>"CanteenProduct",
			"foreignKey"=>"parent_canteen_product_id"
		),
	);
	
	public $hasAndBelongsToMany = array(
	
		"Meta"=>array(
			"order"=>array("Meta.key"=>"ASC")
		),
		"Tag"	
	
	);
	
	public function returnAdminProduct($id) {
		
		$p = $this->find("first",array(
			
			"conditions"=>array(
				"CanteenProduct.id"=>$id
			),
			"contain"=>array(
				"Tag",
				"CanteenProductPrice"=>array(
					"Currency"
				),
				"ChildCanteenProduct"=>array(
				
					"conditions"=>array(
						"ChildCanteenProduct.active"=>1,
						"ChildCanteenProduct.deleted"=>0
					),
					"CanteenProductInventory"=>array(
						"CanteenInventoryRecord"=>array(
							"Warehouse"
						)
					)
				),
				"CanteenProductImage"=>array(
					
					"order"=>array(
						"CanteenProductImage.front_image"=>"DESC",
						"CanteenProductImage.display_weight"=>"ASC"
					)
				 
				),
				"CanteenProductInventory"=>array(
					"CanteenInventoryRecord"
				),
				"Meta"
			)
		
		));
		
		$p['CanteenProduct']['pub_date'] = date("Y-m-d",strtotime($p['CanteenProduct']['publish_date']));
		
		$p['CanteenProduct']['pub_time'] = date("H:i",strtotime($p['CanteenProduct']['publish_date']));
		
		return $p;
		
	}
	
	public function returnProduct($cond = array(),$isAdmin = false,$inner_call = false,$extra = array()) {
		
		
		$token = "return_product_".md5(serialize($cond).$isAdmin.$inner_call.serialize($extra));
		
		if(($prod = Cache::read($token,"1min")) === false) {
			
				
				$_contain = array(
						"ChildCanteenProduct"=>array(
							"conditions"=>array(
								"ChildCanteenProduct.deleted"=>0,
								"ChildCanteenProduct.active"=>1
							)
						),
						"CanteenProductPrice"=>array(
							"Currency"
						),
						"CanteenCategory",
						"Brand",
						"CanteenProductImage"=>array(	
							"order"=>array(
								"CanteenProductImage.front_image"=>"DESC",
								"CanteenProductImage.display_weight"=>"ASC"
							)
						),
						"Meta"=>array("order"=>array("Meta.key"=>"ASC"))

				);
				
				$_cond = array();
			
				if(!$isAdmin) {
					
					$_cond[] = 'CanteenProduct.publish_date < NOW()';
					$_cond['CanteenProduct.active'] = 1;
					
				}
				
				//let's merge the defaults with the incoming
				if(array_key_exists("contain",$cond) && is_array($cond['contain'])) {
					
					$_contain = array_merge($_contain,$cond['contain']);
					
				}
				
				if(array_key_exists("conditions",$cond) && is_array($cond['conditions'])) {
						
					$_cond = array_merge($_cond,$cond['conditions']);
					
				}
				
				$verb = "first";
				
				if($inner_call) {
					
					$verb = "all";
					
				}
				
				$prod = $this->find($verb,array(
				
					"conditions"=>$_cond,
					"contain"=>$_contain
				
				));
				
				if(!$inner_call && empty($prod['CanteenProduct']['id'])) {
					
					
					return false;
					
				}
				
				if(!$inner_call && !isset($extra['no_related']) && !empty($prod['CanteenProduct']['style_code'])) {
					
					$related_styles = array();
					
					$styles = $this->returnProduct(
						array("conditions"=>array(
							"CanteenProduct.style_code"=>$prod['CanteenProduct']['style_code'],
							"NOT"=>array(
								"CanteenProduct.id"=>array($prod['CanteenProduct']['id'])
							)	
						
						)),
						$isAdmin,
						true
					);
					
					$prod['RelatedStyles'] = $styles;
					
				}
				
				Cache::write($token,$prod,"1min");
			
		}
		
		
		return $prod;
		
	}
	
	public function returnCartItem($opts = array()) {
		
		$def = array(
			"parent_canteen_product_id"=>NULL,
			"currency_id"=>"USD"
		);
		
		$opts = array_merge($def,$opts);
		
		$parent_product = $this->find("first",array(
			"contain"=>array(
				"ParentCanteenProduct"=>array(
					"CanteenProductImage",
					"CanteenProductPrice"=>array(
						"conditions"=>array(
							"CanteenProductPrice.currency_id"=>$opts['currency_id']
						)
					)
				)
			),
			"conditions"=>array(
				"ParentCanteenProduct.id"=>$opts['parent_canteen_product_id']
			)
		));
		
		//$parent_product['ParentCanteenProduct'] = $parent_product['CanteenProduct'];
		
		
		$product = $this->find("first",array(
			"conditions"=>array("CanteenProduct.id"=>$opts['canteen_product_id']),
			"contain"=>array(
					"ParentCanteenProduct"=>array(
						"CanteenProductImage",
						"CanteenProductPrice"=>array(
							"conditions"=>array(
								"CanteenProductPrice.currency_id"=>$opts['currency_id']
							)
						),
						"Brand"
					)
			)
		));
		
		return array_merge($product);
		
	}

	public function addNewOption($data = array()) {
		
		$id = $data['CanteenProduct']['id'];
		
		$this->create();
		
		$this->save(array(
			"parent_canteen_product_id"=>$id,
			"active"=>1,
			"deleted"=>0
		));
		
	}
	
	public function addCommonShirtOptions($data = array()) {
		
		$id = $data['CanteenProduct']['id'];
		
		//sizes
		$s = array("S","M","L","XL");
		
		foreach($s as $k=>$v) {
			
			$this->create();
			$this->save(array(
				"parent_canteen_product_id"=>$id,
				"active"=>1,
				"delete"=>0,
				"opt_label"=>"Size",
				"opt_value"=>$v,
				"display_weight"=>($k+1)
			));
			
		}
		
		
	}
	
	public static function merchTemplates() {
		
		$a = array(
		
			"standard"=>"Standard",
			"widescreen"=>"Wide Screen"
		
		);
		
		return $a;
	}
	

	
}