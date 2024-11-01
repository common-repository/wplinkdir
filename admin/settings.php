<?php
global $wpdb,$wp_version;

$_DeleteDirectory ='Uninstall WPLinkDir';
$_UpdateDirectory = 'Update';
$_CreateDirectory = 'Create';

$_Yes = 'Yes';
$_No = 'No';

$wpld_exist = get_option('wpld_exist');
$wpld_DefaultDir = get_option('wpld_default_categories');
$wpld_dir_id = get_option('wpld_dir_id');
	
	if($wpld_exist=='exist'){
	
		$wpld_dir_name = get_option('wpld_dir_name');
		$wpld_addurlname = get_option('wpld_addurlname');
		$wpld_description = get_option('wpld_description');
		$wpld_keywords = get_option('wpld_keywords');
		$wpld_ShortDescSize_size = get_option('wpld_ShortDescSize_size');
		$wpld_description_size = get_option('wpld_description_size');
		$wpld_htmltags = get_option('wpld_htmltags');
		$wpld_emailme = get_option('wpld_emailme');
		$wpld_email_address = get_option('wpld_email_address');
		$wpld_template =  get_option('wpld_template');
		$wpld_template_enable = get_option('wpld_template_enable');
		$wpld_comments_enable = get_option('wpld_comments_enable');
		
		
	}elseif($wpld_exist=='install'){
		$wpld_dir_name = get_option('wpld_dir_name');
		$wpld_addurlname = get_option('wpld_addurlname');
		$wpld_description = get_option('wpld_description');
		$wpld_keywords = get_option('wpld_keywords');
	}

	echo wpld_confirmation().'<div class="wrap">';

	if($_POST['Create']==$_CreateDirectory){
	
		$_wpld_dir_name = $_POST['wpld_dir_name'];
		$_wpld_addurlname = $_POST['wpld_addurl_name'];
		$_wpld_main_description = $_POST['wpld_main_description'];
		$_wpld_main_keywords = $_POST['wpld_main_keywords'];
		
		
		create_wplinkdir_dir($_wpld_dir_name,$_wpld_addurlname,$_wpld_main_description,$_wpld_main_keywords);
		$wpld_exist = get_option('wpld_exist');
		unset($_POST);
	}elseif($_POST['DropWPLinkDir']==$_DeleteDirectory){
	
		include ('DropWPLinkDir.php');
	?>
       
	<script language="JavaScript" type="text/javascript">
			window.onload=function(){
			tb_show('Drop WPLinkDir','#TB_inline?height=155&width=300&inlineId=wpld_DropWPLinkDir');	
		}
	
		</script>
	<?php
	}elseif($_POST['DropWPLinkDir']==$_Yes) {
	 
		wpld_delete_all();
		$wpld_exist = get_option('wpld_exist');
	
	}elseif($_POST['Update']==$_UpdateDirectory){

		$wpld_dir_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title='{$wpld_dir_name}'");
		$wpld_addurl_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title='{$wpld_addurlname}'");
	
		$wpld_DirNewName = $_POST['wpld_dir_name'];
		$wpld_NewAddurlName = $_POST['wpld_addurlname'];
		$_wpld_main_description = $_POST['wpld_main_description'];
		$_wpld_main_keywords = $_POST['wpld_main_keywords'];
		$_wpld_ShortDescSize_size = $_POST['wpld_ShortDescSize_size'];
		$_wpld_description_size = $_POST['wpld_description_size'];
		$_wpld_emailme = $_POST['wpld_emailme'];
		$_wpld_htmltags = $_POST['wpld_htmltags'];
		$_wpld_template = $_POST['wpld_template'];
		$_wpld_template_enable = $_POST['wpld_template_enable'];
		$_wpld_email_address = $_POST['wpld_email_address'];
		$_wpld_comments_enable = $_POST['wpld_comments_enable'];

	
	echo '<div id="message" class="updated fade"><strong>';
	
	if($wpld_dir_name==$wpld_DirNewName){

		 echo 'Sorry nothing to Update!<br />';
	}else{
	// Edit name of your directory
	
		
		$wpld_dir_id = get_option('wpld_dir_id');
	
		$my_post = array();  
		$my_post['ID'] = $wpld_dir_id;
		$my_post['post_title'] = $wpld_DirNewName;
		$my_post['post_name'] = strtolower($wpld_DirNewName);
		wp_update_post( $my_post ); 
		 
		$post_info = get_post($wpld_dir_id);
		update_option('wpld_dir_name', htmlspecialchars($post_info->post_title));
		update_option('wpld_dir_post_name', htmlspecialchars($post_info->post_name));
		 
		 echo $wpld_DirNewName.' was set as new main page name for WPLinkDir! <br />';
		 $wpld_dir_name=$wpld_DirNewName;
	}
	
	
	if($wpld_addurlname==$wpld_NewAddurlName){

		 echo '"ADD YOU URL" did not have anything to Update!<br />';
	}else{
	// Edit name of your add url page
		
		$wpld_addurl_id = get_option('wpld_addurl_id');
	
		$my_post = array();  
		$my_post['ID'] = $wpld_addurl_id;
		$my_post['post_title'] = $wpld_NewAddurlName;
		$my_post['post_name'] = strtolower($wpld_NewAddurlName);
		wp_update_post( $my_post ); 
		 
		 $post_info = get_post($wpld_addurl_id);
		 update_option('wpld_addurlname', htmlspecialchars($post_info->post_title));
		 update_option('wpld_addurl_post_name', htmlspecialchars($post_info->post_name));
		 
		 echo 'You have successful updated "ADD YOU URL" page name and new name is: '.$wpld_NewAddurlName.' !';
		 $wpld_addurlname=$wpld_NewAddurlName;
	}
	
	if($_wpld_template==$wpld_template){
		 echo 'Template was not updated!<br />';
	}else{
		 
		 update_option('wpld_template',$_wpld_template);
		 
		 echo 'Template for directory was updated!<br />';
		 $wpld_template = $_wpld_template;
	}
	
	if($_wpld_template_enable==$wpld_template_enable){
		 echo 'Template was not enabled!<br />';
	}else{
		 update_option('wpld_template_enable',$_wpld_template_enable); 
		 
		 if($_wpld_template_enable == 'Yes'){
			wpld_update_template_meta($wpld_template);
		 }else{
			wpld_update_template_meta($_wpld_template_enable);
		 }
		 
		 echo 'Template was enabled!<br />';
		 $wpld_template_enable = $_wpld_template_enable;
	}
	
	if($_wpld_main_description==$wpld_description){
		 echo 'Meta description text was not updated!<br />';
	}else{
		 update_option('wpld_description',$_wpld_main_description);
		 
		 // Add meta tags into custom fields
    	$meta_description = 'description';
		$unique = true;
		update_post_meta($wpld_dir_id, $meta_description, $_wpld_main_description);
		 
		 echo 'Meta description text for directory was updated!<br />';
		 $wpld_description = $_wpld_main_description;
	}
	
	if($_wpld_main_keywords==$wpld_keywords){
		 echo 'Meta keywords was not updated!<br />';
	}else{
		 update_option('wpld_keywords',$_wpld_main_keywords);
		 
		 // Add meta tags into custom fields
    	$meta_keywords = 'keywords';
		$unique = true;
		update_post_meta($wpld_dir_id, $meta_keywords, $_wpld_main_keywords);
		
		 echo 'Meta keywords for directory was updated!<br />';
		 $wpld_keywords = $_wpld_main_keywords;
	}
	
	if($_wpld_emailme==$wpld_emailme){
		 echo 'Email me was not updated!<br />';
	}else{
		 update_option('wpld_emailme',$_wpld_emailme);
		 echo 'Email me was updated!<br />';
		 $wpld_emailme = $_wpld_emailme;
	}
	
	if($_wpld_email_address==wpld_email_address){
		 echo 'Email adress me was not updated!<br />';
	}else{
		 update_option('wpld_email_address',$_wpld_email_address);
		 echo 'Email adress was updated!<br />';
		 $wpld_email_address = $_wpld_email_address;
	}
	
	if($_wpld_ShortDescSize_size==$wpld_ShortDescSize_size){
		 echo 'Short description size was not updated!<br />';
	}else{
		 update_option('wpld_ShortDescSize_size',$_wpld_ShortDescSize_size);
		 echo 'Short description was updated to maximum '.$_wpld_ShortDescSize_size.' letters!<br />';
		 $wpld_ShortDescSize_size = $_wpld_ShortDescSize_size;
	}
	
	if($_wpld_description_size==$wpld_description_size){
		 echo 'Description size was not updated!<br />';
	}else{
		 update_option('wpld_description_size',$_wpld_description_size);
		 echo 'Description was updated to maximum '.$_wpld_description_size.' letters!<br />';
		 $wpld_description_size = $_wpld_description_size;
	}
	
	if($_wpld_htmltags==$wpld_htmltags){
		 echo 'HTML tags was not updated!<br />';
	}else{
		 update_option('wpld_wpld_htmltags',$_wpld_htmltags);
		 echo 'HTML tags was updated!<br />';
		 $wpld_htmltags = $_wpld_htmltags;
	}
 
 ### LINK OPTIONS ####
 
	
	if($_wpld_comments_enable==$wpld_comments_enable){
		 update_option('wpld_comments_enable',$_wpld_comments_enable);
		 echo 'Link comments was disabled!<br />';
	}else{
		 update_option('wpld_comments_enable',$_wpld_comments_enable);
		 echo 'Link comments was enabled!<br />';
		 $wpld_comments_enable = $_wpld_comments_enable;
	}
	
	echo '</strong></div>';
		unset($_POST);
		
	}elseif($_POST['Delete']==$_DeleteDirectory){
	

		echo '<div id="message" class="updated fade">'.$wpld_dir_name.' and '.$wpld_addurlname.' Deleted!</strong></div>';
		unset($_POST);
		
	}
