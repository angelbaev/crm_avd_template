// JavaScript Document
var R_format_type = {'1':'50', '2':'90', '3':'130', '4':'160'};
	
var P_format_type = {
	'1':{'T':'50'}
	, '2':{'T':'68'}
	, '3':{'T':'86'}
	, '4':{'T':'104'}
	, '5':{'T':'122'}
	, '6':{'T':'140'}
	, '7':{'T':'158'}
	, '8':{'T':'176'}
	, '9':{'T':'194'}
	, '10':{'T':'212'}
};

var J_format_type = {
	'0':{'2':'0', '4':'0', '8':'0', '15':'0'}
	, '1':{'2':'0.22', '4':'0.16', '8':'0.10', '15':'0.09'}
};
var F_format_type = {
	'0':{'2':'0', '4':'0', '8':'0', '15':'0'}
	, '1':{'2':'0.62', '4':'0.38', '8':'0.2', '15':''}
	, '2':{'2':'1.02', '4':'0.72', '8':'0.38', '15':''}
};

var K_format_type = {
	'0':{'K':'0', 'X':'0'}
	, '1':{'K':'0.1', 'X':'10'}
	, '2':{'K':'0.12', 'X':'10'}
	, '3':{'K':'0.20', 'X':'10'}
	, '4':{'K':'0.24', 'X':'15'}
	, '5':{'K':'0.28', 'X':'15'}
	, '6':{'K':'0.26', 'X':'15'}
	, '7':{'K':'0.48', 'X':'15'}
	, '8':{'K':'0.46', 'X':'15'}
	, '9':{'K':'0.62', 'X':'130'}
	, '10':{'K':'0.86', 'X':'196'}
};

var M_format_type = {
	'1':{'2':'0.4', '4':'0.3', '8':'0.2', '15':'0.32'}
	, '2':{'2':'0.8', '4':'0.7', '8':'0.6', '15':'0.4'}
	, '3':{'2':'0.9', '4':'0.8', '8':'0.7', '15':'0.4'}
};
			

function build_calculator(params) {
/*
((QxE)/A)=P (тиражни удари) НЕ УЧАСТВА В ТОТАЛА						
(P+R)xL= цена за хартия						
((B+C)xТ)+D = цена за печат						
(QxJ)+(QxM)  = цена за работа						
(QxF)+(Qx(0,12/A)) = корица						
((Q/A)xK)+X = покритие						
N=файл						

*/
	var P = paper_price = print_price = work_price =  carton = cover = N = 0;

	P = (	((params['Q']*params['E'])/params['A'])	);
	paper_price = (	(params['P']+params['R'])*params['L']	);
	print_price = (	((params['B']+params['C'])*params['T'])+params['D']	);
	work_price = (	(params['Q']*params['J'])+(params['Q']*params['M'])	);
	if(params['A'] != 15) {
		carton = (	params['Q']*params['F']	);
	} else {
		carton = 0;
	}
		cover = (	((params['Q']/params['A'])*params['K'])+params['X']	);
	N = params['N'];

	
	var total_sum = (((((params['P']+params['R'])*params['L'])+(((params['B']+params['C'])*params['T'])+params['D'])+((params['Q']*params['J'])+(params['Q']*params['M']))+(params['Q']*params['F'])+(((params['Q']/params['A'])*params['K'])+params['X']))*params['Z'])+N);
	//(((((P+params['R'])*params['L'])+(((params['B']+params['C'])*params['T'])+params['D'])+((params['Q']*params['J'])+(params['Q']*params['M']))+((params['Q']*params['F'])+(params['Q']*(0.12/params['A'])))+(((params['Q']/params['A'])*params['K'])+params['X']))*params['Z'])+N);
	
	//(ОБЩО ПЕЧАТ *Z)+(ОБЩО ХАРТИЯ  *Z)+(ОБЩО ПОКРИТИЕ *Z)+(ОБЩО ДОВ. РАБОТА *Z)+(ОБЩО ЩАНЦОВАНЕ*Z)+ОБЩО ПРЕДПЕЧАТ
	
	
	var singel_sum = total_sum/params['Q'];
	var vat_sum = (((20.0+100)/100)*total_sum);
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');
	
//add_msg('цена за хартия: '+paper_price+'<br> цена за печат: '+print_price+'<br>  цена за работа: '+work_price+'<br> корица: '+carton+' <br> покритие: '+cover+' <br> файл: '+N,MSG_INFO);
return false;
//	add_msg(html,MSG_SUCCESS);
}//build_calculator


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	if(result.toString().indexOf(".") == -1) result = result+'.00';
	

	return result; //parseFloat(result);
//		return num;
}
