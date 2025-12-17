// JavaScript Document





var mouse_format_type = {
	'A_1':{
		'RECTANGLE':'4'
		,'ELLIPSE':'4.4'
		,'ROUND':'4.8'
		,'CUSTOM':'5.8'
	}
	, 'A_2':{
		'RECTANGLE':'3'
		,'ELLIPSE':'3.36'
		,'ROUND':'3.3'
		,'CUSTOM':'3.86'
	}
	, 'A_3':{
		'RECTANGLE':'2.78'
		,'ELLIPSE':'3.06'
		,'ROUND':'3.01'
		,'CUSTOM':'3.49'
	}
	, 'A_4':{
		'RECTANGLE':'2.68'
		,'ELLIPSE':'2.91'
		,'ROUND':'2.87'
		,'CUSTOM':'3.09'
	}
	, 'A_5':{
		'RECTANGLE':'2.49'
		,'ELLIPSE':'2.63'
		,'ROUND':'2.58'
		,'CUSTOM':'2.87'
	}
	, 'A_6':{
		'RECTANGLE':'2.24'
		,'ELLIPSE':'2.36'
		,'ROUND':'2.31'
		,'CUSTOM':'2.58'
	}
	, 'A_7':{
		'RECTANGLE':'2.12'
		,'ELLIPSE':'2.21'
		,'ROUND':'2.18'
		,'CUSTOM':'2.31'
	}
	, 'A_8':{
		'RECTANGLE':'2.04'
		,'ELLIPSE':'2.09'
		,'ROUND':'2.07'
		,'CUSTOM':'2.18'
	}
	, 'A_9':{
		'RECTANGLE':'1.91'
		,'ELLIPSE':'1.96'
		,'ROUND':'1.94'
		,'CUSTOM':'2.07'
	}
	, 'A_10':{
		'RECTANGLE':'1.74'
		,'ELLIPSE':'1.77'
		,'ROUND':'1.75'
		,'CUSTOM':'1.94'
	}
	, 'A_11':{
		'RECTANGLE':'1.6'
		,'ELLIPSE':'1.63'
		,'ROUND':'1.62'
		,'CUSTOM':'1.75'
	}
}

function build_calculator(params) {
	//(((А според J)+B)*J)+C = цена общо без ДДС
	var total_sum = ( params['C']+(params['J']*(params['B']+params['A'])) );

	var singel_sum = total_sum/params['J'];
	var vat_sum = (((20.0+100)/100)*total_sum);
//( 17.5 + 100 ) / 100 )
	
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

