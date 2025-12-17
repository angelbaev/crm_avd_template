// JavaScript Document

var size_format_type = {
	'114х162':{
		'STANDART_ENVELOPE':'-'
		,'SILICONE_ENVELOPE':'0.041'
		,'BOX_ENVELOPE':'-'
		,'CUSTOM_ENVELOPE':'-'
	}
	, '110х220':{
		'STANDART_ENVELOPE':'-'
		,'SILICONE_ENVELOPE':'0.051'
		,'BOX_ENVELOPE':'0.059'
		,'CUSTOM_ENVELOPE':'-'
	}
	, '162х229':{
		'STANDART_ENVELOPE':'0.11'
		,'SILICONE_ENVELOPE':'0.073'
		,'BOX_ENVELOPE':'0.091'
		,'CUSTOM_ENVELOPE':'-'
	}
	, '229х324':{
		'STANDART_ENVELOPE':'0.195'
		,'SILICONE_ENVELOPE':'0.156'
		,'BOX_ENVELOPE':'0.171'
		,'CUSTOM_ENVELOPE':'-'
	}
	, '250х353':{
		'STANDART_ENVELOPE':'-'
		,'SILICONE_ENVELOPE':'0.19'
		,'BOX_ENVELOPE':'-'
		,'CUSTOM_ENVELOPE':'-'
	}
};

var sub_format_type = {
	'SIZE_1':{
		'F':'0.38'
	}
	,'SIZE_2':{
		'F':'0.25'
	} 
	,'SIZE_3':{
		'F':'0.09'
	} 
	,'SIZE_4':{
		'F':'0.07'
	} 
	,'SIZE_5':{
		'F':'0.05'
	} 

};

function build_calculator(params) {
	//((A според B+(F*C))*E)+(D+(C*30))= обща цена
	
	var total_sum = (((params['A']+(params['F']*params['C']))*params['E'])+(params['D']+(params['C']*30)))
	
	var singel_sum = total_sum/params['E'];
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



