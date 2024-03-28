<?php

namespace GravityRecoverFees;

class GF_AdminLabel
{
	function __construct(){
		add_filter( 'gform_entry_list_column_input_label_only', array($this,'label_only'), 10, 3 );
	}
	function label_only( $input_label_only, $form, $field ) {
		return ($field->type !== 'recoverfees') ? $input_label_only : false;
	}
}
