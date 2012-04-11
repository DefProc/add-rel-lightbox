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
		foreach($html->find('a') as $a) {
			foreach($a->find('img') as $img) {
				if ( preg_match("/(.*?).(jpg|jpeg|png|gif|bmp|ico|svg)/i", $a->href) && !preg_match("/lightbox/i", $a->rel) ) {
					$img_no = "";
					if (preg_match("/wp-image-([0-9]+?)/i", $a->class, $img_no)) {
						$a->title = esc_attr( get_post($img_no[1])->post_content );
					}
					$a->rel = $a->rel . "lightbox[post-" . $id . "]";
				}
			}
		}
		$content = $html->save();
	}

	/* Find links created by [gallery] shortcode */

	// Check the page for link images direct to image (no trailing attributes)
	$regex_search = '/<a href=\'(.*?).(jpg|jpeg|png|gif|bmp|ico|svg)\' title=\'(.*?)\'><img(.*?)class="attachment-thumbnail" (.*?)\/><\/a>/i';

	if (preg_match($regex_search, $content))
	{
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image') );
		
		foreach ($attachments as $num => $attachment)
		{	
			// Then re-arrange the link to include the caption and rel="lightbox" 
			// Note quote usage: <a href='' title=''></a> from [gallery] shortcode
			$caption = esc_attr( $attachment->post_content );
			$pattern = "/<a href='(.*?).(jpg|jpeg|png|gif|bmp|ico|svg)' title='" . $attachment->post_title . "'><img(.*?)class=\"attachment-thumbnail\"(.*?)\/><\/a>/i";
			$replace = '<a href="${1}.${2}" rel="lightbox[post-' . $id . ']" title="' . $caption . '"><img${3}class="attachment-thumbnail"${4}/></a>';
			$content = preg_replace($pattern, $replace, $content, 1);
		}
	}

	return $content;
}


