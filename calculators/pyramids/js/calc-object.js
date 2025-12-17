// JavaScript Document
var L_format_type = {'1':'1.65' ,'2':'1.5' ,'3':'1.15' ,'4':'320' ,'5':'350' ,'6':'380' ,'7':'410' ,'8':'440'};


var P_format_type = {'1':'6' ,'2':'7' ,'3':'12' ,'4':'13'};

var M_format_type = {
		'1':{'1':'0.9' ,'2':'1.5' ,'3':'1'}
		,'2':{'1':'1' ,'2':'1.75' ,'3':'1.2'}
		,'3':{'1':'1.9' ,'2':'3' ,'3':'2'}
		,'4':{'1':'2' ,'2':'3.25' ,'3':'2.2'}
};

var N_format_type = {
		'1':{'1':'1' ,'2':'2' ,'3':'1'}
		,'2':{'1':'1' ,'2':'2' ,'3':'2'}
		,'3':{'1':'2' ,'2':'3' ,'3':'2'}
		,'4':{'1':'2' ,'2':'4' ,'3':'3'}
};

var pad_format_type = {
	'1':{
		'1':{'1':'0.96','2':'0.94','3':'0.92','4':'0.90','5':'0.88','6':'0.82','7':'0.76'}
		, '2':{'1':'1.11','2':'1.08','3':'1.04','4':'0.98','5':'0.92','6':'0.86','7':'0.80'}
		, '3':{'1':'1.26','2':'1.24','3':'1.22','4':'1.20','5':'1.08','6':'1.02','7':'0.90'}
	}
	, '2':{
		'1':{'1':'1.12','2':'1.10','3':'1.08','4':'1.06','5':'1.02','6':'0.96','7':'0.89'}
		, '2':{'1':'1.26','2':'1.25','3':'1.24','4':'1.22','5':'1.06','6':'0.99','7':'0.92'}
		, '3':{'1':'1.42','2':'1.40','3':'1.38','4':'1.36','5':'1.12','6':'1.03','7':'0.95'}
	}
, '3':{
		'1':{'1':'1','2':'0.98','3':'0.96','4':'0.94','5':'0.89','6':'0.85','7':'0.8'}
		, '2':{'1':'1.2','2':'1.16','3':'1.12','4':'1.08','5':'0.96','6':'0.89','7':'0.85'}
		, '3':{'1':'1.35','2':'1.33','3':'1.31','4':'1.29','5':'1.24','6':'1.16','7':'1.06'}
	}
};





function build_calculator(params) {
/*
Формула ако К е по-малко или равно на 199							
((((L*C)*M)+(D*M)+((E*C)*M)+F+(0,01*P))*K)+(J*P)=обща цена							
							
Формула ако К е по-голямо или равно на 200							
(((D*N)+((E*C)*N)+F+(0,006*P))*K)+(J*P)+((L*C)*N)=обща цена							

*/
	if (params['K'] <= 234) {
		//((((L*C)*M)+(D*M)+((E*C)*M)+F+(0,01*P))*K)+(J*P)=обща цена	
		var total_sum = ( ((((params['L']*params['C'])*params['M'])+(params['D']*params['M'])+((params['E']*params['C'])*params['M'])+params['F']+(0.01*params['P']))*params['K'])+(params['J']*params['P']) );
		//( ((params['B']*params['L'])*params['C'])+(((params['D']*params['B'])+((params['E']*params['B'])*params['C'])+params['F'])*params['K'])+(params['J']*params['B']) );
	} else {
		//(((D*N)+((E*C)*N)+F+(0,006*P))*K)+(J*P)+((L*C)*N)=обща цена	
		var total_sum = ( (((params['D']*params['N'])+((params['E']*params['C'])*params['N'])+params['F']+(0.006*params['P']))*params['K'])+(params['J']*params['P'])+((params['L']*params['C'])*params['N']) );
		//( ((((params['B']*params['L'])*params['C'])+(params['D']*params['B'])+((params['E']*params['B'])*params['C'])+params['F'])*params['K'])+(params['J']*params['B'])+80 );
	}
	
	var singel_sum = total_sum/params['K'];
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
