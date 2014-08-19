<?php

class Recent_Comments extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'my_widget_class', 'description' => __('Insert the plugin description here'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Recent_Comments'), $widget_ops, $control_ops );
		$this->alt_option_name = 'widget_recent_comments';

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array($this, 'recent_comments_style') );

		add_action( 'comment_post', array($this, 'flush_widget_cache') );
		add_action( 'edit_comment', array($this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array($this, 'flush_widget_cache') );
	}

	function recent_comments_style() {
		if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
			|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
			return;
		?>
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
	}

	/*function flush_widget_cache() {
		wp_cache_delete('widget_recent_comments', 'widget');
	}*/

	function widget( $args, $instance ) {
		global $comments, $comment;

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
			// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
			$post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
			_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );
                       

			foreach ( (array) $comments as $comment) {
                            /*if($instance['checkbox_2']){
                               
                            $com=get_comment_text();}*/
                             if($instance['checkbox_1']){
                               
                                // echo "on";
                             $posttitle=get_the_title($comment->comment_post_ID);
                             }
                           echo '<div class="combine">';
                             
                             if($instance['checkbox_2']){
                               echo '<div class="text">';
                            $com=get_comment_text();
                            echo '</div>';
                             }
                             
                             if($instance['checkbox_3']){
                                 $var= get_comment_ID();
                                 $id = get_comment($var);
                                 $image=get_avatar($id,'32');
                                  
                             }
                            
                           echo "</div>";
                            $output .=  '<li class="recentcomments">' . /*translators: comments widget: 1: comment author, 2: post link*/  sprintf(_x('<br><br> %1$s on %2$s <br>  %3$s %4$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $posttitle . '</a>' ,'<div class="combine"><div class="avatar">'.$image .'</div>','<div class="text">'.$com.'</div></div>') . '</li>';
                                //$output .=  '<li class="recentcomments">' . /*translators: comments widget: 1: comment author, 2: post link*/  sprintf(_x('%1$s on %2$s <br> %3$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a>',get_comment_text()) . '</li>';
			}
 		}
		//$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('widget_recent_comments', $cache, 'widget');
	}
        
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
		//$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_comments']) )
			delete_option('widget_recent_comments');
                
                 $instance['checkbox_1']=strip_tags($new_instance['checkbox_1']);
                  $instance['checkbox_2']=strip_tags($new_instance['checkbox_2']);
                  $instance['checkbox_3']=strip_tags($new_instance['checkbox_3']);

		return $instance;
	}

	function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
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
       <?php 
	}
}
add_action('widgets_init',create_function('', 'return register_widget("Recent_Comments");'));



