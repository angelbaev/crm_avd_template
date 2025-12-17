// JavaScript Document

var size_table = {
  '1': {'A': 84,'B': 90,'C': 30,'D': 28},
  '2': {'A': 88,'B': 94,'C': 37,'D': 31},
  '3': {'A': 75,'B': 105,'C': 55,'D': 30},
  '4': {'A': 80,'B': 110,'C': 60,'D': 30},
  '5': {'A': 158,'B': 120,'C': 85,'D': 74},
  '6': {'A': 190,'B': 145,'C': 100,'D': 90}
};

function build_calculator(params) {
// ((A+B+C+D)*J)+E=обща цена
	var total_sum = (((params['A'] + params['B'] + params['C'] + params['D']) * params['J']) + params['E']);

	var singel_sum = total_sum/params['J'];
	var vat_sum = (((20.0+100)/100)*total_sum);
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

return false;
}//build_calculator


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result.toFixed(2);;
}
