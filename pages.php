<?php
    	$wpld_dir_id = get_option('wpld_dir_id');
		
		$authorID = $post->post_author;
		$author_info = get_userdata($authorID);
		
		function wpld_get_post_data($postId) {
			global $wpdb;
			return $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=$postId");
		}
		
		$parent_info = wpld_get_post_data($post->post_parent);
		
		$parent = $parent_info[0]->post_parent;
		$parentname = $parent_info[0]->post_name;
		
		$parent_info2 = wpld_get_post_data($parent);
		
		$parentname2 = $parent_info2[0]->post_name;
		
		if($parent == $wpld_dir_id){
			$CategoryName = '/'.$parentname;
		}else{
			$CategoryName = '/'.$parentname2.'/'.$parentname;
		}
		
	
		if($PLinks == 'Yes'){
			$CatLink=get_bloginfo('url').'/'.$directory.$CategoryName.$PrettyTitle;
		}else{
			$Temp=explode('id=',$_SERVER['REQUEST_URI']);
			$CatLink=$Temp[0].$Ex.'cat='.urlencode($Info['category']);
		}
		
		echo '
	<div  class="submission_container">
		<div class="submission_image">
			<img src="http://open.thumbshots.org/image.aspx?url='.$author_info->user_url.'">
		</div>
		<div class="submission_text">
			<strong><a href="'.$author_info->user_url.'">'.$post->post_title.'</a></strong><br />'
			.$author_info->LinkDescription.
			' <span><a href="'.$author_info->user_url.'">'.$author_info->user_url.'</a></span>
		</div>						
		<div class="clear"></div>						
		<div class="submission_added">							
			<img src="'.get_option('home').'/images/submission_plus.png" alt="" /> '.__(' Link Added ',$WPLD_Domain).$author_info->LinkDate.__(' by ',$WPLD_Domain).$author_info->display_name.' in Category: <a href="'.$CatLink.$FILExt.'">'.$author_info->LinkCategory.'</a>
		</div>						
		<div class="submission_info">
			<div class="submission_info_left">								
				<p><span>'.__('Cached Pages:',$WPLD_Domain).' </span> <a target="_blank" href="http://www.google.com/search?ie=UTF-8&q=site:'.$author_info->user_url.'">Google</a>, <a target="_blank" href="http://search.yahoo.com/search?p=site:'.$author_info->user_url.'">Yahoo!</a>, <a target="_blank" href="http://search.msn.com/results.aspx?q=site:'.$author_info->user_url.'">MSN</a>, <a target="_blank" href="http://www.altavista.com/web/results?q=site:'.$author_info->user_url.'">Altavista</a></p>';
		  
		$AlexaLink=str_replace('http://','',str_replace('www.','',strtolower($author_info->user_url)));
		$Pieces=explode('.',$AlexaLink);
		$TLDPos=count($Pieces)-1;
		$DomainPos=$TLDPos-1;
		$TLD=$Pieces[$TLDPos];
		$Domain=$Pieces[$DomainPos];
		
		  echo '<p><span>'.__('Other Links:',$WPLD_Domain).'</span> <a target="_blank" href="http://www.alexa.com/data/details/main/'.$AlexaLink.'">Alexa</a>, <a target="_blank" href="http://whois.net/whois_new.cgi?d='.$Domain.'&tld='.$TLD.'">Whois.net</a></p>
				<br class="clear" />
				<p><span>'.__('Backlinks:',$WPLD_Domain).'</span> <a target="_blank" href="http://www.google.com/search?ie=UTF-8&q=link:'.$author_info->user_url.'">Google</a>, <a target="_blank" href="http://search.yahoo.com/search?p=link:'.$author_info->user_url.'">Yahoo!</a>, <a target="_blank" href="http://search.msn.com/results.aspx?q=link:'.$author_info->user_url.'">MSN</a>, <a target="_blank" href="http://www.altavista.com/web/results?q=links:'.$author_info->user_url.'">Altavista</a></p>
			</div>							
			<div class="submission_info_right">
				<img src="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/images/pr-'.$author_info->LinkRank.'.gif" alt="" />
			</div>
			<div class="clear"></div>						
		</div>
	</div>';
?>