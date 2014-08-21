<?php
class Author_Stats_Counter extends WP_Widget {
	// Controller
	function __construct() {
	$widget_ops = array('classname' => 'author_widget', 'description' => __('Displays the details of the author related to a single post and other posts by the same author'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Author Stats'), $widget_ops, $control_ops );

        }

function form($instance) { 
	$defaults = array( 'title' => __('Author Details'), 'Enter no of posts to display' => __('5'),'checkbox_var' => __('0'), 'checkbox_variable' => ('0'),'radio-button' => ('0'));
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
                <input class="checkbox" type="checkbox" <?php checked($instance['checkbox_var'], 'on'); ?> id="<?php echo $this->get_field_id('checkbox_var'); ?>" name="<?php echo $this->get_field_name('checkbox_var'); ?>" /> 
                <label for="<?php echo $this->get_field_id('checkbox_var'); ?>">Show Top posts by author</label>
        </p>
        <p>
		<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('No. of Posts:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="number" value="<?php echo $post_count;?>" >
	</p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['checkbox_variable'], 'on'); ?> id="<?php echo $this->get_field_id('checkbox_variable'); ?>" name="<?php echo $this->get_field_name('checkbox_variable'); ?>" /> 
                <label for="<?php echo $this->get_field_id('checkbox_variable'); ?>">Show Author Details</label>
        </p>
        <p>
            Select the way you want to sort the posts.
        </p>
        <p>            
                <!--<input type="radio"
                id="<?php echo $this->get_field_id('radio-button'); ?>"
                name="<?php echo $this->get_field_name('radio-button'); ?>"
                <?php if (isset($instance['radio-button']) && $instance['radio-button']=="views") echo "checked";?>
                       value="views">Sort by views<br>
                <input type="radio"
                id="<?php echo $this->get_field_id('radio-button'); ?>"
                name="<?php echo $this->get_field_name('radio-button'); ?>"
                <?php if (isset($instance['radio-button']) && $instance['radio-button']=="comments") echo "checked";?>
                value="comments">Sort by comments
        </p>-->
        
<?php }
function update($new_instance,$old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['post_count'] = strip_tags( $new_instance['post_count'] );
    $instance['checkbox_var']=strip_tags($new_instance['checkbox_var']);
    $instance['checkbox_variable']=strip_tags($new_instance['checkbox_variable']);
    //$instance['radio-button']=strip_tags($new_instance['radio-button']);
    return $instance;
}

function widget($args, $instance) {
      
        $title = apply_filters('widget_title', $instance['title']);
        
        // Display the widget title
        if (is_single ()){
        echo "<div class='author-widget'>";
        if(is_single()){
            if ( $title ){
                echo "<h3 class='widget-title popular'>".$title."</h3>";
                 echo "<hr>";
            }
        
            if(function_exists("asc_get_author_id")){
                
                echo "By: ";
                
               
                asc_get_author_id();
                echo "<br>";
            }
            if($instance['checkbox_var'])
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
	}
       
        echo "</div>";
        }
        
}
}








































































































































