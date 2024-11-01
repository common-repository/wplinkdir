<?php
	echo '<div id="wpld_DeleteDirectory"  style="display:none" align="center">';
	echo '<form method="POST" action="'.$_SERVER['REQUEST_URI'].'" name="wpld_Delete_Directory">';
	if ($wp_version>'2.6.3'){ 
		settings_fields('wplinkdir-group');}
	else{
		wp_nonce_field('update-options');
	}
	echo '<p>Are you sure you want to delete directory?</p>';
	echo '<input name="Delete_Directory" id="Delete_Directory" value="'.$_No.'" type="submit">';
	echo '<input name="Delete_Directory" id="Delete_Directory" value="'.$_Yes.'" type="submit">';
	echo '</form>';
	echo '</div>';
?>
