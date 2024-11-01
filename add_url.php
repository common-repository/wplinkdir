<?php
 	
 	$Tags=get_option('wpld_htmltags');  //Option HTML tags allowed......
	$ShortDescSize=get_option('wpld_ShortDescSize_size');  //Option Short description.........
	$DescriptionSize=get_option('wpld_description_size');  //Option Long Descriptions.......
	$recip_requirement=get_option('wpld_recip_requirement'); //Option Reciprocal link requirement.......
	$ExtraFields=get_option('wpld_extrafields'); //Option  Extra fields
	
	$wpld_dir_id = get_option('wpld_dir_id');

	$parts=explode('-',$wpdb->escape($_POST['wpld_parent']));
	$LinkParent=$parts[0];
	$LinkCat=$parts[1];
	
	$errors = array();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


		
	$FormCheck = array();
		
	$FormCheck['Name'] = $_POST['wpld_entry_name'];  
	$FormCheck['Email'] = $_POST['wpld_entry_email'];
	$FormCheck['URL'] = $_POST['wpld_entry_url']; 
	$FormCheck['Title'] = $_POST['wpld_entry_title'];
	$FormCheck['Category'] = $_POST['wpld_parent']; 
	$FormCheck['ShortDescription'] = strip_tags($_POST['wpld_entry_shortdesc'],$Tags); 
	$FormCheck['Description'] = strip_tags($_POST['wpld_entry_description'],$Tags);
	$FormCheck['Tags'] = $_POST['wpld_entry_tags'];
	
     // Fields that are on form  
     $expected = array('name', 'email', 'comments');  
     // Set required fields  
     $required = array('name', 'comments');  
    // Initialize array for errors  
     //$errors = array(); 
	  
$formChecked = wpld_FormCheck($FormCheck);
	
	//Find errors and put them in an array
	//$errors = array();
			
	foreach ($formChecked as $key => $value) {
		if ($value[1] == NULL){
			$errors[] = $key;
		}
	}
	
	 if (empty($errors)){ 
	 
	 require_once(ABSPATH . WPINC . '/registration.php');
	 
	 $LinkTitle = htmlspecialchars($formChecked['title'][0]); 
	 $LinkName =  $formChecked['name'][0]; #
	 $LinkEmail =  $formChecked['email'][0]; #
	 $LinkURL =  $formChecked['url'][0]; #
	 $LinkShortDesc = htmlspecialchars($formChecked['shortdescription'][0]); #
	 $LinkDescription = htmlspecialchars($formChecked['description'][0]); #
	 $LinkTags = htmlspecialchars($formChecked['tags'][0]); #
	 
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
      
	 //Check if spam or not........
     $LStatus = array();
	 
	 $LStatus['comment_post_ID'] = $wpld_dir_id;  
	 $LStatus['comment_author'] = $LinkName;
	 $LStatus['comment_author_email'] = $LinkEmail;
	 $LStatus['comment_author_url'] = $LinkURL;
	 $LStatus['comment_content'] = $LinkDescription;
	 $LStatus['comment_type'] = '';
	 $LStatus['comment_parent'] = '0';
	  
 
	if (wpld_checkSpam ($LStatus)) {
		$LinkStatus = 'spam';
	 }else{
		$LinkStatus = 'pending';
	 }
	  
	 update_usermeta( $user_id, 'LinkTags', $LinkTags);
	 update_usermeta( $user_id, 'LinkParent', $LinkParent);
	 update_usermeta( $user_id, 'LinkShortDesc', $LinkShortDesc);
	 update_usermeta( $user_id, 'LinkDescription',$LinkDescription);
	 update_usermeta( $user_id, 'LinkRank', $Rank);
	 update_usermeta( $user_id, 'LinkDate', $time);
	 update_usermeta( $user_id, 'LinkStatus', $LinkStatus);
	 update_usermeta( $user_id, 'LinkCategory',$LinkCat);
	 update_usermeta( $user_id, 'LinkTitle', $LinkTitle);
	 

		
		echo sprintf(__('Your link (%s) has been added successfully, thanks!',$WPLD_Domain),$LinkTitle);
		
		
		// Email Admin New Link Details (If Selected)
		if(get_option('wpld_emailme')=='Yes'){
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From:'.get_option('wpld_email_address') . "\r\n";
			$BlogName=get_option('blogname');
			$SiteURL=get_option('siteurl');
			$Subject=sprintf(__('%s - New Link Added',$WPLD_Domain),$BlogName);
			mail(get_option('wpld_email_address'),$Subject,sprintf(__('This is just a quick message to let you know that someone has added a link to your link directory on %1$s (%2$s).'."\n\n".'Link Name: %3$s (PR %4$s)'."\n".'Site URL: %5$s'."\n\n".'You can edit or delete this link by logging into your admin section. You can turn off email updates by turning the Email Me option in the admin section.',$WPLD_Domain),$BlogName,$SiteURL,$LinkTitle,$Rank,$LinkURL),$headers);
		}	  
         unset($errors);  
 	 }else{
	 	$fail = true;
	 }

}else{
		$new = true;
	}
	
