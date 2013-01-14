<?php
/*
  Plugin Name: Responsive Video Light
  Plugin URI: http://bitpusher.tk/responsive-video-light
  Description: A plugin to add responsive videos to pages and posts
  Version: 1.0.6
  Author: Bill Knechtel
  Author URI: http://bitpusher.tk
  License:	GPLv2

  Copyright 2013  William Knechtel

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License version 2 as published 
  by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function rvl_css()  {
  // Register the css styling to make the video responsive:  
  wp_register_style( 
    'responsive-video-light', 
    plugins_url( '/css/responsive-videos.css', __FILE__ ), 
    array(), 
    '20130111', 
    'all' 
  ); 
  wp_enqueue_style('responsive-video-light');  
}  
add_action( 'wp_enqueue_scripts', 'responsive_css' );  

//----------------------------------------------------------------------------
// Create the admin settings page
//----------------------------------------------------------------------------

function register_rvl_settings() { // whitelist options
  register_setting( 'rvl_options', 'rvl_options_field' );
}

function rvl_menu() {
  add_options_page(
    'Responsive Video Light Options', 
    'Responsive Video Light', 
    10, 
    'rvl_options', 
    'rvl_plugin_options'
  );
  add_action( 'admin_init', 'register_rvl_settings' );
}
add_action('admin_menu', 'rvl_menu');

function rvl_plugin_action_links($links, $file) {
  static $this_plugin;

  if (!$this_plugin) {
    $this_plugin = plugin_basename(__FILE__);
  }
  if ($file == $this_plugin) {
    $settings_link = '<a href="' . get_bloginfo('wpurl') 
      . '/wp-admin/admin.php?page=rvl_options">Settings</a>';
    array_unshift($links, $settings_link);
  }
  return $links;
}
add_filter('plugin_action_links', 'rvl_plugin_action_links', 10, 2);


//----------------------------------------------------------------------------
// Admin page plugin options
//----------------------------------------------------------------------------

function rvl_plugin_options() {
  
?>
  <div style="width:75%">
  <h2>Responsive Video Light Settings</h2>
  
	<form method="post" action="options.php">
	  <?php
	  wp_nonce_field('update-options'); 
    settings_fields('rvl_options'); 
    $options = get_option('rvl_options_field');

    // Our single global option
    $disable_youtube_related_videos = $options["disable_youtube_related_videos"];
	  ?>
    <p>
      We supply a single global option:  Indicate to YouTube that we do or do
      not wish to have "Related Videos" displayed at the end of the playing of
      our own video. This can be overridden on a per-video basis with an 
      argument in the short tag.  Please see the documentation below for more 
      info on available short tag arguments.
    </p>
    <p>
      <input name="rvl_options_field[disable_youtube_related_videos]" 
        type="checkbox" value="1" 
        <?php if ( $disable_youtube_related_videos == "1" ) { 
          ?> checked="checked" 
        <?php } ?>/>
      By default, indicate to YouTube that I do not wish to have related 
      videos displayed.
    </p>
    <p class="submit">
  	  <input type="submit" class="button-primary" 
  	    value="<?php _e('Save Changes') ?>" />
  	  <input type="hidden" name="action" value="update" />
  	</p>
	</form>
	
  <h3>Using the Short Tags</h3>
  <h4>YouTube Videos</h4>
  <p>
    Simply insert the responsive_youtube shorttag anywhere shorttags can be 
    used (posts, pages, wherever).  Include either the full URL to the video
    you're embedding (Not the &lt;embed&gt; URL, the full browser URL) or just
    use the video ID.  The following two shortcodes would work identically:
    <br /><code>[responsive_youtube http://www.youtube.com/watch?v=NbCr0UyoFJA]</code>
    <br /><code>[responsive_youtube NbCr0UyoFJA]</code>
  </p>
  <h5>YouTube's "Related Videos"</h5>
  <p>
    When a YouTube video is done playing, it will typically tile a selection
    related videos inside its viewport.  If you want to control whether or not
    those are shown on a per-video basis, you can use the <code>rel</code> or 
    <code>norel</code> 
    options to turn related videos on and off respectively, like this: 
    <br /><code>[responsive_youtube NbCr0UyoFJA rel]</code>
    <br /><code>[responsive_youtube NbCr0UyoFJA norel]</code>
  </p>
  <p>
    Of course, there's an option to tell YouTube that we'd like not to see 
    related videos on a global level on this page, but you can override it 
    on individual videos using these options.
  </p>
  
  <h4>Vimeo Videos</h4>
  <p>
    Simply insert the responsive_vimeo shorttag anywhere shorttags can be 
    used (posts, pages, wherever).  Include either the full URL to the video
    you're embedding (Not the &lt;embed&gt; URL, the full browser URL) or just
    use the video ID.  The following two shortcodes would work identically:
    <br /><code>[responsive_vimeo https://vimeo.com/29506088]</code>
    <br /><code>[responsive_vimeo 29506088]</code>
  </p>
  </div>
  
  <h4>Miscellany</h4>
  <p>
    You can use more than one responsive shortcode in any given post or page,
    And you can mix types as well (Vimeo and YouTube).
  </p>
<?php
}

//----------------------------------------------------------------------------
// Contextual help
//----------------------------------------------------------------------------

//TODO: Update URLs when plugin is accepted to the WordPress plugin site
function rvl_contextual_help($text) {
  $screen = $_GET['page'];
	if ($screen == 'rvl_options') {
	$text = '<h5>Need Help With the Responsive Video Light Plugin?</h5>';
	$text .= '<p><a href="http://wordpress.org/extend/plugins/responsive-video-light/">';
	$text .= 'Check out the Documentation</a></p>';
	}
	return $text;
}
add_action('contextual_help', 'rvl_contextual_help', 10, 1);

//----------------------------------------------------------------------------
// Create the YouTube shortcode
//----------------------------------------------------------------------------

function responsive_youtube_shortcode($attributes, $content = null) {
  $options = get_option('rvl_options_field');
  
  $options['disable_youtube_related_videos'] ? 
    $related_videos = false : $related_videos = true;
    
  $video_id = null;
  
  // Determine what options were passed in
  foreach($attributes as $attribute) {
    switch($attribute) {
      case "rel":
        $related_videos = true;
      break;
      case "norel":
        $related_videos = false;
      break;
      default:
        //Fairly primitive extraction - might watch to beef this up
        if (preg_match('/^http:\/\/.*(v=(\w*)).*$/', $attribute, $matches)) {
          $video_id = $matches[2];
        } else if (preg_match('/^\w*$/', $attribute)) {
          $video_id = $attribute;
        }
      break;
    }
  }
  
  // Format the related videos URL parameter
  $related_videos ? $rel_param = 1 : $rel_param = 0;
  
  // Format and return the content replacement for the short tag
  if ($video_id) {
    $content = '
      <div class="video-wrapper"> 
				<div class="video-container">
				<iframe src="http://www.youtube.com/embed/'.$video_id
				  .'?rel='.$rel_param.'" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
    ';
  } else {
    $content = "[OH DEAR: responsive_youtube has some malformed syntax.]";
  }
  return $content;
}
add_shortcode('responsive_youtube', 'responsive_youtube_shortcode'); 

//----------------------------------------------------------------------------
// Create the Vimeo shortcode
//----------------------------------------------------------------------------

function responsive_vimeo_shortcode($attributes, $content = null) {
  
  $video_id = null;
  
  // Determine what options were passed in (ignore anything that doesn't look 
  // like an id)
  foreach($attributes as $attribute) {
    switch($attribute) {
      default:
        //Fairly primitive extraction - might watch to beef this up
        if (preg_match('/^https?:\/\/.*\/(\d*)$/', $attribute, $matches)) {
          $video_id = $matches[1];
        } else if (preg_match('/^\d*$/', $attribute)) {
          $video_id = $attribute;
        }
      break;
    }
  }
  
  // Format and return the content replacement for the short tag
  if ($video_id) {
    $content = '
      <div class="video-wrapper"> 
				<div class="video-container">
				<iframe src="http://player.vimeo.com/video/'.$video_id
				  .'" frameborder="0" webkitAllowFullScreen mozallowfullscreen 
				  allowFullScreen></iframe> 
				</div>
			</div>
    ';
  } else {
    $content = "[OH DEAR: responsive_vimeo has some malformed syntax.]";
  }
  return $content;
}
add_shortcode('responsive_vimeo', 'responsive_vimeo_shortcode');

?>
