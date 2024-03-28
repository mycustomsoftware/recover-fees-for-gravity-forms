jQuery(function($) {
	var gform = window.gform || {};
	jQuery( document ).on( 'change','.gfield_recoverfees', function (event) {
		$('body').find('.gfield_price input').trigger('change');
		$('body').find('.ginput_quantity').trigger('change');
		$('body').find('.gfield_recoverfeescustomer').val('changed');
	});
	if(gform.addFilter){
		gform.addFilter( 'gform_product_total', function(total, formId){
			if($('body').find('.ginput_container_product_fees .gfield_recoverfees').length){
				var product_fees_container = $('body').find('.ginput_container_product_fees');
				var checkBox = product_fees_container.find('.gfield_recoverfees');
				var IsRecoverFees = checkBox.is(':checked') ? 1 : 0;
					let procentfees = parseFloat(product_fees_container.find('.gfield_procentfees').val());
					var fixedfees = parseFloat(product_fees_container.find('.gfield_fixedfees').val());
					price = total + fixedfees;
					procentfees = total / 100 * procentfees;
					let html = $('body').find('.gform-label_product_fees').attr('data-label-tootlip');
					html = html.replace(new RegExp('%RECOVERFEE%','g'),gformFormatMoney(procentfees+fixedfees));
					$('body').find('.gform-label_product_fees').html(html);
					if(IsRecoverFees === 1 && total > 0) {
						total = price + procentfees;
					}
			}
			return total;
		} );
	}
});
