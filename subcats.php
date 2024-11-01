<?php

###### CATEGORIES ##############################################################################

	// Display all the categories


	$DetailedInfo = get_option('wpld_extended_info');
	$wpld_dir_id = get_option('wpld_dir_id');
	
###### SUBCATS ######################################################################################	

	
	$sql = "SELECT * FROM $wpdb->posts WHERE ID='{$id}' AND post_status = 'publish'";
	$getCat = $wpdb->get_results($sql, ARRAY_A);
	
		foreach($getCat as $CatPretty){
		
			$Cat=$CatPretty['post_title'];
			$Desc=$CatPretty['description'];
			$Parent=$CatPretty['post_parent'];
			$CatID = $CatPretty['ID'];
			$CatURL = $CatPretty['post_name'];
		}

	$ThisURL=preg_replace('/[&\?]flag\=[0-9]+/','',$_SERVER['REQUEST_URI']);

		if(!$Order=get_option('wpld_orderby')){
			$Order=',pr DESC';
		}else{
			$Order=','.$Order;
		}
	
	$result=mysql_query("SELECT * FROM $wpld_cats WHERE id = '{$Parent}'");
	while($getCategory = mysql_fetch_array($result)) {
	$Category = $getCategory['title_pretty'];
	}
	if($Category==$CatURL or $Category===NULL ) {
	$CategoryName = '/';
	}else{
	$CategoryName = '/'.$Category.'/';
	}
	$getLinks = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_value = '{$Cat}' ", ARRAY_A);
	
	$getPremiumLinks=mysql_query("SELECT * FROM $wpld_links WHERE category = '{$Cat}' AND pending = 1 AND premium = 1 ORDER BY premium{$Order}");
	
	echo '<div class="results_title"><h2>'.$Cat.($ShowNums == 'Yes' ? ' ('.wpld_LinkCount($CatID).')' : '').'</h2></div>'.$Desc;

		// Display sub-categories
		if(mysql_num_rows(wpld_SubCatCheck($CatID))>0){
		echo '
<div class="results_sub">
		';
			wpld_getSubCats_Link($CatID,$CatURL);
		echo '							<div class="clear"></div>

</div>						<br /><br />

		';
		}else{
		echo '<div class="results_sub"><br />
		</div>						<br /><br />';
		}

		if(get_option('wpld_nofollow')=='Yes'){
			$NoFollow=' rel="nofollow"';
		}else{
			$NoFollow='';
		}
				
	// Show Premium Links start here


			if (@mysql_num_rows($getPremiumLinks)>0) {
		
				echo '
<div  class="Premium_links">
<p>Premium '.$Cat.' results</p>';

				while($Link=@mysql_fetch_assoc($getPremiumLinks)){

					if(get_option('wplinkdir_nofollow')=='Yes'){
						$NoFollow=' rel="nofollow"';
					}else{
						$NoFollow='';
					}

					if($DetailedInfo!=''){
						$DetailedLink=' - <i><a href="'.get_bloginfo('url').'/'.$directory.'/'.$name.'/'.$Link['cat_pretty'].$FILExt.'">'.$DetailedInfo.'</a></i>';
					}

echo '
<div  class="Premium_links_1">
<img src="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/images/pr'.$Link['pr'].'.gif">
<p>'.$PageRank.'
<a target="'.$Target.'" href="'.$Link['url'].'"'.$NoFollow.'>'.stripslashes($Link['title']).'</a>
 - '.$Link['description'].$DetailedLink.'</p>
</div>
';
		
				}
echo '</div>';
			}
		
	//Show normal links starts here
			
		if (count($getLinks)>0) {
		echo '
<div class="results_head"><img src="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/images/results_icon.png" alt="" />  Results for '.$Cat.'</div><br />';
    
	 
			foreach ($getLinks as $links => $link) {
			
				$user_id = $link['user_id'];
				$user_info = get_userdata($link['user_id']);

					if(get_option('wplinkdir_nofollow')=='Yes'){
						$NoFollow=' rel="nofollow"';
					}else{
						$NoFollow='';
					}

					if($user_info->LinkDescription!=''){
						$DetailedLink=' - <a href="'.get_bloginfo('url').'/'.$directory.$CategoryName.$name.'/'.$user_info->LinkPost_name.$FILExt.'">'.$DetailedInfo.'</a>';
					}

echo '
<div class="results_item">
<img src="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/images/pr'.$user_info->LinkRank.'.gif">
<strong>'.stripslashes($user_info->LinkTitle).'</strong><br />
'.$user_info->LinkShortDesc.$DetailedLink.'
</div>';
			
			
			}
		}




?>