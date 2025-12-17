// JavaScript Document

var preg_format_type = {
	'A_1':{
		'DRY_PRINT':'-'
		,'GOLD_PRINT':'-'
		,'SILK1':'-'
		,'SILK2':'-'
		,'SILK3':'-'
	}
	, 'A_2':{
		'DRY_PRINT':'1.1'
		,'GOLD_PRINT':'1.25'
		,'SILK1':'1.2'
		,'SILK2':'2'
		,'SILK3':'2.8'
	}
	, 'A_3':{
		'DRY_PRINT':'1'
		,'GOLD_PRINT':'1.15'
		,'SILK1':'1.1'
		,'SILK2':'1.9'
		,'SILK3':'2.7'
	}
	, 'A_4':{
		'DRY_PRINT':'0.95'
		,'GOLD_PRINT':'1.1'
		,'SILK1':'1.05'
		,'SILK2':'1.8'
		,'SILK3':'2.55'
	}
	, 'A_5':{
		'DRY_PRINT':'0.9'
		,'GOLD_PRINT':'1.05'
		,'SILK1':'1'
		,'SILK2':'1.75'
		,'SILK3':'2.5'
	}
	, 'A_6':{
		'DRY_PRINT':'0.85'
		,'GOLD_PRINT':'1'
		,'SILK1':'0.9'
		,'SILK2':'1.6'
		,'SILK3':'2.3'
	}
	, 'A_7':{
		'DRY_PRINT':'0.8'
		,'GOLD_PRINT':'0.95'
		,'SILK1':'0.85'
		,'SILK2':'1.45'
		,'SILK3':'2.05'
	}
	, 'A_8':{
		'DRY_PRINT':'0.75'
		,'GOLD_PRINT':'0.9'
		,'SILK1':'0.8'
		,'SILK2':'1.4'
		,'SILK3':'2'
	}
	, 'A_9':{
		'DRY_PRINT':'0.7'
		,'GOLD_PRINT':'0.85'
		,'SILK1':'0.7'
		,'SILK2':'1.3'
		,'SILK3':'1.9'
	}
}

function build_calculator(params) {
	//(А според E)*E +(B+C+D) = обща цена
	
	var total_sum = ( (params['A']*params['E'])+(params['B']+params['C']+params['D']) );
//	var total_sum = ( params['C']+(params['J']*(params['B']+params['A'])) );

	var singel_sum = total_sum/params['E'];
	var vat_sum = (((20.0+100)/100)*total_sum);
//( 17.5 + 100 ) / 100 )
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

//print form 
	$('#single_sum_print').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total_print').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total_print').html(roundNumber(vat_sum, 2)+' лв');

//check_selected();
return false;
//	add_msg(html,MSG_SUCCESS);
}


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
//		return num;
}



