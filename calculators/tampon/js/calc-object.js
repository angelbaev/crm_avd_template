// JavaScript Document

var tampon_small_format_type = {
	'N_1':{'L':'0.511'}
	, 'N_2':{'L':'0.415'}
	, 'N_3':{'L':'0.328'}
	, 'N_4':{'L':'0.286'}
	, 'N_5':{'L':'0.275'}
	, 'N_6':{'L':'0.264'}
	, 'N_7':{'L':'0.253'}
	, 'N_8':{'L':'0.231'}
	, 'N_9':{'L':'0.22'}
	, 'N_10':{'L':'0.209'}
	, 'N_11':{'L':'0.198'}
	, 'N_12':{'L':'0.187'}
	, 'N_13':{'L':'0.176'}
	, 'N_14':{'L':'0.165'}
	, 'N_15':{'L':'0.154'}
	, 'N_16':{'L':'0.143'}
	, 'N_17':{'L':'0.132'}
	, 'N_17':{'L':'0.12'}
	};


function build_calculator(params) {
////((((K*L)*A)*B)*C)+(D*A)+E+J+(K*F)
	
	var total_sum = ( (params['F']*params['K'])+params['J']+params['E']+(params['A']*params['D'])+(params['C']*(params['B']*(params['A']*(params['L']*params['K'])))) );
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

