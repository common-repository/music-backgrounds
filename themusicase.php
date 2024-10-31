<?php
/*
Plugin Name: Background Music
Plugin URI: http://www.themusicase.com/texts/wordpress-plugin.html
Description: Plugin provides 450 professional music tracks included in a royalty free music player, helping owners use legal music backgrounds without worrying about royalties. Player includes licensed and rights pre-cleared music - completely royalty free. No royalties - just select the track, play in your website.
Author: Themusicase.com
Version: 1.0
Author URI: http://www.themusicase.com
*/
include_once(ABSPATH . 'wp-includes/pluggable.php');
function themusicase_options() {
      add_options_page("Background Music", "Background Music", 1, "themusicase.php", "themusicase_admin");
}
function themusicase_admin() {
		include('themusicase_import_admin.php');
}

function admin_stylesheet() {
            wp_register_style('myColorStyleSheets', plugins_url( '/css/jquery.minicolors.css', __FILE__ ));
            wp_enqueue_style( 'myColorStyleSheets');
            add_my_stylesheet();
}

function add_my_stylesheet() {
            wp_register_style('myStyleSheets', plugins_url( '/css/main.css', __FILE__ ));
            wp_enqueue_style( 'myStyleSheets');
}
function wptuts_scripts_basic()
{

    wp_enqueue_script('jquery');
    wp_register_script( 'custom-script', plugins_url( '/js/jquery.jplayer.min.js', __FILE__ ) );
    wp_register_script( 'custom-player', plugins_url( '/js/player.js', __FILE__ ) );
    wp_enqueue_script( 'custom-script' );
    wp_enqueue_script( 'custom-player' );
}

function admin_scripts(){
   wp_register_script( 'colors-script', plugins_url( '/js/jquery.minicolors.js', __FILE__ ) );
   wp_enqueue_script( 'colors-script' );
   wptuts_scripts_basic();
}

function themusicase_player() {
            $var1=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $home_url=home_url();
            $str = preg_replace('#^https?://#', '', $home_url);
            $found=0;
            if (strcasecmp($var1, $str) == 0) {
                $found=1;
            }
            if($found==0){
                $str=$str."/";
            }
            if (strcasecmp($var1, $str) == 0) {
                $found=1;
            }
            $all_pages=get_option('themusicase_all_pages');
            if($all_pages==1) $found=1;

            $link="<div class=\"pad\"></div>";
            $t_show_link=get_option('themusicase_show_link');
            if($t_show_link==1)
                $link="<div class=\"powered\">Music provided by Themusicase.com <a href=\"http://www.themusicase.com\" target=\"_blank\">Royalty free music</a></div>";

            if($found==1){
            $track=get_option('themusicase_track');
            $track=$track."&p=".$t_show_link;
            $autoplay=get_option('themusicase_autoplay');
            $autorepeat=get_option('themusicase_autorepeat');
            $fontColor = get_option('themusicase_fontColor');
			$bgroundColor = get_option('themusicase_bgroundColor');
			$grandientUp = get_option('themusicase_grandientUp');
			$grandientDown = get_option('themusicase_grandientDown');
$style="style='color: $fontColor; background-color: $bgroundColor;'";
            $style2="style='background: -webkit-gradient(linear, left top, left bottom, from($grandientUp), to($grandientDown));
background: -webkit-linear-gradient(top, $grandientUp,$grandientDown);
background-color:$grandientDown;
background: -moz-linear-gradient(top, $grandientUp, $grandientDown);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=$grandientUp, endColorstr=$grandientDown);'";
     $x="
    <script>
        var src=\"$track\";
        var autoplay=$autoplay;
        var autorepeat=$autorepeat;
    </script>
    <div id=\"jquery_jplayer\"></div>
        <div id=\"jp_container\">
        <div id=\"player\">
    			<ul class=\"nav\" $style2>
                    <li>
                        <a href=\"javascript:void(0);\" class=\"iconp-home jp-play\" $style ></a>
                        <a href=\"javascript:void(0);\" class=\"iconp-cog jp-pause\" $style ></a>
                    </li>
                    <li><a href=\"javascript:void(0);\" class=\"iconp-cw jp-stop\" $style ></a></li>
    			</ul>
                </div>
                $link
        </div>
        ";
    echo $x;
    }
}

add_action( 'wp_enqueue_scripts', 'wptuts_scripts_basic' );
add_action('wp_print_styles', 'add_my_stylesheet');
$not_footer=get_option('themusicase_not_footer');
if($not_footer==0) add_filter('wp_footer', 'themusicase_player');
if (current_user_can('update_plugins')) add_action('admin_menu', 'themusicase_options');
add_action( 'admin_init', 'admin_scripts' );
add_action('admin_head', 'admin_stylesheet');
?>
