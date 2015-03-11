<?php

/**
 * If we're in debug mode, display the panels data.
 */
function siteorigin_panels_dump(){
/*
	echo "<!--\n\n";
	echo "<?php \n\n";
	echo '$layouts[] = array (';
	echo "'name' => 'Layout',";
	echo "'description' => 'Layout description',";
		
	if(isset($_GET['page']) && $_GET['page'] == 'so_panels_home_page') {
		var_export( get_option( 'siteorigin_panels_home_page', null ) );
	}
	else{
		global $post;
		var_export( get_post_meta($post->ID, 'panels_data', true));
	}
	echo ");";
	
*/
	echo "<textarea class='hidden'>";
	echo "<?php \n";
	echo '$layouts[] = ';
	global $post;
	$pdata = (get_post_meta($post->ID, 'panels_data', true));
	$pdata['name'] = 'Layout';
	$pdata['description'] = 'Layout description';
	var_export($pdata);
	echo ";</textarea>";
}
add_action('siteorigin_panels_metabox_end', 'siteorigin_panels_dump');