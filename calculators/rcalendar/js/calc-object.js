// JavaScript Document

var file_format_type = {
	'1':{
		'HEAD_ONLY':'5'
		, 'PAD_THROUGHOUT':'10'
		, 'PAD_SMALL':'10'
		}
	, '2':{
		'HEAD_ONLY':'80'
		, 'PAD_THROUGHOUT':'100'
		, 'PAD_SMALL':'90'
		}
	, '3':{
		'HEAD_ONLY':'120'
		, 'PAD_THROUGHOUT':'150'
		, 'PAD_SMALL':'120'
		}
};

var cover_format_type = {
	'NONE':{
		'HEAD_ONLY':'0'
		, 'PAD_THROUGHOUT':'0'
		, 'PAD_SMALL':'0'
		}
	, 'UV_POLISH':{
		'HEAD_ONLY':'0.4'
		, 'PAD_THROUGHOUT':'0.7'
		, 'PAD_SMALL':'0.55'
		}
	, 'MAT_LAMINATE':{
		'HEAD_ONLY':'0.5'
		, 'PAD_THROUGHOUT':'0.9'
		, 'PAD_SMALL':'0.8'
		}
};

var body_format_type = {
	'1':{
		'PRISMA':'1.65'
		, 'STANDARD':'1.55'
		, 'DELTA':'1.25'
		, 'CLASSIC':'1.49'
		, 'BUSINESS':'1.55'
		, 'ACVTIVE_MINI':'1.16'
		, 'CLASSIC_MINI':'0.94'
		, 'PRISMA_MINI':'1.15'
		, 'STANDARD_MINI':'1.35'
		, 'KVADRAT':'1.26'
		, 'COMPACT':'1.01'
		, 'LIGHT':'1.35'
		, 'LUX':'1.67'
		, 'ELITE':'3.35'
	}
	, '2':{
		'PRISMA':'1.5'
		, 'STANDARD':'1.5'
		, 'DELTA':'1.2'
		, 'CLASSIC':'1.44'
		, 'BUSINESS':'1.5'
		, 'ACVTIVE_MINI':'1.11'
		, 'CLASSIC_MINI':'0.89'
		, 'PRISMA_MINI':'1.1'
		, 'STANDARD_MINI':'1.3'
		, 'KVADRAT':'1.21'
		, 'COMPACT':'0.96'
		, 'LIGHT':'1.3'
		, 'LUX':'1.62'
		, 'ELITE':'3.3'
	}
	, '3':{
		'PRISMA':'1.44'
		, 'STANDARD':'1.44'
		, 'DELTA':'1.14'
		, 'CLASSIC':'1.38'
		, 'BUSINESS':'1.44'
		, 'ACVTIVE_MINI':'1.05'
		, 'CLASSIC_MINI':'0.83'
		, 'PRISMA_MINI':'1.04'
		, 'STANDARD_MINI':'1.24'
		, 'KVADRAT':'1.15'
		, 'COMPACT':'0.9'
		, 'LIGHT':'1.24'
		, 'LUX':'1.56'
		, 'ELITE':'3.24'
	}
	};

var print_format_type = {
	'HEAD_ONLY': {
		'1':'5'
		, '2':'3.9'
		, '3':'2.7'
		, '4':'2.45'
		, '5':'2.35'
		, '6':'2.3'
		, '7':'2.25'
		, '8':'2.2'
		, '9':'2.15'
		, '10':'1.95'
		, '11':'1.85'
		, '12':'1.78'
	}
	, 'PAD_THROUGHOUT':{
		'1':'8'
		, '2':'6.5'
		, '3':'4.95'
		, '4':'3.85'
		, '5':'3.45'
		, '6':'3.05'
		, '7':'2.72'
		, '8':'2.6'
		, '9':'2.35'
		, '10':'2.05'
		, '11':'1.98'
		, '12':'1.9'
	}
	, 'PAD_SMALL':{
		'1':'7'
		, '2':'5.2'
		, '3':'4'
		, '4':'2.95'
		, '5':'2.8'
		, '6':'2.7'
		, '7':'2.6'
		, '8':'2.5'
		, '9':'2.25'
		, '10':'2.02'
		, '11':'1.9'
		, '12':'1.82'
	}
};



function build_calculator(params) {
	//((A+B+C+E)*J)+F= обща цена
	//((A+B+C+D+E)*J)+F= обща цена
	
	var total_sum = ( ((params['A']+params['B']+params['C']+params['D']+params['E'])*params['J'])+params['F'] );

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
