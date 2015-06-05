<?php function user_feedback_form_creation( $atts = array()){   ?>
    <!-- <script src="<?php echo(PLUGIN_DIRECTORY); ?>/js/jquery.min.js" type="text/javascript"></script> -->
    <script src="<?php echo(PLUGIN_DIRECTORY); ?>/js/jquery-ui.js"></script>
    <link rel="stylesheet" href="<?php echo(PLUGIN_DIRECTORY); ?>/style/upload/style.css" />
    <link rel="stylesheet" href="<?php echo(PLUGIN_DIRECTORY); ?>/style/jquery-ui.css"/>
    <link href="<?php echo(PLUGIN_DIRECTORY); ?>/style/form.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        jQuery(document).ready(function($) { 
        var hostname = "<?php echo(site_url()); ?>";
        var clicked = true;
        var fileupload = '';
        var uploadfile = false;
        var error_div = $('#error-upload');
        $("input#fake-text").click(browse);
        $("input#fake-browse").click(function(){ browse(); });
        function browse(){
        	$("#file-upload").click();
        }
        $("input#file-upload").change(function(e) {
            $("input#fake-text").val($("input#file-upload").val().split('\\').pop());
        	uploadfile = true;
        	if($("input#file-upload").val() != ''){
        		$("input#submit-button").click();
        		}
        });
        $("#close-button").click(function(e) {
        	closeWindow();
        });
        $("#closeform").click(function(e) {
            closeWindow();
        });

        $("#question-textarea").focus(function(e) {
            if($("span#question-needed").css('display') != 'none'){
        		$("span#question-needed").css({'display':'none'});
        		$("#question-textarea").css({'border':'none'});
        		}
        });
        $('#choice li').click(function(e) {
        	var li = $(this);
        	var desc = '<?php _e("Do you have a question that ODC can help answer? We will gladly help you.", "user_feedback_form", "user_feedback_form")?>';
        	var text = '<?php _e("Ask us anything about the ODC website or  open data.", "user_feedback_form")?>';
        	var email = '<?php _e("Your email (will not be published)", "user_feedback_form")?>';
        	var disclaimer = '';

        	switch(li.attr('id')){
        		case 'ask-question':
        			desc ='<?php _e("Do you have a question that ODC can help answer? We will gladly help you.", "user_feedback_form")?>';
        			text = '<?php _e("Ask us anything about the ODC website or  open data.", "user_feedback_form")?>';
        			email = '<?php _e("Your email (will not be published)", "user_feedback_form")?>';
        			disclaimer = '';
        		break;
        		case 'report-problem':
        			desc = '<?php _e("Have you found a technical problem or issue on the ODC website?", "user_feedback_form")?>';
        			text = '<?php _e("Tell us about what you have found.", "user_feedback_form")?>';
        			email = '<?php _e("Your email (will not be published)", "user_feedback_form")?>';
        			disclaimer = '';
        		break;
        		case 'share-idea':
        			desc = '<?php _e("Do you have a new idea that could help transform the ODC website? We will be glad to hear it.", "user_feedback_form")?>';
        			text = '<?php _e("Describe your idea here.", "user_feedback_form")?>';
        			email = '<?php _e("Your email (will not be published)", "user_feedback_form")?>';
        			disclaimer = '';
        		break;
        		case 'send-feedback':
        			desc = '<?php _e("Tell us how we\'re doing.", "user_feedback_form")?>';
        			text = '<?php _e("Do you have suggestions on how ODC can be improved?", "user_feedback_form")?>';
        			email = '<?php _e("Your email (will not be published)", "user_feedback_form")?>';
        			disclaimer = '';
        		break;
        		case 'submit-resource':
        			desc = '<?php _e("Do you have resources that could help expand the ODC website? We will review any map data, laws, articles, and documents that we do not yet have and see if we can implement them into our site. Please make sure the resources are in the public domain or fall under a <a class=\'a-normal\' href=\'http://creativecommons.org/\'>Creative Commons</a> license.", "user_feedback_form")?>';
        			text = '<?php _e("Tell us about the resources you\'re sharing with us.", "user_feedback_form")?>';
        			email = '<?php _e("Your email (will not be published)", "user_feedback_form")?>';
        			disclaimer = '<?php _e("Disclaimer: ODC will thoroughly review all submitted resources for integrity and relevancy before the resources are hosted. All hosted resources will be in the public domain, or licensed under Creative Commons. We thank you for your support.", "user_feedback_form")?>';
        		break;
        		}

        	$('#involve-desc').html(desc);
        	$('#question-textarea').attr('placeholder',text);
        	$('#email').attr('placeholder',email);
        	if(disclaimer != ''){
        		$('#disclaimer').css({'display':'block'});
        		$('#disclaimer-p').html(disclaimer);
        		}
        		else{
        			$('#disclaimer').css({'display':'none'});
        			}
        		centerTheForm();

        });
        function centerTheForm(){
        		var form = $("#user_feedback_form_fix_left");
        		var half = form.height() + 40 + 4;
        		form.css({'margin-top':-(half/2) + 'px'});
        	}
        $(document).keydown(function(e) {
            if(e.keyCode==27){
        		closeWindow();
        		}
        });
        var loading = $(window.parent.document.getElementById('loading-form'));
        	loading.next().css({'display':'block'});
        $(document).click(function(e) {
        		check();
        		clicked = true;
        });
        $("body").click(function(e) {
                    clicked = false;
                });
        function check(){
        	if(clicked == true){
        		closeWindow();
        		}
        	}
        //$("#question-textarea").focus();
        function closeWindow(){
        	var overlay = $(window.parent.document.getElementById('overlay-div'));
        	$("div#user_feedback_form_fix_left").hide();
            //overlay.next().next().remove();
        	overlay.next().remove();
        	overlay.remove();
        }

$('#user_feedback_form').c
$('#delete_upload').click(function(e) {
	e.preventDefault();        
		jQuery.ajax({
	  type: 'POST',
	  url: hostname +'/wp-admin/admin-ajax.php',
	  data: {
	  action: 'delete_upload',
	  file: fileupload,
	  },
	  success: function(data, textStatus, XMLHttpRequest){
	  if(data != ''){
		  //alert("Unable to delete file! Throw:" + data);
		  error_div.css({'display':'block'});
		  error_div.html('*Unable to delete file!');
		  return ;
		  }
		  $("input#fake-text").val('');
		  $("input#file-upload").removeAttr('disabled');
		  $('#view-delete-upload').css({'display':'none'});
		  $('div#process-state').removeClass('process-state-done');
		  $('div#process-state').removeClass('process-state');
		  $('div#process-state').addClass('process-state');
		  $('div#process-state').css({'display':'none'});
		  //alert('File Deleted!');
	  },
	  error: function(MLHttpRequest, textStatus, errorThrown){
	  	//alert("Something's gone wrong, Please click again!");
	  	error_div.css({'display':'block'});
		error_div.html('*Something\'s gone wrong, Please click again!');
	  }
	  });
});

$('#user_feedback_form-form').submit(function(e) {
    e.preventDefault();
	error_div.css({'display':'none'});
	if(uploadfile == true){
			var ext = $('input#file-upload').val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['gif','png','jpg','jpeg','pdf','doc','docx','xls','xlsx','zip','rar']) == -1) {
				$('input#file-upload').val('');
				$("input#fake-text").val('Invalid file type');
				//alert('');
				error_div.css({'display':'block'});
	  			error_div.html('*Invalid file type!');
				return;
			}
			$('div#process-state').css({'display':'block'});

			var formObj = $(this);
			var formURL = formObj.attr("action");
			//var formURL = 'http://'+ hostname +'/wp-admin/admin-post.php?action=UploadFeedbackFile';
			var formURL = '/wp-admin/'+formURL;
			var formData = new FormData(this);
			$.ajax({
				url: formURL,
				type: 'POST',
				data: formData,
				mimeType:"multipart/form-data",
				contentType: false,
				cache: false,
				processData:false,
			success: function(data, textStatus, jqXHR)
			{
				//.document.write(data);
				if(data.indexOf('imgup:') == 0){

					$('div#process-state').removeClass('process-state');
					$('div#process-state').addClass('process-state-done');

					var f = data.replace('imgup:','');
					fileupload = f;
					$("input#file-upload").val('');
					$("input#file-upload").attr('disabled','disabled');
					$("input#fake-text").val(f);
					var href = '<?php
					$updir = wp_upload_dir();
					echo($updir['baseurl']); ?>/user_feedback_form/temp/';
					$('#view-delete-upload').css({'display':'block'});
					$('#view_uploaded').attr('href',href + f);
					$('#delete_upload').attr('href',href + f);

					//============++++++++++++++++++++++++++++++++++++++++++++++++++++++======= 
					}
					else{
						$("input#file-upload").val('');
						$("input#fake-text").val('');
						//alert(data);
						error_div.css({'display':'block'});
	  					error_div.html(data);
						$('div#process-state').css({'display':'none'});
						}

			},
			 error: function(jqXHR, textStatus, errorThrown)
			 {
			 }
			});
		uploadfile = false;
		return ;
	}

	if($("#question-textarea").val().trim()==""){
		$("span#question-needed").css({'display':'inherit'});
		$("#question-textarea").css({'border':'1px solid #F00'});
		return false;
	}
	else{
			$("span#question-needed").css({'display':'none'});
	}
	$("input:submit").attr("disabled","disabled");
	jQuery.ajax({
          type: 'POST',
          url: hostname +'/wp-admin/admin-ajax.php',
          data: {
              action: 'FeedSubmission',
              question_text: $("#question-textarea").val(),
              file_name:   $("#fake-text").val(),
              email: $("#email").val(),
              question_type: $("li.ui-state-active").attr("id")
         },
         success: function(data, textStatus, XMLHttpRequest){
          if(!data){
        	  alert("Unable to give a feedback, Please Resubmit the form! Throw:" + data);
        	  error_div.css({'display':'block'});
        	//error_div.html('<?php _e("*Unable to give a feedback, Please Resubmit the form!", "user_feedback_form")?>'
        	  error_div.html('*Unable to give a feedback, Please Resubmit the form!');
        	  return ;
        	  }
          	$("input:submit").removeAttr("disabled");
        	var left = parseInt($('div#long-all').css('left').replace('px',''));
        	left += 719;
        	$('div#long-all').css({'left':'-'+left+'px'});
          },
          error: function(MLHttpRequest, textStatus, errorThrown){
        	  Alert("Something's gone wrong, Please Resubmit the form!");
        	  error_div.css({'display':'block'});
        	  error_div.html('*Unable to give a feedback, Please Resubmit the form!');
          }
  });

});

