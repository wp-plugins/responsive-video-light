=== Plugin Name ===
Contributors: billknechtel, kburgoine
Tags: youtube, you tube, vimeo, responsive, video, embed
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.0.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This will display a responsive YouTube or Vimeo video in pages or posts - anywhere shorttags can be used, including the "text" widget.

== Description ==

Features Include:

*    The ability to add one or more videos directly in to a page, post or any of your own custom post types using the video URL (not the embed code) or ID and a short code. Currently YouTube and Vimeo are supported, using the [responsive_youtube] and [responsive_vimeo] shortcodes, respectively.
*    Fully responsive so the video's viewport will fill the width of the containing area and scale depending on screen size. No need to set a width and height, just set the width of the div your content sits in.
*    YouTube videos have a shortcode attribute that lets you turn "related videos" on or off. "Related Videos" are the links that tile across the viewport when a video has completed playing. 
*    Also in relation to YouTube's "related videos", there is a single setting in the plugin settings screen that allows you to turn them off globally.  Of course you can override this setting on each individual video using the shortcode parameter.

Example Usage:

For a YouTube video, you can specify either the full URL to the video or just the unique video ID, like this:

    [responsive_youtube http://www.youtube.com/watch?v=NbCr0UyoFJA\] 
    [responsive_youtube NbCr0UyoFJA]

And if you want to specify whether or not you'd like the "related videos" to display, you can use the "rel" or "norel" parameters in the shortcode syntax, like this:
 
    [responsive_youtube NbCr0UyoFJA norel]
    [responsive_youtube NbCr0UyoFJA rel]

The rel and norel tags will override whatever you have set in the plugin settings screen for that specific video only.

Similarly, for a Vimeo video, you can use the full video player URL or just the video ID, like this:

    [responsive_vimeo https://vimeo.com/29506088\] 
    [responsive_vimeo 29506088]

= Requirements =

* WordPress 3.0 + 

== Installation ==

1. Download responsive-video-light.zip.
2. Upload responsive-video-light.zip to the plugins/ directory.
3. Enable Responsive Video Light in the Plugin admin panel.
4. Go to Settings > Responsive Video Light and Review the single setting and learn how to embed the videos in your posts or pages.
5. Create a new or edit a post (or page), and insert the shortcode according to the simple syntax.


== Frequently Asked Questions ==

= Says wrong video id or no video shows =

Make sure you have copied the main URL from your address bar for the video and not the supplied embed code.

Make sure you have selected the correct video type. If Vimeo is selected but a YouTube URL is pasted (or vice versa), it will not work.

If this is a Vimeo video, ensure you have permission for the video to be embedded. You may have to contact the video owner to get this.

= Video is not resizing =

Make sure that the div or other block-level element that contains the video has a width set and that it is a percentage and not a fixed width.

== Contributing ==

Use anonymous svn to get a current trunk copy, or build a patch against your current install, then email the patch to me for consideration.

== Changelog ==

= 1.0.3 =
* Update readme to correct minor markdown syntax issues
* Add note about using shorttag in text widget
* update contextual help URL

= 1.0.2 =
* Update changelog with revision notes

= 1.0.1 =
* Correct contributor username

= 1.0.0 =
* Initial Release

== Screenshots ==

1. An example of a responsive vimeo shortcode in use
2. The settings page which also shows example usage
3. User-facing responsive embedded video
