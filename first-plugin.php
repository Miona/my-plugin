<?php
   /*
   Plugin Name: Author Stats Counter
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
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
}?>
<?php
//add_action( 'admin_init', 'asc_show_views' );
//add_action( 'wp_head','asc_show_views');
add_action( 'wp_head','asc_add_view');
add_action( 'init', 'register_shortcodes');

function register_shortcodes(){
  //add_shortcode('posts', 'asc_add_view');
  add_shortcode('pst', 'disp_func');
}
function asc_add_view(){
    if(is_single()){
        global $post;    
        $current_views=get_post_meta($post->ID, "asc_views", true);
        if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
            $current_views = 0;
        }
        $new_views = $current_views + 1;
        update_post_meta($post->ID, "asc_views", $new_views);
        return $new_views;
    }
}
function disp_func( $atts ) {
    extract (shortcode_atts(array(
        'count'=> 2,
        ) ,$atts ,'pst' )
        
        );

asc_post_popularity_list_views($count);
}
function catch_that_image() {
  		global $post, $posts;
  		$first_img = '';
  		ob_start();
  		ob_end_clean();
                if(preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches)){
               $first_img = $matches [1] [0];
               return $first_img;
}
               
  		/*$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
                $first_img = $matches [1] [0];*/

  		if(empty($first_img)){ //Defines a default image
  			$first_img = bloginfo('template_directory');
    		$first_img .= "http://www.kaileenelise.com/wp-content/uploads/2014/07/lifeguard_unsplash.png ";
  		}
  		return $first_img;
	}
        
   

function asc_get_view_count() {
    global $post;            
    $current_views = get_post_meta($post->ID, "asc_views", true);
    if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
        $current_views = 0;
    }
    return $current_views;
}

function asc_author_id( ){
    $post_id = get_queried_object_id();
        $auth_id = get_post_field( 'post_author', $post_id );
return $auth_id;
}
function asc_get_author_id( ){
    $post_id = get_queried_object_id();
    $auth_id = get_post_field( 'post_author', $post_id );
  
    echo '<a href="'.get_author_posts_url( '$auth_id' ).'">'.get_the_author_meta( 'user_firstname', $auth_id)." ".get_the_author_meta( 'user_lastname', $auth_id).'</a>';
     
     echo "<div class='gravatar'>";
     echo get_avatar($auth_id,$size='100');
     
     echo "</div>";
     
}
function asc_get_author_details($post_id=0){
     $auth_id = get_post_field( 'post_author', $post_id );
    //$auth_id = asc_get_author_id(); 
    echo get_the_author_meta( 'description', $auth_id);
}
function asc_show_author_post_view($post_count){
    $auth_id = asc_author_id();
                                

    $auth_list=new WP_Query(
            array(
                'author'=>$auth_id,
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
}

function asc_show_author_post_comment($post_count,$post_id=0){
    
    $post_id = get_queried_object_id();
    echo $post_id; 
    $auth_id = get_post_field( 'post_author', $post_id );
    $auth_list=new WP_Query(
            array(
                'author'=>$auth_id,
                "posts_per_page" => $post_count,
                "orderby"=>'comment_count',
                "order" => "DESC"
            ));
    if($auth_list->have_posts()){
       //echo '<a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta( 'user_firstname', $auth_id)." ".get_the_author_meta( 'user_lastname', $auth_id).'</a>'."<br>".get_the_author_meta('description',$auth_id);
        while ( $auth_list->have_posts() ) : $auth_list->the_post();
            echo "<div class='auth-post-list'>"; 
            //the_post_thumbnail('featured-thumb');
            

            
            echo '<a href="'.get_permalink().'">'.the_title('', '', false).'</a>';
            echo comments_number();
            echo "</div>";
        endwhile;
    }
}

function asc_show_views($singular = "view", $plural = "views", $before = "This post has: ") {
    global $post;
    // asc_add_view();
    
  
    $current_views = get_post_meta($post->ID, "asc_views", true);  
    $views_text = $before . $current_views . " ";
    if ($current_views == 1) {
        $views_text .= $singular;
    }
    else {
        $views_text .= $plural;
    }
    echo $views_text."<br>";
    echo comments_number();
}


 
function asc_post_popularity_list_views($post_count) {
    $args = array(
        "posts_per_page" => $post_count,
        "post_type" => "post",
        "post_status" => "publish",
        "meta_key" => "asc_views",
        "orderby" => "meta_value_num",
        "order" => "DESC"
    );
    global $post;
    $asc_list = new WP_Query($args);
    if($asc_list->have_posts()) { echo "<ul class=posts>"; }   
        while ( $asc_list->have_posts() ) : $asc_list->the_post();  
        echo "<li class='post-list'>";
            echo '<a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a>';
            echo "<br>";
     echo "<div class='image'>";             
             if(has_post_thumbnail()){
                the_post_thumbnail(array(100,100),'featured-thumb');
            }
            else{
     
    echo '<img src="'; 
    echo catch_that_image();
    echo '" alt="first-image"/>';        
  }
  echo "</div>";
  echo "<div class='show'>";
            asc_show_views();
           echo "</div>";
          echo " </li>";
        endwhile;
	if($asc_list->have_posts()) { echo "</ul>";}
}

function asc_post_popularity_list_comments($post_count) {
    $args = array(
        "posts_per_page" => $post_count,
	"post_type" => "post",
	"post_status" => "publish",
	"orderby" => "comment_count",
	"order" => "DESC"
    );
    global $post;
    $asc_list = new WP_Query($args);
    if($asc_list->have_posts()) { echo "<ul>"; }
        while ( $asc_list->have_posts() ) : $asc_list->the_post();                                     
            echo '<li><a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a></li>';
             if(has_post_thumbnail()){
                the_post_thumbnail(array(70,70));
            }
            else{
                
    //echo "<div class='featured-thumbnail'>" ;
    echo '<img src="'; 
    echo catch_that_image();
    echo '" alt="first-image" width="70px", height="70px"/>';
    
  
  } 
            asc_show_views();        
	endwhile;
	if($asc_list->have_posts()) { echo "</ul>";}
}

  
  
include 'author_widget.php';
include 'post_widget.php';
add_action('widgets_init',create_function('', 'return register_widget("Post_Stats_Counter");'));
add_action('widgets_init',create_function('', 'return register_widget("Author_Stats_Counter");'));
?>

