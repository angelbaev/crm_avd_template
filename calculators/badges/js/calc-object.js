// JavaScript Document


function build_calculator(params) {
//(((W*H)*(A+B))*E)+C= ед.цена
	
	var total_sum = ( (((params['W']*params['H'])*(params['A']+params['B']))*params['E'])+params['C'] );
	
	var singel_sum = total_sum/params['E'];
	var vat_sum = (((20.0+100)/100)*total_sum);

	if (parseInt(singel_sum) < 30) {
            add_msg('Минимално стойност за поръчка - 30 лв без ДДС!', MSG_INFO);
        }

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

