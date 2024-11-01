<?php
/*
Plugin Name: WPLinkDir
Plugin URI: http://www.wplinkdir.com/demo
Description: Make your wordpress blog to a powerful SEO tool. WPLinkDir is a wordpress link directory plugin devloped and optimized to gain success with search engines. 
Author: Gustavo Lopez
Version: 1.3.2
Author URI: http://www.wplinkdir.com

Copyright 2010  Gustavo Lopez  (email : gustavo@wplinkdir.com)

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $wpdb,$post;

##$directory = get_option('wpld_dir_post_name'); ###'wplinkdir2'; ####### wplinkdir-pro
$FILExt = '.html';
$PluginName = 'WPLinkDir'; #######  WPLinkDir_pro
##$addurl = get_option('wpld_addurl_post_name');###### 'addurl';
$languages = 'english';
$PLinks = 'Yes';
$subcategories = 'Display';
#$DetailedInfo = 'More....';
$paypal = 'No';
$wpld_categories = get_option('wpld_categories');
include 'functions.php';
include 'languages/'.$languages.'.php';

register_activation_hook(__FILE__, 'wpld_init');


define("WPLD_CATS",$wpdb->prefix."wpld_cats");
define("WPLD_LINKS",$wpdb->prefix."wpld_links");



$wpld_version = get_option('wpld_version');


add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {
global $paypal;
    // Add a new top-level menu (ill-advised):
    add_menu_page('WP Link Directory', 'WPLinkdir', 8, __FILE__, 'wplinkdir_main'); 
   // add_menu_page('WP Link Directory', 'WPLinkdir', 8, __FILE__, 'wplinkdir_create');   
	
	// Add a submenu to the custom top-level menu:
    add_submenu_page(__FILE__, 'Categories', 'Categories', 8, 'categories', 'wplinkdir_categories');    
	
	// Add a submenu to the custom top-level menu:
    add_submenu_page(__FILE__, 'Add Link', 'Add link', 8, 'links', 'wplinkdir_links');
	
	if ($paypal == 'Yes'){
	// Add a submenu to the custom top-level menu:
    add_submenu_page(__FILE__, 'Paypal','Paypal', 8, 'paypal', 'wplinkdir_paypal');
	}
	
	// Add a submenu to the custom top-level menu:
    add_submenu_page(__FILE__, 'Settings','Settings', 8, 'settings', 'wplinkdir_settings');
}

// mt_toplevel_page() displays the page content for the custom Test Toplevel menu
function wplinkdir_main() {

		 //include 'admin/add_dir.php';
		 include 'admin/main.php';
}

// mt_sublevel_page() displays the page content for the first submenu
// of the custom Test Toplevel menu
function wplinkdir_categories() {

		 include 'admin/categories.php';
}
function wplinkdir_links() {

		 include 'admin/links.php';
}
function wplinkdir_settings() {

		 //include 'admin/edit_link.php';
		 include 'admin/settings.php';
}
function wplinkdir_paypal() {

		 //include 'admin/edit_link.php';
		 include 'admin/settings.php';
}

function shortcode_wplinkdir() {

		  include 'main.php';
		  
}

add_shortcode('wplinkdir', 'shortcode_wplinkdir');
add_role( 'link_author', 'Link Author', array( 'organize_links'  ) );

add_action('init', 'html_page_permalink', -1);


function html_page_permalink() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
		add_option('wpld_test_permalinks','html_page_permalink');
	}
	
}
add_filter('user_trailingslashit', 'no_page_slash',66,2);

function no_page_slash($string, $type){
   global $wp_rewrite;
	if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page'){
		return untrailingslashit($string);
	}else{
		return $string;
	}
}

function wpld_headmeta() {
	global $posts,$PluginName;
	
	//Get css link to wplinkdir plugin
	$style=get_option('wpld_style');
    echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl').'/wp-content/plugins/'.$PluginName.'/styles/'.$style.'" />' . "\n";

	$plugins = get_option('active_plugins');
	$required_plugin = 'all-in-one-seo-pack/all_in_one_seo_pack.php';
	$plugins_on = FALSE;

	if ( in_array( $required_plugin , $plugins ) ) {
	$plugins_on = TRUE;
	}

	if ($plugins_on == FALSE) {
	// only act when viewing a single post or a page. Else, exit.
	if ( !(is_single() || is_page() ) ) return;
	
	// Get the post
	$post = $posts[0];
	
	// Get the keys and values of the custom fields:
	$id = $post->ID;
	$description = get_post_meta($id, 'description', false);
	//$linkvals = get_post_meta($id, 'head_link', false);

	// A key of either 'keyword' or 'keywords' will generate a 
	// standard 'keywords' meta tag. Both variants are used for
	// compatibility with other plugins, such as Related Posts
	$keywords = get_post_meta($id, 'keywords', false);
	// Generate the tags
	if ($description==NULL) {
			$tag = '';
		}else{
			foreach ($description as $value) {
			$tag = "<meta  name=\"description\" content=\"$value\" />";
		}
			print "$tag\n";
	}
	
	if ($keywords==NULL) {
			$tag = '';
		}else{
			foreach ($keywords as $keys) {
			
			$tag = "<meta name=\"keywords\" content=\"$keys\"/>";
			}
		print "$tag\n";
	}
	}

}
// Hook into the Plugin API
add_action('wp_head', 'wpld_headmeta');

function wpld_upgrade($Version){
	global $wpdb;	
	include ('updates.php');
}

function wpld_init() {

	// The installation function used to set up and activate WP Link Directory. It may also make a call to wpld_upgrade() above.

global $wpdb;

	$wpld_cats = WPLD_CATS;
	
	$Version=get_option('wpld_version');

	// Check for existing installation and run upgrade script if found else create tables and options
	if($Version){
		//wpld_upgrade($Version);
		include ('updates.php');
	}else{
		
		$wpdb->query("CREATE TABLE $wpld_cats (
			`id` INT NOT NULL AUTO_INCREMENT ,
			`title` VARCHAR( 250 ) NOT NULL ,
			`title_pretty` VARCHAR( 250 ) NOT NULL ,
			`parent` VARCHAR( 250 ) NOT NULL ,
			`description` VARCHAR( 250 ) NOT NULL,
			UNIQUE KEY id (id))");
			
		
		$TempTitle=ucfirst(str_replace('www.','',$_SERVER['HTTP_HOST']));	
		add_option('wpld_exist','install');
		add_option('wpld_version','1.3.2');
		add_option('wpld_dir_name','WPlinkDir');
		add_option('wpld_dir_post_name','wplinkdir');
		add_option('wpld_addurlname','Add your URL');
		add_option('wpld_addurl_post_name','add-your-url');
		add_option( 'wpld_default_categories', 'disable' );
		add_option('wpld_dir_id','');
		add_option('wpld_addurl_id','');
		add_option('wpld_template_enable','');
		add_option('wpld_template','wplinkdir.php');
		add_option('wpld_show_numbers','Yes');
		add_option('wpld_show_navbar','Yes');
		add_option('wpld_orderby','date_added DESC');
		add_option('wpld_nofollow','Yes');
		add_option('wpld_extended_info','Read More');
		add_option('wpld_recip_requirement','Nobody');
		add_option('wpld_htmltags','<b><i><u>');
		add_option('wpld_htmlcode','&lt;a href=&quot;http://www.'.$TempTitle.'&quot;&gt;'.$TempTitle.'&lt;/a&gt; - '.get_option('blogdescription'));
		add_option('wpld_extrafields','Optional');
		add_option('wpld_subdomains','No');
		add_option('wpld_ShortDescSize_size','150');
		add_option('wpld_description_size','500');
		add_option('wpld_emailme','No');
		add_option('wpld_email_address','my@email.com');
		add_option('wpld_captcha','No');
		add_option('wpld_premium','No');
		add_option('wpld_style','default.css');
		add_option('wpld_default_categories','');
		add_option('wpld_description','Home Meta Description (used by Another Wordpress Meta Plugin).');
		add_option('wpld_keywords','Home Meta Keywords (used by Another Wordpress Meta Plugin).');
		add_option('wpld_comments_enable','');
		
		
		if ($wp_version>'2.6.3'){

			function myplugin_admin_init(){		
		
			register_setting( 'wplinkdir-group', 'wpld_your_url', '' );
			register_setting( 'wplinkdir-group', 'wpld_permalinks', '' );		
			register_setting( 'wplinkdir-group', 'wpld_recip_requirement', '' );		
			register_setting( 'wplinkdir-group', 'wpld_htmlcode', '' );		
			register_setting( 'wplinkdir-group', 'wpld_htmltags', '' );		
			register_setting( 'wplinkdir-group', 'wpld_orderby', '' );		
			register_setting( 'wplinkdir-group', 'wpld_extended_info', '' );		
			register_setting( 'wplinkdir-group', 'wpld_showpr', '' );		
			register_setting( 'wplinkdir-group', 'wpld_target', '' );		
			register_setting( 'wplinkdir-group', 'wpld_nofollow', '' );		
			register_setting( 'wplinkdir-group', 'wpld_subdomains', '' );		
			register_setting( 'wplinkdir-group', 'wpld_show_numbers', '' );		
			register_setting( 'wplinkdir-group', 'wpld_approval', '' );		
			register_setting( 'wplinkdir-group', 'wpld_extrafields', '' );		
			register_setting( 'wplinkdir-group', 'wpld_description_size', '' );		
			register_setting( 'wplinkdir-group', 'wpld_captcha', '' );		
			register_setting( 'wplinkdir-group', 'wpld_emailme', '' );		
			register_setting( 'wplinkdir-group', 'wpld_emailthem', '' );		
			register_setting( 'wplinkdir-group', 'wpld_style', '' );		
			register_setting( 'wplinkdir-group', 'wpld_track_hitsin', '' );		
			register_setting( 'wplinkdir-group', 'wpld_track_hitsout', '' );		
			register_setting( 'wplinkdir-group', 'wpld_poweredby', '' );		
			register_setting( 'wplinkdir-group', 'wpld_entry_name', '' );		
			register_setting( 'wplinkdir-group', 'wpld_entry_email', '' );		
			register_setting( 'wplinkdir-group', 'wpld_entry_url', '' );		
			register_setting( 'wplinkdir-group', 'wpld_entry_title', '' );		
			register_setting( 'wplinkdir-group', 'wpld_entry_category', '' );	
			register_setting( 'wplinkdir-group', 'wpld_entry_description', '' );	
			register_setting( 'wplinkdir-group', 'wpld_entry_id', '' );	
			register_setting( 'wplinkdir-group', 'wpld_wplinkdir_entry_recip_page', '' );	
			register_setting( 'wplinkdir-group', 'wpld_entry_random', '' );	
			register_setting( 'wplinkdir-group', 'wpld_entry_captcha', '' );	
			register_setting( 'wplinkdir-group', 'wpld_title', '' );	
			register_setting( 'wplinkdir-group', 'wpld_url', '' );	
			register_setting( 'wplinkdir-group', 'wpld_category', '' );
			register_setting( 'wplinkdir-group', 'wpld_description', '' );
			register_setting( 'wplinkdir-group', 'wpld_pending', '' );
			register_setting( 'wplinkdir-group', 'wpld_tags', '' );
			register_setting( 'wplinkdir-group', 'wpld_shortdesc', '' );
			register_setting( 'wplinkdir-group', 'wpld_entry_tags', '' );
			register_setting( 'wplinkdir-group', 'wpld_oldtitle', '' );
			register_setting( 'wplinkdir-group', 'wpld_oldparent', '' );
			register_setting( 'wplinkdir-group', 'wpld_cat_name', '' );
			register_setting( 'wplinkdir-group', 'wpld_parent', '' );
			register_setting( 'wplinkdir-group', 'wpld_permalink_addsite', '' );
			register_setting( 'wplinkdir-group', 'wpld_cleansedelete', '' );
			register_setting( 'wplinkdir-group', 'wpld_enable_default_directory', '' );
/*

*/
			}
		
		add_action( 'admin_init', 'myplugin_admin_init' );
		}

		
	}
}

?>