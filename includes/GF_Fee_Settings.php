<?php

namespace GravityRecoverFees;

class GF_Fee_Settings
{
	function __construct(){
	    add_action('gform_field_standard_settings', array($this, 'gform_field_standard_settings'), 10, 1);
	}
	function gform_field_standard_settings($depth){
		if($depth == 20){ ?>
			<li class="fee_settings field_setting">
                <small style="display: block;margin-top: -10px;">Use "<strong>%COVERFEE%</strong>" in Field Label to display fee total</small><br/>
				<label for="input_class_setting">
					<?php gform_tooltip( 'input_class_setting' ) ?>
				</label>
				<label for="forms_fixedfee">
					<span>Fixed amount cover fee</span><br/>
					<input id="forms_fixedfee" type="text" class="forms_fixedfee">
				</label>
				<label for="forms_procentfee">
					<span>Procent of Cover fee</span><br/>
					<input id="forms_procentfee" type="text" class="forms_procentfee">
				</label>
                <input id="forms_recoverfee" type="checkbox" class="forms_recoverfee">
				<label for="forms_recoverfee">
					<span>Checked by default</span><br/>
				</label>
			</li>
		<?php }
	}
}
