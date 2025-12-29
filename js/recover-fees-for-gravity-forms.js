jQuery(function($) {
	var gform = window.gform || {};
	const changeHtmlLabel = (fee,product_fees_container) => {
		let price_text = `<strong>${gformFormatMoney(fee)}</strong>`;
		let label = product_fees_container.find('.gform-label_product_fees');
		let html = label.attr('data-label-tootlip');
		html = html.replace(new RegExp('%RECOVERFEE%','g'),price_text);
		label.html(html);
	}
	const inputValueToFloatNumber = (input_val) => {
		let return_number = 0;
		if(input_val.trim() !== ""){
			return_number = parseFloat(input_val);
		}
		return return_number;
	}
	const convertPercantToFee = (total,percent) => {
		percentFee = total / 100 * parseFloat(percent);
		return parseFloat(percentFee.toFixed(2));
	}
	$( document ).on( 'change','.gfield_recoverfees', function (event) {
		gformInitPriceFields();
	});
	var product_fees_container = $('body').find('.ginput_container_product_fees');
	if(gform.addFilter && product_fees_container.length > 0){
		gform.addFilter( 'gform_product_total', function(total, formId){
			var checkBox = product_fees_container.find('.gfield_recoverfees');
			if(checkBox.length < 1){
				return total;
			}
			if(product_fees_container.find('.gfield_percentfees').length < 1){
				return total;
			}
			var IsRecoverFees = checkBox.is(':checked') ? 1 : 0;
			let percentfees   = inputValueToFloatNumber(product_fees_container.find('.gfield_percentfees').val());
			var fixedfees     = inputValueToFloatNumber(product_fees_container.find('.gfield_fixedfees').val());
			let totalFee      = 0;
			let feePercent    = convertPercantToFee(total,percentfees);
			if(fixedfees > 0){
				totalFee = fixedfees;
			}
			if(percentfees > 0){
				totalFee = parseFloat((feePercent+fixedfees).toFixed(2));
			}
			changeHtmlLabel(totalFee,product_fees_container);
			if(IsRecoverFees !== 1) {
				return total;
			}
			if(totalFee>0){
				return total + totalFee;
			}
			return total;
		},50.999999 );
	}
});
