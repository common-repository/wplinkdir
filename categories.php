<?php

###### CATEGORIES ##############################################################################

	// Display all the categories


	$DetailedInfo = get_option('wpld_extended_info');
	$wpld_dir_id = get_option('wpld_dir_id');
	$wpld_addurl_id = get_option('wpld_addurl_id');
	

		$parentID = $wpld_dir_id;

	
	$sql = "SELECT ID,post_title,post_name FROM $wpdb->posts WHERE post_parent='{$parentID}' AND post_status = 'publish' ORDER BY post_title ASC";
	$getCats = $wpdb->get_results($sql, ARRAY_A);


	foreach($getCats as $row){
	 if ($wpld_dir_id != $row['ID'] && $wpld_addurl_id != $row['ID']){
		$arrayCat[] =  array( $row['post_title'], $row['post_name'], $row['description'], $row['ID'] );
	 }
	}	

	$split_arr = array_chunk($arrayCat, 3);
	

	$catArray1 = array();
	$catArray2 = array();
	$catArray3 = array();
	
	foreach ($split_arr as $num => $cat) {


			$catArray1[] = array( $cat[0][0],$cat[0][1],$cat[0][2],$cat[0][3] );
			$catArray2[] = array( $cat[1][0],$cat[1][1],$cat[1][2],$cat[1][3] );
			$catArray3[] = array( $cat[2][0],$cat[2][1],$cat[2][2],$cat[2][3] );
	
	}

echo '<div class="category_container_box">';
	$catArray1 = wpld_array_remove_empty($catArray1);
	
	foreach ($catArray1 as $value => $cat) {
	
		$title = $cat[0];
		$title_pretty = $cat[1];
		$description = $cat[2];
		$id = $cat[3];
		$count = wpld_LinkCount($id);		
		
		echo '<div class="category_container">
		<div class="cat-id-'.$id.'"><a href="'.get_bloginfo('url').'/'.$directory.'/'.$title_pretty.$FILExt.'">'.$title.' '.($ShowNums == 'Yes' ? ' ('.$count.')' : '').'</a>
		</div>';
		
		echo '
		<ul>';
		if(mysql_num_rows(wpld_SubCatCheck($id))>0){
		wpld_getSubCats($id);
		}
		echo '
		</ul></div>					
		<div class="clear"></div>';
	}
	echo '</div>';
	


echo '<div class="category_container_box">';
	$catArray2 = wpld_array_remove_empty($catArray2);
	
	foreach ($catArray2 as $value => $cat) {
	
		$title = $cat[0];
		$title_pretty = $cat[1];
		$description = $cat[2];
		$id = $cat[3];	
		$count = wpld_LinkCount($id);	
		
		echo '<div class="category_container">
		<div class="cat-id-'.$id.'"><a href="'.get_bloginfo('url').'/'.$directory.'/'.$title_pretty.$FILExt.'">'.$title.' '.($ShowNums == 'Yes' ? ' ('.$count.')' : '').'</a>
		</div>';
		
		echo '
		<ul>';
		if(mysql_num_rows(wpld_SubCatCheck($id))>0){
		wpld_getSubCats($id);
		}
		echo '
		</ul></div>					<div class="clear"></div>

		';
	}
echo '</div>';

echo '<div class="category_container_box">';
	$catArray3 = wpld_array_remove_empty($catArray3);
	
	foreach ($catArray3 as $value => $cat) {
	
		$title = $cat[0];
		$title_pretty = $cat[1];
		$description = $cat[2];
		$id = $cat[3];		
		$count = wpld_LinkCount($id);
		
		echo '<div class="category_container">
		<div class="cat-id-'.$id.'"><a href="'.get_bloginfo('url').'/'.$directory.'/'.$title_pretty.$FILExt.'">'.$title.' '.($ShowNums == 'Yes' ? ' ('.$count.')' : '').'</a>
		</div>';
		
		echo '
		<ul>';
		if(mysql_num_rows(wpld_SubCatCheck($id))>0){
		wpld_getSubCats($id);
		}
		echo '</ul>
		</div>					
	<div class="clear"></div>';
	}
	echo '</div>';

?>