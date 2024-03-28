<?php
namespace GravityRecoverFees;
if(!class_exists('GFForms')){
	return;
}
use GFCommon;

class GF_Recover_Fee_In_Order
{
	function __construct(){
		add_action( 'gform_product_info', array( $this, 'gform_product_info' ), 9, 3 );
	}
	function gform_product_info($order, $form, $entry){
		if(empty($order['products'])) {
			return $order;
		}
		$recoverfees_inputId = -1;
		foreach ( $form['fields'] as $field ) {
			if ( $field->type === 'recoverfees') {
				$recoverfees_inputId = $field->id;
				$recoverfees = $field;
			}
		}
		if($recoverfees_inputId == -1) {
			return $order;
		}
		$subtotal = 0;
		if (!empty($order['shipping'])) {
				$price = $order['shipping']['price'];
				$subtotal += $price;
		}
		if(!empty($order['products'])) {
			foreach ($order['products'] as $product) {
				$price = GFCommon::to_number( $product['price'] );
				$quantity = $product['quantity'];
				if(isset($product['options'])){
					foreach ($product['options'] as $option) {
						$price += $option['price'];
					}
				}
				$subtotal += ($price * $quantity);
			}
		}
		if(!empty($entry[$recoverfees->id])){
			$Procent    = (float)$recoverfees->ProcentFees;
			$FixedFees   = (float)$recoverfees->FixedFees;
			$ProcentFees = 0;
			if(!empty($Procent)){
				$ProcentFees = $subtotal / 100 * $Procent;
			}
			$price      = $ProcentFees+$FixedFees;
			$cents      = round($price);
			$feecost    = round($price - $cents,3,PHP_ROUND_HALF_UP);
			$feecost    = ceil($feecost*100);
			$feecost    = $feecost / 100;
			$price      = $cents + $feecost;
			$recoverfees_label = "Recover Fees";
			$order['products'][$recoverfees->id] = array(
				'name'     => $recoverfees_label,
				'price'    => $price,
				'quantity' => 1,
			);
		}
		return $order;
	}
}
