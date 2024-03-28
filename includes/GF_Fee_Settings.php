<?php

namespace GravityRecoverFees;

class GF_Fee_Settings
{
	function __construct(){
	    add_action('gform_field_standard_settings', array($this, 'gform_field_standard_settings'), 10, 1);
	}
	function gform_field_standard_settings($depth){
		if($depth == 20){ ?>
			<li class="recoverfees_settings field_setting">
                <small style="display: block;margin-top: -10px;">Use "<strong>%RECOVERFEE%</strong>" in Field Label to display fee total</small><br/>
				<label for="input_class_setting">
					<?php gform_tooltip( 'input_class_setting' ) ?>
				</label>
				<label for="forms_fixedfees">
					<span>Fixed amount cover fee</span><br/>
					<input id="forms_fixedfee" type="text" class="forms_fixedfees">
				</label>
				<label for="forms_procentfees">
					<span>Procent of Cover fee</span><br/>
					<input id="forms_procentfees" type="text" class="forms_procentfees">
				</label>
                <input id="forms_recoverfees" type="checkbox" class="forms_recoverfees">
				<label for="forms_recoverfees">
					<span>Checked by default</span><br/>
				</label>
			</li>
		<?php }
	}
}
