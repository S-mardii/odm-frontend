jQuery(document).ready(function($) { 
    $("a#user_feedback_form").click(function(e) { 
        $("div#wrap-feedback").after('<div id="overlay-div"></div>');
        $("div#overlay-div").after('<div id="loading-form"></div>');
        var left_long_all = 0;
        //left_long_all = parseInt($('div#long-all').css('left').replace('px',''));
        //left_long_all += 719; 
        $('div#long-all').css({'left':+left_long_all+'px'});  
        $('span.needed').hide();
        $('#user_feedback_form-form').find("input[type=text], textarea").removeAttr( 'style' );            
        $('#view-delete-upload').css({'display':'none'});
        $('div#process-state').css({'display':'none'});  
        $('#user_feedback_form-form').find("input[type=text], textarea").val("");  
        $("div#user_feedback_form_fix_left").fadeIn(500);                           
        $("div#user_feedback_form_fix_left form").fadeIn(500);
    });     
   
});