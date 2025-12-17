// JavaScript Document

var a_format_type = {
	'1':{'A':'0.36','B':'0.41','C':'0.48'}
	, '2':{'A':'0.31','B':'0.36','C':'0.42'}
	, '3':{'A':'0.30','B':'0.35','C':'0.41'}
	, '4':{'A':'0.29','B':'0.34','C':'0.40'}
	, '5':{'A':'0.28','B':'0.33','C':'0.39'}
	, '6':{'A':'0.27','B':'0.32','C':'0.38'}
	, '7':{'A':'0.26','B':'0.31','C':'0.37'}
	, '8':{'A':'0.25','B':'0.30','C':'0.36'}
	, '9':{'A':'0.24','B':'0.29','C':'0.35'}
};
var b_format_type = {
	'1':0
	, '2':0
	, '3':0
	, '4':0
	, '5':0
	, '6':0
	, '7':0.02
	, '8':0.03
};
function build_calculator(params) {
	//(A*J)+C=обща цена
	var total_sum = ( ((params['A'] + params['B'])*params['J'])+params['C'] );
	
	var singel_sum = total_sum/params['J'];
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
