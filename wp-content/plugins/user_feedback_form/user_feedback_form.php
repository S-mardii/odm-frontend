<?php            
// User Feedback Form
require_once('layout/form.php');
/**
 * Plugin Name: User Feedback Form
 * Plugin URI: http://www.opendevelopmentcambodia.net/
 * Description: The plugin that let's user to have feedback to ODC
 * Version: 1.0
 * Author: ODC IT team (HENG Huy Eng & HENG Cham Roeun)
 * Forked from: userfeedback (By Mr. HENG Cham Roeun)
 * Author URI: http://www.opendevelopmentcambodia.net/
 */
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
 global $wpdb;
 define("PLUGIN_DIRECTORY" , plugins_url("user_feedback_form"));  
 define("SITE_URL" , plugins_url("user_feedback_form"));
 define("TABLE_NAME" , $wpdb->prefix . 'user_feedback_form');
 register_activation_hook(__FILE__,'CreateFeedbackTable');
 
 
 add_action("wp_enqueue_scripts","add_script");
 add_action("wp_footer","div_user_feedback_form");   
 add_action("wp_footer","FeedbackForm");      
 //these two lines let everyone see in front page(user page)!
 add_action( 'wp_ajax_nopriv_FeedbackForm', 'FeedbackForm' );  
 add_action( 'wp_ajax_FeedbackForm', 'FeedbackForm' );
 add_action( 'wp_ajax_nopriv_FeedSubmission', 'FeedSubmission' );
 add_action( 'wp_ajax_FeedSubmission', 'FeedSubmission' );
 add_action( 'wp_ajax_nopriv_UploadFeedbackFile', 'UploadFeedbackFile' );  
 add_action( 'wp_ajax_UploadFeedbackFile', 'UploadFeedbackFile' );
 add_action( 'wp_ajax_nopriv_delete_upload', 'delete_upload' );  
 add_action( 'wp_ajax_delete_upload', 'delete_upload' );
 add_action('admin_menu', 'user_feedback_form_menu');
 add_action('admin_menu', 'user_feedback_form_sub_menu');  
 add_action('plugins_loaded', 'user_feedback_form_init');   
 add_shortcode('user_feedback_form','user_feedback_form_shortcode_function');  

 
function user_feedback_form_shortcode_function(){ 
    $arg = array('no_popup'=>1, 'no_tab'=>1); 
    return user_feedback_form_creation($arg);              
}
                                                          
