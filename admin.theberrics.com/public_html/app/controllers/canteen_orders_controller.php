<?php

App::import("Controller","LocalApp");

class CanteenOrdersController extends LocalAppController {
	
	public $uses = array('CanteenOrder');
	
	
	public function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->initPermissions();
		
		$this->Auth->allow("barcode");
		
	}
	
	public function ajax_note_reply($id = false) {
		
				
		$this->loadModel("CanteenOrderNote");
		
		
		if(count($this->data)>0) {
			
			$this->CanteenOrderNote->create();
			
			$this->data['CanteenOrderNote']['user_id'] = $this->Auth->User("id");
			
			if($this->CanteenOrderNote->save($this->data)) {
				
				$reply_id = $this->CanteenOrderNote->id;
				
				$this->CanteenOrderNote->create();
				
				$this->CanteenOrderNote->id = $id; 
				
				$this->CanteenOrderNote->save(array(
					"note_status"=>"answered"
				));
				
				//queue an email?
				
				if($this->data['CanteenOrderNote']['send_email']==1) {
					
					$this->loadModel("EmailMessage");
					
					$this->EmailMessage->sendOrderNoteUpdate($this->data['CanteenOrderNote']['canteen_order_id'],$id,$reply_id);
					
				}
				
				die(1);
				
			}
			
		}
		
		
		//get the order note
		$note = $this->CanteenOrderNote->find("first",array(
			"conditions"=>array(
				"CanteenOrderNote.id"=>$id
			),
			"contain"=>array(
				"User"
			)
		));
		
		$this->set(compact("note"));
		
	}
	
	public function search() {
		
		
		if(count($this->data)>0) {
			
				$url = array(
		
					"action"=>"index",
					"s"=>true
				);
				
				
				foreach($this->data as $k=>$v) {
					
					foreach($v as $kk=>$vv) {
						
						$url[$k.".".$kk]=base64_encode($vv);
						
					}
					
				}
				
				return $this->redirect($url);
				
		}
		
		//build some data menus
		
		$orderStatus = CanteenOrder::orderStatusDrop();
		
		$this->set(compact("orderStatus"));
		
		
		/*
		//build some menus
		if(($orderStatusMenu = Cache::read("orderStatus_menu","1min")) === false) {
			
			$orderStatus = $this->CanteenOrder->query("SELECT DISTINCT(order_status) FROM canteen_orders");
			
			$orderStatusMenu = array();
			
			foreach($orderStatus as $s) $orderStatusMenu[$s['canteen_orders']['order_status']] = strtoupper($s['canteen_orders']['order_status']);
			
			Cache::write($orderStatusMenu,"orderStatus_menu","1min");
			
		}
		
		if(($shippingStatusMenu = Cache::read("shippingStatus_menu","1min")) === false) {
			
			
			$shippingStatus = $this->CanteenOrder->query("SELECT DISTINCT(shipping_status) FROM canteen_orders");
			
			$shippingStatusMenu = array();
			
			foreach($shippingStatus as $s) $shippingStatusMenu[$s['canteen_orders']['shipping_status']] = strtoupper($s['canteen_orders']['shipping_status']);
			
			Cache::write($shippingStatusMenu,"shippingStatus_menu","1min");
			
		}
		
		if(($whStatusMenu = Cache::read("whStatus_menu","1min")) === false) {
			
			$whStatus = $this->CanteenOrder->query("SELECT DISTINCT(wh_status) FROM canteen_orders");
			
			$whStatusMenu = array();
			
			foreach($whStatus as $s) $whStatusMenu[$s['canteen_orders']['wh_status']] = strtoupper($s['canteen_orders']['wh_status']);
			
			Cache::write($whStatusMenu,"whStatus_menu","1min");
			
		}
		
		
		
		$this->set(compact("orderStatusMenu","shippingStatusMenu","whStatusMenu"));
		*/
		
	}
	
	public function index() {
		
		$this->paginate['CanteenOrder'] = array(
			"order"=>array("CanteenOrder.created"=>"DESC"),
			"contain"=>array(
				"BillingAddress",
				"ShippingAddress",
				"CanteenShippingRecord",
				"CanteenOrderItem",
				"GatewayTransaction"=>array("GatewayAccount")
			),
			"limit"=>50
		);
		
		$this->setOrderSearchParams();
		
		$orders = $this->Paginate("CanteenOrder");
		
		//foreach($orders as $k=>$v) $orders[$k]['balance'] = $this->CanteenOrder->validateOrderBalance($v);
		
		$this->set(compact("orders"));
		
	}
	
	private function setOrderSearchParams() {
		
		if(!isset($this->params['named']['s'])) return false;
		
		if(isset($this->params['named']['CanteenOrder.order_status'])) {
			
			$this->paginate['CanteenOrder']['conditions']['CanteenOrder.order_status'] = base64_decode($this->params['named']['CanteenOrder.order_status']);
			
		}
		
		if(isset($this->params['named']['CanteenOrder.start_date']) && isset($this->params['named']['CanteenOrder.end_date'])) {
			
			
			$startDate = base64_decode($this->params['named']['CanteenOrder.start_date']);
			
			$endDate = base64_decode($this->params['named']['CanteenOrder.end_date']);
			
			$this->paginate['CanteenOrder']['conditions'][] = "DATE(CanteenOrder.created) BETWEEN '{$startDate}' AND '{$endDate}'";
			
		}
		
		//address stuff
		
		$order_ids = array();
		
		if(isset($this->params['named']['UserAddress.email']) || isset($this->params['named']['UserAddress.address_type'])) {
			
			if(isset($this->params['named']['UserAddress.email'])) {
				
				$a = $this->CanteenOrder->UserAddress->find("all",array(
					"contain"=>array(),
					"conditions"=>array(
						"UserAddress.model"=>"CanteenOrder",
						"UserAddress.address_type"=>"shipping",
						"UserAddress.email LIKE"=> "%".base64_decode($this->params['named']['UserAddress.email'])."%"
					),
					"fields"=>array("UserAddress.foreign_key")
				));
			
				$oids = Set::classicExtract($a,"{n}.UserAddress.foreign_key");
				
				if(count($oids)<=0) {
					
					$oids = array(1);
					
				}
				
				$order_ids = array_merge($order_ids,$oids);
				
				//remove any other address params
				foreach($this->params['named'] as $k=>$v) {
					
					if(preg_match('/^UserAddress/',$k) && !preg_match('/(\.email|\.address_type)/',$k)) {
						
						unset($this->params['named'][$k]);
						
					}
					
				}
				
			} else {
				
				
				
			}
			
		}
		
		if(count($order_ids)>0) $this->paginate['CanteenOrder']['conditions']['CanteenOrder.id'] = $order_ids;
		
		
		
	}
	
	public function edit($order_id) {
		
		$order = $this->CanteenOrder->returnAdminOrder($order_id);
		
	
		
		$this->data = $order;
		
	}
	
	public function print_order($id) {
		
		
		$order = $this->CanteenOrder->returnAdminOrder($id);
		
		$this->set(compact("order"));
		
		$this->render("/elements/canteen_printing/order-receipt");
		
	}
	
	public function barcode() {


		$this->layout = "empty";


	}
	
	public function remove_item($child_id) {
		
		$item = $this->CanteenOrder->CanteenOrderItem->find("first",array(
			"conditions"=>array(
				"CanteenOrderItem.id"=>$child_id
			),
			"contain"=>array()
		));
		
		$this->CanteenOrder->CanteenOrderItem->delete($item['CanteenOrderItem']['id']);
		sleep(2);
		//check to see if the parent row still has items, if it doesn't then fuckin delte it
		
		$line = $this->CanteenOrder->CanteenOrderItem->find("first",array(
			"conditions"=>array(
				"CanteenOrderItem.id"=>$item['CanteenOrderItem']['parent_id']
			),
			"contain"=>array(
				"ChildCanteenOrderItem"
			)
		));
		
		if(count($line['ChildCanteenOrderItem'])<=0) {
			
			$this->CanteenOrder->CanteenOrderItem->delete($item['CanteenOrderItem']['id']);
			
		} else {
			
			//recalc the line total
			
			$new_line_total = 0;
			
			foreach($line['ChildCanteenOrderItem'] as $v) {
				
				$new_line_total += $v['sub_total'];
				
			}
			
			$this->CanteenOrder->CanteenOrderItem->create();
			
			$this->CanteenOrder->CanteenOrderItem->id = $line['CanteenOrderItem']['id'];
			
			$this->CanteenOrder->CanteenOrderItem->save(array(
				"sub_total"=>$new_line_total
			));
			
		}
		
		return $this->redirect("/canteen_orders/edit/".$line['CanteenOrderItem']['canteen_order_id']);
		
	}
	
	public function refund_transaction($trans_id) {
		
		$this->loadModel("GatewayTransaction");
		
		$transaction = $this->GatewayTransaction->find("first",array(
			"conditions"=>array(
				"GatewayTransaction.id"=>$trans_id
			),
			"contain"=>array()
		));
		
		$order = $this->CanteenOrder->returnAdminOrder($transaction['GatewayTransaction']['foreign_key']);
		
		$this->set(compact("order","transaction"));
		
		
		if(count($this->data)>0) {
			
			$amount = $this->data['Refund']['amount'];
			
			$tax = 0;
			
			$shipping = 0;
			
			if($this->data['Refund']['include_sales_tax'] == 1) {
				
				$rate = $order['CanteenOrder']['tax_rate'];
				
				$tax = ($rate/100)*$amount;
				
			}
			
			if($this->data['Refund']['include_shipping']) {
				
				$shipping = $order['CanteenOrder']['shipping_total'];
				
			}
			
			$amount_to_refund = ($amount+$tax+$shipping);
			
			$res = $this->GatewayTransaction->refundTransaction($transaction['GatewayTransaction'],$amount_to_refund);
			
			if($res) {
				
				$this->CanteenOrder->create();
				
				$this->CanteenOrder->id = $order['CanteenOrder']['id'];
				
				$this->CanteenOrder->save(array(
					"sub_total"=>$order['CanteenOrder']['sub_total']-$amount,
					"tax_total"=>$order['CanteenOrder']['tax_total'] - $tax,
					"shipping_total"=>$order['CanteenOrder']['shipping_total']-$shipping,
					"grand_total"=>$order['CanteenOrder']['grand_total'] - $amount_to_refund
				));
				
				$this->Session->setFlash("Refund was successful");
				
			} else {
				
				$this->Session->setFlash("There was an error refunding the transaction");
				
			}
			
			return $this->redirect("/canteen_orders/edit/".$order['CanteenOrder']['id']);
			
		}
		
	}
	
	public function capture_order($transaction_id) {
		
		//get the transaction
		$this->loadModel("GatewayTransaction");
		
		$trans = $this->GatewayTransaction->find("first",array(
			"conditions"=>array(
				"GatewayTransaction.id"=>$transaction_id
			),
			"contain"=>array()
		));
		
		
		if(!empty($trans['GatewayTransaction']['id'])) {
			
			$res = $this->GatewayTransaction->captureTransaction($trans['GatewayTransaction']);
			
			if($res) {
				
				$this->CanteenOrder->updateOrderStatus($trans['GatewayTransaction']['foreign_key'],"approved");
				
				$this->CanteenOrder->processOrderInventory($trans['GatewayTransaction']['foreign_key']);
				$this->CanteenOrder->CanteenShippingRecord->createShipment($trans['GatewayTransaction']['foreign_key']);
				
				$this->Session->setFlash("Order capture successfully");
				
				
				
			} else {
				
				$this->Session->setFlash("Declined");
				
			}
			
			return $this->redirect("/canteen_orders/edit/".$trans['GatewayTransaction']['foreign_key']);
			
		} else {
			
			die("No transaction found");
			
		}
		
		
	}
	
	public function cancel_order($order_id,$override = false) {
		
		if(!$order_id) return $this->cakeError("error404");
		
		$order = $this->CanteenOrder->returnAdminOrder($order_id);
		
		//cancel shipments and line items
		foreach($order['CanteenShippingRecord'] as $s) {
			
			if($s['shipping_status'] != "canceled") $this->CanteenOrder->CanteenShippingRecord->cancelShipment($s['id']);
			
		}
		
		//refund transactions
		foreach($order['GatewayTransaction'] as $t) {
			
			if($t['approved']!=1) continue;
			
			switch(strtoupper($t['method'])) {
				
				case "CHARGE":
				case "CAPTURE":
					$res = $this->CanteenOrder->GatewayTransaction->refundTransaction($t);
					if(!$res) {
						
						$this->CanteenOrder->GatewayTransaction->voidTransaction($t);
						
					}
					break;
				case "AUTH":
					break;
				
			}
			
		}
		
		$this->CanteenOrder->create();
		$this->CanteenOrder->id = $order['CanteenOrder']['id'];
		$this->CanteenOrder->save(array(
			"order_status"=>"canceled",
			"tax_total"=>0,
			"sub_total"=>0,
			"grand_total"=>0,
			"shipping_total"=>0,
			"discount_total"=>0
		));
		
		
		$this->CanteenOrder->CanteenOrderNote->addNote(array(
		
			"canteen_order_id"=>$order['CanteenOrder']['id'],
			"user_id"=>$this->Auth->user("id"),
			"note_type"=>"orderupdate",
			"message"=>"Order has been canceled"
		
		));
		
		return $this->redirect("/canteen_orders/edit/".$order_id);
		
	}
	
	public function credit_order($id = false) {
		
		if(count($this->data)>0) {
			
			
		} else {
			
			$this->data = $this->CanteenOrder->returnAdminOrder($id);
			
		}
		
		
	}
	
	
	public function credit_items($order_id) {
		
		
		
	}
	
	public function skip_message($id = false) {
		
		$this->loadModel("CanteenOrderNote");
		
		$this->CanteenOrderNote->create();
		
		$this->CanteenOrderNote->id = $id;
		
		$this->CanteenOrderNote->save(array(
					"note_status"=>'SKIPPED'
				));	
		
		return $this->redirect("/dashboard/canteen");
		
	}
	
	
	public function edit_line_item($id) {
		
		$this->loadModel("CanteenOrderItem");
		$this->loadModel("CanteenProduct");
		
		$line_item = $this->CanteenOrderItem->find("first",array(
				
						"conditions"=>array(
							"CanteenOrderItem.id"=>$id		
						),
						"contain"=>array(
							"ChildCanteenOrderItem"=>Array(
								"CanteenProduct"=>array(
									"ParentCanteenProduct"=>array(
										"CanteenProductImage"		
									),
											
								),
								"CanteenInventoryRecord"		
							)		
						)
				
				));
		
		$this->data = $line_item;
		
		$productDrop = $this->CanteenProduct->superProductDropdown();
		
	//	die(print_r($productDrop));
		
		$this->set(compact("productDrop"));
		
	}
	
	public function attach_product($order_id) {
		
		$this->loadModel("CanteenProduct");
		
		$order = $this->CanteenOrder->returnAdminOrder($order_id);
		
		if(count($this->data)>0) {
			
			//get the item that is being attached
			
			$item = $this->CanteenProduct->returnChildProduct($this->data['CanteenOrderItem']['canteen_product_id']);
			
			//get the inventory record
			
			$inv = $item['CanteenProductInventory'][0]['CanteenInventoryRecord'];
			
			$inv_wh_id = $inv['Warehouse']['id'];
			
			//check to see if there is a pending shipment from the same warehouse, 
			//if not, then we will die and tell them to create a new shipment
			
			$shp_check = Set::extract("/CanteenShippingRecord[shipping_status=pending]",$order);
			
			$shp_check = Set::extract("/CanteenShippingRecord[warehouse_id={$inv_wh_id}]",$shp_check);
			
			if(count($shp_check)>0) {
				
				$shipment = $shp_check[0];
				
			} else {
				
				$shipment = array(
					"warehouse_id"=>$inv_wh_id,
					"canteen_order_id"=>$order['CanteenOrder']['id'],
					"shipping_status"=>"pending"		
				);
				
				$this->CanteenOrder->CanteenShippingRecord->create();
				
				$this->CanteenOrder->CanteenShippingRecord->save($shipment);
				
				$shipment = array(
							"CanteenShippingRecord"=>$shipment
							);
				
				$shipment['CanteenShippingRecord']['id'] = $this->CanteenOrder->CanteenShippingRecord->id;
				
			}
			
			$this->CanteenOrder->CanteenOrderItem->create();
			$this->CanteenOrder->CanteenOrderItem->save(array("canteen_order_id"=>$order['CanteenOrder']['id']));
			
			$parent_id = $this->CanteenOrder->CanteenOrderItem->id;
			
			$this->CanteenOrder->CanteenOrderItem->create();
			
			$this->CanteenOrder->CanteenOrderItem->save(array(

				"title"=>$item['ParentCanteenProduct']['name']." - ".$item['ParentCanteenProduct']['sub_title'],
				"sub_title"=>$item['CanteenProduct']['opt_label']."=".$item['CanteenProduct']['opt_value'],
				"canteen_shipping_record_id"=>$shipment['CanteenShippingRecord']['id'],
				"canteen_product_id"=>$item['CanteenProduct']['id'],
				"parent_id"=>$parent_id,
				"canteen_inventory_record_id"=>$inv['id'],
				"quantity"=>1
					
			));
			
			return $this->redirect(base64_decode($this->params['named']['callback']));
			
		}
		
		
		$productDrop = $this->CanteenProduct->superProductDropdown();
		
		$this->set(compact("productDrop"));
		
	}
	
	
	
	
	
	
	
	
}