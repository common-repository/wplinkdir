<?php
//create_wplinkdir_dir($wplinkdir);


	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );
	
function create_wplinkdir_dir($wpld_dir_name,$wpld_addurlname,$wpld_main_description,$wpld_main_keywords) {
global $wpdb;

	$wpld_exist = get_option('wpld_exist');
	
	echo '<div id="message" class="updated fade"><strong>';
	
	if($wpld_exist=='install'){
// Create your main directory
     
	 $new_user = array();
	 $new_user['user_login'] = 'WPLinkDir';
	 $new_user['role'] = 'author';
	 $user_id = wp_insert_user($new_user);
	 
	 do_action('user_register', $user_id);
	 
	 $link_info = get_userdata($user_id);
	   
	   
	  	$my_post = array(); 
	 	 $my_post['post_title'] = $wpld_dir_name;
	 	 $my_post['post_content'] = '[wplinkdir]';
	 	 $my_post['post_status'] = 'publish';
	 	 $my_post['post_name'] = $wpld_dir_name;
	 	 $my_post['post_type'] = 'page';
		 $my_post['post_author'] = $link_info->ID;
		 $my_post['comment_status'] = 'closed';
		 $my_post['ping_status'] = 'closed';
		// Insert the page into the database inherit
	 	 
		 $postID = wp_insert_post( $my_post );
		 $post_info = get_post($postID);
		 
		 update_option('wpld_exist', 'exist');
		 update_option('wpld_dir_name', $post_info->post_title);
		 update_option('wpld_dir_post_name', $post_info->post_name);
		 update_option('wpld_dir_id',$postID);
		 add_option('wpld_dir_author',$post_info->post_author);
		 
		// Add meta tags into custom fields
		$meta_description = 'description';
		update_post_meta($postID, $meta_description, $wpld_main_description);
		update_option('wpld_description',$wpld_main_description);
    
		$meta_keywords = 'keywords';
		update_post_meta($postID, $meta_keywords, $wpld_main_keywords);
		update_option('wpld_keywords',$wpld_main_keywords);
		 echo $wpld_dir_name.' was created as main page for WPLinkDir! <br />';
	}else{
		 echo 'Sorry an error occured and WPLinkDir was not abel to finish the setup!<br />';
	}
	
	$wpld_dir_id = get_option('wpld_dir_id');
	$wpld_addurl_id = get_option('wpld_addurl_id');
	
	if($wpld_dir_id==TRUE && $wpld_addurl_id==FALSE){
		// Create your add your url page
	  	 $my_post = array();
	 	 $my_post['post_title'] = $wpld_addurlname;
	 	 $my_post['post_content'] = '[wplinkdir]';
	 	 $my_post['post_status'] = 'publish';
	 	 $my_post['post_name'] = $wpld_addurlname;
	 	 $my_post['post_parent'] = $wpld_dir_id;
	 	 $my_post['post_type'] = 'page';
		 $my_post['comment_status'] = 'closed';
		 $my_post['ping_status'] = 'closed';

		// Insert the page into the database 
	 	 
		 $postID = wp_insert_post( $my_post );
		 $post_info = get_post($postID);
		 
		 
		 update_option('wpld_addurlname', $post_info->post_title);
		 update_option('wpld_addurl_post_name', $post_info->post_name);
		 update_option('wpld_addurl_id', $postID);
		 
		 echo 'And as "ADD YOU URL" page, '.$wpld_addurlname.' was created!<br />';
	}else{
		 echo 'Sorry an error occured and WPLinkDir was not abel to create "ADD YOU URL" page!<br />';
	}
	
	
 
	echo '</strong></div>';
 }

function update_wplinkdir_dir($wpld_DirNewName,$wpld_NewAddurlName) {
global $wpdb;
		 
		$wpld_dir_name = get_option('wpld_dir_name');
		$wpld_addurlname = get_option('wpld_addurlname');
		$wpld_defaultdir = get_option('wpld_default_categories');

	$wpld_dir_id = get_option('wpld_dir_id');
	$wpld_addurl_id = get_option('wpld_addurl_id');

	
	echo '<div id="message" class="updated fade"><strong>';
	
	if($wpld_dir_name==$wpld_DirNewName){

		 echo 'Sorry nothing to Update!<br />';
	}else{
	// Edit name of your directory
	
		$my_post = array();  
		$my_post['ID'] = $wpld_dir_id;
		$my_post['post_title'] = $wpld_DirNewName;
		wp_update_post( $my_post ); 
		 
		update_option('wpld_dir_name', $wpld_DirNewName);
		 
		 echo $wpld_DirNewName.' was set as new main page name for WPLinkDir! <br />';
	}
	
	
	if($wpld_addurlname==$wpld_NewAddurlName){

		 echo '"ADD YOU URL" did not have anything to Update!<br />';
	}else{
	// Edit name of your add url page
	
		$my_post = array();  
		$my_post['ID'] = $wpld_addurl_id;
		$my_post['post_title'] = $wpld_NewAddurlName;
		wp_update_post( $my_post ); 
		 
		 update_option('wpld_addurlname', $wpld_NewAddurlName);
		 
		 echo 'You have successful updated "ADD YOU URL" page name and new name is: '.$wpld_NewAddurlName.' !';
	}
	
	if($wpld_defaultdir==NULL){
		wpld_create_categories();
		update_option( 'wpld_default_categories', 'enable' );
		 echo 'You have successful enebled "DEFAULT CATEGORIES"!<br />';
	}else{
		 echo '"DEFAULT CATEGORIES" are already enebled!<br />';
	}
 
	echo '</strong></div>';
 }