centerTheForm();

});
    </script>
<div id="user_feedback_form_container"> 
	<?php  
    if ($atts['popup'] == 1){ ?>
    	<div id="close-button"></div>
    	<h2><?php _e("Contact Form", "user_feedback_form");?></h2>
	<?php } ?>
        <div id="wrapper">
        	<div id="long-all">
                <div id="involve1">
                <form id="user_feedback_form-form" action="admin-ajax.php?action=UploadFeedbackFile" method="post" enctype="multipart/form-data">
                <div id="tabs">
                  <ul id="choice">
                    <li id="ask-question"><a href="#involve"><span><?php _e("Ask Question", "user_feedback_form");?></span></a></li>
                    <li id="report-problem"><a href="#involve"><span><?php _e("Report Problem", "user_feedback_form");?></span></a></li>
                    <li id="share-idea"><a href="#involve"><span><?php _e("Share Idea", "user_feedback_form");?></span></a></li>
                    <li id="send-feedback"><a href="#involve"><span><?php _e("Send Feedback", "user_feedback_form");?></span></a></li>
                    <li id="submit-resource"><a href="#involve"><span><?php _e("Submit Resources", "user_feedback_form");?></span></a></li>
                  </ul>
                  <div id="involve">
                    <div style="float:left"><p id="involve-desc"><?php _e("Do you have a question that ODC can help answer? We'll gladly help you", "user_feedback_form");?></p>
                    <textarea id="question-textarea" rows="10" placeholder="<?php _e('Ask us anything about the ODC website or  open data.', "user_feedback_form");?>"></textarea>
                    <input id="file-upload" type="file" name="fileupload"/>
                    <input id="fake-text" type="text" placeholder="<?php _e('Attach file (supported type: jpg, png, pdf, doc(x), xls(x), zip).', "user_feedback_form"); ?>" />
                    <input id="fake-browse" type="button" value="<?php _e('Browse', "user_feedback_form");?>" />
                    <div id="process-state" class="process-state"></div>

                    <div id="view-delete-upload"><a href="<?php echo site_url(); ?>/#view" target="_blank" id="view_uploaded"><?php _e("View", "user_feedback_form");?></a> | <a id="delete_upload" href="<?php echo site_url(); ?>/#delete"><?php _e("Delete", "user_feedback_form");?></a></div>
                    <input id="email" type="text" placeholder="<?php _e("Your Email (Will not be published)", "user_feedback_form");?>" /></div>
                    <div id="disclaimer">
                    	<p id="disclaimer-p"></p>
                    </div>
                  </div>
                  <div id="submit-div">
                    <input id="submit-button" type="submit" value="Submit" /><span class='needed' id='question-needed'><?php _e("* The idea box couldn't be blank!", "user_feedback_form");?></span>
                  </div>
                  <div id="error-upload"><?php _e("ERROR!", "user_feedback_form");?></div>
                </div>
                 </form>
                <script type="text/javascript">
                jQuery( "#tabs" ).tabs();
                </script>
                </div>
                <div id="inner">
                    <p>
                    <h2><?php _e("Thank you for taking the time to get in contact!", "user_feedback_form"); ?></h2>
                    <br />
                    <button id="closeform"><?php _e("Close", "user_feedback_form");?></button>
                    </p>
                </div>
			</div>
        </div>
</div>
<?php } //end function ?>