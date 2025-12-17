// JavaScript Document
var body_format_type = {
	'1':{
		'1':'4.01'
		, '2':'4.08'
		, '3':'4.12'
		, '4':'4.45'
		}
	, '2':{
		'1':'3.81'
		, '2':'3.88'
		, '3':'3.92'
		, '4':'4.25'
	}
	, '3':{
		'1':'3.61'
		, '2':'3.68'
		, '3':'3.72'
		, '4':'4.05'
	}
	, '4':{
		'1':'3.56'
		, '2':'3.63'
		, '3':'3.67'
		, '4':'4.00'
	}
};

var print_format_type = {
	'1':{'F':'0.95'}
	, '2':{'F':'0.74'}
	, '3':{'F':'0.65'}
	, '4':{'F':'0.49'}
	, '5':{'F':'0.38'}
	, '6':{'F':'0.27'}
	, '7':{'F':'0.22'}
	, '8':{'F':'0.19'}
};




function build_calculator(params) {
	//((A+(B*C)+(B*F))*E)+D=обща цена

	var total_sum = ( ((params['A']+(params['B']*params['C'])+(params['B']*params['F']))*params['E'])+params['D'] );
	
	var singel_sum = total_sum/params['E'];
	var vat_sum = (((20.0+100)/100)*total_sum);
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

return false;
//	add_msg(html,MSG_SUCCESS);
}//build_calculator


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
//		return num;
}