function wpld_delete_all(){
		
		global $wpdb;
		$wpld_cats = WPLD_CATS;
		$wpld_links = WPLD_LINKS;
		
		$wpld_addurl_id = get_option('wpld_addurl_id');
		
		$userIDs = get_wpld_users();
		if ($userIDs==TRUE){	
			foreach($userIDs as $userID){
				wp_delete_user( $userID );
			}
		}
		
		//wp_delete_post($wpld_dir_id);
		wp_delete_post($wpld_addurl_id);
		
		//Delete category table
		$sql = "DROP TABLE IF EXISTS $wpld_cats;";
		$e = $wpdb->query($sql);
		
		//Delete linktable
		//$sql1 = "DROP TABLE IF EXISTS $wpld_links;";
		//$e1 = $wpdb->query($sql1);
		
		//delete_option($name);
		delete_option('wpld_exist');
		delete_option('wpld_version');
		delete_option('wpld_dir_id');
		delete_option('wpld_dir_post_title');
		delete_option('wpld_dir_post_name');
		delete_option('wpld_dir_name');
		delete_option('wpld_addurl_id');
		delete_option('wpld_addurl_post_title');
		delete_option('wpld_addurl_post_name');
		delete_option('wpld_addurlname');
		delete_option('wpld_comments_enable');
		delete_option('wpld_template_enable');
		delete_option('wpld_template');
		delete_option('wpld_show_numbers');
		delete_option('wpld_show_navbar');
		delete_option('wpld_orderby');
		delete_option('wpld_nofollow');
		delete_option('wpld_extended_info');
		delete_option('wpld_recip_requirement');
		delete_option('wpld_htmltags','<b><i><u>');
		delete_option('wpld_htmlcode');
		delete_option('wpld_extrafields');
		delete_option('wpld_subdomains');
		delete_option('wpld_ShortDescSize_size');
		delete_option('wpld_description_size');
		delete_option('wpld_emailme');
		delete_option('wpld_email_address');
		delete_option('wpld_captcha');
		delete_option('wpld_premium');
		delete_option('wpld_style');
		delete_option('wpld_default_categories');
		delete_option('wpld_description');
		delete_option('wpld_keywords');
		delete_option('wpld_dir_author');

		remove_role( 'link_author'  );
}

function wpld_delete_directory(){
		
		global $wpdb;
		
		$wpld_cats = WPLD_CATS;
		
		$wpld_dir_id = get_option('wpld_dir_id');
		$wpld_addurl_id = get_option('wpld_addurl_id');
		$wpld_dir_author = get_option('wpld_dir_author');
		
		if ( class_exists( 'WP_User_Search' ) ) {
			$wpld_users = get_wpld_users();
		}else{    
			$wpld_users = $wpdb->get_col(' 
			SELECT ID 
			FROM '.$wpdb->users.' INNER JOIN '.$wpdb->usermeta.' 
			ON '.$wpdb->users.'.ID = '.$wpdb->usermeta.'.user_id 
			WHERE '.$wpdb->usermeta.'.meta_key = \''.$wpdb->prefix.'capabilities\' 
			AND '.$wpdb->usermeta.'.meta_value LIKE \'%"link_author"%\' 
			');
		}  
	
		foreach($wpld_users as $userID){
			if($wpld_dir_author != $userID){
			 wp_delete_user( $userID );
			}
		}
		
		
		$wpld_categories=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$wpld_dir_id}'", ARRAY_N);
		
		foreach( $wpld_categories as $key => $value){
	
			$catID = $value[0];
	
			$wpld_subcategories=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$catID}'", ARRAY_N);
			
			if($catID != $wpld_addurl_id){
				wp_delete_post($catID);
			}
			
			if($wpld_subcategories!=NULL){
				foreach( $wpld_subcategories as $key1 => $value1){
			
					$SubcatID = $value1[0];
	
					$wpld_pages=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$SubcatID}'", ARRAY_N);
			
					wp_delete_post($SubcatID);
				
					if ($wpld_pages != NULL) {
						foreach( $wpld_pages as $key2 => $value2){
				
							$PageID = $value2[0];
				
							wp_delete_post($PageID);
						}
					}
				}
			
			}
		}
		update_option( 'wpld_default_categories', 'disable' );
		//Truncate category table
		//$sql = "TRUNCATE TABLE $wpld_cats;";
		//$e = $wpdb->query($sql);
		
		
		//Truncate link table
		//$sql1 = "TRUNCATE TABLE $wpld_links;";
		//$e1 = $wpdb->query($sql1);
		
}

