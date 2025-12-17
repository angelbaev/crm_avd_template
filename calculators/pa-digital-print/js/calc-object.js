// JavaScript Document
	
var А_format_type = {
	'1':{'O':'1', 'Y':'0.04'}
	, '2':{'O':'2', 'Y':'0.06'}
	, '3':{'O':'4', 'Y':'0.10'}
	, '4':{'O':'8', 'Y':'0.15'}
	, '5':{'O':'6', 'Y':'0.16'}
	, '6':{'O':'20', 'Y':'0.20'}
};

var FACE_format_type = {
	'1':{'1':'1',	'2':'0.4',	'3':'0.3',	'4':'0.26',	'5':'0.22',	'6':'0.2',	'7':'0.18',	'8':'0.16',	'9':'0.14',	'10':'0.12'}
	, '2':{'1':'2',	'2':'1',	'3':'0.8',	'4':'0.72',	'5':'0.68',	'6':'0.6',	'7':'0.52',	'8':'0.48',	'9':'0.4',	'10':'0.35'}
};
var BACK_format_type = {
	'0':{'1':'0',	'2':'0',	'3':'0',	'4':'0',	'5':'0',	'6':'0',	'7':'0',	'8':'0',	'9':'0',	'10':'0'}
	, '1':{'1':'1',	'2':'0.4',	'3':'0.3',	'4':'0.26',	'5':'0.22',	'6':'0.2',	'7':'0.18',	'8':'0.16',	'9':'0.14',	'10':'0.12'}
	, '2':{'1':'2',	'2':'1',	'3':'0.8',	'4':'0.72',	'5':'0.68',	'6':'0.6',	'7':'0.52',	'8':'0.48',	'9':'0.4',	'10':'0.35'}
};
	
var E_format_type = {
	'0':{'Q':'0', 'X':'0'}
	,'1':{'Q':'0.06', 'X':'10'}
	,'2':{'Q':'0.09', 'X':'10'}
	,'3':{'Q':'0.12', 'X':'10'}
	,'4':{'Q':'0.18', 'X':'10'}
	,'5':{'Q':'0.18', 'X':'10'}
	,'6':{'Q':'0.18', 'X':'10'}
	,'7':{'Q':'0.34', 'X':'10'}
	,'8':{'Q':'0.34', 'X':'10'}
	,'9':{'Q':'0.36', 'X':'112'}
	,'10':{'Q':'0.63', 'X':'196'}
};
	
var W_format_type = {
	'1':{'W':'0.10'}
	, '2':{'W':'0.08'}
	, '3':{'W':'0.06'}
	, '4':{'W':'0.05'}
	, '5':{'W':'30'}
	, '6':{'W':'80'}
};


function build_calculator(params) {
/*
N/О=P					
(B+C)*P=цена за печат					
P*D=цена за хартия					
(P*Q)+X=цена покритие					
(P*Y)+((J+K)*0,01)+((J+K)*2)=цена за дов. Работи					
L=цена за щанцоване					
M=цена за предпечат					

*/
	var paper = 0;
	var p_print = 0;
	var dov_rab = 0;
	var pred_pechat = 0;
	var P = 0;
	P = (params['N']/params['O']);
	print_price = ((params['B']+params['C'])*P)
	paper_price = (params['D']*P);
	cover_price = ((P*params['Q'])+params['X']);
//	work_price = ( (P*params['Y'])+((params['J']+params['K'])*0.01)+((params['J']+params['K'])*2) );
	work_price =  ((P*params['F']*params['Y'])+((params['J']+params['K'])*0.01)+((params['J']+params['K'])*2) );
	punching_price = params['L'];
	prepress_price = params['M'];
	
	var total_sum = (print_price+paper_price+cover_price+work_price+punching_price+prepress_price);
	//(ОБЩО ПЕЧАТ *Z)+(ОБЩО ХАРТИЯ  *Z)+(ОБЩО ПОКРИТИЕ *Z)+(ОБЩО ДОВ. РАБОТА *Z)+(ОБЩО ЩАНЦОВАНЕ*Z)+ОБЩО ПРЕДПЕЧАТ
	
	
	var singel_sum = total_sum/params['N'];
	var vat_sum = (((20.0+100)/100)*total_sum);
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

return false;
//	add_msg(html,MSG_SUCCESS);
}//build_calculator


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	if(result.toString().indexOf(".") == -1) result = result+'.00';
	

	return result; //parseFloat(result);
//		return num;
}
