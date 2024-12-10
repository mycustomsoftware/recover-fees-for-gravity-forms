jQuery(function($) {
	const fees_strings = recover_fees_for_gravity_forms_js_strings;
	const setFixedFees = (number) => {
		SetFieldProperty('FixedFees', number);
		jQuery(".field_selected .gfield_fixedfees").val(number);
	}
	const setPercentFees = (number) => {
		SetFieldProperty('PercentFees', number);
		jQuery(".field_selected .gfield_percentfees").val(number);
	}
	const setRecoverFees = (isSelected) => {
		SetFieldProperty('RecoverFees', isSelected);
		jQuery(".field_selected .gfield_recoverfees").prop('checked', isSelected == 'yes');
	}
	$(document).on('change','.forms_fixedfees',function (event) {
		setFixedFees($(this).val());
	});
	$(document).on('change','.forms_percentfees',function (event) {
		setPercentFees($(this).val());
	});
	$(document).on('change','.forms_recoverfees',function (event) {
		setRecoverFees($(this).is(':checked') ? 'yes' : 'no');
	});
	$(document).on('gform_field_added', function(event, form, field){
		if(field.type === 'recoverfees'){
			if( fees_strings.fdllabel !== "" ){
				$('[for="input_'+field.id+'"]').text(fees_strings.fdllabel);
				field.label = fees_strings.fdllabel;
			}
			if( fees_strings.fdlfixed !== "" ){
				field.FixedFees = fees_strings.fdlfixed;
			}
			if( fees_strings.fdlpercent !== "" ){
				field.PercentFees = fees_strings.fdlpercent;
			}
		}
	});
	$(document).on('gform_load_field_settings', function(event, field){
		if(field.type === 'recoverfees'){
			if(field.FixedFees){
				$('body').find('.forms_fixedfees').val(field.FixedFees);
			}
			if(field.PercentFees){
				$('body').find('.forms_percentfees').val(field.PercentFees);
			}
			if(field.RecoverFees === 'yes'){
				$('body').find('.forms_recoverfees').prop('checked', true);
			}
		}
	});
});