function user_feedback_form_init() {
  load_plugin_textdomain( 'user_feedback_form', false,  dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}                                               

function div_user_feedback_form(){ 
?>
    <div id="wrap-feedback"><a id="user_feedback_form"><?php _e('Contact Form', 'user_feedback_form'); ?></a></div>
<?php
	 }
function add_script(){
	wp_enqueue_style("user_feedback_form_buttoncss", PLUGIN_DIRECTORY."/style/button.css");       
	wp_enqueue_script("user_feedback_form_buttonjs", PLUGIN_DIRECTORY."/js/button.js", array(), '1.0.0', true ); 	                                                                                          
//	wp_enqueue_style("user_feedback_form_formcss", PLUGIN_DIRECTORY."/style/form.css");
//	wp_enqueue_style("user_feedback_form_stylecss", PLUGIN_DIRECTORY."/style/upload/style.css");
//	wp_enqueue_style("user_feedback_form_jquery-uicss", PLUGIN_DIRECTORY."/style/jquery-ui.css");
//  wp_enqueue_script("user_feedback_form_jquerymin", PLUGIN_DIRECTORY."/js/jquery.min.js");     
	}

function FeedbackForm(){?>
    <div id="user_feedback_form_fix_left">                     
        <?php user_feedback_form_creation(array('no_popup'=>0)); ?>
	</div>
<?php  
}  
function FeedSubmission(){ 
	global $wpdb;
	$request = $_REQUEST;
	$table_name = TABLE_NAME;
	$insert = null;
	$email = $request["email"];
	$desc = $request["question_text"];
	$type = $request["question_type"];
	$file_name = $request["file_name"];
	//echo $request["email"]."-".$request["question_text"]."-".$request["question_type"]."-".$request["file_name"];
    	$insert = $wpdb->insert($table_name,
    	array(
    		'email'=>$email,
    		'description'=>$desc,
    		'type'=>$type,
    		'file_upload'=>$file_name,
    	),
    	array(
        	'%s',
        	'%s',
        	'%s',
        	'%s'
    	)
	);
	
	//move file uploaded--------------------------------------------------------------------     	
	if($file_name != ''){
		rename("../wp-content/uploads/user_feedback_form/temp/".$file_name,"../wp-content/uploads/user_feedback_form/".$file_name);      		
	}
	                                          
	$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: '. $email . "\r\n";
	$subject = 'Open Development Contact Form';
	$message = "There is a feedback from user:".$email.": "."<br/>".
	 "<strong>Message:</strong> "."<br/>".$desc; 
	$mail = false;
	if($email !=""){                	       
            $admin_email = get_settings('admin_email');  
			$mail = wp_mail( $admin_email , $subject, $message,  $headers);
		}
	else{
			$mail = true;
		}
	if($mail ==true){
		echo $wpdb->insert_id;
		}
		else{
			echo 0;
			}
	die();
}  //end FeedSubmission
	
function UploadFeedbackFile(){
	//var_dump($_FILES);
	$ext = pathinfo($_FILES['fileupload']['name'],PATHINFO_EXTENSION);
	$upload_dir = wp_upload_dir();
	
	if(!in_array($ext,UploadSupport())){
		echo 'Invalid file type!';
		die();
	} 
	
	$original_name =$_FILES['fileupload']['tmp_name'];
	$destination_name = "../wp-content/uploads/user_feedback_form/temp/".$_FILES['fileupload']['name'];
	$permanent_file = "../wp-content/uploads/user_feedback_form/".$_FILES['fileupload']['name'];
	
	$destination_name = generateNewFileName($destination_name,$permanent_file,$original_name);
	move_uploaded_file($original_name, $destination_name);
	$filename = basename($destination_name);
	
	echo('imgup:'.$filename);
	die();
}


function user_feedback_form_menu(){

	add_menu_page( 'User Feedback Options', 'User Feedback', "edit_others_posts",  "user_feedback_form", 'user_feedback_form_option_content', PLUGIN_DIRECTORY.'/images/feedback-logo.png' );
	
}

function user_feedback_form_sub_menu(){
	add_submenu_page( NULL, 'Feedback Detail', 'Feedback Detail', "edit_others_posts", 'feedback_detail', 'user_feedback_form_option_content_detail' );
}

function user_feedback_form_option_content(){
	require_once("admin/index.php");
}

function user_feedback_form_option_content_detail(){
	require_once("admin/detail.php");
}

function CreateFeedbackTable(){
	global $wpdb;
	$table_name = TABLE_NAME;
	
	$sql = "CREATE TABLE $table_name(
        	id INT( 10 ) NOT NULL AUTO_INCREMENT ,
        	email VARCHAR( 100 ) NOT NULL ,
        	description TEXT NOT NULL ,
        	type VARCHAR( 50 ) NOT NULL ,
        	file_upload TEXT NOT NULL ,
        	date_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
        	status BOOLEAN NOT NULL DEFAULT  '0' ,
        	trash BOOLEAN NOT NULL DEFAULT  '0' ,
        	PRIMARY KEY( id )
            )DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );       
	//Create upload folder : 
		wp_mkdir_p('../wp-content/uploads/user_feedback_form');
		wp_mkdir_p('../wp-content/uploads/user_feedback_form/temp');
	
}
function generateNewFileName($destination_name,$permanent_file,$original_name){
	$destination_new_name =$destination_name;
	if(file_exists($destination_name) || file_exists($permanent_file)){

		if(file_exists($destination_name)){
			if(filesize($original_name) == filesize($destination_name)){
				echo('File already Existed!');
				die;
				}
		}
		if(file_exists($permanent_file)){
			if(filesize($original_name) == filesize($permanent_file)){
				echo('File already Existed!');
				die;
				}
		 }
			$no_ext = preg_replace("/\\.[^.\\s]{3,4}$/", "", $destination_name);
			$only_ext = str_replace($no_ext,'',$destination_name);
			$destination_new_name = $no_ext.'(1)'.$only_ext;
			$permanent_file = preg_replace("/\\.[^.\\s]{3,4}$/", "", $permanent_file).'(1)'.$only_ext;
			return generateNewFileName($destination_new_name,$permanent_file,$original_name);
		}
		return $destination_new_name;
	}

function delete_upload(){
	$ext = pathinfo($_REQUEST['file'],PATHINFO_EXTENSION);
	if(!in_array($ext,UploadSupport())){
		echo 'Invalid file type!';
		die();
		}
	unlink('../wp-content/uploads/user_feedback_form/temp/'.$_REQUEST['file']);
	die();
}

function UploadSupport(){
	$support = array('gif','png','jpg','jpeg','pdf','doc','docx','xls','xlsx','zip','rar');
	return $support;
} 
 ?>