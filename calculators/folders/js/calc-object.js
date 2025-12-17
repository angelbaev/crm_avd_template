// JavaScript Document



var R_format_type = {'1':'50', '2':'90', '3':'130', '4':'160'};



	



var P_format_type = {



	'1':{'M1':'28', 'M2':'40'}



	, '2':{'M1':'35', 'M2':'52'}



	, '3':{'M1':'42', 'M2':'64'}



	, '4':{'M1':'49', 'M2':'76'}



	, '5':{'M1':'56', 'M2':'88'}



	, '6':{'M1':'63', 'M2':'96'}



	, '7':{'M1':'70', 'M2':'104'}



	, '8':{'M1':'77', 'M2':'112'}



	, '9':{'M1':'84', 'M2':'120'}



	, '10':{'M1':'91', 'M2':'128'}



	, '11':{'M1':'98', 'M2':'136'}



	, '12':{'M1':'105', 'M2':'144'}



	, '13':{'M1':'112', 'M2':'152'}



	, '14':{'M1':'119', 'M2':'160'}



	, '15':{'M1':'126', 'M2':'168'}



	, '16':{'M1':'133', 'M2':'176'}



};







var F_format_type = {



	'0':{'M1':'0', 'M2':'0', 'X':'0'}



	, '1':{'M1':'0.05', 'M2':'0.08', 'X':'12'}



	, '2':{'M1':'0.08', 'M2':'0.13', 'X':'12'}



	, '3':{'M1':'0.10', 'M2':'0.16', 'X':'12'}



	, '4':{'M1':'0.16', 'M2':'0.26', 'X':'12'}



	, '5':{'M1':'0.10', 'M2':'0.15', 'X':'15'}



	, '6':{'M1':'0.08', 'M2':'0.13', 'X':'15'}



	, '7':{'M1':'0.20', 'M2':'0.30', 'X':'15'}



	, '8':{'M1':'0.16', 'M2':'0.26', 'X':'15'}



	, '9':{'M1':'0.21', 'M2':'0.27', 'X':'55'}



	, '10':{'M1':'0.42', 'M2':'0.54', 'X':'110'}



};







var K_format_type = {



	'1':{'1':'0.84', '2':'0.98'}



	, '2':{'1':'0.52', '2':'0.6'}



	, '3':{'1':'0.5', '2':'0.58'}



	, '4':{'1':'0.46', '2':'0.55'}



	, '5':{'1':'0.42', '2':'0.49'}



	, '6':{'1':'0.38', '2':'0.44'}



	, '7':{'1':'0.32', '2':'0.38'}



	, '8':{'1':'0.3', '2':'0.36'}



	, '9':{'1':'0.25', '2':'0.31'}



};







var W_format_type = {



	'1':{'M1':'0.06', 'M2':'0.08'}



	, '2':{'M1':'0.04', 'M2':'0.07'}



	, '3':{'M1':'0.02', 'M2':'0.06'}



	, '4':{'M1':'0.015', 'M2':'0.04'}



	, '5':{'M1':'40', 'M2':'75'}



};











var A_format_type = {



	'1':{'M':'M2', 'N':'2', 'O':'2'}



	, '2':{'M':'M1', 'N':'1', 'O':'4'}



	, '3':{'M':'M1', 'N':'1', 'O':'4'}



	, '4':{'M':'M1', 'N':'1', 'O':'4'}



	, '5':{'M':'M1', 'N':'1', 'O':'4'}



	, '6':{'M':'M1', 'N':'2', 'O':'4'}



};







