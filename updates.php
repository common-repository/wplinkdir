<?php
	
	
if ($Version == '1.0'){
	
		add_option('wpld_template','wplinkdir.php');
		add_option('wpld_template_enable','No');
		add_option('wpld_addurl_id','');
		$wpld_dir_id = get_option('wpld_dir_id');	
		$wpld_addurl_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title='{$wpld_addurlname}' AND post_parent='{$wpld_dir_id}'");
		update_option('wpld_addurl_id',$wpld_addurl_id);
		update_option('wpld_version','1.1');
		
		return;
}
if ($Version == '1.1'){
	
		add_option('wpld_comments_enable','');
		add_option('wpld_dir_post_name','wplinkdir');
		add_option('wpld_addurl_post_name','addurl');
		add_option( 'wpld_default_categories', 'disable' );
		
		  $wpld_dir_id = get_option('wpld_dir_id');
		  $wpld_cat_id = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$wpld_dir_id}'", ARRAY_N);
		
		  $my_post = array();  
		  $my_post['ID'] = $wpld_dir_id;
		  $my_post['comment_status'] = 'closed';
		  $my_post['ping_status'] = 'closed';

		  // Update the post into the database
		  wp_update_post( $my_post );
		if($wpld_cat_id){  
		 foreach( $wpld_cat_id as $key => $value){
			$catID = $value[0];
			$wpld_subcategories=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$catID}'", ARRAY_N);
			
			$my_post1 = array();  
			$my_post['ID'] = $catID;
			$my_post['comment_status'] = 'closed';
			$my_post['ping_status'] = 'closed';

		    // Update the post into the database
		    wp_update_post( $my_post1 );
			
			
			if($wpld_subcategories){
				foreach( $wpld_subcategories as $key1 => $value1){
					
					$SubcatID = $value1[0];
					$wpld_pages=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$SubcatID}'", ARRAY_N);
					
					$my_post2 = array();  
					$my_post['ID'] = $SubcatID;
					$my_post['comment_status'] = 'closed';
					$my_post['ping_status'] = 'closed';

					// Update the post into the database
					wp_update_post( $my_post2 );
					
					if ($wpld_pages) {
						foreach( $wpld_pages as $key2 => $value2){
				
						$PageID = $value2[0];
						
						$my_post3 = array();  
						$my_post['ID'] = $PageID;
						$my_post['comment_status'] = 'closed';
						$my_post['ping_status'] = 'closed';

						// Update the post into the database
						wp_update_post( $my_post3 );
						
						}
					}
					
				}
			}
		}
		}
		  update_option('wpld_version','1.1.1');
		
		return;
}
if($Version == '1.1.1'){
			
		$linkrows = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wpld_links' , ARRAY_A );
		//print_r($linkrows);
		if($linkrows) {
			foreach($linkrows as $linkrow){
	 
				$new_user = array();
				//$new_user['user_pass'] = 'someone';
				$new_user['user_login'] = $linkrow[name];
				$new_user['user_email'] = $linkrow[email];
				$new_user['user_url'] = $linkrow[url];
				$new_user['display_name'] = $linkrow[name];
				//$new_user['description'] = $LinkDescription;
				//$new_user['user_registered'] = $time;
				$new_user['role'] = 'link_author';
				$user_id = wp_insert_user($new_user);
	 
				do_action('user_register', $user_id);
	 
				update_usermeta( $user_id, 'LinkTags', $linkrow[tags]);
				//update_usermeta( $user_id, 'LinkParent', $linkrow[parent]);
				update_usermeta( $user_id, 'LinkShortDesc', $linkrow[shortdesc]);
				update_usermeta( $user_id, 'LinkDescription',$linkrow[description]);
				update_usermeta( $user_id, 'LinkRank', $linkrow[pr]);
				update_usermeta( $user_id, 'LinkDate', $linkrow[date_added]);
				update_usermeta( $user_id, 'LinkStatus', 'pending');
				update_usermeta( $user_id, 'LinkCategory',$linkrow[category]);
				update_usermeta( $user_id, 'LinkTitle', $linkrow[title]);
		
				$user = get_userdata($user_id);
				
				
				$my_post = array();  
				$my_post['ID'] = $linkrow[id];
				$my_post['post_author'] = $user->ID;
		
				if ($linkrow[pending]==0){ 
					// Set the link to pending in Wordpress database 
					$my_post['post_status'] = 'pending';
					update_usermeta( $user_id, 'LinkStatus', 'pending');#ok
				}elseif($linkrow[pending]==1){
					// Set the link to directory approved in Wordpress database
					$my_post['post_status'] = 'publish';
					update_usermeta( $user_id, 'LinkStatus', 'publish');#ok
				}
		
				$wpld_comments_enable = get_option('wpld_comments_enable');
		 
				if ($wpld_comments_enable != 'Yes'){
					$my_post['comment_status'] = 'closed';
					$my_post['ping_status'] = 'closed';
				}else{
					$my_post['comment_status'] = 'open';
					$my_post['ping_status'] = 'open';
				}
  
				wp_update_post( $my_post );
			
				$post_info = get_post($linkrow[id]);
					update_usermeta( $post_info->post_author, 'LinkStatus', 'publish');
					update_usermeta( $post_info->post_author, 'LinkID', $post_info->ID);
					update_usermeta( $post_info->post_author, 'LinkPost_name', $post_info->post_name);
					update_usermeta( $post_info->post_author, 'LinkParent', $post_info->post_parent);
			}
		}
		  update_option('wpld_version','1.2');
		
		return;
}
if ($Version == '1.2'){
		
		update_option('wpld_version','1.3');
		add_option('wpld_addurl_id','');
		
		return;
}
if ($Version == '1.3'){
	$wpld_addurl_id = get_option('wpld_addurl_id');
	$wpld_addurlname = get_option('wpld_addurlname');
	$wpld_dir_author = get_option('wpld_dir_author');
	 
	if ($wpld_addurl_id != TRUE){
      
	 add_option('wpld_addurl_id','Test');
	 $wpld_dir_id = get_option('wpld_dir_id');	
	  
	 $wpld_addurl_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title='{$wpld_addurlname}' AND post_parent='{$wpld_dir_id}'");
	  
	 update_option('wpld_addurl_id',$wpld_addurl_id); 
	}
	if($wpld_dir_author != TRUE){
	 $new_user = array();
	 $new_user['user_login'] = 'WpLinkdir';
	 $new_user['display_name'] = 'WpLinkdir';
	 $new_user['role'] = 'author';
	 $user_id = wp_insert_user($new_user);
	 
	 do_action('user_register', $user_id);

     add_option('wpld_dir_author',$user_id);
	  
   // $wpld_dir_author = get_option('wpld_dir_author');
		
	 $wpld_dir_id = get_option('wpld_dir_id');
	 $wpld_cat_id = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$wpld_dir_id}'", ARRAY_N);
		
	 $my_post = array();  
	 $my_post['ID'] = $wpld_dir_id;
	 $my_post['post_author'] = $user_id;

		  // Update the post into the database
	 wp_update_post( $my_post );
		  
	 if($wpld_cat_id){  
 	  foreach( $wpld_cat_id as $key => $value){
  	   $catID = $value[0];
  	   //echo $wpld_dir_id;
  	   $wpld_subcategories=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$catID}'", ARRAY_N);
			
  	   $my_post1 = array();  
  	   $my_post1['ID'] = $catID;
  	   $my_post1['post_author'] = $user_id;

  			// Update the post into the database
  	   wp_update_post( $my_post1 );
			
  	   if($wpld_subcategories){
   	    foreach( $wpld_subcategories as $key1 => $value1){
					
    	 $SubcatID = $value1[0];
					
    	 $my_post2 = array();  
    	 $my_post2['ID'] = $SubcatID;
    	 $my_post2['post_author'] = $user_id;

    			// Update the post into the database
    	 wp_update_post( $my_post2 );
		}
  	   }
 	  }
		
	  $wpld_users = wpld_users('ID');
	  foreach ($wpld_users as $wpld_user){
	   //echo $wpld_user['ID'].'-'.$wpld_user['LinkID'].'<br>';
	   $my_post3 = array();  
	   $my_post3['ID'] = $wpld_user['LinkID'];
	   $my_post3['post_author'] = $wpld_user['ID'];

		// Update the post into the database
	   wp_update_post( $my_post3 );
	  }
	 }
	}
	update_option('wpld_version','1.3.1');
	return;	
}
if ($Version == '1.3.1'){
		//print_r(get_option('wpld_version'));
		$update='1.3.2';
		update_option('wpld_version', '1.3.2');
		//update_option('wpld_wersion_test_id',get_option('wpld_version'));
		//add_option('wpld_wersion_test_id','54');
		
		return;
}
	

?>