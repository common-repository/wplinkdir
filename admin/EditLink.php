<?php
$postIDx=explode('-',$_GET['postID']);

if ($postIDx[0]=='pending'){
 $postID = 'pending';
}else{
 $postID = $_GET['postID'];
 $post_info = get_post($postID);
}

$userID = $_GET['userID'];

	foreach ($wpld_users as $user){
		if($user['ID']==$userID){
			$wpld_user = $user; 
		 }
	}

$sqlGetCats = "SELECT * FROM $wpdb->posts WHERE post_parent='{$PluginID}' AND post_status = 'publish' ORDER BY post_title ASC";
$getCats = $wpdb->get_results($sqlGetCats, ARRAY_A);

	$linkID = $wpld_user['ID'];
	$linkPR = $wpld_user['LinkRank'];
	$linkURL = $wpld_user['user_url'];
	$linkPENDING = $wpld_user['LinkStatus'];
	$linkTITLE = $wpld_user['LinkTitle'];
	$linkCATEGORY = $wpld_user['LinkCategory'];
	$linkSHORTDESC = $wpld_user['LinkShortDesc'];
	$linkDESCRIPTION = $wpld_user['LinkDescription'];
	$linkTAGS = $wpld_user['LinkTags'];
	//$linkPREMIUM = $wpld_user['premium'];
	$linkEMAIL = $wpld_user['user_email'];
	$linkNAME = $wpld_user['display_name'];
	$linkDATE = $wpld_user['user_registered'];
	$linkUPDATED = $wpld_user['post_modified'];
	
	if($linkPREMIUM==1){
		$Premium=' SELECTED';
		$Normal='';
	}else{
		$Premium='';
		$Normal=' SELECTED';
	}

	if($linkPENDING=='pending'){
		$LinkPending=' SELECTED';
		$LinkApproved='';
	}elseif($linkPENDING=='publish'){
		$LinkPending='';
		$LinkApproved=' SELECTED';
	}


	echo '<form method="POST" action="'.$_SERVER['PATH_INFO'].'admin.php?page=WPLinkDir/directory.php" name="wplinkdir_UpdateLink">';
	
	if ($wp_version>'2.6.3'){ 
		settings_fields('wplinkdir-group');}
	else{
		wp_nonce_field('update-options');
	}
echo ' <table class="form-table">';	
echo ' <h2>'.$_EditLink.$value.'</h2>';
echo '<tbody>
		<tr>
			<td colspan="2">
				<img src="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/images/pr'.$linkPR.'.gif" style="padding: 0px; float: left; width: 40px; height: 14px;">
				<p style="margin: 0px; padding: 0px 5px; color: rgb(221, 221, 221);">
				<a href="'.$linkURL.'" style="margin: 0px; padding: 0px 5px 0px 15px; color: rgb(0, 0, 0); font-size: 18px;"><b>'.$linkURL.'</b></a>
				</p>
			</td>
			<td align="right">';
				if($linkPENDING=='pending'){
					echo '<p style="padding:0px 5px 0px 5px;color:red;font_size:13px;margin:0px;">'.$WPLD_Trans['Pending'].'</p>';
					
				}else{
					echo '<p style="padding:0px 5px 0px 5px;color:green;font_size:13px;margin:0px;">'.$WPLD_Trans['Approved'].'</p>';
				}
		echo'</td>
	    </tr>
		<tr>
			<td>
				Website Title:
				<br>
				<input name="wplinkdir_title" id="wplinkdir_title" value="'.$linkTITLE.'" type="text">
			</td>
			<td>
				Website URL:
				<br> 
				<input name="wplinkdir_url" id="wplinkdir_url" value="'.$linkURL.'" size="40" type="text">
			</td>
			<td>
				Category:
				<br>
				<select name="wplinkdir_category" id="wplinkdir_category">';
				wpld_sort_categories($getCats,$linkCATEGORY);
		  echo '</select>
			</td>
		</tr>
		<tr>
			<td>	
				Short description:
				<br>
				<textarea rows="2" cols="30" name="wplinkdir_shortdesc" id="wplinkdir_shortdesc">'.$linkSHORTDESC.'</textarea>
			</td>
			<td>
				Description:
				<br>
				<textarea rows="2" cols="30" name="wplinkdir_description" id="wplinkdir_description">'.$linkDESCRIPTION.'</textarea>
			</td>
			<td>
				Tags:
				<br>
				<textarea rows="2" cols="30" name="wplinkdir_tags" id="wplinkdir_tags">'.$linkTAGS.'</textarea>
			</td>
		<tr>
			<td>
				Premium link:
				<br>
				Status: <select name="wplinkdir_premium_link" id="wplinkdir_premium_link"><option value="0"'.$Normal.'>Normal</option><option value="1"'.$Premium.'>Premium</option></select>
			</td>
			<td>
				Checked:
				<br>
				Status: <select name="wplinkdir_pending" id="wplinkdir_pending"><option value="1" '.$LinkApproved.'>'.$WPLD_Trans['Approved'].'</option><option value="0" '.$LinkPending.'>'.$WPLD_Trans['Pending'].'</option></select>

			</td>
			<td>
				Email:
				<br>
				<input id="wplinkdir_entry_email" name="wplinkdir_entry_email" value="'.$linkEMAIL.'" size="40" type="text">
			</td>
		</tr>
		<tr style="background: rgb(221, 221, 221) none repeat scroll 0% 0%; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;">
			<td>
				Added By: '.$linkNAME.' &lt;<a href="mailto:'.$linkEMAIL.'">'.$linkEMAIL.'</a>&gt; '.$linkDATE.'<br>Updated at: '.$linkUPDATED.'<br></td>
				<input  type="hidden" value="'.$postID.'-'.$userID.'" name="wpld_updated_linkID" id="wpld_updated_linkID"/>
				<input  type="hidden" value="'.$postID.'-'.$userID.'" name="wpld_delete_linkID" id="wpld_delete_linkID"/>
				<input  type="hidden" value="'.$linkCATEGORY.'" name="wplinkdir_OrgCategory" id="wplinkdir_OrgCategory"/>
				<input  type="hidden" value="'.$userID.'" name="wpld_approve_link[]" id="wpld_approve_link"/>
				
  			<td colspan="2" align="right">';
			if($postID=='pending'){
			 echo '<input name="wpld_ApproveLink" id="wpld_ApproveLink" value="'.$_ApproveLink.'" type="submit">';
			}else{
		     echo '<input name="wpld_UpdateLink" id="wpld_UpdateLink" value="'.$_UpdateLink.'" type="submit">
				<input name="wpld_DeleteLink" id="wpld_DeleteLink" value="'.$_delete.'" type="submit">';
			}

	echo '</td>
		</tr>
	  </tbody>
	</table></form><br>';
?>