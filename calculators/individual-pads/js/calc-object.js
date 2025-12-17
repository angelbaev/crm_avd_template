// JavaScript Document

var print_format_type = {
	'1':{
		'1':'5.54'
		, '2':'4.85'
		, '3':'4.09'
		, '4':'3.56'
		, '5':'3.21'
		, '6':'2.96'
		, '7':'2.83'
		, '8':'2.64'
		, '9':'2.58'
	}
	, '2':{
		'1':'7.55'
		, '2':'6.46'
		, '3':'5.34'
		, '4':'4.96'
		, '5':'4.71'
		, '6':'4.58'
		, '7':'4.35'
		, '8':'4.18'
		, '9':'3.96'
	}
	, '3':{
		'1':'7.21'
		, '2':'6.13'
		, '3':'5.35'
		, '4':'5.08'
		, '5':'4.8'
		, '6':'4.69'
		, '7':'4.56'
		, '8':'4.38'
		, '9':'4.07'
	}
	, '4':{
		'1':'6.26'
		, '2':'5.23'
		, '3':'4.89'
		, '4':'4.09'
		, '5':'3.91'
		, '6':'3.71'
		, '7':'3.56'
		, '8':'3.32'
		, '9':'3.14'
	}
	, '5':{
		'1':'-'
		, '2':'8.47'
		, '3':'8.17'
		, '4':'7.84'
		, '5':'7.46'
		, '6':'7.28'
		, '7':'7.07'
		, '8':'6.82'
		, '9':'6.63'
	}
	, '6':{
		'1':'-'
		, '2':'5.86'
		, '3':'5.67'
		, '4':'5.26'
		, '5':'4.85'
		, '6':'4.61'
		, '7':'4.34'
		, '8':'4.12'
		, '9':'3.83'
	}
};




function build_calculator(params) {
	//((A+B+C+D)*F)+E=обща цена	

	var total_sum = ( ((params['A']+params['B']+params['C']+params['D'])*params['F'])+params['E'] ) ;
	//( ((params['A']+(params['B']*params['C'])+(params['B']*params['F']))*params['E'])+params['D'] );
	
	var singel_sum = total_sum/params['F'];
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