function wpld_confirmation($Alert='Are you sure?'){

	// This function just creates a jS confirmation request. You call it by having onclick="return wpld_confirmation();" in a submit button and calling this function somewhere before it

	global $WPLD_Domain,$WPLD_Trans;

	$back='<script type="text/javascript"><!--
	function confirmation(){
		var answer = confirm("'.$Alert.'");
		if(answer){
			window.location = "'.$_SERVER['RQUEST_URI'].'";
		}else{
			alert(\''.__('Action Cancelled',$WPLD_Domain).'\');
			return false;
		}
	}
	//--></script>';

	return $back;
}


function exclude_pages_filter() {
	global $wpdb;
	// get the list of excluded pages and merge them with the current list
	$excludes = array_merge((array)$excludes, (array)$wpdb->get_col("SELECT DISTINCT `post_id` FROM `".$wpdb->postmeta."` WHERE `meta_key` IN ( 'exclude_page' ) AND `meta_value` IN ( '1' )"));
	return $excludes;
} 
add_filter('wp_list_pages_excludes', 'exclude_pages_filter');


function wpld_array_remove_empty($arr){
    $narr = array();
    while(list($key, $val) = each($arr)){
        if (is_array($val)){
            $val = wpld_array_remove_empty($val);
            // does the result array contain anything?
            if (count($val)!=0){
                // yes :-)
                $narr[$key] = $val;
            }
        }
        else {
            if (trim($val) != ""){
                $narr[$key] = $val;
            }
        }
    }
    unset($arr);
    return $narr;
}

function wpld_getSubCats($id){

global $wpdb,$directory,$FILExt;


	$ShowNums=get_option('wpld_show_numbers');
	$directory = get_option('wpld_dir_post_name');

	$wpld_cats = WPLD_CATS;
	$wpld_links = WPLD_LINKS;

		$query = "SELECT title,title_pretty,id FROM $wpld_cats WHERE parent = '{$id}' ORDER BY title ASC";
		$getSubCats = mysql_query($query) or die(mysql_error());
		
		 $Catname = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE ID='{$id}'")
		 or die(mysql_error());
		 	
		
		while($row = mysql_fetch_array($getSubCats)){
	
			$SubTitle = $row['title'];
			$SubTitle_pretty = $row['title_pretty'];
			$SubCatID = $row['id'];
			
			
		echo '
		<li>
		<a href="'.get_bloginfo('url').'/'.$directory.'/'.$Catname.'/'.$SubTitle_pretty.$FILExt.'">'.$SubTitle.($ShowNums == 'Yes' ? ' ('.wpld_LinkCount($SubCatID).')' : '').'</a>
		</li>
		';

			
			}
			
		
}

function wpld_LinkCount($postID){
	global $wpdb;
	

	 
	if ( class_exists( 'WP_User_Search' ) ) {
		$wpld_users = get_wpld_users();
	}else{    
		$wpld_users = $wpdb->get_col(' 
		SELECT ID 
		FROM '.$wpdb->users.' INNER JOIN '.$wpdb->usermeta.' 
		ON '.$wpdb->users.'.ID = '.$wpdb->usermeta.'.user_id 
		WHERE '.$wpdb->usermeta.'.meta_key = \''.$wpdb->prefix.'capabilities\' 
		AND '.$wpdb->usermeta.'.meta_value LIKE \'%"link_author"%\' 
		');
	}
	$Approved = array();
	
	if ($wpld_users==TRUE){	
		foreach($wpld_users as $userID){
		
	
			$user = get_userdata($userID);
			if ($user->LinkParent == $postID && $user->LinkStatus == 'publish'){
				$Approved[] = $userID;
			}
		}
	}
	
	$LinkCount = count($Approved);
	
	return $LinkCount;
}

function wpld_SubCatCheck($title){
	global $wpdb;

	$wpld_cats = WPLD_CATS;

		$query="SELECT * FROM $wpld_cats WHERE parent = '{$title}' ORDER BY title ASC";
		$getSubCats = mysql_query($query) or die(mysql_error());
		
	return $getSubCats;

}

function wpld_getSubCats_Link($title,$CatURL){

global $wpdb,$FILExt;


	$ShowNums=get_option('wpld_show_numbers');
	$directory = get_option('wpld_dir_post_name');

	$wpld_cats = WPLD_CATS;

		$query = "SELECT title,title_pretty,id FROM $wpld_cats WHERE parent = '{$title}' ORDER BY title ASC";
		$getSubCats = mysql_query($query) or die(mysql_error());


	while($row = mysql_fetch_array($getSubCats)){
	
		$arraySubCat[] =  array( $row['title'], $row['title_pretty'], $row['id']);

		}

	$split_arr = array_chunk($arraySubCat, 3);
	
	$SubCatArray1 = array();
	$SubCatArray2 = array();
	$SubCatArray3 = array();
	
	foreach ($split_arr as $num => $cat) {


			$SubCatArray1[] = array( $cat[0][0],$cat[0][1],$cat[0][2] );
			$SubCatArray2[] = array( $cat[1][0],$cat[1][1],$cat[1][2] );
			$SubCatArray3[] = array( $cat[2][0],$cat[2][1],$cat[2][2] );
		
	}

echo '<ul>
';
	$SubCatArray1 = wpld_array_remove_empty($SubCatArray1);
	
	foreach ($SubCatArray1 as $value => $cat) {
	
		$title = $cat[0];
		$title_pretty = $cat[1];
		$id = $cat[2];		
		
		echo '
		<li class="cat-id-'.$id.'">
		<a href="'.get_bloginfo('url').'/'.$directory.'/'.$CatURL.'/'.$title_pretty.$FILExt.'">'.$title.($ShowNums == 'Yes' ? ' ('.wpld_LinkCount($id).')' : '').'</a>
		</li>';
	}
	echo '</ul>
	';

echo '<ul>
';
	$SubCatArray2 = wpld_array_remove_empty($SubCatArray2);
	
	foreach ($SubCatArray2 as $value => $cat) {
	
		$title = $cat[0];
		$title_pretty = $cat[1];
		$id = $cat[2];		
		
		echo '
		<li class="cat-id-'.$id.'">
		<a href="'.get_bloginfo('url').'/'.$directory.'/'.$CatURL.'/'.$title_pretty.$FILExt.'">'.$title.($ShowNums == 'Yes' ? ' ('.wpld_LinkCount($id).')' : '').'</a>
		</li>';
	}
	echo '</ul>
	';

echo '<ul>
';
	$SubCatArray3 = wpld_array_remove_empty($SubCatArray3);
	
	foreach ($SubCatArray3 as $value => $cat) {
	
		$title = $cat[0];
		$title_pretty = $cat[1];		
		
		echo '
		<li class="cat-id-'.$id.'">
		<a href="'.get_bloginfo('url').'/'.$directory.'/'.$CatURL.'/'.$title_pretty.$FILExt.'">'.$title.($ShowNums == 'Yes' ? ' ('.wpld_LinkCount($id).')' : '').'</a>
		</li>';
	}
	echo '</ul>
	';
}

function wpld_CatName() {
	global $wpdb;
	$wpld_cats = WPLD_CATS;
	
		$queryCatName = "SELECT title_pretty FROM $wpld_cats";	
	$getCatName = mysql_query($queryCatName) or die(mysql_error());
	
	while($row = mysql_fetch_assoc($getCatName)){
		
		$array[] = wrap_each($row['title_pretty'] );
	}
 	
	$CatName = implode (' || ', $array);
	return $CatName;

}
function wrap_each(&$item)
{
    $item = "'$item'";
	return $item;
}

# Check if a Category exist!!!!
function wpld_CatNameCheck($var) {
	global $wpdb;
	
	$wpld_dir_id = get_option('wpld_dir_id');
	$wpld_addurl_id = get_option('wpld_addurl_id');
    $wpld_dir_author = get_option('wpld_dir_author');
	//echo $wpld_dir_author;
	$sql = "SELECT ID,post_name FROM $wpdb->posts WHERE post_status = 'publish' AND post_author = '{$wpld_dir_author}'";
	$getCatName = $wpdb->get_results($sql, ARRAY_A);
	
	foreach($getCatName as $row){
	 
	 if ($wpld_dir_id != $row['ID'] && $wpld_addurl_id != $row['ID']){
	 
		if ($var == $row['post_name']) {
		echo 'True';
		return true;
		}
	 }	
	}
}

# Check if a Link page exist!!!!	
function wpld_LinkNameCheck($var) {
	global $wpdb;
	$wpld_links = WPLD_LINKS;
	
		$queryLinkName = "SELECT cat_pretty FROM $wpld_links";	
	$getLinkName = mysql_query($queryLinkName) or die(mysql_error());
	
	while($row = mysql_fetch_assoc($getLinkName)){
		
		if ($var == $row['cat_pretty']) {
		return true;
		}
		
	}
 	

}

# Get page Rank for URL
function getpagerank($url){

	$fp = fsockopen("toolbarqueries.google.com", 80, $errno, $errstr, 30);
	if(!$fp){
		echo "$errstr ($errno)<br />\n";
	}else{
		$out="GET /search?client=navclient-auto&ch=".CheckHash(HashURL($url))."&features=Rank&q=info:".$url."&num=100&filter=0 HTTP/1.1\r\n";
		$out.="Host: toolbarqueries.google.com\r\n";
		$out.="User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)\r\n";
		$out.="Connection: Close\r\n\r\n";

		fwrite($fp, $out);

		while(!feof($fp)){
			$data=fgets($fp, 128);
			$pos=strpos($data, "Rank_");
			if($pos===false){} else{
				$pagerank = substr($data, $pos + 9);
				return $pagerank;
			}
		}
		fclose($fp);
	}
}

function CheckHash($Hashnum){
	$CheckByte=0;
	$Flag=0;

	$HashStr=sprintf('%u', $Hashnum);
	$length=strlen($HashStr);

	for($i=$length-1; $i>=0; $i--) {
		$Re=$HashStr{$i};
		if(1===($Flag % 2)){
			$Re+=$Re;
			$Re=(int)($Re/10)+($Re%10);
		}
		$CheckByte+=$Re;
		$Flag++;
	}

	$CheckByte %= 10;
	if (0 !== $CheckByte){
		$CheckByte=10-$CheckByte;
		if (1 === ($Flag % 2) ) {
			if (1 === ($CheckByte % 2)) {
				$CheckByte += 9;
			}
			$CheckByte >>= 1;
		}
	}
	return '7'.$CheckByte.$HashStr;
}

function HashURL($String){
	$Check1 = StrToNum($String, 0x1505, 0x21);
	$Check2 = StrToNum($String, 0, 0x1003F);

	$Check1>>=2; 	
	$Check1=(($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
	$Check1=(($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
	$Check1=(($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);	

	$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
	$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );

	return ($T1 | $T2);
}

function StrToNum($Str, $Check, $Magic){
	$Int32Unit = 4294967296; // 2^32

	$length = strlen($Str);
	for ($i = 0; $i < $length; $i++) {
		$Check *= $Magic; 	
		// If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
		// the result of converting to integer is undefined
		// refer to http://www.php.net/manual/en/language.types.integer.php
		if ($Check >= $Int32Unit) {
			$Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
			//if the check less than -2^31
			$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
		}
		$Check += ord($Str{$i}); 
	}
	return $Check;
}

function wpld_FormCheck($FormCheck) {

	$form = array();
	
	//Form Name:
	if ($FormCheck['Name'] == NULL) {
		$form['name'][0] = $FormCheck['Name'];
		$form['name'][1] = NULL;
	}else{
		$form['name'][0] = $FormCheck['Name'];
		$form['name'][1] = true;
	}
	
	//Form Email
	
	if ($FormCheck['Email'] == NULL) {
		$form['email'][0] = NULL;
		$form['email'][1] = NULL;
	}
	else{
		$email = $FormCheck['Email'];
		
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
			$form['email'][0] = $FormCheck['Email'];
		    $form['email'][1] = true;
		}
		else {
			$form['email'][0] = NULL;
			$form['email'][1] = NULL;
		}
	}
	
	//Form URL
	if ($FormCheck['URL'] == NULL) {
     	$errMsg['URL'] = "* Please enter valid URL including http://";
		$form['url'][0] = NULL;
		$form['url'][1] = NULL;
	}else{
	$url=$FormCheck['URL'];
		function isValidURL($url)
		{
 			return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
		}
		
			if(!isValidURL($url)){
     			$errMsg['URL'] = "* Please enter valid URL including http://";
				$form['url'][0] = NULL;
				$form['url'][1] = NULL;
			}else{
				$form['url'][0] = $url;
				$form['url'][1] = true;
			}
	}
	
	//Form Title:
	if ($FormCheck['Title'] == NULL) {
		$form['title'][0] = NULL;
		$form['title'][1] = NULL;
	}else{
		$form['title'][0] = $FormCheck['Title'];
		$form['title'][1] = true;
	}
	
	//Form Category:
	if ($FormCheck['Category'] == NULL) {
		$form['category'][0] = NULL;
		$form['category'][1] = NULL;
	}else{
		$form['category'][0] = $FormCheck['Category'];
		$form['category'][1] = true;
	}
	
	//Form ShortDescription:
	if ($FormCheck['ShortDescription'] == NULL) {
		$form['shortdescription'][0] = NULL;
		$form['shortdescription'][1] = NULL;
	}else{
		$form['shortdescription'][0] = $FormCheck['ShortDescription'];
		$form['shortdescription'][1] = true;
	}
	
	//Form Description:
	if ($FormCheck['Description'] == NULL) {
		$form['description'][0] = NULL;
		$form['description'][1] = NULL;
	}else{
		$form['description'][0] = $FormCheck['Description'];
		$form['description'][1] = true;
	}
	
	//Form Tags:
	if ($FormCheck['Tags'] == NULL) {
		$form['tags'][0] = NULL;
		$form['tags'][1] = NULL;
	}else{
		$form['tags'][0] = $FormCheck['Tags'];
		$form['tags'][1] = true;
	}
	return $form;
}

function wpld_create_categories() {

global $wpdb;
$wpld_cats = WPLD_CATS;
$wpld_dir_id = get_option('wpld_dir_id');
$wpld_dir_author = get_option('wpld_dir_author');

	$CategoryList = array("Finance",
		  			  "Home & Family",
                      "Computers",
                      "Disease & Illness",
                      "Vehicles",
		              "Self Improvement",
                      "Product Reviews",
                      "Arts & Entertainment",
                      "Food & Beverage",
		              "Internet Business",
                      "Recreation & Sports",
                      "Society",
                      "Writing & Speaking",
		              "Communications",
                      "Fashion",
                      "Health & Fitness",
                      "Politics",
                      "Reference & Education",
                      "Travel & Leisure",
					  "Business");

	foreach ($CategoryList as $CatTitle) {

		$CatParent = $wpld_dir_id;
		
	    // Create your new category
	  	 $my_post = array();
	 	 $my_post['post_title'] = $CatTitle;
	 	 $my_post['post_content'] = '[wplinkdir]';
	 	 $my_post['post_status'] = 'publish';
	 	 $my_post['post_parent'] = $CatParent;
		 $my_post['post_author'] = $wpld_dir_author;
	 	 $my_post['post_type'] = 'page';
		 $my_post['comment_status'] = 'closed';
		 $my_post['ping_status'] = 'closed';

		// Insert the page into the database  
	 	 
		 wp_insert_post( $my_post );
		 
		 //Insert data into wplinkdir category table
		 $CatRow = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_title='{$CatTitle}' AND post_parent='{$CatParent}'")
		 or die(mysql_error());
		 
		 $CatRowID = $CatRow->ID;
		 $CatRowName = $CatRow->post_name;
		
		 $wpdb->query("INSERT INTO `$wpld_cats` (`id`, `title`, `title_pretty`, `parent`, `description`) VALUES
			('{$CatRowID}', '{$CatTitle}', '{$CatRowName}', '{$CatParent}', '{$CatDescription}');")
		    or die(mysql_error());
		
		 //Exclude the category from frontpage 	
		 $wpld_exclude_meta = $wpdb->get_var("SELECT `meta_id` FROM `".$wpdb->postmeta."` WHERE `{$CatRowID}` IN('".$CatRowID."') AND `meta_key` IN ('exclude_page') LIMIT 1");
		 if(is_numeric($wpld_exclude_meta) && ($wpld_exclude_meta > 0)) {
			  update_post_meta($CatRowID, 'exclude_page', '1');
		 }else{
		
			add_post_meta($CatRowID, 'exclude_page', '1');		
		 }

	}	

	$SubCategoryList = array("Celebrities"=>"Arts & Entertainment",
		      		  "Humanities"=>"Arts & Entertainment",
                      "Movies"=>"Arts & Entertainment",
                      "Photography"=>"Arts & Entertainment",
                      "Poetry"=>"Arts & Entertainment",
		              "Computer Certification"=>"Computers",
                      "Data Recovery"=>"Computers",
                      "Games"=>"Computers",
                      "Hardware"=>"Computers",
		              "Software"=>"Computers",
                      "Credit"=>"Finance",
                      "Currency Trading"=>"Finance",
                      "Debt Consolidation"=>"Finance",
		              "Fundraising"=>"Finance",
                      "Insurance"=>"Finance",
                      "Investing"=>"Finance",
                      "Leasing"=>"Finance",
                      "Loans"=>"Finance",
                      "Mortgage"=>"Finance",
		              "Mutual Funds"=>"Finance", 
                      "Personal Finance"=>"Finance",
                      "Real Estate"=>"Finance",
                      "Stock Market"=>"Finance",
		              "Taxes"=>"Finance",
                      "Wealth Building"=>"Finance",
                      "Babies"=>"Home & Family",
                      "Crafts"=>"Home & Family",
		              "Elderly Care"=>"Home & Family",
                      "Gardening"=>"Home & Family",
                      "Hobbies"=>"Home & Family",
                      "Holidays"=>"Home & Family",
		              "Home Improvement"=>"Home & Family",
                      "Interior Design"=>"Home & Family",
                      "Landscaping"=>"Home & Family",
                      "Parenting"=>"Home & Family",
                      "Pets"=>"Home & Family",
                      "Pregnancy"=>"Home & Family", 
		              "Book Reviews"=>"Product Reviews",
                      "Consumer Electronics"=>"Product Reviews",
                      "Digital Products"=>"Product Reviews",
                      "Movie Reviews"=>"Product Reviews",
		              "Music Reviews"=>"Product Reviews",
                      "Data Recovery"=>"Product Reviews",
                      "Attraction"=>"Self Improvement",
                      "Coaching"=>"Self Improvement",
		              "Goal Setting"=>"Self Improvement",
                      "Grief"=>"Self Improvement",
                      "Happiness"=>"Self Improvement",
                      "Innovation"=>"Self Improvement",
		              "Inspirational"=>"Finance",
                      "Leadership"=>"Self Improvement",
                      "Motivation"=>"Self Improvement",
                      "Organizing"=>"Self Improvement",
                      "Spirituality"=>"Self Improvement",
                      "Stress Management"=>"Self Improvement",
		              "Success"=>"Self Improvement", 
                      "Time Management"=>"Self Improvement",
                      "Boats"=>"Vehicles",
                      "Cars"=>"Vehicles",
		              "Motorcycles"=>"Vehicles",
                      "RVs"=>"Finance",
                      "Trucks-SUVS"=>"Vehicles",
                      "Breast Cancer"=>"Disease & Illness",
		              "Colon Cancer"=>"Disease & Illness",
                      "Leukemia"=>"Disease & Illness",
                      "Mesothelioma"=>"Disease & Illness",
                      "Multiple Sclerosis"=>"Disease & Illness",
		              "Ovarian Cancer"=>"Disease & Illness",
                      "Prostate Cancer"=>"Disease & Illness",
                      "Skin Cancer"=>"Disease & Illness",
                      "Coffee"=>"Food & Beverage",
                      "Cooking"=>"Food & Beverage",
                      "Gourmet"=>"Food & Beverage",
		              "Recipes"=>"Food & Beverage",
                      "Wine"=>"Food & Beverage",
                      "Affiliate Programs"=>"Internet Business",
                      "Auctions"=>"Internet Business",
		              "Audio-Video Streaming"=>"Internet Business",
                      "Blogging"=>"Internet Business",
                      "Domains"=>"Internet Business",
                      "Ebooks"=>"Internet Business",
		              "Ecommerce"=>"Internet Business",
                      "Email Marketing"=>"Internet Business",
                      "Ezine Marketing"=>"Internet Business",
                      "Forums"=>"Internet Business",
		              "Internet Marketing"=>"Internet Business",
                      "Podcasts"=>"Internet Business",
                      "PPC"=>"Internet Business",
                      "RSS"=>"Internet Business",
                      "Security"=>"Internet Business",
                      "SEO"=>"Internet Business",
		             "Site Promotion"=>"Internet Business", 
                      "Spam"=>"Finance",
                      "Traffic Generation"=>"Internet Business",
                      "Web Design"=>"Internet Business",
		              "Web Hosting"=>"Internet Business",
                      "Biking"=>"Recreation & Sports",
                      "Extreme"=>"Recreation & Sports",
                      "Fishing"=>"Recreation & Sports",
		              "Gambling & Casinos"=>"Recreation & Sports",
		              "Golf"=>"Recreation & Sports",
                      "Hunting"=>"Recreation & Sports",
                      "Martial Arts"=>"Recreation & Sports",
                      "Running"=>"Recreation & Sports",
		              "Tennis"=>"Recreation & Sports",
                      "Dating"=>"Society",
                      "Divorce"=>"Society",
                      "Marriage"=>"Society",
		              "Relationships"=>"Society",
                      "Sexuality"=>"Society",
                      "Weddings"=>"Society",
                      "Article Writing"=>"Writing & Speaking",
		              "Book Marketing"=>"Writing & Speaking",
                      "Copywriting"=>"Writing & Speaking",
                      "Public Speaking"=>"Writing & Speaking",
                      "Writing"=>"Writing & Speaking",  
                      "Broadband Internet"=>"Communications",
                      "GPS"=>"Communications",
		              "Mobile Phones"=>"Communications", 
                      "Satellite Radio"=>"Communications",
                      "Satellite TV"=>"Communications",
                      "Video Conferencing"=>"Communications",
		              "VOIP"=>"Communications",
                      "Clothing"=>"Fashion",
                      "Jewelry"=>"Fashion",
                      "Shoes"=>"Fashion",
		              "Acne"=>"Health & Fitness",
                      "Alternative Medicine"=>"Health & Fitness",
                      "Beauty"=>"Health & Fitness",
                      "Cardio"=>"Health & Fitness",
		              "Depression"=>"Health & Fitness",
                      "Diabetes"=>"Health & Fitness",
                      "Exercise"=>"Health & Fitness",
                      "Fitness Equipment"=>"Health & Fitness",
		              "Hair Loss"=>"Health & Fitness",
                      "Medicine"=>"Health & Fitness",
                      "Meditation"=>"Health & Fitness",
                      "Mens Issues"=>"Health & Fitness",
                      "Muscle Building"=>"Health & Fitness",
                      "Nutrition"=>"Health & Fitness",      
		              "Supplements"=>"Health & Fitness",
                      "Weight Loss"=>"Health & Fitness",
                      "Womens Issues"=>"Health & Fitness",
                      "Yoga"=>"Health & Fitness",
                      "Commentary"=>"Politics",
                      "Current Events"=>"Politics", 
		              "History"=>"Politics",
                      "Adult"=>"Reference & Education",
                      "College"=>"Reference & Education",
                      "Environmental"=>"Reference & Education",
		              "Homeschooling"=>"Reference & Education",
                      "K-12 Education"=>"Reference & Education",
                      "Language"=>"Reference & Education",
                      "Legal"=>"Reference & Education",
		              "Philosophy"=>"Reference & Education",
                      "Psychology"=>"Reference & Education",
                      "Science"=>"Reference & Education",
                      "Sociology"=>"Reference & Education",
		              "Weather"=>"Reference & Education",
		              "Aviation"=>"Travel & Leisure", 
                      "Boating"=>"Travel & Leisure",
                      "Cruises"=>"Travel & Leisure",
                      "Destinations"=>"Travel & Leisure",
		              "Outdoors"=>"Travel & Leisure",
                      "Travel Tips"=>"Travel & Leisure",
                      "Vacations"=>"Travel & Leisure",
					  "Advertising"=>"Business",
                      "Article Marketing"=>"Business",
                      "Careers"=>"Business",
                      "Customer Service"=>"Business",
		              "Entrepreneurs"=>"Business",
                      "Ethics"=>"Business",
                      "Home Based Business"=>"Business",
                      "Management"=>"Business",
		              "Marketing"=>"Business",
		              "Networking"=>"Business",
		              "Public Relations"=>"Business",
		              "Sales"=>"Business");

	foreach ($SubCategoryList as $SubCatTitle => $CategoryTitle) {

		 $SubCatRow = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_title='{$CategoryTitle}' AND post_author = '{$wpld_dir_author}'")
		 
		 or die(mysql_error());
		 
		 $SubCatParent = $SubCatRow->ID;
		
	    // Create your new category
	  	 $my_post = array();
	 	 $my_post['post_title'] = $SubCatTitle;
	 	 $my_post['post_content'] = '[wplinkdir]';
	 	 $my_post['post_status'] = 'publish';
		 $my_post['post_author'] = $wpld_dir_author;
	 	 $my_post['post_parent'] = $SubCatParent;
	 	 $my_post['post_type'] = 'page';
		 $my_post['comment_status'] = 'closed';
		 $my_post['ping_status'] = 'closed';

		// Insert the page into the database  
	 	 
		 wp_insert_post( $my_post );
		 
		 //Insert data into wplinkdir category table
		 $CatRow = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_title='{$SubCatTitle}' AND post_parent='{$SubCatParent}'")
		 or die(mysql_error());
		 
		 $CatRowID = $CatRow->ID;
		 $CatRowName = $CatRow->post_name;
		
		 //Exclude the category from frontpage 	
		 $wpld_exclude_meta = $wpdb->get_var("SELECT `meta_id` FROM `".$wpdb->postmeta."` WHERE `{$CatRowID}` IN('".$CatRowID."') AND `meta_key` IN ('exclude_page') LIMIT 1");
		 if(is_numeric($wpld_exclude_meta) && ($wpld_exclude_meta > 0)) {
			  update_post_meta($CatRowID, 'exclude_page', '1');
		 }else{
		
			add_post_meta($CatRowID, 'exclude_page', '1');		
		 }

	}	

}

function wpld_sort_categories($Cats,$Category){
	// This function is used to show a list of categories in a select menu. It's used to so the user can select 
	//a category for adding a link on the Add URL page and on the Edit Links page where the admin must select a category for new or existing links.
	
global $wpdb,$subcategories;

	$wpld_cats = WPLD_CATS;
	
	$wpld_dir_id = get_option('wpld_dir_id');
    $wpld_addurl_id = get_option('wpld_addurl_id');
    $wpld_dir_author = get_option('wpld_dir_author'); 
	
	if($Category==TRUE) {
		$query1 = "SELECT * FROM $wpdb->posts WHERE post_author='{$wpld_dir_author}' AND post_title='{$Category}'";
		$SELECTED = $wpdb->get_results($query1, ARRAY_A);
	
		foreach ($SELECTED as $result) {
		
		$SelectID = $result['ID'];
		$SelectParent = $result['post_parent'];
		}
	
		if ($SelectParent==$wpld_dir_id) {
			echo '<option  SELECTED value="'.$SelectID.'-'.$Category.'">'.$Category.'</option>';
		}else{
			echo '<option  SELECTED value="'.$SelectID.'-'.$Category.'">- '.$Category.'</option>';
		}
	}else{
	echo '<option  SELECTED value=""></option>';
	}
	
	foreach($Cats as $getCat){
	
		 if ($getCat['ID'] != $wpld_addurl_id){
		  $getCatID = $getCat['ID'];
		  $getCatTITLE = $getCat['post_title'];
		  echo '<option value="'.$getCatID.'-'.$getCatTITLE.'">'.$getCatTITLE.'</option>';
		
			if ($subcategories = 'Display') {
				wpld_sort_subcats($getCatID);
			}
		 }
 	}
}

function wpld_sort_subcats($id) {
	
global $wpdb;

    $wpld_dir_author = get_option('wpld_dir_author');
    $wpld_dir_id = get_option('wpld_dir_id');
    $wpld_addurl_id = get_option('wpld_addurl_id');

			$GetSubCats=$wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_parent='{$id}' AND post_author='{$wpld_dir_author}' AND post_status = 'publish' ", ARRAY_A);
			 
				foreach($GetSubCats as $SubCats){
				
				 if ($wpld_addurl_id != $getCat['ID']){
 					$Subcategories = $SubCats['post_title'];
					$SubCatparent = $SubCats['post_parent'];
					$SubCatID = $SubCats['ID'];
					echo '<option value="'.$SubCatID.'-'.$Subcategories.'">- '.$Subcategories.'</option>';
				 }
				}
}
function wpld_navbar(){
global $post,$wpdb;
$wpld_dir_name = get_option('wpld_dir_name');
	$directory = get_option('wpld_dir_post_name');

	$id = $post->ID;
	$title = $post->post_title;
	$name = $post->post_name;
	$parent = $post->post_parent;

	// First navbar link
	$myrows = $wpdb->get_results( "SELECT ID, post_title, post_name, post_parent FROM $wpdb->posts where ID = '{$parent}'", ARRAY_A );
	
	$post_title1 = $myrows[0]['post_title'];
	$post_name1 = $myrows[0]['post_name'];
	$post_parent1 = $myrows[0]['post_parent'];
	
	
	// Second navbar link
	if($post_parent1!=0){
		$myrows = $wpdb->get_results( "SELECT ID, post_title, post_name, post_parent FROM $wpdb->posts where ID = '{$post_parent1}'", ARRAY_A );
		
		$post_title2 = $myrows[0]['post_title'];
		$post_name2 = $myrows[0]['post_name'];
		$post_parent2 = $myrows[0]['post_parent'];
		
		// Third navbar link
		if($post_parent2!=0){
			$myrows = $wpdb->get_results( "SELECT ID, post_title, post_name, post_parent FROM $wpdb->posts where ID = '{$post_parent2}'", ARRAY_A );
		
			$post_title3 = $myrows[0]['post_title'];
			$post_name3 = $myrows[0]['post_name'];
			$post_parent3 = $myrows[0]['post_parent'];
			
			$Navlink3 = '<a href="'.get_bloginfo('url').'/'.$directory.'.html">'.$wpld_dir_name.'</a> &#187; ';
			$Navlink2 = '<a href="'.get_bloginfo('url').'/'.$directory.'/'.$post_name2.'.html">'.$post_title2.'</a> &#187; ';
			$Navlink1 = '<a href="'.get_bloginfo('url').'/'.$directory.'/'.$post_name2.'/'.$post_name1.'.html">'.$post_title1.'</a> &#187; '.$title;
		}else{
			$Navlink3 = '<a href="'.get_bloginfo('url').'/'.$directory.'.html">'.$wpld_dir_name.'</a> &#187; ';
			$Navlink2 = '<a href="'.get_bloginfo('url').'/'.$directory.'/'.$post_name1.'.html">'.$post_title1.'</a> &#187; '.$title;
		}
	}else{
		if($parent!=0){
			$Navlink3 = '<a href="'.get_bloginfo('url').'/'.$directory.'.html">'.$wpld_dir_name.'</a> &#187; '.$title;
		}else{
			$Navlink3 = $wpld_dir_name;
		}
	}

echo $Navlink3.$Navlink2.$Navlink1;
}
function wpld_footer(){
	global $wpdb,$directory,$addurl;
	$wpld_addurlname = get_option('wpld_addurlname');
	$directory = get_option('wpld_dir_post_name');
	$addurl = get_option('wpld_addurl_post_name');
	
	echo '<div class="wpld_footer"><div class="footer_left"><a href="'.get_bloginfo('url').'/'.$directory.'/'.$addurl.'.html">'.$wpld_addurlname.'</a></div><div class="footer_right">Scripted by <a href="http://www.wplinkdir.com" title="Wordpress link directory">WPLinkDir</a></div></div>';
}
function wpld_update_template_meta($template) {
	global $wpdb,$post;

	$wpld_dir_id = get_option('wpld_dir_id');
	update_post_meta($wpld_dir_id, '_wp_page_template', $template);

	$wpld_categories=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$wpld_dir_id}'", ARRAY_N);
	
	foreach( $wpld_categories as $key => $value){
	
		$catID = $value[0];
	
		$wpld_subcategories=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$catID}'", ARRAY_N);
		
		update_post_meta($catID, '_wp_page_template', $template);
		
		if($wpld_subcategories!=NULL){
			foreach( $wpld_subcategories as $key1 => $value1){
			
				$SubcatID = $value1[0];
	
				$wpld_pages=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$SubcatID}'", ARRAY_N);
			
				update_post_meta($SubcatID, '_wp_page_template', $template);
				
				if ($wpld_pages != NULL) {
					foreach( $wpld_pages as $key2 => $value2){
				
						$PageID = $value2[0];
				
						update_post_meta($PageID, '_wp_page_template', $template);
					}
				}
			}
		}
	}
}

// redirect subscribers to the home page (WP 2.6.2)
function change_login_redirect($redirect_to, $request_redirect_to, $user) {
  if (is_a($user, 'WP_User') && $user->has_cap('edit_posts') === false) {
    return get_bloginfo('siteurl');    
  }
  return $redirect_to;
}
 
// add filter with default priority (10), filter takes (3) parameters
add_filter('login_redirect','change_login_redirect', 10, 3);

function wpld_disable_comments(){
	
	global $wpdb,$post;
	
		  $wpld_dir_id = get_option('wpld_dir_id');
		  echo $wpld_dir_id;
		  $wpld_cat_id = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$wpld_dir_id}'", ARRAY_N);
		
		  $my_post = array();  
		  $my_post['ID'] = $wpld_dir_id;
		  $my_post['comment_status'] = 'closed';
		  $my_post['ping_status'] = 'closed';

		  // Update the post into the database
		  wp_update_post( $my_post );
		  
		  foreach( $wpld_cat_id as $key => $value){
			$catID = $value[0];
			$wpld_subcategories=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$catID}'", ARRAY_N);
			
			$my_post = array();  
			$my_post['ID'] = $catID;
			$my_post['comment_status'] = 'closed';
			$my_post['ping_status'] = 'closed';

		    // Update the post into the database
		    wp_update_post( $my_post );
			
			
			if($wpld_subcategories!=NULL){
				foreach( $wpld_subcategories as $key1 => $value1){
					
					$SubcatID = $value1[0];
					$wpld_pages=$wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent='{$SubcatID}'", ARRAY_N);
					
					$my_post = array();  
					$my_post['ID'] = $SubcatID;
					$my_post['comment_status'] = 'closed';
					$my_post['ping_status'] = 'closed';

					// Update the post into the database
					wp_update_post( $my_post );
					
					if ($wpld_pages != NULL) {
						foreach( $wpld_pages as $key2 => $value2){
				
						$PageID = $value2[0];
						
						$my_post = array();  
						$my_post['ID'] = $PageID;
						$my_post['comment_status'] = 'closed';
						$my_post['ping_status'] = 'closed';

						// Update the post into the database
						wp_update_post( $my_post );
						
						}
					}
					
				}
			}
		}
}

function getUsersByRole( $role, $author ) {  
	if ( class_exists( 'WP_User_Search' ) ) {  
		$wp_user_search = new WP_User_Search( '', '', $role );  
		$userIDs = $wp_user_search->get_results();  
	} else {  
		global $wpdb;  
		$userIDs = $wpdb->get_col(' 
		SELECT ID 
		FROM '.$wpdb->users.' INNER JOIN '.$wpdb->usermeta.' 
		ON '.$wpdb->users.'.ID = '.$wpdb->usermeta.'.user_id 
		WHERE '.$wpdb->usermeta.'.meta_key = \''.$wpdb->prefix.'capabilities\' 
		AND '.$wpdb->usermeta.'.meta_value LIKE \'%"'.$role.'"%\' 
		');  
	}  
	
	foreach($userIDs as $userID){
		if($userID == $author) {
		return TRUE;
		}

	} 
}

function wpld_pending_links() {
	global $wpdb;
	 
	$Pendings = $wpdb->get_results("SELECT ID,post_author FROM $wpdb->posts WHERE post_status='pending'", ARRAY_A );
	
	$link_pending = array();
	
	foreach($Pendings as $Pending){
		$links_pending = getUsersByRole('link_author',$Pending['post_author']);
		if($links_pending == TRUE){
			$link_pending[] = array("ID" => $Pending['ID'],"post_author" => $Pending['post_author']);
			$ShowPendingLinks = TRUE;
		}

	}
}

function wpld_approved_links($postID) {
	global $wpdb;
	
	$wpld_users = get_wpld_users();
	$Approved = array();
	
	if ($wpld_users==TRUE){	
		foreach($wpld_users as $userID){
		
			$user = get_userdata($userID['ID']);
			if ($user['LinkParent']==$postID){
				$Approved[] = '"ID" => '.$userID['ID'];
			}
		}
	}
	$LinkCount = count($Approved);
	
	return $LinkCount;
}

function get_wpld_users() {
	//gets all users with specified role
	 
	$wp_user_search = new WP_User_Search($usersearch, $userspage, 'link_author');
	
	return $wp_user_search->get_results();
}

function wpld_approve_link($userID) {
	global 	$wpdb;
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

function wpld_users($filter){
global $wpdb;
			 $wpld_post_row = '
			 SELECT
			  ID, user_url,user_registered,display_name,user_email, 
			  LinkTitle.meta_value AS LinkTitle,
			  LinkStatus.meta_value AS LinkStatus, 
			  LinkCategory.meta_value AS LinkCategory,
			  LinkID.meta_value AS LinkID,
			  LinkRank.meta_value AS LinkRank,
			  LinkShortDesc.meta_value AS LinkShortDesc,
			  LinkDescription.meta_value AS LinkDescription,
			  LinkTags.meta_value AS LinkTags,
			  '.$wpdb->prefix.'capabilities.meta_value AS '.$wpdb->prefix.'capabilities
			 FROM
			  '.$wpdb->users.' wpusers
			  JOIN '.$wpdb->usermeta.' AS '.$wpdb->prefix.'capabilities ON ('.$wpdb->prefix.'capabilities.user_id = ID)
			   AND '.$wpdb->prefix.'capabilities.meta_key="'.$wpdb->prefix.'capabilities"
			  LEFT JOIN '.$wpdb->usermeta.' AS LinkStatus ON (LinkStatus.user_id = ID) 
			   AND LinkStatus.meta_key = "LinkStatus"
			  LEFT JOIN '.$wpdb->usermeta.' AS LinkCategory ON (LinkCategory.user_id = ID) 
			   AND LinkCategory.meta_key = "LinkCategory"
			  LEFT JOIN '.$wpdb->usermeta.' AS LinkTitle ON (LinkTitle.user_id = ID) 
			   AND LinkTitle.meta_key = "LinkTitle"
			  LEFT JOIN '.$wpdb->usermeta.' AS LinkID ON (LinkID.user_id = ID) 
			   AND LinkID.meta_key = "LinkID"
			  LEFT JOIN '.$wpdb->usermeta.' AS LinkRank ON (LinkRank.user_id = ID) 
			   AND LinkRank.meta_key = "LinkRank"
			  LEFT JOIN '.$wpdb->usermeta.' AS LinkShortDesc ON (LinkShortDesc.user_id = ID) 
			   AND LinkShortDesc.meta_key = "LinkShortDesc"
			  LEFT JOIN '.$wpdb->usermeta.' AS LinkDescription ON (LinkDescription.user_id = ID) 
			   AND LinkDescription.meta_key = "LinkDescription"
			  LEFT JOIN '.$wpdb->usermeta.' AS LinkTags ON (LinkTags.user_id = ID) 
			   AND LinkTags.meta_key = "LinkTags"
			 WHERE
			  '.$wpdb->prefix.'capabilities.meta_value LIKE "%link_author%"
			 ORDER BY '.$filter;
			 $wpld_users = $wpdb->get_results($wpld_post_row, ARRAY_A);
			 
			 $linkID = array();
			 foreach ($wpld_users as $links){
			  if($links['LinkStatus']=='pending'){
			   $linkID[] = $links; 
			  }
			 }
			 $wpdb->show_errors();
			 return $wpld_users;

}
function wpld_checkSpam ($content) {

	// innocent until proven guilty
	$isSpam = FALSE;

	$content = (array) $content;

	if (function_exists('akismet_init')) {

		$wpcom_api_key = get_option('wordpress_api_key');

		if (!empty($wpcom_api_key)) {

			global $akismet_api_host, $akismet_api_port;

			// set remaining required values for akismet api
	        $content['user_ip']    = $_SERVER['REMOTE_ADDR'];
			$content['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$content['referrer'] = $_SERVER['HTTP_REFERER'];
			$content['blog'] = get_option('home');
	        $content['blog_lang']  = get_locale();
	        $content['blog_charset'] = get_option('blog_charset');
	        $content['permalink']  = get_permalink($comment['comment_post_ID']);
	        $content['user_role'] = akismet_get_user_roles($comment['user_ID']);


			if (empty($content['referrer'])) {
				$content['referrer'] = get_permalink();
			}

			$queryString = '';
			
			foreach ($content as $key => $data) {
				if (!empty($data)) {
					$queryString .= $key . '=' . urlencode(stripslashes($data)) . '&';
				}
			} 

			$response = akismet_http_post($queryString, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);

			if ( 'true' == $response[1] ) {
				update_option('akismet_spam_count', get_option('akismet_spam_count') + 1);
				$isSpam = TRUE;
			}
			
		}

	}

	return $isSpam;

}
?>
