// JavaScript Document
var pad_format_type = {
	'0':{
		'1':'0'
		, '2':'0'
	}
	, '1':{
		'1':'0.2'
		, '2':'0.4'
	}
};

var print_format_type = {
	'1':{
		'K':'35'
		, 'L':'15'
	}
	, '2':{
		'K':'50'
		, 'L':'20'
	}
};




function build_calculator(params) {
	//(((А+C+D)*J)+(B*(((J/20)*L)+K))*1,4)+E=обща цена

	var total_sum = ( (((params['A']+params['C']+params['D'])*params['J'])+(params['B']*(((params['J']/20)*params['L'])+params['K']))*1.4)+params['E'] )
	
	var singel_sum = total_sum/params['J'];
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
