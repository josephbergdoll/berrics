<?php
class CanteenPromoCode extends AppModel {
	var $name = 'CanteenPromoCode';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasMany = array(
		"CanteenOrder"
	);
	
	public function applyPromoCode($CanteenOrder) {
		
		//set all the promo codes on the order
		$currency = ClassRegistry::init("Currency");
		$user_id = false;

		if(CakeSession::check("Auth.User.id")) {

			$user_id = CakeSession::read("Auth.User.id");

		}
		
		#User Account Promo Code
		if(
			isset($CanteenOrder['CanteenOrder']['user_account_canteen_promo_code_id']) && 
			!empty($CanteenOrder['CanteenOrder']['user_account_canteen_promo_code_id'])
		) {
			
			$uap = $this->find("first",array(
			
				"conditions"=>array(
					"CanteenPromoCode.id"=>$CanteenOrder['CanteenOrder']['user_account_canteen_promo_code_id']
				),
				"contain"=>array()
			
			));
			
			if(isset($uap['CanteenPromoCode']['id'])) $CanteenOrder['UserAccountCanteenPromoCode'] = $uap['CanteenPromoCode'];
			
		}
		
		#Shipping Promo Code
		### For Certain Users
		$free_shipping_users = array(
			'4d715e72-7124-486a-9129-7ace0ab55011',
			'4d54b193-7c1c-4741-bdfe-0fe90a000269'
		);
		if(in_array($user_id,$free_shipping_users)) {

			$CanteenOrder['CanteenOrder']['shipping_total'] = 0.00;
			$CanteenOrder['CanteenOrder']['shipping_canteen_promo_code_id'] = 2;
			
			//get the promo code
			
			$code = $this->find("first",array(
				"contain"=>array(),
				"conditions"=>array(
					"CanteenPromoCode.id"=>2		
				)		
					
			));
			
			$CanteenOrder['ShippingCanteenPromoCode'] = $code['CanteenPromoCode'];

		}


		/*
		if(
			env('GEOIP_COUNTRY_CODE') == "US" && 
			$CanteenOrder['CanteenOrder']['sub_total'] >= 74.95
		) {
			
			$CanteenOrder['CanteenOrder']['shipping_total'] = 0.00;
			$CanteenOrder['CanteenOrder']['shipping_canteen_promo_code_id'] = 2;
			
			//get the promo code
			
			$code = $this->find("first",array(
				"contain"=>array(),
				"conditions"=>array(
					"CanteenPromoCode.id"=>2		
				)		
					
			));
			
			$CanteenOrder['ShippingCanteenPromoCode'] = $code['CanteenPromoCode'];
			
		}
		*/
		
		#Promotion Promo Code
		//check to see if there are more than three items
		if(count($CanteenOrder['CanteenOrderItem'])>=3) {
			
			$CanteenOrder['UserAccountCanteenPromoCode'] = null;
			
			$uap = $this->find("first",array(
						
					"conditions"=>array(
							"CanteenPromoCode.id"=>3
					),
					"contain"=>array()
						
			));
				
			if(isset($uap['CanteenPromoCode']['id'])) $CanteenOrder['PromotionCanteenPromoCode'] = $uap['CanteenPromoCode'];
			
			
			
		}
		
		#Standard Promo Code
		
		#Calculate all the discounts
		
		$discount = 0;
		
		if(isset($CanteenOrder['UserAccountCanteenPromoCode']['rate'])) {
			
			$discount += ($CanteenOrder['UserAccountCanteenPromoCode']['rate']/100)*$CanteenOrder['CanteenOrder']['sub_total'];
			
		}
		
		if(isset($CanteenOrder['PromotionCanteenPromoCode']['rate'])) {
				
			$discount += ($CanteenOrder['PromotionCanteenPromoCode']['rate']/100)*$CanteenOrder['CanteenOrder']['sub_total'];
				
		}
		
		
		if($discount>0) $discount = "-".$discount;
			
		$CanteenOrder['CanteenOrder']['discount_total'] = $discount;
		
		$CanteenOrder['CanteenOrder']['sub_total'] += $discount;
		$CanteenOrder['CanteenOrder']['taxable_total'] += $discount;
		
		return $CanteenOrder;
		
	}
	
	
	public static function promoTypeSelect() {
		
		$a = array(
			"shipping",
			"account",
			"store",
			"standard"
		);
		
		$r = array();
		
		foreach($a as $v) $r[$v] = strtoupper($v);
		
		return $r;
		
	}
	
}
?>