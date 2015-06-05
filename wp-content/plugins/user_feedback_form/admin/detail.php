<?php

if(!function_exists('add_action')){
	echo 'Hi there!  I\'m just a plugin part, I can\'work directly.';
	exit;
	}
if ( !current_user_can( 'edit_others_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
global $wpdb;
define('REPLYTABLE',$wpdb->prefix.'reply');
$id = $_REQUEST['id'];

$update = $wpdb->update( TABLE_NAME, array('status'=>1), array('id'=>$_REQUEST['id'],'status'=>'0'));


$resultset = $wpdb->get_row('SELECT * FROM '.TABLE_NAME.' WHERE id = '.$_REQUEST['id']);

$email = $resultset->email;
$desc = $resultset->description;
$file = $resultset->file_upload;
$trash_command =($resultset->trash==1?'undo_trash':'trashed');
$trash_display =($resultset->trash==1?'Undo Trash':'trash');


if(isset($_REQUEST['reply'])){
	
	$id = $_REQUEST['id'];
	$reply_desc = $_REQUEST['reply'];
	$insert = $wpdb->insert(REPLYTABLE,
	array(
		'feedback_id'=>$id,
		'description'=>$reply_desc
	),
	array(
	'%d',
	'%s'
	)
	);
	
	$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: '. 'info@opendevcam.net' . "\r\n";
	$subject = 'ODC Contact Form Reply';
	$message = $reply_desc;
	$mail = mail( $email , $subject, $message,  $headers);
	
    echo('<div id="message" class="updated below-h2"><p>'.($mail ==true?'Email Sent':'Something went wrong, please try again').'</a></p></div>');
	
	$update = $wpdb->update( TABLE_NAME, array('status'=>2), array('id'=>$id));
	
	}



$replyset = $wpdb->get_results('SELECT * FROM '.REPLYTABLE.' WHERE feedback_id = '.$_REQUEST['id']);


?>
<div class="wrap">
<div id="icon-edit" class="icon32 icon32-posts-law_regulation"><br></div>
<h2>Feedback Detail</h2>
<div id="poststuff">
<div id="post-body-content">
	<div class="postarea"><h3 style="border:none">Detail of: <?php echo($email); ?><div style="float:right">
    	<a href="admin.php?page=user_feedback_form&id=<?php echo($_REQUEST['id']); ?>&action=delete" title="Delete this feedback" rel="permalink" onclick="javascript:return(confirm('This action could not rollback. Are you sure?'));">Delete</a>&nbsp;|&nbsp;
        <a class="submitdelete" title="Move this feedback to the Trash" href="admin.php?page=user_feedback_form&id=<?php echo($id); ?>&action=<?php echo($trash_command.($only=='t'?'&only=trashed':'')) ?>"><?php echo($trash_display) ?></a></div>
    </h3>
        
        <div style="padding-left:10px;">
        	
            <hr style="border-bottom:none; margin-bottom:0px;" />
            
            <div style="float:left;width:100%">
            	<table width="100%">
            	<tr>
                	<td style="width:100px;height:50px">Email:&nbsp;<strong><?php echo($email); ?></strong></td>
                    <td></td>
                </tr>
                <tr>
                	<td colspan="2" style="vertical-align:top; height:20px">Idea:</td>
                </tr>
                <tr>
                    <td colspan="2" style="height:70px; border:1px solid #dedede; padding:10px; vertical-align:top"><?php echo nl2br($desc); ?></td>
                </tr>
                <tr>
                	<td style="height:50px">File:</td>
                    <td><strong><?php echo($file==''?'NO':'<a href="../wp-content/uploads/user_feedback_form/'.$file.'" target="_blank">'.$file.'</a>'); ?></strong></td>
                </tr>
            </table>
            </div>
            <div style="float:right; width:100%">
            <h2>Reply Detail:</h2>
            <form action="admin.php?page=feedback_detail&id=<?php echo($_REQUEST['id']); ?>" method="post">
            <table cellpadding="10px" style="width:100%">
            	<?php
				//echo(count($replyset));
					foreach($replyset as $reply){
						?>
                       <tr><td style="background-color:#eaeaea; border-bottom:1px solid #ffffff; height:30px;">
                            <strong>
                                <?php echo('&bull;&nbsp;&nbsp;&nbsp;'.nl2br($reply->description)); ?>
                            </strong><div style="float:right"><?php 
							$date = date_format(date_create($reply->reply_date),'M d, Y');
							$time = date_format(date_create($reply->reply_date),'h:i:s A');
							$date_submitted = '<strong>'.$date.'</strong><br/><a><span class="count">'.$time.'</span></a>';
							echo($date_submitted); ?></div>
                        </td></tr>
                        <?php
						}
                ?>
                <tr><td style="background-color:#eaeaea; border-bottom:1px solid #ffffff; height:30px;">
                        <textarea name="reply" rows="5" placeholder="reply" style="width:100%"></textarea>
                </td></tr>
                <tr><td>
                	<input type="submit" name="submit" value="Submit" id="apply-button" class="button action" style="float:right" />
                </td></tr>
            </table>
            </form>
            </div>
        
        </div>
	</div>
</div>
</div>


</div>
