<?php

namespace RecoverFeesForGravityForms;
if(!class_exists('GFForms')){
	return;
}
use GF_Field;
use GFCommon;

class GF_Field_Recover_Fees extends GF_Field
{
	public $type              = 'recoverfees';
	public $PercentFees        = 0;
	public $FixedFees          = 0;
	public $RecoverFees          = 'no';
	public $RecoverFeesCustomer  = '';
	public $adminLabel        = 'Recover Fees';
	/**
	 * Returns the field's form editor icon.
	 *
	 * This could be an icon url or a gform-icon class.
	 *
	 * @since 2.8
	 *
	 * @return string
	 */
	public function get_form_editor_field_icon() {
		return 'gform-icon--quantity';
	}
	public function get_form_editor_field_title() {
		return esc_attr__('Recover Fee','recover-fees-for-gravity-forms');
	}
	public function get_form_editor_button() {
		return array(
			'group' => 'pricing_fields',
			'text'  => $this->get_form_editor_field_title()
		);
	}
	/**
	 * Indicates if this field supports state validation.
	 *
	 * @since 2.5.11
	 *
	 * @var bool
	 */
	protected $_supports_state_validation = true;

	function get_form_editor_field_settings() {
		return array(
			'label_setting',
			'recoverfees_settings',
			'admin_label_setting',
		);
	}


	public function validate( $value, $form ) {
		$price = GFCommon::to_number( $value );
		if ( ! rgblank( $value ) && ( $price === false || $price < 0 ) ) {
			$this->failed_validation  = true;
			$this->validation_message = empty( $this->errorMessage ) ? __( 'Please enter a valid amount.', 'recover-fees-for-gravity-forms' ) : $this->errorMessage;
		}
	}


	public function get_field_input( $form, $value = '', $entry = null ) {
		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
		$id              = (int) $this->id;
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";
		$placeholder_attribute = $this->get_field_placeholder_attribute();
		$disabled_text         = $is_form_editor ? 'disabled="disabled"' : '';
		$required_attribute    = $this->isRequired ? 'aria-required="true"' : '';
		$invalid_attribute     = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';
		$describedby_attribute = $this->get_aria_describedby();
		$recoverfeeschacked = '';
		$recoverFeesCustomer = isset($_POST["input_{$id}_4"]) ? sanitize_text_field($_POST["input_{$id}_4"]) : $this->RecoverFeesCustomer;
		if(isset($_POST["input_{$id}_1"])){
			$recoverfeeschacked = 'checked="checked"';
		}
		if($this->RecoverFees == 'yes'){
			if($recoverFeesCustomer === ''){
				$recoverfeeschacked = 'checked="checked"';
			}
		}
		$tabindex = $this->get_tabindex();
		return "<span class='ginput_container ginput_container_product_fees'>
			<input name='input_{$id}_1' id='{$field_id}' type='checkbox' ".$recoverfeeschacked." value='yes' class='gfield_recoverfees' {$tabindex} {$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$describedby_attribute} {$disabled_text}/>
			<input name='input_{$id}_2' id='{$field_id}_2' type='hidden' value='{$this->FixedFees}' class='gfield_fixedfees' {$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$describedby_attribute} {$disabled_text}/>
			<input name='input_{$id}_3' id='{$field_id}_3' type='hidden' value='{$this->PercentFees}' class='gfield_percentfees'  {$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$describedby_attribute} {$disabled_text}/>
			<input name='input_{$id}_4' id='{$field_id}_4' type='hidden' value='{$recoverFeesCustomer}' class='gfield_recoverfeescustomer'  {$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$describedby_attribute} {$disabled_text}/>
		</span>";
	}

	public function get_field_content( $value, $force_frontend_label, $form ) {
		$form_id         = $form['id'];
		$admin_buttons   = $this->get_admin_buttons();
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
		$is_admin        = $is_entry_detail || $is_form_editor;
		$field_label     = $this->get_field_label( $force_frontend_label, $value );
		$field_id        = $is_admin || $form_id == 0 ? "input_{$this->id}" : 'input_' . $form_id . "_{$this->id}";
		$label           = str_replace('%RECOVERFEE%', '', $field_label);
		$field_content = sprintf( "%s{FIELD}<label class='gfield_label gform-field-label gform-label_product_fees' for='%s' data-label-tootlip='%s'>%s</label>", $admin_buttons, $field_id, $field_label,$label );
		return $field_content;
	}
	public function get_value_save_entry( $value, $form, $input_name, $lead_id, $lead ) {
		$id              = (int) $this->id;
		foreach ($form['fields'] as $field) {
			if($field->type !== 'recoverfees'){
				continue;
			}
			$recoverfeeschacked = false;
			$recoverFeesCustomer = isset($_POST["input_{$id}_4"]) ? sanitize_text_field($_POST["input_{$id}_4"]) : $this->RecoverFeesCustomer;
			if(isset($_POST["input_{$id}_1"])){
				$recoverfeeschacked = true;
			}
			if($this->RecoverFees == 'yes'){
				if($recoverFeesCustomer === ''){
					$recoverfeeschacked = true;
				}
			}
			if($recoverfeeschacked !== true){
				continue;
			}
			if(!empty($this->FixedFees)){
				$currency = GFCommon::get_currency();
				$value   .= GFCommon::to_money( $this->FixedFees, $currency );
			}
			if(!empty($this->PercentFees)){
				if(!empty($value)){
					$value .= '+';
				}
				$value .= $this->PercentFees."%";
			}
		}
		return $value;
	}
	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {
		return !empty($value) ? 'yes' : 'no';
	}
}
