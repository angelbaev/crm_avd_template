// JavaScript Document

var invoice_small_format_type = {
	'N_1':{'L':'16'}
	, 'N_2':{'L':'22'}
	, 'N_3':{'L':'28'}
	, 'N_4':{'L':'48'}
	};

var invoice_large_format_type = {
	'M_1':{
		'A4':'6'
		,'A5':'4'
		,'A6':'3.5'
		,'10х21':'4'
	}
	, 'M_2':{
		'A4':'4.5'
		,'A5':'3'
		,'A6':'2.5'
		,'10х21':'3'
	}
	, 'M_3':{
		'A4':'3.6'
		,'A5':'2.5'
		,'A6':'2'
		,'10х21':'2.5'
	}
	, 'M_4':{
		'A4':'3.3'
		,'A5':'2.3'
		,'A6':'1.8'
		,'10х21':'2.3'
	}
	, 'M_5':{
		'A4':'2.8'
		,'A5':'1.9'
		,'A6':'1.4'
		,'10х21':'1.9'
	}
	, 'M_6':{
		'A4':'2.4'
		,'A5':'1.6'
		,'A6':'1.1'
		,'10х21':'1.6'
	}
 
}

function invoice_calculator(params) {
	//[{A (според М)xK}+{(B+E+F)хL(според N)}+(DxK)+J]xC=Oбща цена
	//	total_sum = C*( J+(K*D) + (L*(F+E+B)) + (K*A)   );
	
	
	
	//total_sum = C*(J+(D*K)+(L*(F+E+B))+(K*A))
	//Обща цена / К = Ед. Цена 
//alert(params['C']*(params['J']+(params['K']*params['D'])+(params['L']*(params['F']+params['E']+params['B']))+(params['K']*params['A'])));	
//	var total_sum = params['C']*(params['J']+(params['K']*params['D'])+(params['L']*(params['F']+params['E']+params['B']))+(params['K']*params['A']));


//	var total_sum = params['C']*(params['J']+(params['D']*params['K'])+(params['L']*(params['F']+params['E']+params['B']))+(params['K']*params['A']));

	total_sum = params['C']*( params['J']+(params['K']*params['D']) + (params['L']*(params['F']+params['E']+params['B'])) + (params['K']*params['A'])   );

//	total_sum = 1.35*( 5+(10*0.5) + (16*(1+1+0)) + (10*4.5)   );
	var singel_sum = total_sum/params['K'];
	var vat_sum = (((20.0+100)/100)*total_sum);
//( 17.5 + 100 ) / 100 )
//	var total_sum = params['C']*( params['J']+(params['K']+params['D']) );
	var Formula = 'total_sum = params[\'C\']*(params[\'J\']+(params[\'D\']*params[\'K\'])+(params[\'L\']*(params[\'F\']+params[\'E\']+params[\'B\']))+(params[\'K\']*params[\'A\']))';
	var Formula_1 = 'total_sum = '+params['C']+'*('+params['J']+'+('+params['D']+'*'+params['K']+')+('+params['L']+'*('+params['F']+'+'+params['E']+'+'+params['B']+'))+('+params['K']+'*'+params['A']+'))';
	var	param_html = '<br>(размер) [A (Спрямо M)] = '+params['A']+'<br>';
	 		param_html += ' (цветност)[B] = '+params['B']+'<br>';
			param_html += '(брой в кочан) [C] = '+params['C']+'<br>';
			param_html += '(корица) [D] = '+params['D']+'<br>';
			param_html += '(перфорация) [E] = '+params['E']+'<br>';
			param_html += '(номерация) [F] = '+params['F']+'<br>';
			param_html += '(файл) [J] = '+params['J']+'<br>';
			param_html += '(брой кочани) [K] = '+params['K']+'<br>';
			param_html += '[L(според N)] = '+params['L']+'<br>';
	html = 'Формула: '+Formula;
	html += '<br> (Формула): '+Formula_1+'<br>';
	html += '<br><br> Параметри: '+param_html;
	html += '<br><br> ед.цена без ДДС: '+roundNumber(singel_sum, 2)+' лв.<br>';
	html += '<br><br> общо без ДДС: '+roundNumber(total_sum, 2)+' лв.<br>';
	html += '<br><br> ТОТАЛ: '+roundNumber(vat_sum, 2)+' лв.<br>';
	
	$('#single_sum').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total').html(roundNumber(vat_sum, 2)+' лв');

//print form 
	$('#single_sum_print').html(roundNumber(singel_sum, 2)+' лв/бр');
	$('#sum_total_print').html(roundNumber(total_sum, 2)+' лв');
	$('#sum_vat_total_print').html(roundNumber(vat_sum, 2)+' лв');

//check_selected();
return false;
//	add_msg(html,MSG_SUCCESS);
}


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
//		return num;
}


