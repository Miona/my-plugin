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


/*function wp_comment_count( ){
    $post_id = get_queried_object_id();
        $comment_count = get_post_field( 'post_author', $post_id );
return $comment_count;
}

 function wp_show_comment($post_count){
    $comment_count= wp_comment_count();
                                

    $auth_list=new WP_Query(
            array(
                'author'=>$comment_count,
                "posts_per_page" => $post_count,
                "meta_key" => "asc_views",
                "orderby"=>'meta_value_num',
                "order" => "DESC"
            ));
    if($auth_list->have_posts()){
       // echo '<a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta( 'user_firstname', $auth_id)." ".get_the_author_meta( 'user_lastname', $auth_id).'</a>'."<br>".get_the_author_meta('description',$auth_id);
        while ( $auth_list->have_posts() ) : $auth_list->the_post();
            echo "<div class='auth-post-list'>";         
            echo '<a href="'.get_permalink().'">'.the_title('', '', false).'</a>'.'<br>';
            echo comments_number();
            
            echo "</div>";
        endwhile;
         
    }
   // echo '<a href="'.get_author_posts_url($auth_id).'">'.get_the_author_meta( 'description', $auth_id).'</a>';
}*/

function wp_get_comment_count(){
    global $count;
    $count= comment_number() ;
    return $count;
}

    
   
    
   // echo '<a href="'.get_author_posts_url($auth_id).'">'.get_the_author_meta( 'description', $auth_id).'</a>';

 

include 'recents.php';
add_action('widgets_init',create_function('', 'return register_widget("Recent_Comments");'));
?>
