// JavaScript Document
var sticer_format_type = {
	'S_1':{
		'C':'0.9'
		,'D':'0.09'
	}
	, 'S_2':{
		'C':'0.7'
		,'D':'0.08'
	}
	, 'S_3':{
		'C':'0.6'
		,'D':'0.07'
	}
	, 'S_4':{
		'C':'0.5'
		,'D':'0.06'
	}
	, 'S_5':{
		'C':'0.42'
		,'D':'0.06'
	}
	, 'S_6':{
		'C':'0.35'
		,'D':'0.05'
	}

};


function build_calculator(params) {
//((((W*H)*D)+C)*B)+A= ед.цена

	var total_sum = (((((params['W']*params['H'])*params['D'])+params['C'])*params['B'])+params['A']);
	
	var singel_sum = total_sum/params['B'];
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