//popup
//jQuery.noConflict();

(function($){
     $.fn.extend({
          center: function (options) {
               var options =  $.extend({ // Default values
                    inside:window, // element, center into window
                    transition: 0, // millisecond, transition time
                    minX:0, // pixel, minimum left element value
                    minY:0, // pixel, minimum top element value
                    vertical:true, // booleen, center vertical
                    withScrolling:true, // booleen, take care of element inside scrollTop when minX < 0 and window is small or when window is big
                    horizontal:true // booleen, center horizontal
               }, options);
               return this.each(function() {
                    var props = {position:'absolute'};
                    if (options.vertical) {
                         var top = ($(options.inside).height() - $(this).outerHeight()) / 2;
                         if (options.withScrolling) top += $(options.inside).scrollTop() || 0;
                         top = (top > options.minY ? top : options.minY);
                         $.extend(props, {top: top+'px'});
                    }
                    if (options.horizontal) {
                          var left = ($(options.inside).width() - $(this).outerWidth()) / 2;
                          if (options.withScrolling) left += $(options.inside).scrollLeft() || 0;
                          left = (left > options.minX ? left : options.minX);
                          $.extend(props, {left: left+'px'});
                    }
                    if (options.transition > 0) $(this).animate(props, options.transition);
                    else $(this).css(props);
                    return $(this);
               });
          }
     });
})(jQuery);


function showPopUp(id,shadow){
    if(!shadow) shadow=0;


		check_selected();

    jQuery('.popupWrapp').hide();
    jQuery('.popup').hide();
    if(shadow==1) {
        jQuery('#'+id).prev(".popupWrapp").show();
        jQuery('#'+id).prev(".popupWrapp").click(function(){

           jQuery('.popupWrapp').hide();
           jQuery('.popup').hide();
        });
    } 
    //jQuery('#'+id).show("1000");
	jQuery('#'+id).show();
    jQuery('#'+id).center();   
    jQuery('#'+id).animate({
          "opacity": "show"
        },400); 
}

function hidePopUp(id){
     jQuery('.popupWrapp').hide();
     jQuery("#"+id).hide(); 

	$('#print_paper_size').html('(no)');
	$('#print_paper_color').html('(no)');
	$('#print_paper_number_in_COB').html('(no)');
	$('#print_paper_file').html('(no)');

	$('#print_paper_cover').html('(no)');
	$('print_paper_perforation').html('(no)');
	$('print_paper_numeration').html('(no)');
	$('print_paper_cnt').html('(no)');
}

function check_selected() {
		
		
	$('#print_paper_size').html('&nbsp;'+$('#paper_size option:selected').text());
	$('#print_paper_color').html('&nbsp;'+$('#paper_color option:selected').text());
	$('#print_paper_number_in_COB').html('&nbsp;'+$('#paper_number_in_COB option:selected').text());
	$('#print_paper_cover').html('&nbsp;'+$('#paper_cover option:selected').text());
	$('#print_paper_perforation').html('&nbsp;'+$('#paper_perforation option:selected').text());
	$('#print_paper_numeration').html('&nbsp;'+$('#paper_numeration option:selected').text());
	$('#print_paper_file').html('&nbsp;'+$('#paper_file option:selected').text());
	$('#print_paper_cnt').html('&nbsp;'+$('#paper_cnt').val());
	
	
//	return false;
}

function clear_selected() {
	$('#print_paper_size').html('&nbsp;');
	$('#print_paper_color').html('&nbsp;');
	$('#print_paper_number_in_COB').html('&nbsp;');
	$('#print_paper_cover').html('&nbsp;');
	$('#print_paper_perforation').html('&nbsp;');
	$('#print_paper_numeration').html('&nbsp;');
	$('#print_paper_file').html('&nbsp;');
	$('#print_paper_cnt').html('&nbsp;');

}

