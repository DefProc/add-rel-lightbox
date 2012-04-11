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

	/* Find internal image links */

	// Check the page for link images direct to image (no trailing attributes)
	$regex_search = '/<a href="(.*?).(jpg|jpeg|png|gif|bmp|ico|svg)"><img(.*?)class="(.*?)wp-image-(.*?)" \/><\/a>/i';
	preg_match_all($regex_search, $content, $matches, PREG_SET_ORDER);

	// Check which image is referenced
	foreach ($matches as $val)
	{
		$caption = '';

		$image = get_post($val[5]);
		$caption = esc_attr( $image->post_content );

		//Replace the instance with the lightbox and title(caption) references. Won't fail if caption is empty.
		$string = '<a href="' . $val[1] . '.' . $val[2] . '"><img' . $val[3] . 'class="' . $val[4] . 'wp-image-' . $val[5] . '" /></a>';
		$replace = '<a href="' . $val[1] . '.' . $val[2] . '" rel="lightbox[post-' . $id . ']" title="' . $caption . '"><img' . $val[3] . 'class="' . $val[4] . 'wp-image-' . $val[5] . '" /></a>';
		$content = str_replace($string, $replace, $content);
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


