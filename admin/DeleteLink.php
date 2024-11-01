<?php
	echo '<div id="wpld_DeleteLink"  style="display:none" align="center">';
	echo '<form method="POST" action="'.$_SERVER['REQUEST_URI'].'" name="wpld_approve_NewLinks">';
	if ($wp_version>'2.6.3'){ 
		settings_fields('wplinkdir-group');}
	else{
		wp_nonce_field('update-options');
	}
	echo '<p>Are you sure you want to delete this link?</p>';
	echo '<input  type="hidden" value="'.$userID.'" name="wpld_userID" id="wpld_userID"/>';
	echo '<input name="wpld_DeleteLink" id="wpld_DeleteLink" value="'.$_No.'" type="submit">';
	echo '<input name="wpld_DeleteLink" id="wpld_DeleteLink" value="'.$_Yes.'" type="submit">';
	echo '</form>';
	echo '</div>';
?>
