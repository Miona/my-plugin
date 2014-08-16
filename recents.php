<?php
class Recent_Comments extends WP_Widget {
	// Controller
	function __construct() {
	$widget_ops = array('classname' => 'my_widget_class', 'description' => __('Insert the plugin description here'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Recent_Comments'), $widget_ops, $control_ops );
?>
<?php
}

function form($instance) { 
        global $comment_count;
	$defaults = array( 'title' => __('Recent_comments'), 'comment_count' => __('5'),'checkbox_1' => __('0'),'checkbox_2' => __('0'),'checkbox_3' => __('0'),'post_count' => __('0'),'comment_ID' => __('0'));
	$instance = wp_parse_args( (array) $instance, $defaults ); 

	if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			$comment_count=$instance['comment_count'];
		}
	else {
			$title =$defaults['title'];
			$comment_count=$defaults['comment_count'];
		}?>
	<p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('comment_count'); ?>"><?php _e('Number of comments', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('comment_count'); ?>" name="<?php echo $this->get_field_name('comment_count'); ?>" type="number" value="<?php echo $comment_count;?>" />
	</p>
                    
                <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['checkbox_1'], 'on'); ?> id="<?php echo $this->get_field_id('checkbox_1'); ?>" name="<?php echo $this->get_field_name('checkbox_1'); ?>" /> 
                <label for="<?php echo $this->get_field_id('checkbox_1'); ?>">Display post-title</label>
        </p>
        
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['checkbox_2'], 'on'); ?> id="<?php echo $this->get_field_id('checkbox_2'); ?>" name="<?php echo $this->get_field_name('checkbox_2'); ?>" /> 
                <label for="<?php echo $this->get_field_id('checkbox_2'); ?>">Display comment-text</label>
        </p>
         <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['checkbox_3'], 'on'); ?> id="<?php echo $this->get_field_id('checkbox_3'); ?>" name="<?php echo $this->get_field_name('checkbox_3'); ?>" /> 
                <label for="<?php echo $this->get_field_id('checkbox_3'); ?>">Display commenter's gravatar</label>
        </p>
        

<?php }
function update($new_instance,$old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['comment_count'] = strip_tags( $new_instance['comment_count'] );
    $instance['checkbox_1']=strip_tags($new_instance['checkbox_1']);
    $instance['checkbox_2']=strip_tags($new_instance['checkbox_2']);
     $instance['checkbox_3']=strip_tags($new_instance['checkbox_3']);
    //$instance['radio-button']=strip_tags($new_instance['radio-button']);
    return $instance;
}
function widget($args, $instance) {
      
        $title = apply_filters('widget_title', $instance['title']);
        $comment_count = $instance['comment_count'];
        // Display the widget title
        echo "<div class='comment-widget'>";
       
            if ( $title ){
                echo "<h3 class='widget-title'>".$title."</h3>";
                 echo "<hr>";
                
            }
            global $post_count,$comment_id;
            $comment_id = get_comment_ID();
            $args = array(
        "posts_per_page" => $post_count,
        "comment_ID"=> $comment_id,
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
        
            //echo '<a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a>';
            
            //if (function_exists('wp_get_comment')) {
            
            
            
            if($instance['checkbox_1']){
                echo "<br>";
                echo 'Post Title:';
               echo '<a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a>';
               echo '<br />';
            }
            if($instance['checkbox_2']){
             
             
               
                
               /* echo ':<br /> ';
               // echo comment_text(); 
               echo 'Comment by:' ;*/
                comment_author();
              echo '<br />';
                echo 'comment-text:';
              
                echo comment_text();

               

               
            }
             if($instance['checkbox_3']){
                  echo '<br />';
                 // echo get_avatar( $comment,  ); 
                  //echo get_avatar( get_post_meta( 'ID' ), 32 );
                  echo get_avatar(1, '32'); 
              
                 
             }
            
            //}
           /* else {
                echo 'hello';
            }*/
           /* if(function_exists("asc_get_author_id")){
                
                echo "By: ";
                
               
                asc_get_author_id();
                echo "<br>";
            }*/
          /*  if($instance['checkbox_var'])
            {
        	if (function_exists("asc_show_author_post_view")) 
                    {
                        if($instance['post_count']){
                            $post_count = apply_filters('widget_title',$instance['post_count']); 
                        }
                        else{
                            $post_count= 5;
                        }
                        echo 'More posts by the author:<br>';
                        
                        if($instance['radio-button']=="views")
                        asc_show_author_post_view($post_count);
                        else
                            asc_show_author_post_comment($post_count);
                    }
            }
            if($instance['checkbox_variable']){
                echo 'Author Description: ';
                asc_get_author_details();
            }
	}*/     
            endwhile;
        echo "</div>";
        
        

}
}


