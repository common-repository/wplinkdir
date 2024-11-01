<?php

	if ($_GET['link_status'] == TRUE){
	 if($_GET['link_status'] == 'pending'){
		$wpld_link_status_count = $pending;	 
	 }elseif($_GET['link_status'] == 'publish'){ 
		$wpld_link_status_count = $publish;	
	 }elseif($_GET['link_status'] == 'spam'){ 
		$wpld_link_status_count = $spam;	
	 }elseif($_GET['link_status'] == 'trash'){ 
		$wpld_link_status_count = $trash;	
	 }else{ 
		$wpld_link_status_count = $wpld_users;	
	 }
	}else{
		$wpld_link_status_count = $wpld_users;
	}
	
	
	//Max link to display on main page
	$max_links=20;
		  
		
	$link_count = count($wpld_link_status_count);
	$total_links=ceil($link_count/$max_links);
	//print_r($userIDs);
		
		if (isset($_GET['link']) && is_numeric($_GET['link'])){
			$cur_page=$_GET['link'];
		}else{
			$cur_page=1;
		}
		
		if ($cur_page < 1 || $cur_page > $total_links){
			$cur_page=1;}
			$start_link = ($cur_page - 1) * $max_links;
			
			if($_GET['link_status']=='pending'){
			
			  $wpld_PendingLinks = array_chunk($pending,$max_links);
			  $i = $cur_page-1 ;
			  $wpld_links = $wpld_PendingLinks[$i];
			 
			}elseif($_GET['link_status']=='publish'){
			  
			  $wpld_PublishLinks = array_chunk($publish,$max_links);
			  $i = $cur_page-1 ;
			  $wpld_links = $wpld_PublishLinks[$i];
			  
			}elseif($_GET['link_status']=='spam'){
			  
			  $wpld_SpamLinks = array_chunk($spam,$max_links);
			  $i = $cur_page-1 ;
			  $wpld_links = $wpld_SpamLinks[$i];
			  
			}elseif($_GET['link_status']=='trash'){
			  
			  $wpld_TrashLinks = array_chunk($trash,$max_links);
			  $i = $cur_page-1 ;
			  $wpld_links = $wpld_TrashLinks[$i];
			  
			}else{
			  
			  $wpld_AllLinks = array_chunk($wpld_users,$max_links);
			  $i = $cur_page-1 ;
			  $wpld_links = $wpld_AllLinks[$i];
			  
			
			}
			
			
			$pagetotal = 3; // maximum page numbers to display at once, must be an odd number
			$pagelimit = ($pagetotal-1)/2;
			$pagemax = $pagetotal>$total_links?$total_links:$pagetotal;
			$nav  = '';

			if ($cur_page - $pagelimit < 1) {
				$pagemin = 1;
			}

			if ($cur_page - $pagelimit >=1 && $cur_page + $pagelimit <= $total_links) {
				$pagemin = $cur_page - $pagelimit;
				$pagemax = $cur_page + $pagelimit;
			}

			if ($cur_page - $pagelimit >=1 && $cur_page + $pagelimit > $total_links) {
				$pagemin = ($total_links-$pagetotal+1)<1?1:($total_links-$pagetotal+1);
				$pagemax = $total_links;
			}
		
			if ($cur_page + $pagelimit > $total_links) {
				$pagemax = $total_links;
			}
		
			for($page = $pagemin; $page <= $pagemax; $page++){
	
				if ($page == $cur_page){
				//$nav .= " $page "; // no need to create a link to current page
					$nav .= '
					<span class="page-numbers current">' . $page . '</span>';
				}else{
					$nav .= '
					<a class="page-numbers" href="' .$_SERVER['PATH_INFO']. 'admin.php?page=WPLinkDir/directory.php&amp;link_status=' .$_GET['link_status'].'&amp;link=' . $page . '">' . $page . '</a>';
				}

			}
			if ($cur_page > 1){
				$page  = $cur_page - 1;
				$prev = '<a class="prev page-numbers" href="' .$_SERVER['PATH_INFO']. 'admin.php?page=WPLinkDir/directory.php&amp;link_status=' .$_GET['link_status'].'&amp;link=' . $page . '">&#171;</a>';
				if ($cur_page > 2){
					$first = '
					<a class="page-numbers" href="' .$_SERVER['PATH_INFO']. 'admin.php?page=WPLinkDir/directory.php&amp;link_status=' .$_GET['link_status'].'&amp;link=1">1</a>
					<span class="page-numbers dots">...</span>';
				}
			}else{
				$prev  = '&nbsp;'; // we're on page one, don't print previous link
				$first = '&nbsp;'; // nor the first page link
			}
	
			if ($cur_page < $total_links){
				$page = $cur_page + 1;
				$next ='
				<a class="next page-numbers" href="' .$_SERVER['PATH_INFO']. 'admin.php?page=WPLinkDir/directory.php&amp;link_status=' .$_GET['link_status'].'&amp;link=' . $page . '">&#187;</a>';
				if ($cur_page < $total_links - 1) {
					$last = '
					<span class="page-numbers dots">...</span>
					<a class="page-numbers" href="' .$_SERVER['PATH_INFO']. 'admin.php?page=WPLinkDir/directory.php&amp;link_status=' .$_GET['link_status'].'&amp;link=' . $total_links . '">' . $total_links . '</a>';
				}	  
			}else{
				$next = '&nbsp;'; // we're on the last page, don't print next link
				$last = '&nbsp;'; // nor the last page link
			}
?>