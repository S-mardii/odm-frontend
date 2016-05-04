jQuery(document).ready(function($){  
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;


	$('#opendev_logo_button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$("#"+id).val(attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}

		wp.media.editor.open(button);
		return false;
	});
	
	$('.add_media').on('click', function(){
		_custom_media = false;
	});
	// If site is under development
	if ($('#opendev_site_in_development').is(':checked')) {
		$('#opendev_message_construction').prop('disabled', false);
	}else{		
		$('#opendev_message_construction').prop('disabled', true);
	}
	$('#opendev_site_in_development').on('change', function(){
		if(this.checked) {
			$('#opendev_message_construction').prop('disabled', false);
		}else{
			$('#opendev_message_construction').prop('disabled', true);
		} 
		
	});
});