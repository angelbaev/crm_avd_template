// JavaScript Document


function build_calculator(params) {
//((W*H)*(B*A))+(C*E)+D
//Е*(((W*H)*(B*A))+C)+D
	
	var total_sum = ( params['D']+(params['C']+((params['A']*params['B'])*(params['H']*params['W'])))*params['E'] );
	//( params['D']+(params['E']*params['C'])+((params['A']*params['B'])*(params['H']*params['W'])) );
	var singel_sum = total_sum/params['E'];
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


function check_selected() {
		
		
	$('#print_paper_size').html('&nbsp;'+$('#paper_size option:selected').text());
	$('#print_paper_color').html('&nbsp;'+$('#paper_color option:selected').text());
	$('#print_paper_number_in_COB').html('&nbsp;'+$('#paper_number_in_COB option:selected').text());
	$('#print_paper_cover').html('&nbsp;'+$('#paper_cover option:selected').text());
	$('#print_paper_perforation').html('&nbsp;'+$('#paper_perforation option:selected').text());
	$('#print_paper_numeration').html('&nbsp;'+$('#paper_numeration option:selected').text());
	$('#print_paper_file').html('&nbsp;'+$('#paper_file option:selected').text());
	$('#print_paper_cnt').html('&nbsp;'+$('#paper_cnt').val());
	
	
//	return false;
}

function clear_selected() {
	$('#print_paper_size').html('&nbsp;');
	$('#print_paper_color').html('&nbsp;');
	$('#print_paper_number_in_COB').html('&nbsp;');
	$('#print_paper_cover').html('&nbsp;');
	$('#print_paper_perforation').html('&nbsp;');
	$('#print_paper_numeration').html('&nbsp;');
	$('#print_paper_file').html('&nbsp;');
	$('#print_paper_cnt').html('&nbsp;');

}