function build_calculator(params) {

/*

K/N=P (не участва в тотала)			

(P*W)+S=V (не участва в тотала)			

((B+C)*T)+D=цена за печат *Z			

((P+R)/O)*E=цена за хартия *Z			

(P*F)+X=цена за покритие *Z			

((K*G)+H)+Q=цена за работа *Z			

U=цена за предпечат			

Ако A е едно от 4-те,  B=4, C е различно от 0, а К е по-малко от МИН: 

формулата за цената за печат се променя на: (P+5)*1,1=цена за печата, а формулата за хартия се променя на(P/O)*E=цена за хартия *Z

*/

	var paper_price = print_price = work_price = cover_price = U = 0;

  var K_MIN = 0;

  var can_change_FM = false;

  if (params['A'] == 1) {K_MIN = false; can_change_FM = false;}

  if (params['A'] == 2) {K_MIN = false; can_change_FM = false;}

  if (params['A'] == 3) {K_MIN = 200; can_change_FM = true;}

  if (params['A'] == 4) {K_MIN = 200; can_change_FM = true;}

  if (params['A'] == 5) {K_MIN = 200; can_change_FM = true;}

  if (params['A'] == 6) {K_MIN = 300; can_change_FM = true;}



   var c_msg = '';

   if (can_change_FM  && params['K'] <= K_MIN) {

      c_msg += 'A != 1 или 2  и K < W <br>';

      if (params['C'] == 0) {

        print_price = ((params['P']+5)*0.8);

        c_msg += 'C == 0 <br>';

        c_msg += '	print_price = ((params[\'P\']+5)*0.8) | '+print_price+' = (('+params['P']+'+5)*0.8)<br>';

        paper_price =  ((params['P']/params['O'])*params['E']);

        c_msg += '	paper_price =  ((params[\'P\']/params[\'O\'])*params[\'E\']) | '+paper_price+' = (('+params['P']+'/'+params['O']+')*'+params['E']+') ------ цена за хартия ='+(paper_price*params['Z'])+'<br>';

        

      } else {

        c_msg += 'C != 0 <br>';

      	print_price = ((params['P']+5)*1.4);

        c_msg += '	print_price = ((params[\'P\']+5)*1.1) | '+print_price+' = (('+params['P']+'+5)*1.1)<br>';

     // 	paper_price = (((params['P']+params['R'])/params['O'])*params['E']);

      	paper_price = ((params['P']/params['O'])*params['E']);

        c_msg += '	paper_price =  ((params[\'P\']/params[\'O\'])*params[\'E\']) | '+paper_price+' = (('+params['P']+'/'+params['O']+')*'+params['E']+') ------ цена за хартия ='+(paper_price*params['Z'])+'<br>';

      

      }

   } else if (params['K'] > K_MIN) {

        c_msg += 'A != 1 или 2  и K > W <br>';

      	print_price = (((params['B']+params['C'])*params['T'])+ params['D']);

        c_msg += '	print_price = (((params[\'B\']+params[\'C\'])*params[\'T\'])+ params[\'D\']) | '+paper_price+' = ((('+params['B']+'+'+params['C']+')*'+params['T']+')+'+params['D']+')<br>';

        paper_price =  ((params['P']/params['O'])*params['E']);

        c_msg += '	paper_price =  ((params[\'P\']/params[\'O\'])*params[\'E\']) | '+paper_price+' = (('+params['P']+'/'+params['O']+')*'+params['E']+') ------ цена за хартия ='+(paper_price*params['Z'])+'<br>';

   

   } else {

        c_msg += 'A != 1 или 2  и K > W <br>';

      	print_price = (((params['B']+params['C'])*params['T'])+ params['D']);

        c_msg += '	print_price = (((params[\'B\']+params[\'C\'])*params[\'T\'])+ params[\'D\']) | '+paper_price+' = ((('+params['B']+'+'+params['C']+')*'+params['T']+')+'+params['D']+')<br>';

        paper_price =  ((params['P']/params['O'])*params['E']);

        c_msg += '	paper_price =  ((params[\'P\']/params[\'O\'])*params[\'E\']) | '+paper_price+' = (('+params['P']+'/'+params['O']+')*'+params['E']+') ------ цена за хартия ='+(paper_price*params['Z'])+'<br>';

   }

   /*

//  if (can_change_FM  &&  params['B'] == 4 &&  params['C'] == 0 && params['K'] < K_MIN) {

  if (can_change_FM  &&  params['C'] == 0 && params['K'] <= K_MIN) {

    c_msg += 'формулата за цената за печат се променя на: (P+5)*1,1=цена за печата, а формулата за хартия се променя на(P/O)*E=цена за хартия *Z<br>';

   // alert('формулата за цената за печат се променя на: (P+5)*1,1=цена за печата, а формулата за хартия се променя на(P/O)*E=цена за хартия *Z');

   //(P+5)*0,6=цена за печат

  	print_price = ((params['P']+5)*0.6);

    c_msg += '	print_price = ((params[\'P\']+5)*0.6) | '+print_price+' = (('+params['P']+'+5)*0.6)<br>';

  	paper_price =  ((params['P']/params['O'])*params['E']);

    c_msg += '	paper_price =  ((params[\'P\']/params[\'O\'])*params[\'E\']) | '+paper_price+' = (('+params['P']+'/'+params['O']+')*'+params['E']+') ------ цена за хартия ='+(paper_price*params['Z'])+'<br>';

  } else {

    c_msg += 'Ако е различно от това условие -> формулата за цената за печат се променя на: (P+5)*1,1=цена за печата, а формулата за хартия се променя на(P/O)*E=цена за хартия *Z<br>';

  //	print_price = ((params['B']+params['C'])*params['T'])+params['D'];

  	print_price = ((params['P']+5)*1.1);

    c_msg += '	print_price = ((params[\'P\']+5)*1.1) | '+print_price+' = (('+params['P']+'+5)*1.1)<br>';

 // 	paper_price = (((params['P']+params['R'])/params['O'])*params['E']);

  	paper_price = ((params['P']/params['O'])*params['E']);

    c_msg += '	paper_price =  ((params[\'P\']/params[\'O\'])*params[\'E\']) | '+paper_price+' = (('+params['P']+'/'+params['O']+')*'+params['E']+') ------ цена за хартия ='+(paper_price*params['Z'])+'<br>';

  } 

*/

	cover_price = ((params['P']*params['F'])+params['X']);

  c_msg += '	cover_price = ((params[\'P\']*params[\'F\'])+params[\'X\']) | '+cover_price+' = (('+params['P']+'*'+params['F']+')+'+params['X']+')<br>';



	work_price = (((params['K']*params['G'])+params['H'])+params['Q']);

  c_msg += '	work_price = (((params[\'K\']*params[\'G\'])+params[\'H\'])+params[\'Q\']) | '+work_price+' = ((('+params['K']+'*'+params['G']+')+'+params['H']+')+'+params['Q']+')<br>';



	U = params['U'];



	var total_sum =  (((paper_price+print_price+cover_price+work_price)*params['Z'])+U);

  c_msg += 'total_sum =  (((paper_price+print_price+cover_price+work_price)*params[\'Z\'])+U) | '+total_sum+' = ((('+paper_price+'+'+print_price+'+'+cover_price+'+'+work_price+')*'+params['Z']+')+'+U+')<br>';



  //add_msg(c_msg,MSG_SUCCESS); 



	var singel_sum = total_sum/params['K'];

	var vat_sum = (((20.0+100)/100)*total_sum);



	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');

	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');

	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');



return false;



}







