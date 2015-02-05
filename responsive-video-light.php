<?php
/*
 * Plugin Name: Responsive Video Light
 * Plugin URI: http://bitpusher.tk/responsive-video-light
 * Description: A plugin to add responsive videos to pages and posts
 * Version: 1.2.1
 * Author: Bill Knechtel
 * Author URI: http://bitpusher.tk
 * License: GPLv2
 *
 * Copyright 2013 William Knechtel
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2 as published
 * by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

$base_path = plugin_dir_path(__FILE__);
require_once $base_path . '/twig/lib/Twig/Autoloader.php';

Twig_Autoloader::register();
$twig_loader = new Twig_Loader_Filesystem($base_path . '/templates');
$twig = new Twig_Environment(
    $twig_loader,
    array('cache' => $base_path . '/twig_cache')
);

function rvl_css()
{
    // Register the css styling to make the video responsive:
    wp_register_style(
        'responsive-video-light',
        plugins_url('/css/responsive-videos.css', __FILE__),
        array(),
        '20130111',
        'all'
    );
    wp_enqueue_style('responsive-video-light');
}

add_action('wp_enqueue_scripts', 'rvl_css');

// ----------------------------------------------------------------------------
// Create the admin settings page
// ----------------------------------------------------------------------------

function register_rvl_settings()
{ // whitelist options
    register_setting('rvl_options', 'rvl_options_field');
}

function rvl_menu()
{
    add_options_page(
        'Responsive Video Light Options',
        'Responsive Video Light',
        'activate_plugins',
        'rvl_options',
        'rvl_plugin_options'
    );

    add_action('admin_init', 'register_rvl_settings');
}

add_action('admin_menu', 'rvl_menu');

function rvl_plugin_action_links($links, $file)
{
    static $this_plugin;

    if (! $this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }
    if ($file == $this_plugin) {
        $settings_link =
            '<a href="'
            . get_bloginfo('wpurl')
            . '/wp-admin/admin.php?page=rvl_options">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_action_links', 'rvl_plugin_action_links', 10, 2);

// ----------------------------------------------------------------------------
// Admin page plugin options
// ----------------------------------------------------------------------------

function rvl_plugin_options()
{
    global $twig;
    $options = get_option('rvl_options_field');

    // Plugin options
    $rvl_plugin_options_data = array();
    $rvl_plugin_options_data['check_disable_youtube_related'] =
        $options["disable_youtube_related_videos"] == "1" ? 'checked="checked"' : '';
    $rvl_plugin_options_data['youtube_wmode'] = $options["youtube_wmode"];

    echo $twig->render('rvl_plugin_options_head.html');
    wp_nonce_field('update-options');
    settings_fields('rvl_options');
    echo $twig->render('rvl_plugin_options.html', $rvl_plugin_options_data);
}

// ----------------------------------------------------------------------------
// Create the YouTube shortcode
// ----------------------------------------------------------------------------
function responsive_youtube_shortcode($attributes, $content = null)
{
    $options = get_option('rvl_options_field');

    $related_videos = $options['disable_youtube_related_videos'] ? false :  true;

    $video_id = null;

    if ($options['youtube_wmode']) {
        switch ($options['youtube_wmode']) {
            case "transparent":
                $wmode = "&wmode=transparent";
                break;
            case "opaque":
                $wmode = "&wmode=opaque";
                break;
            default:
                $wmode = "";
                break;
        }
    } else {
        $wmode = "";
    }

    // Determine what options were passed in
    foreach ($attributes as $attribute) {
        switch ($attribute) {
            case "rel":
                $related_videos = true;
                break;
            case "norel":
                $related_videos = false;
                break;
            case "wmode_none":
                $wmode = "";
                break;
            case "wmode_opaque":
                $wmode = "&wmode=opaque";
                break;
            case "wmode_transparent":
                $wmode = "&wmode=transparent";
                break;
            default:
                // Fairly primitive extraction - might want to beef this up
                if (preg_match('/^http[s]?:\/\/.*(v=([-0-9a-zA-Z_]*)).*$/', $attribute, $matches)) {
                    $video_id = $matches[2];
                } elseif (preg_match('/^[-0-9a-zA-Z_]*$/', $attribute)) {
                    $video_id = $attribute;
                }
                break;
        }
    }

    // Format the related videos URL parameter
    $related_videos ? $rel_param = 1 : $rel_param = 0;

    // Format and return the content replacement for the shortcode
    if ($video_id) {
        $content = '
      <div class="video-wrapper">
        <div class="video-container">
          <iframe src="//www.youtube.com/embed/' . $video_id . '?rel=' . $rel_param . $wmode . '" frameborder="0" allowfullscreen></iframe>
        </div>
      </div>
    ';
    } else {
        $content = "[OH DEAR: responsive_youtube has some malformed syntax.]";
    }
    return $content;
}

add_shortcode('responsive_youtube', 'responsive_youtube_shortcode');

// ----------------------------------------------------------------------------
// Create the Vimeo shortcode
// ----------------------------------------------------------------------------
function responsive_vimeo_shortcode($attributes, $content = null)
{
    $video_id = null;
    $extra_params = array();

    // Determine what options were passed in (ignore anything that doesn't look
    // like an id)
    foreach ($attributes as $attribute) {
        switch ($attribute) {
            case "title":
                array_push($extra_params, "title=1");
                break;
            case "notitle":
                array_push($extra_params, "title=0");
                break;
            case "byline":
                array_push($extra_params, "byline=1");
                break;
            case "nobyline":
                array_push($extra_params, "byline=0");
                break;
            case "portrait":
                array_push($extra_params, "portrait=1");
                break;
            case "noportrait":
                array_push($extra_params, "portrait=0");
                break;
            case "notab":
                array_push($extra_params, "title=0");
                array_push($extra_params, "byline=0");
                array_push($extra_params, "portrait=0");
                break;
            default:

                // Fairly primitive extraction - might want to beef this up
                if (preg_match('/^https?:\/\/.*\/(\d*)$/', $attribute, $matches)) {
                    $video_id = $matches[1];
                } elseif (preg_match('/^\d*$/', $attribute)) {
                    $video_id = $attribute;
                }
                break;
        }
    }

    // Prepare $extra_params for insertion into the video URL
    if (count($extra_params) > 0) {
        $extra_params = '?' . join('&', $extra_params);
    } else {
        $extra_params = '';
    }

    // Format and return the content replacement for the shortcode
    if ($video_id) {
        $content = '
      <div class="video-wrapper">
        <div class="video-container">
        <iframe src="//player.vimeo.com/video/' . $video_id . $extra_params . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
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
