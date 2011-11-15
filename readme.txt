=== add-rel-lightbox ===
Contributors: DefProc
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sales@def-proc.co.uk&currency_code=GBP&no_shipping=1&item_name=Donation%20for%20add-rel-lightbox
Version: 0.3
Stable tag: 0.3
Tags: lightbox, slimbox, image, images
Requires at least: 3
Tested up to: 3.2.1

Add rel="lightbox" to &lt;a&gt; wrapped image links in the content and to [gallery] image links, and include captions for lightbox/slimbox

== Description ==

WordPress plugin to automatically add rel="lightbox" attribute to link wrapped images, as placed into WordPress posts.

This plugin requires lightbox or slimbox (or any lightbox-compatible image handler) to also be installed on your site, either as part of the theme, or via a plugin. If either lightbox, or add-rel-lightbox is not activated at any time, your site will revert gracefully to the standard "open image as new page".

The plugin currently only works with images placed into posts using the "add media" dialogues, and will only apply to images that are in your media library. However, it will also *not* interfere with any images that have already have attributes applied to them.

add-rel-lightbox also works with the [gallery] shortcode, adding the rel="lightbox" attribute to links created by it. Note that you will have to use the `[gallery link="file"]` format, or select "Image File" when inserting the gallery for lighbox to work as expected. Galleries that link to the attachment page are unaffected by this plugin.

In addition to applying rel="lightbox", this plugin will retrieve the media library caption for any images, and correctly escape any html entities, so that the caption is displayed as expected in the lightbox.

== Installation ==

This section describes how to install the plugin and get it working.

Either Install the plugin by searching for "add-rel-lightbox" from the plugin page of your wordpress installation, or:

1. Download and extract the plugin from the zip file
1. Copy the plugin folder into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

To function, you will also need (lightbox/slimbox/lighbox clone) installed and activated.

To Upgrade:

Either upgrade automatically from your admin pages. Or if upgrading manually: deactivate the plugin *before* copying 
the new files across; then enable when completed.

== Changelog ==

= v 0.3 = 

* Adjusted the filter hook priority so it uses the core [gallery] shortcode function.
* Also works with `the_extract();` e.g. on front page and category listings.
* All images and gallery form one linked gallery per post/page.

= v 0.2 =

* Now also works with [gallery link="file"] shortcode. 

= v 0.1.1 =

* Added .svg support to image types

= v 0.1 =

Initial Release

* Adds rel="lightbox[this_page]" to link wrapped images.
* Adds lighbox caption from WordPress Media Library.

== Upgrade Notice ==

Revised [gallery] shortcode integration.
