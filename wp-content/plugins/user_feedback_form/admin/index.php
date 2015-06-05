<?php

if(!function_exists('add_action')){
	echo 'Hi there!  I\'m just a plugin part, I can\'work directly.';
	exit;
	}
if ( !current_user_can( 'edit_others_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	global $wpdb;
	$count=0;
	$only = 'a';
	$trash_command = 'trashed';
	$trash_display = 'Trash';
	
	if(isset($_REQUEST['only'])){
		if($_REQUEST['only']=='trashed'){
				$trash_command = 'undo_trash';
				$trash_display = 'Undo Trash';
				$only = 't';
			}
			else{
				$trash_command = 'trashed';
				$trash_display = 'Trash';
				$only = 'a';
				}
		}
	if(isset($_REQUEST['apply_button'])){
		if($_REQUEST['choice'] != '-1'){
			
			$feed = $_REQUEST['feed'];
			$ids = (is_array($feed)?implode($feed,','):$feed);
			$field ='';
			$value ='';
			$addition_cond = '';
			switch($_REQUEST['choice']){
				case 'trashed':
					$field = 'trash';
					$value = '1';
				break;
				case 'undo_trash':
					$field = 'trash';
					$value = '0';
				break;
				case 'read':
					$field = 'status';
					$value = '1';
					$addition_cond = " AND ".$field." <> 2";
				break;
				case 'unread':
					$field = 'status';
					$value = '0';
					$addition_cond = " AND ".$field." <> 2";
				break;
				}
				$wpdb->query("UPDATE ".TABLE_NAME." SET ".$field." = ".$value." WHERE id IN (".$ids.") ".$addition_cond);
				if($update>=1){
				?>
	<div id="message" class="updated below-h2"><p>Process done</p></div>
				<?php
				}
		}
	}
	if(isset($_REQUEST['id']) && isset($_REQUEST['action'])){
		$rid = $_REQUEST['id'];
		$action = $_REQUEST['action'];
		$data = array();
		$where = array('id'=>$rid);
		
		switch($action){
			case 'trashed':
				$data = array('trash'=>'1');
			break;
			case 'undo_trash':
				$data = array('trash'=>'0');
			break;
			case 'delete':
				$data = array('trash'=>'0');
				$update = $wpdb->delete( TABLE_NAME, $where);
					if($update>=1){
				?>
						<div id="message" class="updated below-h2"><p>Feedback Deleted</p></div>
				<?php
				}
			break;
			}
			
		$update = $wpdb->update( TABLE_NAME, $data, $where);
		if($update>=1 && $action=='trashed'){
			?>
<div id="message" class="updated below-h2"><p>Feedback Trashed <a href="admin.php?page=user_feedback_form&id=<?php echo($_REQUEST['id']); ?>&action=undo_trash">Undo</a></p></div>
            <?php
			}
		}
	
	$result_set = $wpdb->get_results("SELECT * FROM ".TABLE_NAME." WHERE trash = '".($only=='t'?1:0)."' ORDER BY id DESC");
	$count = count($result_set);
	$all = $wpdb->get_var("SELECT COUNT(id) FROM ".TABLE_NAME);
	$trash = $wpdb->get_var("SELECT COUNT(id) FROM ".TABLE_NAME." WHERE trash = '1'");
	$status_options= array(0=>"Unread",1=>"Read",2=>"Replied");
	$icon_options = array(
					'report-problem'=>'exclamation-mark.png',
					'ask-question'=>'question-mark.png',
					'share-idea'=>'tips-mark.png',
					'send-feedback'=>'texting-tooltip.png',
					'submit-resource'=>'resource.png',
				);
	$reverse_status_options = array(0=>"read",1=>"unread",2=>"replied");
	//echo('<img src="http://www.zonefivesoftware.com/sporttracks/images/plugin-naked.png" />');
?>
<div class="wrap">
<div id="icon-edit" class="icon32 icon32-posts-law_regulation"><br></div>
<h2>User Feedback</h2>
<p><strong>List of <?php echo($only=='t'?'trashed':''); ?> Feedback from user:</strong></p>
<ul class="subsubsub">
	<li class="all"><a href="admin.php?page=user_feedback_form" class="current">All <span class="count">(<?php echo($all); ?>)</span></a> |</li>
	<li class="publish"><a href="admin.php?page=user_feedback_form&only=trashed">Trashed <span class="count">(<?php echo($trash); ?>)</span></a></li>
</ul>
<form id="posts-filter" action="admin.php?page=user_feedback_form<?php echo(($only=='t'?'&only=trashed':'')); ?>" method="post">
    <table class="wp-list-table widefat fixed posts" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" id="cb" class="column-cb check-column"><input type="checkbox"></th>
                <th scope="col" id="email">Email</th>
                <th scope="col" id="type-of-feedback">Type of Feedback</th>
                <th scope="col" id="description" class="column-title">Description</th>
                <th scope="col" id="attachment">Attachment</th>
                <th scope="col" id="submitted-date">Submitted</th>
                <th scope="col" id="status" class="column-date">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($result_set as $result_row){ 
            $id = $result_row->id;
            $email = $result_row->email;
            $description = (strlen($result_row->description)>50?substr($result_row->description,0,50)."...":$result_row->description);
            $type = ucwords(str_replace('-',' ', $result_row->type));
            $icon = PLUGIN_DIRECTORY."/style/images/".$icon_options[$result_row->type];
            $file_upload = $result_row->file_upload;
			$date = date_format(date_create($result_row->date_submitted),'M d, Y');
			$time = date_format(date_create($result_row->date_submitted),'h:i:s A'); 
			$date_submitted = '<strong>'.$date.'</strong><br/><a><span class="count">'.$time.'</span></a>'; 
            $status = $status_options[$result_row->status];
			$reverse_status = $reverse_status_options[$result_row->status];
            ?>
            <tr>
                <th scope="col" class="check-column"><input name="feed[]" type="checkbox" value="<?php echo($id); ?>"></th>
                <td scope="col" id="email">
                    <strong><a class="row-title"><?php echo($email) ?></a></strong>
                    <div class="row-actions">
                        <span class="view"><a href="admin.php?page=feedback_detail&id=<?php echo($id); ?>" title="View this feedback" rel="permalink">View Feedback</a> | </span>
                        <span class="trash"><a class="submitdelete" title="Move this feedback to the Trash" href="admin.php?page=user_feedback_form&id=<?php echo($id); ?>&action=<?php echo($trash_command.($only=='t'?'&only=trashed':'')) ?>"><?php echo($trash_display) ?></a></span>
                        <br /><span class="trash"> | <a href="admin.php?page=user_feedback_form&id=<?php echo($id); ?>&action=delete<?php echo(($only=='t'?'&only=trashed':'')); ?>" title="Delete this feedback" rel="permalink" onclick="javascript:return(confirm('This action could not rollback. Are you sure?'));">Delete</a></span>
                    </div>
                </td>
                <td scope="col" id="type-of-feedback"><strong><img src="<?php echo($icon) ?>" style="height:15px; width:auto; margin-right:5px; float:left"/><?php echo($type) ?></strong></td>
                <td scope="col" id="description"><?php echo($description) ?></td>
                <td scope="col" id="attachment"><?php echo($file_upload) ?></td>
                <td scope="col" id="submitted-date"><?php echo($date_submitted) ?></td>
                <td scope="col" id="status"><a href="admin.php?page=user_feedback_form&apply_button=1&choice=<?php echo($reverse_status.($only=='t'?'&only=trashed':'')); ?>&feed=<?php echo($id); ?>"><strong><?php echo($status) ?></strong></a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="tablenav bottom">
        <div class="alignleft actions bulkactions">
            <select name="choice">
                <option value="-1" selected="selected">Bulk Actions</option>
                <option value="read">Mark as Read</option>
                <option value="unread">Mark as Unread</option>
                <option value="<?php echo($only=='a'?'trashed':'undo_trash'); ?>"><?php echo($only=='a'?'Move to Trash':'Undo Trash'); ?></option>
            </select>
            <input type="submit" name="apply_button" id="apply-button" class="button action" value="Apply">
        </div>
    <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo($count); ?> item(s)</span></div>
            <br class="clear">
	</div>
</form>

</div>
