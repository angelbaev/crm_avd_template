// JavaScript Document


function build_calculator(params) {
//(((((L*M)*B)*D)+C+E)*J)+F= обща цена

	var total_sum = ((((((params['W']*params['H'])*params['B'])*params['D'])+params['C']+params['E'])*params['J'])+params['F']);
	
	var singel_sum = total_sum/params['J'];
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
