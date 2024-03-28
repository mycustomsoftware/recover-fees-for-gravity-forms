<?php

namespace GravityRecoverFees;
if(!class_exists('GFAPI')){
	return;
}
use GFAPI;
class GF_SearchFilter
{
	function __construct(){
		add_filter( 'gform_search_criteria_entry_list', array( $this, 'search_entry_list' ), 9, 2 );
	}
	function search_entry_list($search_criteria, $form_id){
		if(isset($search_criteria['field_filters']) && is_array($search_criteria['field_filters'])) {
			foreach ($search_criteria['field_filters'] as $key => $field_filter) {
				$field_id = (int)$field_filter['key'];
				$revrited_filter = $field_filter;
				if (!empty($field_id)) {
					$field_one = GFAPI::get_field($form_id, $field_id);
					if (!empty($field_one)) {
						if ($field_one->type == 'recoverfees') {
							$operator = $field_filter['operator'];
							$value = $field_filter['value'];
							if (strtolower($value) == 'no') {
								$revrited_filter = array(
									'key' => $field_id,
									'operator' => 'is',
									'value' => '',
								);
							}
							if (strtolower($value) == 'yes') {
								if (strtolower($operator) == 'is') {
									$revrited_filter = array(
										'key' => $field_id,
										'operator' => 'isnot',
										'value' => '',
									);
								}
							}
						}
					}
				}
				$search_criteria['field_filters'][$key] = $revrited_filter;
			}
		}
		return $search_criteria;
	}
}
