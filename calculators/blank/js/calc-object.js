// JavaScript Document
var R_format_type = {'1':'50', '2':'90', '3':'130', '4':'160'};
	
var Q_format_type = {
    '1':{'T':'50', 'Y':'15'},
    '2':{'T':'68', 'Y':'25'},
    '3':{'T':'86', 'Y':'35'},
    '4':{'T':'104', 'Y':'45'},
    '5':{'T':'122', 'Y':'55'},
    '6':{'T':'140', 'Y':'65'},
    '7':{'T':'158', 'Y':'75'},
    '8':{'T':'176', 'Y':'85'},
    '9':{'T':'194', 'Y':'95'},
    '10':{'T':'212', 'Y':'105'},
    '11':{'T':'230', 'Y':'115'},
    '12':{'T':'248', 'Y':'125'},
    '13':{'T':'266', 'Y':'135'},
    '14':{'T':'284', 'Y':'145'},
    '15':{'T':'302', 'Y':'155'},
    '16':{'T':'320', 'Y':'165'}
};





function build_calculator(params) {
/*
total = ((õàğòèÿ*Z) + (ïå÷àò*Z) + (ïîêğèòèå*Z) + (äîâ. ğàáîòè*Z))
(
((Q/À)+R)/4)*F = õàğòèÿ							
((B+C)*T)+D = ïå÷àò							
Y = äîâ. Ğàáîòè							
Î+P = ïğåäïå÷àò							

*/
	var paper = 0;
	var p_print = 0;
	var dov_rab = 0;
	var pred_pechat = 0;

	paper = (	(((params['Q']/params['A'])+params['R'])/4)*params['F']	);
	p_print = (	((params['B']+params['C'])*params['T'])+params['D']	);
	dov_rab = params['Y'];
	pred_pechat = (params['O']+params['P']);
	
	var total_sum = ((p_print*params['Z']) + (paper*params['Z']) + (dov_rab*params['Z']) +pred_pechat);
	//(ÎÁÙÎ ÏÅ×ÀÒ *Z)+(ÎÁÙÎ ÕÀĞÒÈß  *Z)+(ÎÁÙÎ ÏÎÊĞÈÒÈÅ *Z)+(ÎÁÙÎ ÄÎÂ. ĞÀÁÎÒÀ *Z)+(ÎÁÙÎ ÙÀÍÖÎÂÀÍÅ*Z)+ÎÁÙÎ ÏĞÅÄÏÅ×ÀÒ
	
	
	var singel_sum = total_sum/params['Q'];
	var vat_sum = (((20.0+100)/100)*total_sum);
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' ëâ/áğ');
	$('#sum_total').html(roundNumber(total_sum, 2)+' ëâ');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' ëâ');

return false;
//	add_msg(html,MSG_SUCCESS);
}//build_calculator


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	if(result.toString().indexOf(".") == -1) result = result+'.00';
	

	return result; //parseFloat(result);
//		return num;
}
