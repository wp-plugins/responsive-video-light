=== Plugin Name ===
Contributors: billknechtel, kburgoine
Tags: youtube, you tube, vimeo, responsive, video, embed
Requires at least: 3.0
Tested up to: 4.3
Stable tag: 1.3.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed responsive YouTube or Vimeo videos in pages, posts, or even the "text" widget using a super-simple shortcode.

== Description ==

Features Include:

*    The ability to add one or more videos directly in to a page, post or any of your own custom post types using the video URL (not the embed code) or ID and a short code. Currently YouTube and Vimeo are supported, using the [responsive_youtube] and [responsive_vimeo] shortcodes, respectively.
*    Fully responsive so the video's viewport will fill the width of the containing area and scale depending on screen size. No need to set a width and height, just set the width of the div your content sits in.
*    YouTube videos have a shortcode attribute that lets you turn "related videos" on or off. "Related Videos" are the links that tile across the viewport when a video has completed playing. 
*    YouTube videos also support light and dark themes, autoplaying, showinfo, and modest branding (which removes most YouTube brand imagery).
*    Vimeo videos can have the video portrait, title, and byline shut off.
*    A comprehensive settings screen which explains all the options and shows you the syntax of the shortcodes.

Example Usage:

For a YouTube video, you can specify either the full URL to the video or just the unique video ID, like this:

    [responsive_youtube http://www.youtube.com/watch?v=NbCr0UyoFJA ] 
    [responsive_youtube NbCr0UyoFJA]

And if you want to specify whether or not you'd like the "related videos" to display, you can use the "rel" or "norel" parameters in the shortcode syntax, like this:
 
    [responsive_youtube NbCr0UyoFJA norel]
    [responsive_youtube NbCr0UyoFJA rel]

The rel and norel tags will override whatever you have set in the plugin settings screen for that specific video only.

Similarly, for a Vimeo video, you can use the full video player URL or just the video ID, like this:

    [responsive_vimeo https://vimeo.com/29506088 ] 
    [responsive_vimeo 29506088]
    
There is a more complete treatment of the shortcode syntax in the settings screen of the plugin itself.

= Please Note = 

This release, 1.3.x, is a major upgrade.  The settings screen has undergone a complete overhaul, I've added a handful of options to YouTube, and have added the Vimeo options to the settings screen.  Functionally, however, the syntax of the shortcode is identical to the way it used to work (with more optional parameters available), and is indeed fully backwards compatible with all your existing RVL shortcodes.

= Requirements =

* WordPress 3.0 + 

== Installation ==

1. Download responsive-video-light.zip.
2. Upload responsive-video-light.zip to the plugins/ directory.
3. Enable Responsive Video Light in the Plugin admin panel.
4. Go to Settings > Responsive Video Light and Review the two settings and learn how to embed the videos in your posts or pages.
5. Create a new or edit a post (or page), and insert the shortcode according to the simple syntax.


== Frequently Asked Questions ==

= Says wrong video id or no video shows =

Make sure you have copied the main URL from your address bar for the video and not the supplied embed code.

Make sure you have selected the correct video type. If Vimeo is selected but a YouTube URL is pasted (or vice versa), it will not work.

If this is a Vimeo video, ensure you have permission for the video to be embedded. You may have to contact the video owner to get this.

= Video is not resizing =

Make sure that the div or other block-level element that contains the video has a width set and that it is a percentage and not a fixed width.

= YouTube videos are displaying in front of a part of my HTML =

Check out the wmode options in the plugin documentation.  Chances are that
setting it to "transparent" will fix the problem.

== Contributing ==

Use anonymous svn to get a current trunk copy, or build a patch against your current install, then email the patch to me for consideration.

== Changelog ==

= 1.3.2 =
* Add showinfo parameter support

= 1.3.1 =
* Added missing admin screen CSS.  D'oh!

= 1.3.0 =
* Added new YouTube options like modest branding and themes (yay!)
* Added extant Vimeo options to the global settings screen
* Reworked settings screen to accommodate all the new global settings.

= 1.2.1 =
* Fixed http/https warnings & issues by using protocol independent URI.
* Internal restructure to comply with PSR-2
* Fix DEBUG mode error by correcting role call to use name as opposed to ID.
* Refactored administrative interface to use Twig templating system

I'm looking at feature upgrades currently. Be looking for a new release soon with an enhanced feature set.

= 1.2.0 =
* Add notitle, nobyline, and noportrait vimeo parameters
* Remove vestigial contextual help screen code that really did nothing useful

= 1.1.0 =
* Add wmode shortcode parameter for responsive_youtube.
* Add underscore to acceptable character set in YouTube video ID regex.
* Minor whitespace tweaks.
* Much thanks to kjfrank and f.macdonald for the heads-up and suggested fixes on these issues!

= 1.0.8 =
* Fix acceptable YouTube ID character set regex.  The Hyphen is now usable as well, per user feedback. (Thanks so much!)

= 1.0.7 =
* Fix call_user_fun_array() error caused by renaming the css function.

= 1.0.6 =

* Rename the css integration function so as not to interfere with the original "Rsponsive video" plugin.

= 1.0.5 =
* Update documentation markdown to interpolate closing brackets correctly where a URL is the last parameter of the shortcode.

= 1.0.4 =
* Update description to fit within prescribed limits. Going to take a while to get used to the "WordPress Way"

= 1.0.3 =
* Update readme to correct minor markdown syntax issues
* Add note about using shortcode in text widget
* update contextual help URL

= 1.0.2 =
* Update changelog with revision notes

= 1.0.1 =
* Correct contributor username

= 1.0.0 =
* Initial Release

== Screenshots ==

1. An example of responsive shortcodes in use
2. The settings page which also shows example usage
3. User-facing responsive embedded video
