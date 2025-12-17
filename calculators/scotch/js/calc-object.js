// JavaScript Document

var count_in_box = {
	'19mm':{'F':'72'}
	, '25mm':{'F':'72'}
	, '36mm':{'F':'72'}
	, '48mm':{'F':'72'}
	, 'PVC50mm':{'F':'54'}
};

var cliche_type = {
	'19mm':{'C':'60'}
	, '25mm':{'C':'60'}
	, '36mm':{'C':'60'}
	, '48mm':{'C':'60'}
	, 'PVC50mm':{'C':'60'}
};

var count_format_type = {
	'1':{
		'19mm':'3.08'
		, '25mm':'3.19'
		, '36mm':'3.52'
		, '48mm':'4.39'
		, 'PVC50mm':'3.63'
		}
	, '2':{
		'19mm':'2.97'
		, '25mm':'3.08'
		, '36mm':'3.41'
		, '48mm':'4.28'
		, 'PVC50mm':'3.52'
		}
	, '3':{
		'19mm':'2.75'
		, '25mm':'2.97'
		, '36mm':'3.25'
		, '48mm':'4.22'
		, 'PVC50mm':'3.36'
		}
	, '4':{
		'19mm':'2.53'
		, '25mm':'2.75'
		, '36mm':'3.19'
		, '48mm':'4.17'
		, 'PVC50mm':'3.3'
		}
	, '5':{
		'19mm':'2.48'
		, '25mm':'2.59'
		, '36mm':'3.14'
		, '48mm':'4.11'
		, 'PVC50mm':'3.25'
		}
};
function build_calculator(params) {
 //((J*(?+L))+(C*B))+D= obshta cena

 var total_sum = (((params['J']*(params['K']+params['L']))+(params['C']*params['B']))+params['D']);
 
 var singel_sum = total_sum/params['J'];
 var vat_sum = (((20.0+100)/100)*total_sum);
 
 $('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
 $('#sum_total').html(roundNumber(total_sum, 2)+' лв');
 $('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

return false;
// add_msg(html,MSG_SUCCESS);
}


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
//		return num;
}



