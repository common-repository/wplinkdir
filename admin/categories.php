<?php
global $wpdb;

$wpld_cats = WPLD_CATS;
$wpld_links = WPLD_LINKS;
	
$wpld_dir_id = get_option('wpld_dir_id');
$wpld_exist = get_option('wpld_exist');
$wpld_addurl_id = get_option('wpld_addurl_id');
	
$_create = 'Create';
$_create_directory = 'Create Directory';
$_delete_directory = 'Delete Directory';
$_default_directory = 'Enable Directory';

$_Yes = 'Yes';
$_No = 'No';

	
	echo wpld_confirmation().'<div class="wrap">';
	
	if($_POST['Default_Directory']==$_default_directory){
	  $wpld_default_categories = get_option('wpld_default_categories');
		if($_POST['wpld_default_directory'] == 'enable' && $wpld_default_categories == 'disable'){
		 wpld_create_categories();
		 update_option( 'wpld_default_categories', 'enable' );
		 $wpld_default_categories = 'enable';
		}
	}

	
	if($_POST['Delete_Directory']==$_delete_directory){
	
		include ('DeleteDirectory.php');
	?>
       
	<script language="JavaScript" type="text/javascript">
	window.onload=function(){
	tb_show('Delete Link','#TB_inline?height=155&width=300&inlineId=wpld_DeleteDirectory');	
	}
	
	</script>
	<?php
	}elseif($_POST['Delete_Directory']==$_Yes) {
	 
	 wpld_delete_directory();
	}
	
$wpld_default_categories = get_option('wpld_default_categories');
	
	if($_POST['Create']==$_create){
		
		$Tags = array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array());
		$CatTitle = $_POST['wpld_cat_name'];
		$CatDescription = wp_kses($_POST['wpld_description'],$Tags );
		
		if (empty($_POST['wpld_parent'])) {
			$LinkParent = $wpld_dir_id;
		}else{
			$parts=explode('-',$wpdb->escape($_POST['wpld_parent']));
	   		$LinkParent=$parts[0];
	    	$LinkCat=$parts[1];
		}

		$wpld_dir_author = get_option('wpld_dir_author');
		
		echo $CatParent;
	// Create your new category
	  	$my_post = array();
	 	 $my_post['post_title'] = $CatTitle;
	 	 $my_post['post_content'] = '[wplinkdir]';
	 	 $my_post['post_status'] = 'publish';
	 	 $my_post['post_parent'] = $LinkParent;
		 $my_post['post_author'] = $wpld_dir_author;
	 	 $my_post['post_type'] = 'page';
		 $my_post['comment_status'] = 'closed';
		$my_post['ping_status'] = 'closed';

		// Insert the page into the database  
	 	 
		 $CatRowID = wp_insert_post( $my_post );
			
		 $CatRow = get_post($CatRowID);
		 
		 //Insert data into wplinkdir category table
		 
		 $CatRowID = $CatRow->ID;
		 $CatRowName = $CatRow->post_name;
		 $CatRowParent = $CatRow->post_parent;
		 $CatRowTitle = $CatRow->post_title;
		
		//Exclude the category from frontpage 	
		$wpld_exclude_meta = $wpdb->get_var("SELECT `meta_id` FROM `".$wpdb->postmeta."` WHERE `{$CatRowID}` IN('".$CatRowID."') AND `meta_key` IN ('exclude_page') LIMIT 1");
		if(is_numeric($wpld_exclude_meta) && ($wpld_exclude_meta > 0)) {
			  update_post_meta($CatRowID, 'exclude_page', '1');
		}else{
		
			add_post_meta($CatRowID, 'exclude_page', '1');		
		}
		
		// Add meta tags into custom fields
    	$meta_description = 'description';
    	$meta_keywords = 'keywords';
		$meta_keywords_value = 'keywords';
		$unique = true;
		add_post_meta($CatRowID, $meta_description, $CatDescription, $unique);
		add_post_meta($CatRowID, $meta_keywords, $meta_keywords_value, $unique);
		
		// Add template if enabled
			
			$template = get_option('wpld_template');
			$template_enable=get_option('wpld_template_enable'); //Option  Template enabled
			
			if ($template_enable=='Yes'){
				update_post_meta($CatRowID, '_wp_page_template', $template);
			}
		if ($wpld_default_categories == 'disable'){
		 update_option( 'wpld_default_categories', 'enable' );
		}
		 
		$wpld_default_categories = get_option('wpld_default_categories');
	}
	
	
	$sql = "SELECT * FROM $wpdb->posts WHERE post_parent='{$wpld_dir_id}' AND post_status = 'publish' ORDER BY post_title ASC";
	$getCats = $wpdb->get_results($sql, ARRAY_A);
	########Check if directory exist #####
	
	
if($wpld_exist=='install'){
	echo 'You have to create a directory first! Go to settings and activate WPLinkDir!!!';
}elseif($wpld_exist==''){
	echo 'Deactivate WPLinkDir!!!';
}else{	
	
if ($wp_version>'2.6.3'){ 
	settings_fields('wplinkdir-group');}
else{
	wp_nonce_field('update-options');
	}
	
	echo '<form method="POST" action="" name="new_category">';
	if($wpld_default_categories == 'disable'){
	echo '<h2>Enable default directory!</h2>
	You have not created any categories!
	If you like to use default categories check the box and press <b>'.$_default_directory.'</b> to create default categories! 
			<input type="checkbox" name="wpld_default_directory" id="wpld_default_directory" value="enable"><br><br>
	And if you like to make your own categories uncheck the checkbox and start to add new categories.';
	echo '<p class="submit"><input type="submit" name="Default_Directory" value="'.$_default_directory.'" class="button-primary"  onclick="return wpld_confirmation();">
		</p>';
	}
	
	echo '<h2>Add a new category</h2><br><br>
	';
	echo '<table class="form-table">
	<tr valign="top">
		<th scope="row">
	    <label for="wpld_cat_name">Category Title:</label>
	    </th>
		<td>
			<input type="text" name="wpld_cat_name" id="wpld_cat_name">
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_parent">Sub-Category of: </label>
	    </th>
		<td>
			<select name="wpld_parent" id="wpld_parent">
			<option></option>';
			
		foreach ($getCats as $getCat) {
		 if ($wpld_addurl_id != $getCat['ID']){
		  $getCatID = $getCat['ID'];
		  $getCatTITLE = $getCat['post_title'];
		  echo '<option value="'.$getCatID.'-'.$getCatTITLE.'">'.$getCatTITLE.'</option>';
		 }
		}
			
			echo '</select>
	    </td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="wpld_description">Description:</label>
	    </th>
	    <td>
			<textarea name="wpld_description" id="wpld_description" rows="3" cols="45">'.$Cat['description'].'</textarea>
		</td>
	</tr>
	</table>
	<p class="submit">
			<input type="submit" name="Create" value="'.$_create.'" class="button-primary" >
	</p>';
	echo '<h2>Delete Directory</h2><br>
	If you need to delete all categories you can do it with the button <strong>'.$_delete_directory.'</strong>. 
	This will also delete all your links in your directory. The only thing that will be left is your <strong>main directory</strong>
	page and <strong>add your url</strong> page!';
	
		echo '<p class="submit"><input type="submit" name="Delete_Directory" value="'.$_delete_directory.'" class="button-primary"  onclick="return wpld_confirmation();">
		</p>';
	
	
	echo '
	</form>';
}
echo	'</div>';
?>