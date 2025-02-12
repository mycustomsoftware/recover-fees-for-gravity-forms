jQuery(function($) {
	var gform = window.gform || {};
	const trigger_inputs = function(){
		$('body').find('.gfield_price input').trigger('change');
		$('body').find('.gfield_price input').get(0).dispatchEvent(new Event("change"));
		$(".gfield--input-type-price input").get(0).dispatchEvent(new Event("change"));
		if($('body').find('.ginput_quantity').length){
			$('body').find('.ginput_quantity').trigger('change');
			$('body').find('.ginput_quantity').get(0).dispatchEvent(new Event("change"));
		}
		$('body').find('.gfield_recoverfeescustomer').val('changed');
		$('body').find('.gfield_recoverfeescustomer').get(0).dispatchEvent(new Event("change"));
		$(".gfield--input-type-price input").get(0).dispatchEvent(new Event("change"));
	}
	$( document ).on( 'change','.gfield_recoverfees', function (event) {
		gformInitPriceFields();
	});
	if(gform.addFilter){
		gform.addFilter( 'gform_product_total', function(total, formId){
			if($('body').find('.ginput_container_product_fees .gfield_recoverfees').length){
				var product_fees_container = $('body').find('.ginput_container_product_fees');
				var checkBox = product_fees_container.find('.gfield_recoverfees');
				var IsRecoverFees = checkBox.is(':checked') ? 1 : 0;
				let percentfees = parseFloat(product_fees_container.find('.gfield_percentfees').val());
				var fixedfees = parseFloat(product_fees_container.find('.gfield_fixedfees').val());
				price = total + fixedfees;
				percentfees = total / 100 * percentfees;
				let html = $('body').find('.gform-label_product_fees').attr('data-label-tootlip');
				let price_text = gformFormatMoney(percentfees+fixedfees);
				price_text = `<strong>${price_text}</strong>`;
				html = html.replace(new RegExp('%RECOVERFEE%','g'),price_text);
				$('body').find('.gform-label_product_fees').html(html);
				if(IsRecoverFees === 1 && total > 0) {
					total = price + percentfees;
				}
			}
			return total;
		} );
	}
});
