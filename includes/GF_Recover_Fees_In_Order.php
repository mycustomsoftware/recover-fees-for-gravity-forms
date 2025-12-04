<?php
namespace RecoverFeesForGravityForms;
if(!class_exists('GFForms')){
	return;
}
use GFCommon;

class GF_Recover_Fees_In_Order
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
			$Percent    = (float)$recoverfees->PercentFees;
			$FixedFees   = (float)$recoverfees->FixedFees;
			$PercentFees = 0;
			if(!empty($Percent)){
				$PercentFees = $subtotal / 100 * $Percent;
			}
			$price = 0;
			if($FixedFees > 0){
				$price = number_format($PercentFees+$FixedFees, 2);
			}
			$recoverfees_label = __("Recover Fees");
			$order['products'][$recoverfees->id] = array(
				'name'     => $recoverfees_label,
				'price'    => $price,
				'quantity' => 1,
			);
		}
		return $order;
	}
}
