<?php

function wplinkdir_translation(){
	// Defines some translation variables which are used in more than one place

	global $WPLD_Trans,$WPLD_Domain;

	$WPLD_Trans['Yes']=__('Yes',$WPLD_Domain);	// Only used in the admin section when displaying Yes/No menu selections
	$WPLD_Trans['No']=__('No',$WPLD_Domain);

	$WPLD_Trans['Update']=__('Update',$WPLD_Domain);	// Used in the edit links admin page. These two options are checked and shouldn't contain special characters
	$WPLD_Trans['Delete']=__('Delete',$WPLD_Domain);
	$WPLD_Trans['Edit']=__('Edit',$WPLD_Domain);
	$WPLD_Trans['Status']=__('Status:',$WPLD_Domain);
	$WPLD_Trans['Flags']=__('Flags',$WPLD_Domain);
	$WPLD_Trans['Approved']=__('Approved',$WPLD_Domain);
	$WPLD_Trans['Pending']=__('Pending',$WPLD_Domain);
	$WPLD_Trans['Premium link']=__('Premium link',$WPLD_Domain);
	$WPLD_Trans['Link Updated']=__('Link Updated',$WPLD_Domain);
	$WPLD_Trans['Link Deleted']=__('Link Deleted',$WPLD_Domain);
	$WPLD_Trans['Link Deleted2']=__('The link has been deleted.',$WPLD_Domain);
	$WPLD_Trans['Link Added']=__('Link Added',$WPLD_Domain);
	$WPLD_Trans['Reciprocal Page']=__('Reciprocal Page',$WPLD_Domain);
	$WPLD_Trans['Date Modified']=__('Date Modified',$WPLD_Domain);
	$WPLD_Trans['Add Category']=__('Add Category',$WPLD_Domain);
	$WPLD_Trans['Add Link']=__('Add Link',$WPLD_Domain);
	$WPLD_Trans['Your Name']=__('Your Name:',$WPLD_Domain);
	$WPLD_Trans['Your E-Mail:']=__('Your E-Mail:',$WPLD_Domain);
	$WPLD_Trans['Reciprocal URL:']=__('Reciprocal URL:',$WPLD_Domain);
	$WPLD_Trans['Category Deleted']=__('Category Deleted',$WPLD_Domain);
	$WPLD_Trans['Category Added']=__('Category Added',$WPLD_Domain);
	$WPLD_Trans['Category Updated']=__('Category Updated',$WPLD_Domain);
	$WPLD_Trans['Sub-Category of']=__('Sub-Category of',$WPLD_Domain);
	$WPLD_Trans['Title']=__('Title',$WPLD_Domain);
	$WPLD_Trans['Delete Links']=__('Delete Links',$WPLD_Domain);
	$WPLD_Trans['Cleanse']=__('Cleanse',$WPLD_Domain);
	$WPLD_Trans['Captcha:']=__('Captcha:',$WPLD_Domain);
	$WPLD_Trans['HTML tags allowed:']=__('HTML tags allowed:',$WPLD_Domain);
	$WPLD_Trans['Maximum characters']=__('Maximum: %s characters',$WPLD_Domain);

	$WPLD_Trans['Hidden']=__('Hidden',$WPLD_Domain);	// Used in the menu on the admin page for the Extra Fields option
	$WPLD_Trans['Optional']=__('Optional',$WPLD_Domain);
	$WPLD_Trans['Required']=__('Required',$WPLD_Domain);
	
	$WPLD_Trans['Name']=__('Name',$WPLD_Domain);		// These options are displayed to the admin and front end user
	$WPLD_Trans['Email']=__('Email',$WPLD_Domain);
	$WPLD_Trans['WebsiteTitle']=__('Website Title',$WPLD_Domain);
	$WPLD_Trans['WebsiteURL']=__('Website URL',$WPLD_Domain);
	$WPLD_Trans['Category']=__('Category',$WPLD_Domain);
	$WPLD_Trans['Description']=__('Description',$WPLD_Domain);
	$WPLD_Trans['ShortDesc']=__('Short description',$WPLD_Domain);
	$WPLD_Trans['Tags']=__('Tags',$WPLD_Domain);

	$WPLD_Trans['AddedBy']=__('Added By:',$WPLD_Domain);		// Shown on admin screen and detailed information screen
	$WPLD_Trans['DateAdded']=__('Date Added:',$WPLD_Domain);

	$WPLD_Trans['WPLD']=__('WP Link Directory',$WPLD_Domain);	// Used to display the main menu.
	$WPLD_Trans['WordPressLinkDirectory']=__('WordPress LinkDir',$WPLD_Domain);	// Full name of the script, used in the 'powered by' link
	$WPLD_Trans['WPLD_EditLinks']=__('Edit Links',$WPLD_Domain);		// Used in the sub-menus
	$WPLD_Trans['WPLD_EditCategories']=__('Edit Categories',$WPLD_Domain);
	$WPLD_Trans['WPLD_PremiumLinks']=__('Premium Links',$WPLD_Domain);
	$WPLD_Trans['WPLD_TaggingOptions']=__('Tagging Options',$WPLD_Domain);

	$WPLD_Trans['Nobody']=__('Nobody',$WPLD_Domain);		// Used in the options menu for reciprocal requirements
	$WPLD_Trans['Everybody']=__('Everybody',$WPLD_Domain);		// Used as above and below
	$WPLD_Trans['SitesPR']=__('Sites < PR',$WPLD_Domain);		// Displayed to the admin and is directly proceeded by the PR value ie 'Sites < PR3'
		
	$WPLD_Trans['admin_Your URL']=__('Your URL',$WPLD_Domain);
	$WPLD_Trans['admin_text2']=__('Your homepage or the URL you want people to link to',$WPLD_Domain);
	$WPLD_Trans['admin_text3']=__('Require Reciprocal Links From',$WPLD_Domain);
	$WPLD_Trans['admin_text4']=__('Sites of these specifications will be required to link back to you to add themselves to your directory.',$WPLD_Domain);
	$WPLD_Trans['admin_text5']=__('Reciprocal Link HTML',$WPLD_Domain);
	$WPLD_Trans['admin_text6']=__('Enter the HTML people should use to link to your site. This will be displayed on the Link To Us page.',$WPLD_Domain);
	$WPLD_Trans['admin_text7']=__('Allow HTML in Description',$WPLD_Domain);
	$WPLD_Trans['admin_text8']=__('People adding links to your directory will be able to use these HTML tags in their description. If you want to enable bold text, images and linking, for example, you would enter \'&#60;b&#62;&#60;a&#62;&#60;img&#62;\'',$WPLD_Domain);
	$WPLD_Trans['OrderA']='Newest First';
	$WPLD_Trans['OrderB']='Oldest First';
	$WPLD_Trans['OrderC']='Pagerank Highest to Lowest';
	$WPLD_Trans['OrderD']='Pagerank Lowest to Highest';
	$WPLD_Trans['OrderE']='Alphabetical A to Z';
	$WPLD_Trans['OrderF']='Alphabetical Z to A';
	$WPLD_Trans['admin_text9']=__('Order Links By',$WPLD_Domain);
	$WPLD_Trans['admin_text10']=__('The order in which to display links in your directory.',$WPLD_Domain);
	$WPLD_Trans['admin_text11']=__('Detailed Info',$WPLD_Domain);
	$WPLD_Trans['admin_text12']=__('By default a <i>read more</i> link is shown for each link where more detailed information about the link is shown. You can change the text of this link or leave this field blank to disable it.',$WPLD_Domain);
	$WPLD_Trans['admin_Flagging']=__('Flagging',$WPLD_Domain);
	$WPLD_Trans['admin_text13']=__('By default a <i>flag link</i> link is shown for each link which the reader can click to flag the link (i.e. if the link is broken). You can change the text of this link or leave this field blank to disable it.',$WPLD_Domain);
	$WPLD_Trans['admin_text14']=__('Display PageRank',$WPLD_Domain);
	$WPLD_Trans['admin_text15']=__('If you don\'t want to display the PR bar next to links set this to No.',$WPLD_Domain);
	$WPLD_Trans['admin_text16']=__('Link Target',$WPLD_Domain);
	$WPLD_Trans['admin_text17']=__('_blank will open links in a new window whereas _self will open links on the same page. See http://www.w3.org/TR/1999/REC-html401-19991224/types.html#type-frame-target for more information.',$WPLD_Domain);
	$WPLD_Trans['admin_text18']=__('Use NoFollow?',$WPLD_Domain);
	$WPLD_Trans['admin_text19']=__('NoFollow is an HTML attribute that can be used in links. It tells Google and other search engines to ignore the link and not follow it (<a href="http://en.wikipedia.org/wiki/Nofollow">read more</a>). Turning NoFollow on will add rel="nofollow" to all your links.',$WPLD_Domain);
	$WPLD_Trans['admin_text20']=__('Multiple Links From Same Domain?',$WPLD_Domain);
	$WPLD_Trans['admin_text21']=__('If set to Yes then multiple links from the same domain will be allowed. I.e. http://www.google.com http://images.google.com and http://www.google.com/preferences would all be allowed.',$WPLD_Domain);
	$WPLD_Trans['admin_text22']=__('Categories Per Row',$WPLD_Domain);
	$WPLD_Trans['admin_text23']=__('When displaying the categories on the front page of your directory, this is how many will be displayed per row.',$WPLD_Domain);
	$WPLD_Trans['admin_text24']=__('Show Counts?',$WPLD_Domain);
	$WPLD_Trans['admin_text25']=__('On the front page of your directory you can chose to show or hide the number of links in each category. I.e. \'Links (23)\' vs \'Links\'.',$WPLD_Domain);
	$WPLD_Trans['ApproveA']='Require Approval For All Links';
	$WPLD_Trans['ApproveB']="Require Approval For Links That Don't Meet Requirements";
	$WPLD_Trans['ApproveC']='Do Not Require Approval';
	$WPLD_Trans['admin_text26']=__('Require Approval?',$WPLD_Domain);
	$WPLD_Trans['admin_text27']=__('Links can be held in a pending queue before being displayed to the public. Option B. will mean that if a link is submitted which doesn\'t meet your reciprocal link requirements it will be placed in the queue for approval, otherwise they will be thrown out.',$WPLD_Domain);
	$WPLD_Trans['admin_text28']=__('Extra Fields',$WPLD_Domain);
	$WPLD_Trans['admin_text29']=__('You can include Name and E-Mail fields on your Add URL page. Setting to Hidden will not show these fields, Optional will display but not require them and Required will mean that people cannot add their link without supplying a name and email address.',$WPLD_Domain);
	$WPLD_Trans['admin_text30']=__('Description Size',$WPLD_Domain);
	$WPLD_Trans['admin_text31']=__('The amount of characters allowed in the description field. Leave blank for no limit.',$WPLD_Domain);
	$WPLD_Trans['admin_text32']=__('Require Captcha?',$WPLD_Domain);
	$WPLD_Trans['admin_text33']=__('Captcha is a form of checking that the user is human and not a spam-bot. Enabling captcha displays an image on your Add URL page that the user must enter to have their link accepted.',$WPLD_Domain);
	$WPLD_Trans['admin_text34']=__('Email Me?',$WPLD_Domain);
	$WPLD_Trans['admin_text35']=__('If set to Yes the script will send you an email (to the WordPress admin email address) each time a new link is added to your directory.',$WPLD_Domain);
	$WPLD_Trans['admin_text36']=__('Email Them?',$WPLD_Domain);
	$WPLD_Trans['admin_text37']=__('If set to Yes the script will send an email to the person who added the link when you approve it for the first time.',$WPLD_Domain);
	$WPLD_Trans['admin_text38']=__('Style File',$WPLD_Domain);
	$WPLD_Trans['admin_text39']=__('Style files (.css files) are contained in the <i>styles</i> directory and can be edited to change the look of your directory.',$WPLD_Domain);
	$WPLD_Trans['admin_text40']=__('Permalinks',$WPLD_Domain);
	$WPLD_Trans['admin_text41']=__('If you are using custom permalinks in WordPress then WP Link Directory can adopt pretty permalinks to make your link directory look better to search engines. If you use the default "links" option then http://www.yoursite.com/wordpress/links/ will be the directory homepage. You must have custom permalinks enabled for this to work and \'/%postname%\' will not work. \'/%category%/%postname%\' is a good working example. If for some reason you don\'t wish to use permalinks you can leave this blank.',$WPLD_Domain);
	$WPLD_Trans['admin_text42']=__('Track Hits In?',$WPLD_Domain);
	$WPLD_Trans['admin_text43']=__('Tracks incoming hits for each site. Note that on sites receiving a large amount of traffic from other sites this may cause things to slow down slightly.',$WPLD_Domain);
	$WPLD_Trans['admin_text44']=__('Track Hits Out?',$WPLD_Domain);
	$WPLD_Trans['admin_text45']=__('Tracks outgoing hits for each site. Note that this uses a redirect which means the linked site wont get any pagerank from you.',$WPLD_Domain);
	$WPLD_Trans['admin_text46']=__('Display Powered By?',$WPLD_Domain);
	$WPLD_Trans['admin_text47']=__('I offer WP Link Directory for free, for everyone, and a link back to my site isn\'t required, but it is appreciated. If selected this will insert a small link on the home page of the directory to the plugins homepage.',$WPLD_Domain);
	$WPLD_Trans['admin_update']=__('Save Changes',$WPLD_Domain);
	$WPLD_Trans['admin_text48']=__('Are you sure?',$WPLD_Domain);
	$WPLD_Trans['admin_text49']=__('Directory name',$WPLD_Domain);
	$WPLD_Trans['admin_text50']=__('Here is where you put in your dir name of your WordPress page you use for WPLinkDir. If your page url is " http://www.something.com/blog/my-link-directory" you will enter "my-link-directory" here. ',$WPLD_Domain);
	$WPLD_Trans['admin_text51']=__('AddURL page name',$WPLD_Domain);
	$WPLD_Trans['admin_text52']=__('Here you put in name of the subpage you use as Add URL page for your WPLinkDir',$WPLD_Domain);
	$WPLD_Trans['admin_text53']=__('Every now and then you should perform a cleanse which checks all the links in your directory to make sure they comply with your Reciprocal Link requirements. If your Reciprocal Link requires All Sites < PR 4, for example, any sites with < PR 4 will be checked.<br>If you set Delete Links to Yes then links which don\'t meet requirements will be deleted, otherwise they will simply be flagged up for you to check and/or delete manually.',$WPLD_Domain);
	$WPLD_Trans['admin_text54']=__('Your Reciprocal Requirements option is set to Nobody. This means no sites are required to link back to you and therefor there is nothing to check/cleanse.',$WPLD_Domain);
	$WPLD_Trans['admin_text55']=__('We couldn\'t find a reciprocal link on the page %1$s which is the reciprocal URL supplied for %2$s (%3$s).',$WPLD_Domain);
	$WPLD_Trans['admin_text56']=__('The reciprocal page for %1$s (%2$s) which was at %3$s no longer exists.',$WPLD_Domain);
}

//wplinkdir_translation_setup();
wplinkdir_translation();
?>