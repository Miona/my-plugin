<?php
   /*
   Plugin Name: Widget Pack Plugin
   Plugin URI: http://www.ideaboxthemes.com
   Description: A plugin to count post views and display popular posts using a sidebar widget or shortcode
   Version: 1.0
   Author: Shruti Taldar
   Author URI: http://ideaboxthemes.com
   License: GPL2 or later
   License URI: http://www.gnu.org/licenses/gpl-2.0.html
   */
?>
<?php 




add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );


function prefix_add_my_stylesheet() {
   
    wp_register_style( 'prefix-style', plugins_url('recent.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
}?>
<?php   
   

    
   

 

include 'recents.php';
add_action('widgets_init',create_function('', 'return register_widget("Recent_Comments");'));
?>
