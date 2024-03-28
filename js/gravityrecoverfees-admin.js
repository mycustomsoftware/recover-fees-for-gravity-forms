jQuery(function($) {
	// gform.addAction('formEditorNullClick',(event)=>{
	// 	console.log(event);
	// })
	const setFixedFee = (number) => {
		SetFieldProperty('FixedFee', number);
		jQuery(".field_selected .gfield_fixedfee").val(number);
	}
	const setProcentFee = (number) => {
		SetFieldProperty('ProcentFee', number);
		jQuery(".field_selected .gfield_procentfee").val(number);
	}
	const setCoverFee = (isSelected) => {
		SetFieldProperty('CoverFee', isSelected);
		jQuery(".field_selected .gfield_coverfee").prop('checked', isSelected == 'yes');
	}
	$(document).on('change','.forms_fixedfee',function (event) {
		setFixedFee($(this).val());
	});
	$(document).on('change','.forms_procentfee',function (event) {
		setProcentFee($(this).val());
	});
	$(document).on('change','.forms_recoverfee',function (event) {
		setCoverFee($(this).is(':checked') ? 'yes' : 'no');
	});
	$(document).on('gform_field_added', function(event, form, field){
		if(field.type === 'recoverfee'){

			if(gravityrecoverfees_js_strings.fdllabel !== ""){
				$('[for="input_'+field.id+'"]').text(gravityrecoverfees_js_strings.fdllabel);
				field.label = gravityrecoverfees_js_strings.fdllabel;
			}
			if(gravityrecoverfees_js_strings.fdlfixed !== ""){
				field.FixedFee = gravityrecoverfees_js_strings.fdlfixed;
			}
			if(gravityrecoverfees_js_strings.fdlprocent !== ""){
				field.ProcentFee = gravityrecoverfees_js_strings.fdlprocent;
			}
		}
	});
	$(document).on('gform_load_field_settings', function(event, field){
		if(field.type === 'recoverfee'){
			if(field.FixedFee){
				$('body').find('.forms_fixedfee').val(field.FixedFee);
			}
			if(field.ProcentFee){
				$('body').find('.forms_procentfee').val(field.ProcentFee);
			}
			if(field.CoverFee === 'yes'){
				$('body').find('.forms_recoverfee').prop('checked', true);
			}
		}
	});
});
