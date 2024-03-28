jQuery(function($) {
	var gform = window.gform || {};
	GFFeeLogger = function(...args){
		console.log(...args);
	}
	jQuery( document ).on( 'change','.gfield_coverfee', function (event) {
		$('body').find('.gfield_price input').trigger('change');
		$('body').find('.ginput_quantity').trigger('change');
		$('body').find('.gfield_coverfeecustomer').val('changed');
	});
	if(gform.addFilter){
		gform.addFilter( 'gform_product_total', function(total, formId){
			if($('body').find('.ginput_container_product_fee .gfield_coverfee').length){
				var IsCoverFee = $('body').find('.ginput_container_product_fee .gfield_coverfee').is(':checked') ? 1 : 0;
					var procentfee = parseFloat($('body').find('.ginput_container_product_fee .gfield_procentfee').val());
					var fixedfee = parseFloat($('body').find('.ginput_container_product_fee .gfield_fixedfee').val());
					price = total + fixedfee;
					procentfee = total / 100 * procentfee;
					let html = $('body').find('.gform-label_product_fee').attr('data-label-tootlip');
					html = html.replace(new RegExp('%COVERFEE%','g'),gformFormatMoney(procentfee+fixedfee));
					// GFFeeLogger(fixedfee+' fixedfee');
					$('body').find('.gform-label_product_fee').html(html);
					if(IsCoverFee === 1 && total > 0) {
						total = price + procentfee;
					}
			}
			return total;
		} );
	}
	// jQuery(document).bind('gform_post_render', function(event, form_id, current_page) {
	// gformUpdateTotalFieldPrice( formId, price );
});
