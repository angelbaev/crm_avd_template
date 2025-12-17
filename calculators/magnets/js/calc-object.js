// JavaScript Document

var magnet_format_type = {

	'M_1':{

		'J':'0.35'

		,'K':'0.01'

		,'A':'0.85'

		,'C':'0.25'

		,'D':'0.15'

	}

	, 'M_2':{

		'J':'0.15'

		,'K':'0.01'

		,'A':'0.36'

		,'C':'0.24'

		,'D':'0.12'

	}

	, 'M_3':{

		'J':'0.1'

		,'K':'0.009'

		,'A':'0.28'

		,'C':'0.23'

		,'D':'0.11'

	}

	, 'M_4':{

		'J':'0.07'

		,'K':'0.007'

		,'A':'0.13'

		,'C':'0.22'

		,'D':'0.10'

	}

	, 'M_5':{

		'J':'0.07'

		,'K':'0.003'

		,'A':'0'

		,'C':'0.2'

		,'D':'0.08'

	}

	, 'M_6':{

		'J':'0.12'

		,'K':'0.002'

		,'A':'0'

		,'C':'0.18'

		,'D':'0.07'

	}

	, 'M_7':{

		'J':'0.09'

		,'K':'0.001'

		,'A':'0'

		,'C':'0.16'

		,'D':'0.07'

	}



};



function build_calculator(params) {

//((((W*H)*K)+(J+A+C+D))*F)+E = ед.цена



	var total_sum = (((((params['W']*params['H'])*params['K'])+(params['J']+params['A']+params['C']+params['D']+params['B']))*params['F'])+params['E'] );

	

	var singel_sum = total_sum/params['F'];

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

