add-rel-lightbox
================

WordPress plugin to automatically add rel="lightbox" attribute to link wrapped images, as placed into WordPress posts.

This plugin requires lightbox or slimbox (or any lightbox-compatible image handler) to also be installed on your site, either as part of the theme, or via a plugin. If either lightbox, or add-rel-lightbox is not activated at any time, your site will revert gracefully to the standard "open image as new page".

The plugin currently only works with images placed into posts using the "add media" dialogues, and will only apply to images that are in your media library. However, it will also *not* interfere with any images that have already have attributes applied to them.

In addition to applying rel="lightbox", this plugin will retrieve the media library caption for any images, and correctly escape any html entities, so that the caption is displayed as expected in the lightbox.
