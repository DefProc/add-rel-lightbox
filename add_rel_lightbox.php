<?php
/*
Plugin Name: add-rel-lightbox
Description: Add rel="lightbox[this_page]" to &lt;a&gt; wrapped image links in the content, and include captions for lightbox/slimbox
Version: 0.4
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

	if ( !function_exists('str_get_html') ) {
		require_once('simple_html_dom.php');
	}

	$html = str_get_html($content);

	/* Find internal image links */

	// First, check that there's content to process otherwise Simple_HTML_DOM will throw errors.
	if (!empty($content)) {
		// Collect details about any image attachments that may be in a gallery.
		$images = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image') );

		// Find any links…
		foreach($html->find('a') as $a) {
			// …that wrap images.
			foreach($a->find('img') as $img) {
				// Check the link points to an image, and no rel="lightbox" is already applied.
				// Note: this also means that adding rel="nolightbox" will skip that link.
				if ( preg_match("/\.(jpg|jpeg|png|gif|bmp|ico|svg)$/i", $a->href) && !preg_match("/lightbox/i", $a->rel) ) {
					$image_no = "";
					// If it's a solo image from an internal source…
					if (preg_match("/wp-image-([0-9]+?)/i", $a->class, $image_no)) {
						// …then append its html escaped description.
						$a->title = esc_attr( get_post($image_no[1])->post_content );
					}
					// Else, if it's an attachment in the gallery…
					elseif ( !empty($images) && preg_match("/attachment-thumbnail/i", $img->class) ) {
						foreach ($images as $image_id => $image) {
							// …check for the right database entry by title…
							if ("$image->post_title" == "$img->title") {
								// …and add the html escaped description.
								$a->title = esc_attr($image->post_content);
								break;
							}
						}
					}

					// Then add the rel="lightbox[post-id]" to make it open with lightbox.
					$a->rel = "lightbox[post-" . $id . "]";
				}
			}
		}

		// Save the content if it's changed.
		$content = $html->save();
	}

	// And return the content back to where it's called from.
	return $content;
}


