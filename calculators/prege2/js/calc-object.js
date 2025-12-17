// JavaScript Document

var preg_format_type = {
	'A_1':{
		'DRY_PRINT':'0.84'
		,'GOLD_PRINT':'0.98'
	}
	, 'A_2':{
		'DRY_PRINT':'0.52'
		,'GOLD_PRINT':'0.6'
	}
	, 'A_3':{
		'DRY_PRINT':'0.5'
		,'GOLD_PRINT':'0.58'
	}
	, 'A_4':{
		'DRY_PRINT':'0.46'
		,'GOLD_PRINT':'0.55'
	}
	, 'A_5':{
		'DRY_PRINT':'0.42'
		,'GOLD_PRINT':'0.49'
	}
	, 'A_6':{
		'DRY_PRINT':'0.38'
		,'GOLD_PRINT':'0.44'
	}
	, 'A_7':{
		'DRY_PRINT':'0.32'
		,'GOLD_PRINT':'0.38'
	}
	, 'A_8':{
		'DRY_PRINT':'0.3'
		,'GOLD_PRINT':'0.36'
	}
	, 'A_9':{
		'DRY_PRINT':'0.25'
		,'GOLD_PRINT':'0.31'
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



