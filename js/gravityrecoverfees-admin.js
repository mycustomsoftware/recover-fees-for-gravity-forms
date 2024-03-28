jQuery(function($) {
	// gform.addAction('formEditorNullClick',(event)=>{
	// 	console.log(event);
	// })
	const setFixedFees = (number) => {
		SetFieldProperty('FixedFees', number);
		jQuery(".field_selected .gfield_fixedfees").val(number);
	}
	const setProcentFees = (number) => {
		SetFieldProperty('ProcentFees', number);
		jQuery(".field_selected .gfield_procentfees").val(number);
	}
	const setRecoverFees = (isSelected) => {
		SetFieldProperty('RecoverFees', isSelected);
		jQuery(".field_selected .gfield_recoverfees").prop('checked', isSelected == 'yes');
	}
	$(document).on('change','.forms_fixedfees',function (event) {
		setFixedFees($(this).val());
	});
	$(document).on('change','.forms_procentfees',function (event) {
		setProcentFees($(this).val());
	});
	$(document).on('change','.forms_recoverfees',function (event) {
		setRecoverFees($(this).is(':checked') ? 'yes' : 'no');
	});
	$(document).on('gform_field_added', function(event, form, field){
		if(field.type === 'recoverfees'){

			if(gravityrecoverfees_js_strings.fdllabel !== ""){
				$('[for="input_'+field.id+'"]').text(gravityrecoverfees_js_strings.fdllabel);
				field.label = gravityrecoverfees_js_strings.fdllabel;
			}
			if(gravityrecoverfees_js_strings.fdlfixed !== ""){
				field.FixedFees = gravityrecoverfees_js_strings.fdlfixed;
			}
			if(gravityrecoverfees_js_strings.fdlprocent !== ""){
				field.ProcentFees = gravityrecoverfees_js_strings.fdlprocent;
			}
		}
	});
	$(document).on('gform_load_field_settings', function(event, field){
		if(field.type === 'recoverfees'){
			if(field.FixedFees){
				$('body').find('.forms_fixedfees').val(field.FixedFees);
			}
			if(field.ProcentFees){
				$('body').find('.forms_procentfees').val(field.ProcentFees);
			}
			if(field.RecoverFees === 'yes'){
				$('body').find('.forms_recoverfees').prop('checked', true);
			}
		}
	});
});
