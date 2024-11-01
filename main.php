<?php
global $post,$wpdb,$FILExt,$PluginName,$addurl,$WPLD_Trans,$PLinks;

		$id = $post->ID;
		$title = $post->post_title;
		$name = $post->post_name;
		$parent = $post->post_parent;
		$author = $post->post_author;
		
		$link_author = getUsersByRole('link_author',$author);
	
	$wpld_cats = WPLD_CATS;
	$wpld_links = WPLD_LINKS;
		
	$ShowNums=get_option('wpld_show_numbers');
	$wpld_addurlname = get_option('wpld_addurlname');
	$wpld_show_navbar = get_option('wpld_show_navbar');
	$directory = get_option('wpld_dir_post_name');
	$addurl = get_option('wpld_addurl_post_name');
    $wpld_dir_author = get_option('wpld_dir_author');


switch($name) :
	case $directory:
		define('THIS_PAGE', 'index');
	break;
	case $wpld_addurlname:
		define('THIS_PAGE', 'addurl');
	break;
	
	case ($CatName = $name):
		define('THIS_PAGE', 'index');
endswitch;



	$Target=get_option('wplinkdir_target');

echo '
<!-- WPLinkDir -->

<div id="wplinkdir">';
echo '<div class="wpld_navbar"><div class="wpld_navbar_left">';
wpld_navbar();
echo '</div>';
if ($wpld_show_navbar == 'Yes'){
echo '<div class="wpld_navbar_right"><a href="'.get_bloginfo('url').'/'.$directory.'/'.$addurl.'.html">'.$wpld_addurlname.'</a></div>';
}
echo '</div><div class="clear"></div>';
	# Now figure out which page the user is viewing and display it
	
	# Display addsite page start here
	
	if($name == $directory){
	
		
		include 'categories.php'; 
		

	}
	
	if($name == $addurl){
	
		echo '<div  class="add_container">';
		
		include 'add_url.php';
		 
		echo '</div>';

	}
	
	if($name == wpld_CatNameCheck($name)){
	
		echo '<div class="results_container">';
		
		include 'subcats.php';
		
		echo '</div>';

	}
	
	
	if($link_author == TRUE && $author != $wpld_dir_author){
		
	
		include 'pages.php';
		

	}
		
    else {
	
	# Categories start here.........
	
		if(!isset($wpld_cat) && !isset($wpld_id) && !isset($wpld_act)){
		
		}
		
	
	# Display links start here.......
		if(isset($wpld_cat)){
		
			echo '<div id="wplinkdir_links" align="center">';
	
			echo '</div>';
		}
	
	# Display requested link start here
	
		if($wpld_id){
		
			echo '<div id="wplinkdir_requested_link" align="center">';
		
			echo '</div>';
		
		}
	}
	# Footer start here.........
	echo '<div class="clear"></div>';
	wpld_footer();
	
	echo '</div>';
	echo '<!-- WPLinkDir -->';
		
?>