// JavaScript Document

var oxidation_format_type = {
	'C_1':{'C':'1'}
	, 'C_2':{'C':'0.7'}
	, 'C_3':{'C':'0.6'}
	, 'C_4':{'C':'0.5'}
	, 'C_5':{'C':'0.25'}
	, 'C_6':{'C':'0.2'}
	, 'C_7':{'C':'0.2'}
	, 'C_8':{'C':'0.2'}
	, 'C_9':{'C':'0.2'}
	, 'C_10':{'C':'0.2'}
	, 'C_11':{'C':'0.15'}
	, 'C_12':{'C':'0.12'}
	, 'C_13':{'C':'0.1'}
	};
var kind_format_type = {
	'C_1':{
		'BORDER':'20'
		,'FILLED':'20'
	}
	, 'C_2':{
		'BORDER':'11'
		,'FILLED':'12'
	}
	, 'C_3':{
		'BORDER':'6'
		,'FILLED':'7'
	}
	, 'C_4':{
		'BORDER':'3.5'
		,'FILLED':'4.5'
	}
	, 'C_5':{
		'BORDER':'2.4'
		,'FILLED':'2.9'
	}
	, 'C_6':{
		'BORDER':'2.2'
		,'FILLED':'2.6'
	}
	, 'C_7':{
		'BORDER':'1.52'
		,'FILLED':'1.68'
	}
	, 'C_8':{
		'BORDER':'1.08'
		,'FILLED':'1.23'
	}
	, 'C_9':{
		'BORDER':'0.87'
		,'FILLED':'1.07'
	}
	, 'C_10':{
		'BORDER':'0.82'
		,'FILLED':'0.98'
	}
	, 'C_11':{
		'BORDER':'0.73'
		,'FILLED':'0.84'
	}
	, 'C_12':{
		'BORDER':'0.66'
		,'FILLED':'0.73'
	}
	, 'C_13':{
		'BORDER':'0.53'
		,'FILLED':'0.64'
	}
 
}

function build_calculator(params) {
	//Jx(B+А) според J + C според J + (DxJ)+E = цена
	
	
	var total_sum =  ( params['E']+(params['J']*params['D'])+(params['J']*(params['C']+params['A']+params['B'])) ) ;


//	total_sum = 1.35*( 5+(10*0.5) + (16*(1+1+0)) + (10*4.5)   );
	var singel_sum = total_sum/params['J'];
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
	
	if (parseInt(singel_sum) < 30) {
            add_msg('Минимално стойност за поръчка - 30 лв без ДДС!', MSG_INFO);
        }
        
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