$wpld_exist = get_option('wpld_exist');
if($wpld_exist=='install') {
		
	echo '
<form method="POST" action="" name="new_directory">';
	
	if ($wp_version>'2.6.3'){ 
		settings_fields('wplinkdir-group');}
	else{
		wp_nonce_field('update-options');
	}
	
	echo '
<table class="form-table">
	<tr valign="top">
		<td>
			<b>Add Directory name: </b>
			<br>
			<input type="text" name="wpld_dir_name" id="wpld_dir_name" value="'.$wpld_dir_name.'">
		</td>
		<td>
		</td>
	</tr>
	<tr valign="top">
	    <td>
			<b>Add "ADD URL" name: </b>
			<br>
			<input type="text" name="wpld_addurl_name" id="wpld_addurl_name" value="'.$wpld_addurlname.'">
		</td>
		<td>
			
		</td>
	</tr>
	<tr valign="top">
	    <td>
			<b>Meta description for main page: </b>
			<br>
			<textarea name="wpld_main_description" id="wpld_main_description" rows="3" cols="45">'.$wpld_description.'</textarea>
		</td>
		<td>
		</td>
	</tr>
	<tr valign="top">
	    <td>
			<b>Meta keywords for main page: </b>
			<br>
			<textarea name="wpld_main_keywords" id="wpld_main_keywords" rows="3" cols="45">'.$wpld_keywords.'</textarea>
		</td>
		<td>
		</td>
	</tr>
	<tr valign="top">
	</tr>
	<tr style="background:#ddd;">
		<td colspan="2" align="right">
			<input type="submit" name="Create" value="'.$_CreateDirectory.'" onclick="wpld_confirmation('.__('Are you sure',$WPLD_Domain).');">
		</td>
	</tr>
	</table>
	</form>';
}elseif($wpld_exist==''){
	echo 'Deactivate WPLinkDir!!!';
}
if($wpld_exist=='exist') {
	
		$wpld_dir_name = get_option('wpld_dir_name');
		$wpld_addurlname = get_option('wpld_addurlname');
		$wpld_description = get_option('wpld_description');
		$wpld_keywords = get_option('wpld_keywords');
		$wpld_ShortDescSize_size = get_option('wpld_ShortDescSize_size');
		$wpld_description_size = get_option('wpld_description_size');
		$wpld_htmltags = get_option('wpld_htmltags');
		$wpld_emailme = get_option('wpld_emailme');
		$wpld_email_address = get_option('wpld_email_address');
		$wpld_template =  get_option('wpld_template');
		$wpld_template_enable = get_option('wpld_template_enable');
		$wpld_comments_enable = get_option('wpld_comments_enable');
		
echo '
<h2> Main Options for Directory</h2>
<form method="POST" action="" name="options_directory">';
	
	if ($wp_version>'2.6.3'){ 
		settings_fields('wplinkdir-group');}
	else{
		wp_nonce_field('update-options');
	}
	
	echo '
<table  class="form-table">
	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_dir_name">Change Directory name:</label>
	 </th>
	 <td>
		<input name="wpld_dir_name" type="text" id="wpld_dir_name" value="'.$wpld_dir_name.'" class="regular-text" />
	 </td>
	</tr>

	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_addurlname">Change Add URL name:</label>
	 </th>
	 <td>
		<input name="wpld_addurlname" type="text" id="wpld_addurlname" value="'.$wpld_addurlname.'" class="regular-text" />
	 </td>
	</tr>

	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_template">Change Template name:</label>
	 </th>
	 <td>
		<input name="wpld_template" type="text" id="wpld_template" value="'.$wpld_template.'" class="regular-text" />
	 </td>
	</tr>
	
	<tr valign="top">
	 <th scope="row">
		Enable to use template:
	 </th>
	 <td> 
	  <fieldset>
	   <legend class="screen-reader-text">
	    <span>Enable to use template:</span>
	   </legend>
	   <label for="wpld_template_enable">';
			if($wpld_template_enable == 'Yes'){
			echo '<input type="checkbox" name="wpld_template_enable" id="wpld_template_enable" value="Yes" checked="true" />';
			}else{
				
			echo '<input type="checkbox" name="wpld_template_enable" id="wpld_template_enable" value="Yes" />';
			}
echo		'Check this to enable theme template for the directory.
	   </label>
	  </fieldset></td>
	</tr>

	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_main_description">Meta description for main page:</label>
	 </th>
	 <td>
		<textarea name="wpld_main_description" id="wpld_main_description" rows="3" cols="50" class="large-text code">'.$wpld_description.'</textarea>
		</td>
	</tr>

	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_main_keywords">Meta keywords for main page:</label>
	 </th>
	 <td>
		<textarea name="wpld_main_keywords" id="wpld_main_keywords" rows="3" cols="50" class="large-text code">'.$wpld_keywords.'</textarea>
		</td>
	</tr>
	
	<tr valign="top">
	 <th scope="row">
		Enable email notification:
	 </th>
	 <td> 
	  <fieldset>
	   <legend class="screen-reader-text">
	    <span>Enable email notification:</span>
	   </legend>
	   <label for="wpld_emailme">';
			if($wpld_emailme == 'Yes'){
			echo '<input type="checkbox" name="wpld_emailme" id="wpld_emailme" value="Yes" checked="true" />';
			}else{
				
			echo '<input type="checkbox" name="wpld_emailme" id="wpld_emailme" value="Yes" />';
			}
echo		'A notification by email when a link was added.
	   </label>
	  </fieldset></td>
	</tr>

	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_email_address">Email adress:</label>
	 </th>
	 <td>
		<input name="wpld_email_address" type="text" id="wpld_email_address" value="'.$wpld_email_address.'" class="regular-text" />
	 </td>
	</tr>
	
	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_ShortDescSize_size">Short description limitation:</label>
	 </th>
	 <td>
		<input name="wpld_ShortDescSize_size" type="text" id="wpld_ShortDescSize_size" value="'.$wpld_ShortDescSize_size.'" class="regular-text" />
	 </td>
	</tr>
	
	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_description_size">Description limitation:</label>
	 </th>
	 <td>
		<input name="wpld_description_size" type="text" id="wpld_description_size" value="'.$wpld_description_size.'" class="regular-text" />
	 </td>
	</tr>
	
	<tr valign="top">
	 <th scope="row">
	  <label for="wpld_htmltags">Allowed HTML tags:</label>
	 </th>
	 <td>
		<input name="wpld_htmltags" type="text" id="wpld_htmltags" value="'.$wpld_htmltags.'" class="regular-text" />
	 </td>
	</tr>
	
	</table>
	<p class="submit">
	<input type="submit" name="Update" value="'.$_UpdateDirectory.'" onclick="return wpld_confirmation();" class="button-primary" >
	</p>
	
	<h2>Link page options</h2>
	
<table  class="form-table">
	<tr valign="top">
	 <th scope="row">
		Comments on link page:
	 </th>
	 <td> 
	  <fieldset>
	   <legend class="screen-reader-text">
	    <span>Enable comments on link page:</span>
	   </legend>
	   <label for="wpld_comments_enable">';
			if($wpld_comments_enable == 'Yes'){
			echo '<input type="checkbox" name="wpld_comments_enable" id="wpld_comments_enable" value="Yes" checked="true" />';
			}else{
				
			echo '<input type="checkbox" name="wpld_comments_enable" id="wpld_comments_enable" value="Yes" />';
			}
echo		'Check this to enable comments on link page!
	   </label>
	  </fieldset></td>
	</tr>
</table>
	
	<h2>Uninstall WPLinkDir</h2>
	If you want to uninstall WPLinkDir for some reason you can do it here. Press <strong>'.$_DeleteDirectory.'</strong>
	to uninstall everything from your WordPress. This help to keep your WordPress clean from inactive plugins
	and prevent any future security issues. After you have uninstalled your directory go to your plugins and deactivate
	WPLinkdir and remove all files.
	<br />
	<p class="submit">
	<input type="submit" name="DropWPLinkDir" value="'.$_DeleteDirectory.'" class="button-primary" >
	</p>
	</form>';
	

}
echo '</div>';
?>