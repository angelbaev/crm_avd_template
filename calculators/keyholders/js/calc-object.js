// JavaScript Document


var holder_format_type = {
	'1':{
		'1':'10.00'
		, '2':'7.50'
		, '3':'7.00'
		, '4':'7.75'
		, '5':'5.10'
		, '6':'3.4'
		}
	, '2':{
		'1':'12'
		, '2':'8.00'
		, '3':'7.50'
		, '4':'7.20'
		, '5':'5.60'
		, '6':'3.9'
		}
	, '3':{
		'1':'14'
		, '2':'8.50'
		, '3':'8.00'
		, '4':'7.70'
		, '5':'6.1'
		, '6':'4.4'
		}
};
function build_calculator(params) {
	//((A+B+C+D)*F)+E=обща цена
	var total_sum = ( ((params['A']+params['B']+params['C']+params['D'])*params['F'])+params['E'] );
	
	var singel_sum = total_sum/params['F'];
	var vat_sum = (((20.0+100)/100)*total_sum);
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

return false;
//	add_msg(html,MSG_SUCCESS);
}


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
//		return num;
}



