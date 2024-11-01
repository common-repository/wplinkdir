<?php
	echo '<div id="wpld_DropWPLinkDir"  style="display:none" align="center">';
	echo '<form method="POST" action="'.$_SERVER['REQUEST_URI'].'" name="wpld_Drop_WPLinkDir">';
	if ($wp_version>'2.6.3'){ 
		settings_fields('wplinkdir-group');}
	else{
		wp_nonce_field('update-options');
	}
	echo '<p>Are you sure you want to uninstall WPLinkDir?</p>';
	echo '<input name="DropWPLinkDir" id="DropWPLinkDir" value="'.$_No.'" type="submit">';
	echo '<input name="DropWPLinkDir" id="DropWPLinkDir" value="'.$_Yes.'" type="submit">';
	echo '</form>';
	echo '</div>';
?>
