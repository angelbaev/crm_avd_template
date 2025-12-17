// JavaScript Document
/*
var media_format_type = {
	'MEDIA_1':{
		'720DPI':'15'
		,'1440DPI':'30'
	}
	, 'MEDIA_2':{
		'720DPI':'14'
		,'1440DPI':'28'
	}
	, 'MEDIA_3':{
		'720DPI':'16'
		,'1440DPI':'30'
	}
	, 'MEDIA_4':{
		'720DPI':'19'
		,'1440DPI':'19'
	}
	, 'MEDIA_5':{
		'720DPI':'8'
		,'1440DPI':'8'
	}
	, 'MEDIA_6':{
		'720DPI':'19'
		,'1440DPI':'38'
	}
	, 'MEDIA_7':{
		'720DPI':'14'
		,'1440DPI':'26'
	}
	, 'MEDIA_8':{
		'720DPI':'22'
		,'1440DPI':'34'
	}
	, 'MEDIA_9':{
		'720DPI':'32'
		,'1440DPI':'48'
	}
	, 'MEDIA_10':{
		'720DPI':'32'
		,'1440DPI':'40'
	}
	, 'MEDIA_11':{
		'720DPI':'29'
		,'1440DPI':'40'
	}
	, 'MEDIA_12':{
		'720DPI':'29'
		,'1440DPI':'40'
	}

};
*/
var media_format_type = {
	'MEDIA_1':{
		'720DPI':'24'
		,'1440DPI':'30'
	}
	, 'MEDIA_2':{
		'720DPI':'80'
		,'1440DPI':'80'
	}
	, 'MEDIA_3':{
		'720DPI':'24'
		,'1440DPI':'30'
	}
	, 'MEDIA_4':{
		'720DPI':'19'
		,'1440DPI':'19'
	}
	, 'MEDIA_5':{
		'720DPI':'34'
		,'1440DPI':'38'
	}
	, 'MEDIA_6':{
		'720DPI':'24'
		,'1440DPI':'24'
	}
	, 'MEDIA_7':{
		'720DPI':'25'
		,'1440DPI':'25'
	}
	, 'MEDIA_8':{
		'720DPI':'40'
		,'1440DPI':'40'
	}
	, 'MEDIA_9':{
		'720DPI':'48'
		,'1440DPI':'48'
	}
	, 'MEDIA_10':{
		'720DPI':'52'
		,'1440DPI':'52'
	}
	, 'MEDIA_11':{
		'720DPI':'42'
		,'1440DPI':'42'
	}
	, 'MEDIA_12':{
		'720DPI':'48'
		,'1440DPI':'48'
	}

};



function build_calculator(params) {
//((((W*H)*B според А и B)+((W*H)*C)+((W*H)*D)+((W*H)*J)+E+F)*K)+L
	var total_sum = ( ((((params['W']*params['H'])*params['B'])+((params['W']*params['H'])*params['C'])+((params['W']*params['H'])*params['D'])+((params['W']*params['H'])*params['J'])+params['E']+params['F'])*params['K'])+params['L'] );
//	var total_sum = ( params['O']+(params['F']*(params['A']*(params['E']+params['D']+params['C']+params['B'])*(params['H']*params['W']))) );	
	var singel_sum = total_sum/params['K'];
	var vat_sum = (((20.0+100)/100)*total_sum);

	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

//check_selected();
return false;
//	add_msg(html,MSG_SUCCESS);
}


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
//		return num;
}




