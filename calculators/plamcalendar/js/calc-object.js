// JavaScript Document
var calendar_format_type = {
	'1':{
	'RECTANGLE':'0.14'
	, 'ROUNDED':'0.18'
	}
	, '2':{
	'RECTANGLE':'0.12'
	, 'ROUNDED':'0.14'
	}
	, '3':{
	'RECTANGLE':'0.082'
	, 'ROUNDED':'0.102'
	}
	, '4':{
	'RECTANGLE':'0.074'
	, 'ROUNDED':'0.084'
	}
	, '5':{
	'RECTANGLE':'0.072'
	, 'ROUNDED':'0.082'
	}
	, '6':{
	'RECTANGLE':'0.07'
	, 'ROUNDED':'0.08'
	}
	, '7':{
	'RECTANGLE':'0.068'
	, 'ROUNDED':'0.078'
	}
	, '8':{
	'RECTANGLE':'0.066'
	, 'ROUNDED':'0.076'
	}
	, '9':{
	'RECTANGLE':'0.058'
	, 'ROUNDED':'0.068'
	}
	, '10':{
	'RECTANGLE':'0.052'
	, 'ROUNDED':'0.06'
	}
	, '11':{
	'RECTANGLE':'0.048'
	, 'ROUNDED':'0.056'
	}
	, '12':{
	'RECTANGLE':'0.044'
	, 'ROUNDED':'0.054'
	}
};

var covering_format_type = {
	'1':'0.02'
	, '2':'0.02'
	, '3':'0.018'
	, '4':'0.016'
	, '5':'0.014'
	, '6':'0.012'
	, '7':'0.012'
	, '8':'0.012'
	, '9':'0.012'
	, '10':'0.012'
	, '11':'0.012'
	, '12':'0.012'
};

var back_format_type = {
	'1':'0.04'
	, '2':'0.032'
	, '3':'0.022'
	, '4':'0.02'
	, '5':'0.02'
	, '6':'0.02'
	, '7':'0.01'
	, '8':'0.01'
	, '9':'0.01'
	, '10':'0.01'
	, '11':'0.01'
	, '12':'0.01'
};

function build_calculator(params) {
	//((А+B+C)*J)+D=обща цена

	//alert(( ((params['А']+params['B']+params['C'])*params['J'])+params['D'] );
	var total_sum = ( ((params['A']+params['B']+params['C'])*params['J'])+params['D'] );

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
