<?php


// Creating the widget 
class dmv_widget extends WP_Widget {
  
	function __construct() {
	parent::__construct(
	  
	// Base ID of your widget
	'dmv_widget', 
	  
	// Widget name will appear in UI
	__('DMVWidgetCalendar', 'dmv_widget_domain'), 
	  
	// Widget description
	array( 'description' => __( 'Calendar widget', 'dmv_widget_domain' ), ) 
	);
	}
	  
	// Creating widget front-end
	  
	public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	
	  
	// before and after widget arguments are defined by themes
	echo $args['before_widget'];
	if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title'];
	  
	// This is where you run the code and display the output
	
	?>
		
		<div id="eventCalendar"></div>

	<?php

	echo $args['after_widget'];
	}
			  
	// Widget Backend 
	public function form( $instance ) {
	 
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );

		?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>


	<?php 
	}
	public function update( $new_instance ) {
		$instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        
		return $instance;
	}  
	
	 
	// Class dmv_widget ends here
	} 
	 
	 
	// Register and load the widget
	function wpb_load_widget() {
		register_widget( 'dmv_widget' );
	}
	add_action( 'widgets_init', 'wpb_load_widget' );