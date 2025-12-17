// JavaScript Document

	

var A_format_type = {

	'1':{'K':'1.2', 'L':'12'}

	, '2':{'K':'1.5', 'L':'6'}

};



var M_format_type = {

	'1':{'M':'0.95'}

	, '2':{'M':'0.85'}

	, '3':{'M':'0.75'}

	, '4':{'M':'0.7'}

	, '5':{'M':'0.65'}

	, '6':{'M':'0.55'}

	, '7':{'M':'0.43'}

	, '8':{'M':'0.32'}

	, '9':{'M':'0.27'}

	, '10':{'M':'0.24'}

};







	

	







function build_calculator(params) {

/*

(F*B)/L=P (тираж А3)				

(P*C)*M=цена за печат				

(P+10)*0,07=цена за хартия				

(P*C)*D=цена за покритие				

(Е*B)=цена за предпечат				

E*K=цена за пластмасови холдери				



*/

	price_print  = ((params['P']*params['C'])*params['M']);

	price_paper = ((params['P']+10)*0.07);

	price_cover = ((params['P']*params['C'])*params['D']);

	price_prepress  = (params['E']*params['B']);

	price_holders = (params['F']*params['K']);

	

	

	var total_sum = (price_print + price_paper + price_cover + price_prepress + price_holders);

	//(ОБЩО ПЕЧАТ *Z)+(ОБЩО ХАРТИЯ  *Z)+(ОБЩО ПОКРИТИЕ *Z)+(ОБЩО ДОВ. РАБОТА *Z)+(ОБЩО ЩАНЦОВАНЕ*Z)+ОБЩО ПРЕДПЕЧАТ

	

	

	var singel_sum = total_sum/params['F'];

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