function __build_calculator1(params) {







/*



((B+C)*T)+D=цена за печат *Z			



((P+R)/O)*E=цена за хартия *Z			



(P*F)+X=цена за покритие *Z			



((K*G)+H)+Q=цена за работа *Z			



U=цена за предпечат			



*/



/*



	var paper_price = print_price = work_price = cover_price = U = 0;



	print_price = ((params['B']+params['C'])*params['T'])+params['D'];







	paper_price = (((params['P']+params['R'])/params['O'])*params['E']);



	cover_price = ((params['P']*params['F'])+params['X']);



	work_price = (((params['K']*params['G'])+params['H'])+params['Q']);



	U = params['U'];



	//Общо е = (((цена за печат + цена за хартия + цена за покритие + цена за работа)*Z)+U);



	var total_sum =  (((paper_price+print_price+cover_price+work_price)*params['Z'])+U);



	//(((((params['P']+params['R'])*params['L'])+(((params['B']+params['C'])*params['T'])+params['D'])+((params['Q']*params['J'])+(params['Q']*params['M']))+(params['Q']*params['F'])+(((params['Q']/params['A'])*params['K'])+params['X']))*params['Z'])+N);



	



	//(ОБЩО ПЕЧАТ *Z)+(ОБЩО ХАРТИЯ  *Z)+(ОБЩО ПОКРИТИЕ *Z)+(ОБЩО ДОВ. РАБОТА *Z)+(ОБЩО ЩАНЦОВАНЕ*Z)+ОБЩО ПРЕДПЕЧАТ



  */



	var paper_price = print_price = work_price = cover_price = U = 0;



  var K_MIN = 0;



  



 // if (params['A'] == 1) {K_MIN = 100;}



//  if (params['A'] == 2) {K_MIN = 100;}



  if (params['A'] == 3) {K_MIN = 200;}



  if (params['A'] == 4) {K_MIN = 200;}



  if (params['A'] == 5) {K_MIN = 200;}



  if (params['A'] == 6) {K_MIN = 400;}



  var debug_formula = '';



	if ((params['A'] != 1 || params['A'] != 2)  &&  params['B'] == 4 &&  params['C'] == 0 && params['K'] < K_MIN) {



    debug_formula = 'Ако A е едно от 4-те,  B=4,C=0, а К е по-малко от МИН:';



    print_price = ((params['P']+5)*0.6); //(P+5)*0,6=цена за печат



    paper_price = ((params['P']/params['O'])*params['E'])*params['Z'];//(P/O)*E=цена за хартия *Z 



  }  else if ((params['A'] != 1 || params['A'] != 2)  &&  params['B'] == 4 &&  params['C'] != 0 && params['K'] < K_MIN) {



    debug_formula = 'Ако A е едно от 4-те,  B=4, C е различно от 0, а К е по-малко от МИН:';



    print_price = ((params['P']+5)*1.1);  //(P+5)*1,1=цена за печата



    paper_price = ((params['P']/params['O'])*params['E'])*params['Z'];



  }  else {



  	print_price = ((params['B']+params['C'])*params['T'])+params['D'];



  	paper_price = (((params['P']+params['R'])/params['O'])*params['E']);



  }







	cover_price = ((params['P']*params['F'])+params['X']);



	work_price = (((params['K']*params['G'])+params['H'])+params['Q']);



	U = params['U'];



	var total_sum =  (((paper_price+print_price+cover_price+work_price)*params['Z'])+U);







   



	var singel_sum = total_sum/params['K'];



	var vat_sum = (((20.0+100)/100)*total_sum);



  



  



alert(debug_formula+' paper_price: '+paper_price+', print_price: '+print_price+', cover_price: '+cover_price+', work_price: '+work_price+', U: '+U+', total_sum: '+total_sum+', singel_sum: '+singel_sum);	







	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');



	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');



	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');



var __html = 'Цена хартия: =	(E*(O/(R+P)))	<br>'+



'Цена хартия: =	(	'+params['E']+'*('+params['O']+'/('+params['R']+'+'+params['P']+'))<br>'+



'Цена хартия: =	'+paper_price+'<br>';



//add_msg('цена за печат: '+print_price+'<br> цена за хартия: '+paper_price+'<br> цена за покритие: '+cover_price+'<br> цена за работа: '+work_price+'<br>цена за предпечат: '+U+'<br> тотал: '+total_sum+'<br><br><br>'+__html ,MSG_INFO);



return false;



//	add_msg(html,MSG_SUCCESS);



}//build_calculator







function roundNumber(num, dec) {



	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);



	if(result.toString().indexOf(".") == -1) result = result+'.00';



	







	return result; //parseFloat(result);



//		return num;



}



