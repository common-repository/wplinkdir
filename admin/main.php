<?php
global $wpdb,$WPLD_Trans,$PluginName,$wpld_categories;

	$wpld_cats = WPLD_CATS;
	
	$action = htmlspecialchars($_GET["action"]);
	
	$wpld_exist = get_option('wpld_exist');
	$PluginID = get_option('wpld_dir_id'); 
	
	$_UpdateLink = $WPLD_Trans['Update'];
	$_delete = $WPLD_Trans['Delete'];
	$_Yes = 'Yes';
	$_No = 'No';
	
	$_AddLink=__('Edit & Approve Links',$WPLD_Domain);
	$_EditLink=__('Edit Link',$WPLD_Domain);
	$_ApproveLink=__('Approve Links',$WPLD_Domain);
	$_DeleteLink=__('Delete Link',$WPLD_Domain);
	$_PendingLinks=__('Pending Links',$WPLD_Domain);
	$_ManageLinks=__('Manage Links',$WPLD_Domain);
	
	
	
	$filter = $_POST['wpld-filter'];
	if ($filter == TRUE){
		$filter = $_POST['wpld-filter'];
	}else{
		$filter = 'ID';
	}
	
	$wpld_users = wpld_users($filter);
	
	$pending = array();
	foreach ($wpld_users as $user){
		if($user['LinkStatus']=='pending'){
			$pending[] = $user; 
		 }
	}
	$publish = array();
	foreach ($wpld_users as $user){
		if($user['LinkStatus']=='publish'){
			$publish[] = $user; 
		 }
	}
	$spam = array();
	foreach ($wpld_users as $user){
		if($user['LinkStatus']=='spam'){
			$spam[] = $user; 
		 }
	}
	$trash = array();
	foreach ($wpld_users as $user){
		if($user['LinkStatus']=='trash'){
			$trash[] = $user; 
		 }
	}
	
	############ EDIT URL ################
	if($_POST['wpld_UpdateLink']==$_UpdateLink){
	 
	 $updateID=explode('-',$wpdb->escape($_POST['wpld_updated_linkID']));
	 $postID=$updateID[0];
	 $user_id=$updateID[1];
	 
	 $updateTitle = $_POST['wplinkdir_title']; #OK
	 $updateUrl = $_POST['wplinkdir_url']; #OK
	 $updateOrgCat = htmlspecialchars($_POST['wplinkdir_OrgCategory']);
	 $updateShortdesc = htmlspecialchars($_POST['wplinkdir_shortdesc']); #OK
	 $updateDescription = htmlspecialchars($_POST['wplinkdir_description']); #OK
	 $updateTags =  htmlspecialchars($_POST['wplinkdir_tags']); #OK
	 $updatePremium_link = htmlspecialchars($_POST['wplinkdir_premium_link']);
	 $updatePending = $wpdb->escape($_POST['wplinkdir_pending']); #OK
	 $updateEmail = $wpdb->escape($_POST['wplinkdir_entry_email']);#OK
	 
	 $parts=explode('-',$wpdb->escape($_POST['wplinkdir_category']));
	 $LinkID=$parts[0];
	 $LinkCat=$parts[1];
	 if ($updateOrgCat !== $LinkCat) {
	 	$updateCategory = $LinkCat;
	 }else{
	 	$updateCategory = $LinkCat;
	 }
	 	
		$my_post = array();  
		$my_post['ID'] = $postID;
		$my_post['post_title'] = $updateTitle;
		$my_post['post_parent'] = $LinkID;
		
	 if ($updatePending==0){ 
		// Set the link to pending in Wordpress database 
		$my_post['post_status'] = 'pending';
		update_usermeta( $user_id, 'LinkStatus', 'pending');#ok
	 }elseif($updatePending==1){
		// Set the link to directory approved in Wordpress database
		$my_post['post_status'] = 'publish';
		update_usermeta( $user_id, 'LinkStatus', 'publish');#ok
		
	 }
	 
	 wp_update_post( $my_post );
	 
	 $update_user = array();
	 $update_user['ID'] = $user_id;
	 $update_user['user_email'] = $updateEmail;
	 $update_user['user_url'] = $updateUrl;
	 wp_update_user($update_user);
	 
	 
	 update_usermeta( $user_id, 'LinkTags', $updateTags); #OK
	 update_usermeta( $user_id, 'LinkShortDesc', $updateShortdesc);
	 update_usermeta( $user_id, 'LinkDescription',$updateDescription);
	 update_usermeta( $user_id, 'LinkRank', $Rank);
	 update_usermeta( $user_id, 'LinkDate', $time);
	 update_usermeta( $user_id, 'LinkCategory',$LinkCat);
	 update_usermeta( $user_id, 'LinkID', $postID);
	 update_usermeta( $user_id, 'LinkTitle', htmlspecialchars($updateTitle));
	 $post = get_post($postID);
	 update_usermeta( $user_id, 'LinkPost_name', $post->post_name);
	 update_usermeta( $user_id, 'LinkParent', $LinkID);
	 
		# Add meta tags into custom fields
    	$meta_description = 'description';
    	$meta_keywords = 'keywords';
		$unique = true;
		update_post_meta($postID, $meta_description, $updateShortdesc);
		update_post_meta($postID, $meta_keywords, $updateTags);
	 
	 $unset = $_POST['wpld_UpdateLink'];
	 unset($unset);
	}
	

	
	if($_POST['wpld_ApproveLink']==$_ApproveLink){
	
		$Value = $_POST['wpld_approve_link'];
		
		foreach ($Value as &$userID) {
		
			$user = get_userdata($userID);
			$wpld_comments_enable = get_option('wpld_comments_enable');
			
		// Insert New Link Into Database

			$my_post = array();
			if ($user->LinkID != NULL) {
			$my_post['ID'] = $user->LinkID;
			}
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

		unset($Value); 
	
	}
	
	//Bulk actions
	if($_POST['wpld-action']){
		if($_POST['wpld-action']=='unapprove'){
			foreach ($_POST['wpld_checked'] as &$postID){
				$my_post = array();  
				$my_post['ID'] = $postID;
				$my_post['post_status'] = 'pending';
				wp_update_post( $my_post );
				
				$post_info = get_post($postID);
				update_usermeta( $post_info->post_author, 'LinkStatus', 'pending');
				//echo 'Unapprove: '. $postID;
			}
		}elseif($_POST['wpld-action']=='approve'){
		
			foreach ($_POST['wpld_checked'] as &$postID){
			    $p = preg_match("/pending/", $postID, $matches);
				if ($p) {
				 $e = explode('-',$postID);
				 $postID = $e[1];
				 wpld_approve_link($postID);
				}else{
				 $my_post = array();  
				 $my_post['ID'] = $postID;
				 $my_post['post_status'] = 'publish';
				 wp_update_post( $my_post );
				
				 $post_info = get_post($postID);
				 update_usermeta( $post_info->post_author, 'LinkStatus', 'publish');
				 //echo 'Approve: '. $postID;
				}
			}
		}elseif($_POST['wpld-action']=='spam'){
			foreach ($_POST['wpld_checked'] as &$postID){
			
			    $p = preg_match("/pending/", $postID, $matches);
				if ($p) {
				 $e = explode('-',$postID);
				 $postID = $e[1];
			     update_usermeta( $postID, 'LinkStatus', 'spam');
				}else{
				 $my_post = array();  
				 $my_post['ID'] = $postID;
				 $my_post['post_status'] = 'spam';
				 wp_update_post( $my_post );
				
				 $post_info = get_post($postID);
				 update_usermeta( $post_info->post_author, 'LinkStatus', 'spam');
				 //echo 'Spam: '. $postID;
				}
			}
		}elseif($_POST['wpld-action']=='trash'){
			foreach ($_POST['wpld_checked'] as &$postID){
			
			    $p = preg_match("/pending/", $postID, $matches);
				if ($p) {
				 $e = explode('-',$postID);
				 $postID = $e[1];
			     update_usermeta( $postID, 'LinkStatus', 'trash');
				}else{
				 $my_post = array();  
				 $my_post['ID'] = $postID;
				 $my_post['post_status'] = 'trash';
				 wp_update_post( $my_post );
				
				 $post_info = get_post($postID);
				 update_usermeta( $post_info->post_author, 'LinkStatus', 'trash');
				 //echo 'Trash: '. $postID;
				 
				}
			}
		}elseif($_POST['wpld-action']=='delete'){
			foreach ($_POST['wpld_checked'] as &$postID){
				// echo 'Delete: '. $postID;
			
			    $p = preg_match("/pending/", $postID, $matches);
				 
				 if ($p) {
				  $e = explode('-',$postID);
				  $postID = $e[1];
			      wp_delete_user($postID);
				 }else{
				  wp_delete_user($postID); 
				 }
			 }
		}
	}
		
	echo '<div class="wrap">';
	
	if($_POST['wpld_DeleteLink']==$_delete){
	 $updateID=explode('-',$wpdb->escape($_POST['wpld_delete_linkID']));
	 $postID=$updateID[0];
	 $userID=$updateID[1];
		include ('DeleteLink.php');
	?>
       
	<script language="JavaScript" type="text/javascript">
	window.onload=function(){
	tb_show('Delete Link','#TB_inline?height=155&width=300&inlineId=wpld_DeleteLink');	
	}
	
	</script>
	<?php
	}elseif($_POST['wpld_DeleteLink']==$_Yes) {
	$userID = $_POST['wpld_userID'];
	 
	 wp_delete_user( $userID );
	}


	########Check if directory exist #####
	
if($wpld_exist== 'install'){
	echo 'You have to create a directory first! Go to settings and activate WPLinkDir!!!';
}elseif($wpld_exist==''){
	echo 'Deactivate WPLinkDir!!!';
}else{	
	
	########## EDIT LINK ##########	
	
	if($action == 'edit'){
		include ('EditLink.php');
	
	}else{ 
		include('MainNav.php');

			echo '<h2>'.$_ManageLinks.'</h2>';
			echo '<form id="WPLD-Manage-form" action="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'" method="post">';
	
	if ($wp_version>'2.6.3'){ 
		settings_fields('wplinkdir-group');}
	else{
		wp_nonce_field('update-options');
	}
	?>
<ul class="subsubsub">
<?php if($_GET['link_status']=='pending'){
 $status_pending = 'current';
}elseif($_GET['link_status']=='publish'){
 $status_publish = 'current';
}elseif($_GET['link_status']=='spam'){
 $status_spam = 'current';
}elseif($_GET['link_status']=='trash'){
 $status_trash = 'current';
}else{
 $status_all = 'current';
}
echo '<li class="all"><a href="'.$_SERVER['PATH_INFO'].'admin.php?page=WPLinkDir/directory.php&amp;link_status=all" class="'.$status_all.'">All <span class="count">(<span class="all-count">'.count($wpld_users).'</span>)</span> 
</a> |</li>
<li class="pending"><a href="'.$_SERVER['PATH_INFO'].'admin.php?page=WPLinkDir/directory.php&amp;link_status=pending" class="'.$status_pending.'">Pending <span class="count">(<span class="pending-count">'.count($pending).'</span>)</span></a> |</li>
<li class="approved"><a href="'.$_SERVER['PATH_INFO'].'admin.php?page=WPLinkDir/directory.php&amp;link_status=publish" class="'.$status_publish.'">Approved <span class="count">(<span class="publish-count">'.count($publish).'</span>)</a> |</li>
<li class="spam"><a href="'.$_SERVER['PATH_INFO'].'admin.php?page=WPLinkDir/directory.php&amp;link_status=spam" class="'.$status_spam.'">Spam <span class="count">(<span class="spam-count">'.count($spam).'</span>)</span></a> |</li>
<li class="trash"><a href="'.$_SERVER['PATH_INFO'].'admin.php?page=WPLinkDir/directory.php&amp;link_status=trash" class="'.$status_trash.'">Trash <span class="count">(<span class="trash-count">'.count($trash).'</span>)</span></a></li>';
 ?>
</ul>
<p class="search-box">
	<label class="screen-reader-text" for="WPLD-search-input">Search WPLinkDir:</label>
	<input id="wpld-search-input" name="wpld-search-input" value="" type="text">
	<input value="Search WPLinkDir" class="button" type="submit">
</p>

<div class="tablenav">

<?php

	echo '<div class="tablenav-pages">';
	echo $prev;
	echo $first;
    echo $nav;
	echo $last;
	echo $next;
	echo '</div>';
?>
<div class="alignleft actions">
<?php
echo '<select name="wpld-action" id="wpld-action">';
echo ' <option value="-1" selected="selected">Bulk Actions</option>';
	
	if ($_GET['link_status']!='pending'){
		echo ' <option value="unapprove">Unapprove</option>';
	}
	
	if ($_GET['link_status']!='publish'){
		echo ' <option value="approve">Approve</option>';
	}
	
	if ($_GET['link_status']!='spam'){
		echo ' <option value="spam">Mark as Spam</option>';
	}
	
	if ($_GET['link_status']=='trash'){
		echo ' <option value="delete">Delete</option>';
	}else{
		echo ' <option value="trash">Move to Trash</option>';
	}
	
echo '</select>';
?>
<input name="doaction" id="doaction" value="Apply" type="submit">
<select name="wpld-filter" id="wpld-filter">
	<option value="id" selected="selected">View all links</option>
	<option value="user_registered">Date</option>
	<option value="LinkTitle">Title</option>
	<option value="display_name">Author</option>
	<option value="LinkCategory">Categories</option>
	<option value="user_url">URL</option>
	<option value="mailed">Mailed</option>
</select>
<input id="wpld-post-query-submit" value="Filter" type="submit">


</div>


<br class="clear">
</div>

<br class="clear">

<table class="widefat">

	<thead>
	<tr>

	<th scope="col" class="check-column"><input type="checkbox"></th>
	<th scope="col">Date</th>
	<th scope="col">Title</th>
	<th scope="col">Author</th>
	<th scope="col">Categories</th>

	<th scope="col">Url</th>
	<th scope="col">Status</th>

	</tr>
	</thead>
	<tbody>
<?php
if ($wpld_links != NULL) {	
foreach ($wpld_links as $wpld_link) {

//$post_info = get_post($wpld_link['ID']);
//$link_info = get_userdata($wpld_link['ID']);
	
	if ($wpld_link['LinkID']){
    	$linkDATE = $wpld_link['user_registered'];
 		$postID = $wpld_link['LinkID'];
 	}else{
 		$linkDATE = $wpld_link['user_registered'];
		$postID = 'pending-'.$wpld_link['ID'];
 	}
	
	$linkID = $wpld_link['ID'];
	$linkURL = $wpld_link['user_url'];
	$linkPENDING = $wpld_link['LinkStatus'];
	$linkTITLE = $wpld_link['LinkTitle'];
	$linkCATEGORY = $wpld_link['LinkCategory'];
	$linkSHORTDESC = $link_info->LinkShortDesc;
	$linkDESCRIPTION = $link_info->LinkDescription;
	$linkTAGS = $link_info->LinkTags;
	$linkPREMIUM = $edit_result['premium'];
	$linkEMAIL = $link_info->user_email;
	$linkNAME = $wpld_link['display_name'];
	$linkUPDATED = $post_info->post_modified;
	
 echo '
	<tr id="wpld-'.$postID.'" class="alternate author-self status-publish" valign="top">

		<th scope="row" class="check-column"><input name="wpld_checked[]" id="wpld_checked[]" value="'.$postID.'" type="checkbox"></th>
				<td><abbr title="Added '.$linkDATE.'">'.$linkDATE.'</abbr></td>
				<td><strong><a class="row-title" href="'.$_SERVER['PATH_INFO'].'admin.php?page=WPLinkDir/directory.php&amp;action=edit&amp;postID='.$postID.'&amp;userID='.$linkID.'" title="Edit &quot;'.$linkTITLE.'&quot;">'.$linkTITLE.'</a></strong>
		</td>
				<td>'.$linkNAME.'</td>
				<td>'.$linkCATEGORY.'</td>

				<td>'.$linkURL.'</a></td>

				<td>';
				if($linkPENDING=='pending'){
					echo $WPLD_Trans['Pending'];	
				}elseif($linkPENDING=='publish'){
					echo $WPLD_Trans['Approved'];
				}elseif($linkPENDING=='trash'){
					echo 'Trash';
				}elseif($linkPENDING=='spam'){
					echo 'Spam';
				}
		echo'
		</td>
			</tr>';
 }
}
 ?>
	</tbody>
</table>

</form>

<div class="tablenav">

<div class="tablenav-pages">
<?php

	echo $prev;
	echo $first;
    echo $nav;
	echo $last;
	echo $next;
?></div>
<br class="clear">
</div>

<br class="clear">
<?php
	}
}

	echo '</div>';
?>