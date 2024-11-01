<?php

global $wpdb,$WPLD_Trans;

$wpld_cats = WPLD_CATS;

$Mode = 'Display';
$Selected = '';
	
$wpld_dir_id = get_option('wpld_dir_id');
$wpld_exist = get_option('wpld_exist');
$Selected = '';
	
$_AddLink = $WPLD_Trans['Add Link'];
	
	echo wpld_confirmation().'<div class="wrap">';
	
	if($_POST['Submit']==$_AddLink){
		require_once(ABSPATH . WPINC . '/registration.php');
		
		$parts=explode('-', $wpdb->escape($_POST['wpld_parent']));
		$LinkParent=$parts[0];
		$LinkCat=$parts[1];
		
		$LinkName = $_POST['wpld_entry_name'];
		$LinkEmail = $_POST['wpld_entry_email'];
		$LinkURL = $_POST['wpld_entry_url'];
		$LinkTitle = $_POST['wpld_entry_title'];
		$LinkShortDesc = htmlspecialchars( $_POST['wpld_entry_shortdesc']);
		$LinkDescription = htmlspecialchars( $_POST['wpld_entry_description']);
		$LinkTags = htmlspecialchars( $_POST['wpld_entry_tags']);
		
		
	 $time = date('F jS Y',time());
	 
	 $new_user = array();
	 $new_user['user_login'] = $LinkName;
	 $new_user['user_email'] = $LinkEmail;
	 $new_user['user_url'] = $LinkURL;
	 $new_user['display_name'] = $LinkName;
	 $new_user['role'] = 'link_author';
	 $user_id = wp_insert_user($new_user);
	 
	 do_action('user_register', $user_id);
	 
	 if(getpagerank($LinkURL)){
	 $Rank=getpagerank($LinkURL);
	 }else{
	 $Rank='0';
	 }
	 update_usermeta( $user_id, 'LinkTags', $LinkTags);
	 update_usermeta( $user_id, 'LinkParent', $LinkParent);
	 update_usermeta( $user_id, 'LinkShortDesc', $LinkShortDesc);
	 update_usermeta( $user_id, 'LinkDescription',$LinkDescription);
	 update_usermeta( $user_id, 'LinkRank', $Rank);
	 update_usermeta( $user_id, 'LinkDate', $time);
	 update_usermeta( $user_id, 'LinkStatus', 'publish');
	 update_usermeta( $user_id, 'LinkCategory',$LinkCat);
	 update_usermeta( $user_id, 'LinkTitle', htmlspecialchars($LinkTitle));
		 
		$user = get_userdata($user_id);
			$wpld_comments_enable = get_option('wpld_comments_enable');
			
		// Insert New Link Into Database

			$my_post = array();
			
			$my_post['post_title'] = $user->LinkTitle;
			$my_post['post_content'] = '[wplinkdir]';
			$my_post['post_status'] = 'publish';
			$my_post['post_parent'] = $user->LinkParent;
			$my_post['post_type'] = 'page';
			$my_post['post_author'] = $user->ID;
		 
			if ($wpld_comments_enable != 'Yes'){
				$my_post['comment_status'] = 'closed';
				$my_post['ping_status'] = 'closed';
			}else{
				$my_post['comment_status'] = 'open';
				$my_post['ping_status'] = 'open';
			}
  
	 	 
			$postID = wp_insert_post( $my_post );
			
			$post_info = get_post($postID);
			update_usermeta( $post_info->post_author, 'LinkStatus', 'publish');
			update_usermeta( $post_info->post_author, 'LinkID', $post_info->ID);
			update_usermeta( $post_info->post_author, 'LinkPost_name', $post_info->post_name);
			
			
		
			//Exclude the category from WP frontpage 
			$wpld_exclude_meta = $wpdb->get_var("SELECT `meta_id` FROM $wpdb->postmeta WHERE {$postID} IN ('".$postID."') AND meta_key IN ('exclude_page') LIMIT 1");
			if(is_numeric($wpld_exclude_meta) && ($wpld_exclude_meta > 0)) {
				update_post_meta($postID, 'exclude_page', '1');
			}else{
				add_post_meta($postID, 'exclude_page', '1');		
			}

			// Add meta tags into custom fields
			$meta_description = 'description';
			$meta_keywords = 'keywords';
			$unique = true;
			add_post_meta($postID, $meta_description, $user->LinkShortDesc, $unique);
			add_post_meta($postID, $meta_keywords, $user->LinkTags, $unique);
	    
			// Add template if enabled
			
			$template = get_option('wpld_template');
			$template_enable=get_option('wpld_template_enable'); //Option  Template enabled
			
			if ($template_enable=='Yes'){
				update_post_meta($postID, '_wp_page_template', $template);
			}
    

		 
	}
	########Check if directory exist #####
	
if($wpld_exist== 'install'){
	echo 'You have to create a directory first! Go to settings and activate WPLinkDir!!!';
}elseif($wpld_exist==''){
	echo 'Deactivate WPLinkDir!!!';
}else{
	
	$sql = "SELECT * FROM $wpdb->posts WHERE post_parent='{$wpld_dir_id}' AND post_status = 'publish' ORDER BY post_title ASC";
	$getCats = $wpdb->get_results($sql, ARRAY_A);
	
	echo '<h2>Add a new Link</h2><br><br>
	<form method="POST" action="" name="add_link">';
	
if ($wp_version>'2.6.3'){ 
	settings_fields('wplinkdir-group');}
else{
	wp_nonce_field('update-options');
	}
	
	echo '<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="wpld_entry_name">'.$WPLD_Trans['Name'].':</label>
		</th>
		<td>
			<input type="text" name="wpld_entry_name" id="wpld_entry_name">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_entry_email">'.$WPLD_Trans['Email'].':</label>
		</th>
		<td>
			<input type="text" name="wpld_entry_email" id="wpld_entry_email">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_entry_url">'.$WPLD_Trans['WebsiteURL'].':</label>
		</th>
		<td>
			<input type="text" name="wpld_entry_url" id="wpld_entry_url" value="http://">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_entry_title">'.$WPLD_Trans['WebsiteTitle'].':</label>
		</th>
		<td>
			<input type="text" name="wpld_entry_title" id="wpld_entry_title">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_parent">'.$WPLD_Trans['Category'].':</label>
		</th>
		<td>
			<select name="wpld_parent" name="wpld_parent">';
			
		
				wpld_sort_categories($getCats,$linkCATEGORY);
			
			echo '</select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_entry_shortdesc">'.$WPLD_Trans['ShortDesc'].':</label>
		</th>
		<td>
			<textarea name="wpld_entry_shortdesc" id="wpld_entry_shortdesc" rows="3" cols="35"></textarea>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_entry_description">'.$WPLD_Trans['Description'].':</label>
		</th>
		<td>
			<textarea name="wpld_entry_description" id="wpld_entry_description" rows="3" cols="35"></textarea>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_entry_tags">'.$WPLD_Trans['Tags'].':</label>
		</th>
		<td>
			<textarea name="wpld_entry_tags" id="wpld_entry_tags" rows="3" cols="35"></textarea>
		</td>
	</tr>
	</table>
	<p class="submit">
			<input type="submit" name="Submit" value="'.$_AddLink.'" class="button-primary" >
	</p>
	</form>
	<br />';
}
echo	'</div>';
?>