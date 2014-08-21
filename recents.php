<?php

class Recent_Comments extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'my_widget_class', 'description' => __('Insert the plugin description here'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Recent_Comments'), $widget_ops, $control_ops );
		$this->alt_option_name = 'widget_recent_comments';

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array($this, 'recent_comments_style') );

		
	}

	function recent_comments_style() {
		if ( ! current_theme_supports( 'widgets' ) 
			|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
			return;
		?>
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
	}

	
	function widget( $args, $instance ) {
		global $comments, $comment,$posttitle,$image;

		echo '<div class="comments">';
                 
 		extract($args, EXTR_SKIP);
 		$output = '';
                
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Comments' );
                
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
 			$number = 5;

		$comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) ) );
		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;

		$output .= '<ul id="recentcomments">';
               
                
		if ( $comments ) {
			

			foreach ( (array) $comments as $comment) {
                           
                            if($instance['post_title']){
                               
                               
                                 global $posttitle,$com;
                             $posttitle=get_the_title($comment->comment_post_ID);
                             }
                           echo '<div class="combine">';
                             
                             if($instance['comment_text']){
                               echo '<div class="text">';
                            $com=get_comment_text();
                            echo '</div>';
                             }
                             
                             if($instance['com_avatar']){
                                
                                 $var= get_comment_ID();
                                 $id = get_comment($var);
                                 $image=get_avatar($id,'32');
                                  
                             }
                            
                           echo "</div>";
                            $output .=  '<li class="recentcomments">' .  sprintf(_x('<br><br> %1$s on %2$s <br>  %3$s %4$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $posttitle. '</a>' ,'<div class="combine"><div class="avatar">'.$image .'</div>','<div class="text">'.$com.'</div></div>') . '</li>';
                                
			}
 		} 
                echo '</ul>';
		
		$output .= $after_widget;

		echo $output;
		
	}
        
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
		

		
                
                 $instance['post_title']=strip_tags($new_instance['post_title']);
                  $instance['comment_text']=strip_tags($new_instance['comment_text']);
                  $instance['com_avatar']=strip_tags($new_instance['com_avatar']);

		return $instance;
	}

	function form( $instance ) {
                 $defaults=array('title'=>'Recent Comments','comment_text'=>'0','number'=>__('5'),'com_avatar'=>'0','post_title'=>'0');
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
           <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['post_title'], 'on'); ?> id="<?php echo $this->get_field_id('post_title'); ?>" name="<?php echo $this->get_field_name('post_title'); ?>" /> 
                <label for="<?php echo $this->get_field_id('post_title'); ?>">Display post-title</label>
        </p>
        
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['comment_text'], 'on'); ?> id="<?php echo $this->get_field_id('comment_text'); ?>" name="<?php echo $this->get_field_name('comment_text'); ?>" /> 
                <label for="<?php echo $this->get_field_id('comment_text'); ?>">Display comment-text</label>
        </p>
         <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['com_avatar'], 'on'); ?> id="<?php echo $this->get_field_id('com_avatar'); ?>" name="<?php echo $this->get_field_name('com_avatar'); ?>" /> 
                <label for="<?php echo $this->get_field_id('com_avatar'); ?>">Display commenter's gravatar</label>
        </p>
       <?php 
	}
}
add_action('widgets_init',create_function('', 'return register_widget("Recent_Comments");'));