if ($fail == true or $new == true){

echo '<div class="add_title"><img src="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/images/add_icon.png" alt="" /> Add your page:</div>';
echo '<div class="add_text">
		Our directory is an easy way to gain higher ranking for your page.
		<br /><br />
		Link popularity is fast becoming one of the highest weighted criteria used in ranking your site in the search engines.
		The more related sites linking to yours the higher your sites ranking climbs in the search engine results. 
		<br /><br />
		We like to link with sites that complement ours or can offer our clients a valid service. We will not exchange links 
		with any investment site that is unduly offensive or fraudulently boastful in its claims. 
	</div>						
	<div class="add_title"><img src="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/images/add_icon2.png" alt="" /> How do I add my site:</div>
	<div class="add_text">

		Its easy to use our service you just follow the instructions below.
		<br /><br />
		Add our URL to your page using this HTML code:
		<br /><br />							
		<div class="add_code">'.get_option('wpld_htmlcode').' - SEO friendly link directory
        </div>
		<br />							
		<strong>This will generate a link like this:</strong>
		<br />
		<a class="add_example_link" href="http://www.Wplinkdir.com">Wplinkdir.com</a> - SEO friendly link directory
	</div>						
	Enter the required information below. We willll review your site as soon as possible, validate the reciprocal link on 
	your site and if we feel that your site provide good complimentary content, we will active your link. We will notify 
	you by e-mail when your link has been added.
	<br /><br />						
	Please complete the form below. Mandatory fields marked <span class="red">*</span>
<div class="add_info_container">';

echo "<form  action=\"".$_SERVER['URI']."\" id=\"Form\" method=\"post\" >      \n"; 
/*if ($wp_version>'2.6.3'){ 
			settings_fields('wplinkdir-group');}
		else{
			wp_nonce_field('update-options');
		}
		*/
// Form Name 
echo '<div class="add_info_col">
		<label for="domain" title="Enter your name!">';
	if(in_array("name",$errors)){
		echo '<span class="red"><strong>Your Name</strong>*</span>';}
	else{ echo '<strong>Your Name</strong><span class="red">*</span>';}
  echo '</label>
  		<input id="wpld_entry_name" name="wpld_entry_name" value="';
	if (isset($errors)) { 
		echo $formChecked['name'][0]; 
	}
echo '" maxlength="128" type="text" />';

  echo '<br /><br />';
// Form Url
  echo '<label for="url" title="Enter your URL here and start with http//:">';
	if(in_array("url",$errors)){
		echo '<span class="red"><strong>Website URL</strong>*</span>';}
	else{ echo '<strong>Website URL</strong><span class="red">*</span>';}
  echo '</label>
        <input id="wpld_entry_url" name="wpld_entry_url" value="';
	if (isset($errors)) { 
		echo $formChecked['url'][0]; 
	}else{
		echo 'http://';
	}
echo '" maxlength="128" type="text" />';
	
  echo '<br /><br />';  
// Form Category
  echo '<label for="wpld_parent" title="Pick a category that fits for your link!">';
	if(in_array("category",$errors)){
		echo '<span class="red"><strong>Category</strong>*</span>';}
	else{ echo '<strong>Category</strong><span class="red">*</span>';}
  echo '</label>
		<select id="wpld_parent" name="wpld_parent" >'; 

	//GET Categories.....
	$sql = "SELECT * FROM $wpdb->posts WHERE post_parent='{$wpld_dir_id}' AND post_status = 'publish' ORDER BY post_title ASC";
	$getCats = $wpdb->get_results($sql, ARRAY_A);

	$Mode!='Display';
	wpld_sort_categories($getCats,$linkCATEGORY);
	
  echo '</select>';

  echo '<br /><br />'; 
// Form Short Description
  echo '<label for="wpld_entry_shortdesc" title="Give a short description of your homepage with maximum 50 words!">';
	if(in_array("shortdescription",$errors)){
		echo '<span class="red"><strong>Short Description</strong>*</span>';}
	else{ echo '<strong>Short Description</strong><span class="red">*</span>';}
  echo '</label>
        <textarea onkeydown="limitText(this,30);" onkeyup="limitText(this,30);" name="wpld_entry_shortdesc" id="wpld_entry_shortdesc" rows="2">';
	if (isset($errors)) { 
		echo $formChecked['shortdescription'][0]; 
	}
  echo '</textarea>';
  echo '<br /><br />';
 
// Form Tags 
  echo '<label for="wpld_entry_tags" title="Enter keywords to use and each keyword separated with &#8221; &#8218; &#8221; example: green,red,blue !">';
	if(in_array("tags",$errors)){
		echo '<span class="red"><strong>Keywords</strong>*</span>';}
	else{ echo '<strong>Keywords</strong><span class="red">*</span>';}
  echo '</label>
  <input id="wpld_entry_tags" name="wpld_entry_tags" value="';
	if (isset($errors)) { 
		echo $formChecked['tags'][0]; 
	}
echo '" maxlength=\"128\" type=\"text\" />';
  echo '<br /><br />';
  
// Form Captcha
//echo '<strong>Captcha</strong><span class="red">*</span>';

echo ' </div><div class="add_info_col">';

// Form Email  
  echo '<label for="email" title="Enter an valid email address!">';
	if(in_array("email",$errors)){
		echo '<span class="red"><strong>Your Email</strong>*</span>';}
	else{ echo '<strong>Your Email</strong><span class="red">*</span>';}
  echo '</label>
      <input id="wpld_entry_email" name="wpld_entry_email" value="';
	if (isset($errors)) { 
		echo $formChecked['email'][0]; 
	}
echo '" maxlength="128" type="text" />';

  echo '<br /><br />';
// Form Title
  echo '<label for="title" title="Enter title you like to use for your link!">';
	if(in_array("title",$errors)){
		echo '<span class="red"><strong>Website Title</strong>*</span>';}
	else{ echo '<strong>Website Title</strong><span class="red">*</span>';}
  echo '</label>
        <input id="wpld_entry_title" name="wpld_entry_title" value="';
	if (isset($errors)) { 
		echo $formChecked['title'][0]; 
	}
echo '" maxlength="128" type="text" />';
		
  echo '<br /><br />';
 // Form Description 
  echo '<label for="wpld_entry_description" title="Describe your homepage with minimum of 250 words!">';
	if(in_array("description",$errors)){
		echo '<span class="red"><strong>Description</strong> (HTML tags allowed)*</span>';}
	else{ echo '<strong>Description</strong> (HTML tags allowed)<span class="red">*</span>';}
  echo '</label>
        <textarea onkeydown="limitText(this,30);" onkeyup="limitText(this,30);" name="wpld_entry_description" id="wpld_entry_description" rows="10">';
	if (isset($errors)) { 
		echo $formChecked['description'][0]; 
	}
  echo '</textarea>';

echo '</div>
	  <div class="clear"></div>
	</div>
	<input type="image" src="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/images/add_info_button.png" alt="Add Link" id="submit" value="Submit" type="submit" />
	</form>';
	  
         unset($errors);
}
?>