<?php

class EmailMessage extends AppModel {
	
	public $belongsTo = array(
		"CanteenOrder"=>array(
			"className"=>"CanteenOrder",
			"foreignKey"=>"foreign_key",
			"conditions"=>array("model"=>"CanteenOrder")
		)
	);
	
	public function queueCanteenEmail() {
		
		$args = func_get_args();
		
		//lets chec a few things
		if(count($args) === 2) {
			
			$subject = '';
			switch(strtolower($args[0])) {
				
				case "canteen_order_conf":
					$subject = "The Berrics Canteen - Order Confirmation";
					
				break;
				
			}
			
			$data = array(
				"template"=>$args[0],
				"canteen_order_id"=>$args[1]['CanteenOrder']['id'],
				"from"=>"Do Not Reply <do.not.reply@theberrics.com>",
				"app_name"=>"Canteen",
				"send_as"=>"html",
				"serialized_data"=>serialize($args[1]),
				"to"=>$args[1]['CanteenOrder']['email'],
				"subject"=>$subject,
				"app_name"=>"Canteen",
				"processed"=>0
			);
			
			$this->create();
			return $this->save($data);
			
		}
		
		throw new Exception("Wrong Parameters For Queue Canteen Email");
		
	}
	/**
	 * 
	 * @param unknown_type $CanteenOrder
	 * @return unknown_type
	 */
	
	public function canteenOrderConfirmation($CanteenOrder) {
		
		$ship = Set::extract("/UserAddress[address_type=shipping]",$CanteenOrder);
		
		$this->create();
		
		
		$this->save(array(
			"subject"=>"The Berrics Canteen: Order Confirmation",
			"to"=>$CanteenOrder['ShippingAddress']['email'],
			"from"=>"Do Not Reply <do.not.reply@theberrics.com>",
			"send_as"=>"html",
			"template"=>"canteen_order_conf",
			"app_name"=>"Canteen",
			"serialized_data"=>serialize(array("CanteenOrder"=>$CanteenOrder['CanteenOrder'])),
			"model"=>"CanteenOrder",
			"foreign_key"=>$CanteenOrder['CanteenOrder']['id'],
			"processed"=>0
		));
	}
	
	/**
	 * 
	 * @param unknown_type $email_id
	 * @param unknown_type $resend_label
	 * @return unknown_type
	 */
	public function resendEmail($email_id = false, $resend_label = false) {
		
		
		//get the email that we are referencing
		$email = $this->find("first",array(
			"conditions"=>array("EmailMessage.id"=>$email_id),
			"contain"=>array()
		));
		
		if($resend_label) {
			
			$email['EmailMessage']['subject'] = "RESEND:".$email['EmailMessage']['subject'];
			
		}
		
		unset($email['EmailMessage']['id'],$email['EmailMessage']['processed'],$email['EmailMessage']['created'],$email['EmailMessage']['modified']);
		
		$email['EmailMessage']['processed'] = 0;
		
		$this->create();
		
		return $this->save($email['EmailMessage']);
		
		
	}
	
	public function sendOrderNoteUpdate($canteen_order_id,$orig_id = false,$reply_id = false) {
		
		$order = $this->CanteenOrder->returnAdminOrder($canteen_order_id);
		
		//get the shipping address email
		
		$address = Set::extract("/UserAddress[address_type=/shipping|billing/]",$order);
		
		$d = array();
		
		$d['subject'] = "The Berrics Canteen: A note has been added to your order";
		$d['to'] = $order['ShippingAddress']['email'];
		$d['from'] = "Do Not Reply <do.not.reply@theberrics.com>";
		$d['send_as'] = "html";
		$d['template'] = "canteen_order_note_update";
		$d['model'] = "CanteenOrder";
		$d['foreign_key'] = $canteen_order_id;
		$d['processed'] = 0;
		
		$d['serialized_data'] = serialize(array(
			"reply_id"=>$reply_id,
			"orig_id"=>$orig_id
		));
		
		$this->create();
		
		return $this->save($d);
		
	}
	
	public function sendCanteenShippingConfirmation($canteen_shipping_record_id = false) {
		
		$csr = ClassRegistry::init("CanteenShippingRecord");
		
		$record = $csr->returnAdminRecord($canteen_shipping_record_id);
		
		$d = array();
		
		$d['subject'] = "The Berrics Canteen: Your order has shipped";
		$d['to'] = $record['UserAddress']['email'];
		$d['from'] = "Do Not Reply <do.not.reply@theberrics.com>";
		$d['send_as'] = "html";
		$d['template'] = "canteen_shipping_confirmation";
		$d['model'] = "CanteenOrder";
		$d['foreign_key'] = $record['CanteenShippingRecord']['canteen_order_id'];
		$d['processed'] = 0;
		
		$d['serialized_data'] = serialize(array(
			"canteen_shipping_record_id"=>$canteen_shipping_record_id
		));
		
		unset($csr);
		
		$this->create();
		
		return $this->save($d);
		
		
	}
	
	public function queueUserPasswdReset($UserPasswdReset) {
		
		if(isset($UserPasswdReset['UserPasswdReset'])) 
				$UserPasswdReset = $UserPasswdReset['UserPasswdReset'];
		
		$user = ClassRegistry::init("User");
		
		$User = $user->returnUserProfile($UserPasswdReset['user_id']);
		
		unset($user);
		
		$this->create();
		
		return $this->save(array(
					"subject"=>"The Berrics - Password Reset",
					"to"=>$User['User']['email'],
					"from"=>"Do Not Reply <do.not.reply@theberrics.com>",
					"send_as"=>"html",
					"model"=>"User",
					"foreign_key"=>$UserPasswdReset['user_id'],
					"processed"=>0,
					"serialized_data"=>$UserPasswdReset['id'],
					"template"=>"user_passwd_reset_request"
				));
		
		
	}
	
	public function userEmailConfirmation($User) {
		
		
		$this->create();
		
		return $this->save(array(
				"subject"=>"The Berrics - Account Confirmation",
				"to"=>$User['email'],
				"from"=>"Do Not Reply <do.not.reply@theberrics.com>",
				"send_as"=>"html",
				"model"=>"User",
				"foreign_key"=>$User['id'],
				"processed"=>0,
				"serialized_data"=>$User['id'],
				"template"=>"account_confirmation"
		));
		
	}
	
	
}