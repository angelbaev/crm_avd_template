// JavaScript Document
var print_format_type = {
	'1':{'1':'0.72','2':'1.88','3':'0.72'}
	,'2':{'1':'0.65','2':'1.8','3':'0.65'}
	,'3':{'1':'0.6','2':'1.75','3':'0.6'}
	,'4':{'1':'0.55','2':'1.66','3':'0.55'}
	,'5':{'1':'160','2':'220','3':'95'}
	,'6':{'1':'180','2':'265','3':'113'}
	,'7':{'1':'200','2':'315','3':'133'}
	,'8':{'1':'220','2':'365','3':'151'}
	,'9':{'1':'240','2':'412','3':'170'}
};


var list_format_type = {
	'1':{'1':'4', '2':'4', '3':'4.4'}
	, '2':{'1':'6', '2':'6', '3':'6'}
	, '3':{'1':'7', '2':'7', '3':'7.4'}
	, '4':{'1':'12', '2':'12', '3':'12'}
	, '5':{'1':'13', '2':'13', '3':'13.4'}
};

var paper_format_type = {
	'1':{'1':'0.09','2':'0.18','3':'0.06'}
	, '2':{'1':'0.11','2':'0.22','3':'0.076'}
	, '3':{'1':'0.125','2':'0.25','3':'0.084'}
	, '4':{'1':'0.22','2':'0.44','3':'0.147'}
};

var cover_format_type = {
	'2':{'1':'0.1','2':'0.15','3':'0.08'}
	, '3':{'1':'0.24','2':'0.32','3':'0.16'}
};

var hook_format_type = {
	'1':{'1':'1','2':'1.5','3':'0.9'}
	, '2':{'1':'1.5','2':'2.1','3':'1.5'}
};





function build_calculator(params) {
	//((A+(B*C)+(B*F))*E)+D=обща цена
/*
Формула ако К е равно или по-голямо от 360										
((B*L)*C)+(((D*B)+((E*B)*C)+F)*K)+(J*B)=обща цена										
B - ползва се абсолютна стойност - без цифрите след запетаята - важи само за 2-те B в червено										
										
										
Формула ако К е по-малко или равно на 359, а А е различно от "50х70"										
(((B*L)*C)+((D*B)+((E*B)*C)+F)*K)+(J*B)=обща цена										
B - ползва се абсолютна стойност - без цифрите след запетаята - важи само за 2-те B в червено										
										
										
Формула ако К е по-малко или равно на 359, а А е "50х70"										
(((B*L)*C)+((D*B)+((E*B)*C)+F)*K)+(J*B)+80=обща цена										

*/	
var view_form = ''
	if (params['K'] >= 360) {
		//((B*L)*C)+(((D*B)+((E*B)*C)+F)*K)+(J*B)=обща цена
		var total_sum = ( ((params['B']*params['L'])*params['C'])+(((params['D']*params['B'])+((params['E']*params['B'])*params['C'])+params['F'])*params['K'])+(params['J']*params['B']) );
    view_form = 'K >= 360: '+total_sum;
	} else if (params['K'] <= 359 && params['A'] != 2) {
		
		// ((((B*L)*C)+(D*B)+((E*B)*C)+F)*K)+(J*B)=обща
    var total_sum =  ( ((((params['B']*params['L'])*params['C'])+(params['D']*params['B'])+((params['E']*params['B'])*params['C'])+params['F'])*params['K'])+(params['J']*params['B']) ); //( (((params['B']*params['L'])*params['C'])+(params['D']*params['B'])+((params['E']*params['B'])*params['C'])+params['F'])*params['K'])+(params['J']*params['B']) );
    view_form = 'K <= 359: '+total_sum;
	} else {
		//(((B*L)*C)+((D*B)+((E*B)*C)+F)*K)+(J*B)+80=обща цена	
		//((((B*L)*C)+(D*B)+((E*B)*C)+F)*K)+(J*B)+80=обща цена									
		var total_sum = ( ((((params['B']*params['L'])*params['C'])+(params['D']*params['B'])+((params['E']*params['B'])*params['C'])+params['F'])*params['K'])+(params['J']*params['B'])+80 );
    view_form = 'Всичко останало : '+total_sum;
	//	( (((params['B']*params['L'])*params['C'])+((params['D']*params['B'])+((params['E']*params['B'])*params['C'])+params['F'])*params['K'])+(params['J']*params['B'])+80 );
	}
//	add_msg(view_form,MSG_SUCCESS);
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
