// JavaScript Document
var article_type = {
  '1': {'1': 60, '2': 75, '3': 0.65, '4': 0.6, '5': 0.55, '6': 0.5, '7': 0.45},
'2': {'1': 70, '2': 90, '3': 0.8, '4': 0.75, '5': 0.7, '6': 0.65, '7': 0.6},
'3': {'1': 80, '2': 110, '3': 1, '4': 0.95, '5': 0.9, '6': 0.75, '7': 0.65},
'4': {'1': 90, '2': 130, '3': 1.15, '4': 1.1, '5': 1, '6': 0.9, '7': 0.8},
'5': {'1': 160, '2': 250, '3': 2, '4': 1.7, '5': 1.6, '6': 1.5, '7': 1.4},
'6': {'1': 180, '2': 270, '3': 2.1, '4': 1.8, '5': 1.7, '6': 1.6, '7': 1.5},
'7': {'1': 200, '2': 290, '3': 2.2, '4': 1.9, '5': 1.8, '6': 1.7, '7': 1.6}
};

function build_calculator(params) {
// Ако Е е от 10 до 100				
// (А*B)+C = обща цена				
// ----------------------
// Ако Е е над 101				
// ((А*E)*B)+C = обща цена				

	var total_sum = 0;
	if (params['E'] <= 100) {
      total_sum = ((params['A']*params['B'])+params['C']);
  } else {
      total_sum = (((params['A']*params['E'])*params['B'])+params['C']);;
  }
	var singel_sum = total_sum/params['E'];
	var vat_sum = (((20.0+100)/100)*total_sum);
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

return false;
}//build_calculator


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result.toFixed(2);
}
