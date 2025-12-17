// JavaScript Document
var print_format_type = {
	'1':{
		'PASTEL':{
			'1':{
				'1':'0.45'
				, '2':'0.392'
				, '3':'0.340'
				, '4':'0.265'
				, '5':'0.240'
				, '6':'0.156'
			}
			, '2':{
				'1':'0.525'
				, '2':'0.421'
				, '3':'0.373'
				, '4':'0.296'
				, '5':'0.27'
				, '6':'0.187'
			}
		}
		, 'METALLIC':{
			'1':{
				'1':'0.525'
				, '2':'0.421'
				, '3':'0.373'
				, '4':'0.296'
				, '5':'0.27'
				, '6':'0.187'
			}
			, '2':{
				'1':'0.555'
				, '2':'0.464'
				, '3':'0.405'
				, '4':'0.328'
				, '5':'0.300'
				, '6':'0.202'
			}
		}
	}
	, '2': {
		'PASTEL':{
			'1':{
				'1':'0.9'
				, '2':'0.783'
				, '3':'0.408'
				, '4':'0.319'
				, '5':'0.288'
				, '6':'0.187'
			}
			, '2':{
				'1':'1.05'
				, '2':'0.841'
				, '3':'0.447'
				, '4':'0.356'
				, '5':'0.324'
				, '6':'0.224'
			}
		}
		, 'METALLIC':{
			'1':{
				'1':'1.05'
				, '2':'0.841'
				, '3':'0.447'
				, '4':'0.356'
				, '5':'0.324'
				, '6':'0.224'
			}
			, '2':{
				'1':'1.11'
				, '2':'0.928'
				, '3':'0.486'
				, '4':'0.393'
				, '5':'0.360'
				, '6':'0.242'
			}
		}
	}
};




function build_calculator(params) {
	//(А+Е)*J+D=обща цена
	//(((А+Е)*J)*1,1)+D=обща цена
	var total_sum = ( (((params['A']+params['E'])*params['J'])*1.1)+params['D'] );
	
	var singel_sum = total_sum/params['J'];
	var vat_sum = (((20.0+100)/100)*total_sum);
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

return false;
//	add_msg(html,MSG_SUCCESS);
}//build_calculator


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
//		return num;
}
