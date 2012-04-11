<?php
/*
Plugin Name: add-rel-lightbox
Description: Add rel="lightbox[this_page]" to &lt;a&gt; wrapped image links in the content, and include captions for lightbox/slimbox
Version: 0.3
Author: Patrick Fenner (Def-Proc.co.uk)
Author URI: http://www.deferredprocrastination.co.uk/
*/

/* Filter Hook */

add_filter('the_content', 'add_rel_lightbox', 12);
add_filter('the_excerpt', 'add_rel_lightbox', 12);


/**
 * Add-rel-lighbox
 */

function add_rel_lightbox($content)
{
	global $post;
	$id = $post->ID;

	require_once('simple_html_dom.php');

	$html = str_get_html($content);

	/* Find internal image links */

	if (!empty($content)) {
		$images = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image') );

		foreach($html->find('a') as $a) {
			foreach($a->find('img') as $img) {
				if ( preg_match("/(.*?).(jpg|jpeg|png|gif|bmp|ico|svg)/i", $a->href) && !preg_match("/lightbox/i", $a->rel) ) {
					$image_no = "";
					if (preg_match("/wp-image-([0-9]+?)/i", $a->class, $image_no)) {
						$a->title = esc_attr( get_post($image_no[1])->post_content );
					}
					elseif (!empty($images)) {
						foreach ($images as $image_id => $image) {
							if ("$image->post_title" == "$img->title") {
								$a->title = esc_attr($image->post_content);
							}
						}
					}
					$a->rel = $a->rel . "lightbox[post-" . $id . "]";
				}
			}
		}
		$content = $html->save();
	}

	return $content;
}


