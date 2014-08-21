<?php
class Post_Stats_Counter extends WP_Widget {
	// Controller
	function __construct() {
	$widget_ops = array('classname' => 'my_widget_class', 'description' => __('Insert the plugin description here'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Post Stats Counter'), $widget_ops, $control_ops );
?>
<?php
}

function form($instance) { 
        
	$defaults = array( 'title' => __('Popular Posts'), 'post_count' => __('5'));
	$instance = wp_parse_args( (array) $instance, $defaults ); 

	if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			$post_count=$instance['post_count'];
		}
	else {
			$title =$defaults['title'];
			$post_count=$defaults['post_count'];
		}?>
	<p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Top Posts:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="number" value="<?php echo $post_count;?>" />
	</p>
        <p>            
                <input type="radio"
                id="<?php echo $this->get_field_id('radio-button'); ?>"
                name="<?php echo $this->get_field_name('radio-button'); ?>"
                <?php if (isset($instance['radio-button']) && $instance['radio-button']=="views") echo "checked";?>
                       value="views">Sort by views<br>
                <input type="radio"
                id="<?php echo $this->get_field_id('radio-button'); ?>"
                name="<?php echo $this->get_field_name('radio-button'); ?>"
                <?php if (isset($instance['radio-button']) && $instance['radio-button']=="comments") echo "checked";?>
                value="comments">Sort by comments
        </p>

<?php }
function update($new_instance,$old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['post_count'] = strip_tags( $new_instance['post_count'] );
    $instance['radio-button'] = strip_tags( $new_instance['radio-button'] );
    return $instance;
}

function widget($args, $instance) {
        
         echo '<div class="post-widget">';
    	$title = apply_filters('widget_title', $instance['title']);
        if ( $title ){
            echo "<h3 class='widget-title popular'>".$title."</h3>";
            echo "<hr>";
        }
        
        if (function_exists("asc_post_popularity_list_views")) {
        $post_count = $instance['post_count'];
        // Display the widget title	
	//Display the name 	
//    asc_post_popularity_list($post_count);
       
        if($instance['radio-button']=="views")
             asc_post_popularity_list_views($post_count);
        else
             asc_post_popularity_list_comments($post_count);
      
    }
  
    echo "</div>";
     
}
}









































