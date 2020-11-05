<?php 

function eventsdmv_register_assets() {
	wp_register_style('eventsdmv-events-style',plugins_url('/css/dmv-events-style.css', __FILE__));
	wp_register_style('calendar_theme',plugins_url('/css/eventCalendar_theme_responsive.css', __FILE__));
	wp_register_style('calendarcss',plugins_url('/css/eventCalendar.css', __FILE__));

	wp_register_script('eventsdmv-events-scripts',plugins_url('/js/dmv-events-scripts.js', __FILE__), array('jquery'));
	wp_register_script('loadmore',plugins_url('/js/loadmore.js', __FILE__), array('jquery'),'1.0', true);
	wp_register_script('eventCalendar',plugins_url('/js/jquery.eventCalendar.js', __FILE__), array('jquery'),'1.0', true);
	wp_register_script('moment',plugins_url('/js/moment.js', __FILE__), array('jquery'),'1.0', true);
	wp_register_script('calendar',plugins_url('/js/calendar.js', __FILE__), array('jquery','moment','eventCalendar'),'1.0', true);
	wp_register_script('search-my',plugins_url('/js/search.js', __FILE__), array('jquery'),'1.0',true);
	
}

function dmv_widgets() {
    register_sidebar( array(
        'name' => 'Sidebar', 
        'id' => 'sidebar',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
    
}
add_action('widgets_init', 'dmv_widgets' );

function eventsdmv_events_scripts() {

	global $post, $wp_query;

	wp_localize_script( 'eventsdmv-events-scripts', 'myajax', array(
		'url'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'liked' ),
		'postID' => $post->ID,
		
	  ) );
	  
	wp_enqueue_script('eventsdmv-events-scripts', plugins_url('/js/dmv-events-scripts.js', __FILE__), array('jquery'));
	
	wp_localize_script( 'loadmore', 'loadajax', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
		'mypostID' => $post->ID,
		'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
		'max_page' => $wp_query->max_num_pages
	  ) );

	wp_enqueue_script('loadmore');
	wp_enqueue_script('eventCalendar');
	wp_enqueue_script('moment');

	wp_localize_script( 'calendar', 'calajax', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
		
	  ) );
	wp_enqueue_script('calendar');
	wp_enqueue_script('search-my');
	wp_localize_script( 'search-my', 'searchData', array(
		'root_url' => get_site_url(),
	));
	

	wp_enqueue_style('eventsdmv-events-style');
	wp_enqueue_style('calendar_theme');
	wp_enqueue_style('calendarcss');
	wp_enqueue_style('fontawesome','https://use.fontawesome.com/releases/v5.15.1/css/all.css');
	wp_enqueue_script('select2-dmv','https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js');
	

	if( is_page('events') || is_singular('old_events') ) {

		$custom_css = esc_html( get_option('custom') );
		wp_add_inline_style( 'eventsdmv-events-style', $custom_css );
		

}
	
}
function admin_style() {
	
	wp_enqueue_style('admin-styles',plugins_url('/css/admin-styles.css', __FILE__));
	wp_enqueue_style('select2-styles','https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css');

	wp_enqueue_script('repeater_metaboxes',plugins_url('/js/repeater_custom_field.js', __FILE__), array('jquery','select2-dmv'),'1.0', false);
	wp_enqueue_script('select2-dmv','https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js');
  }

  add_action('admin_enqueue_scripts', 'admin_style');




function eventsdmv_events_post() {

    register_post_type('old_events', array(
		'labels'             => array(
			'name'               => 'Old Events', 
			'singular_name'      => 'Old Event', 
			'add_new'            => 'Add new',
			'add_new_item'       => 'Add new event',
			'edit_item'          => 'Edit event',
			'new_item'           => 'New event',
			'view_item'          => 'View event',
			'search_items'       => 'Search event',
			'not_found'          => 'Event not found',
			'not_found_in_trash' => 'Event not found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Old Event'

		  ),
        'public'             => true,
        'custom-fields'      => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest' 		 => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','editor','thumbnail','excerpt','comments'),
		
	) );

	register_taxonomy(
        'importance',
        'old_events',
        array(
            'label' => 'Importance',
            'public' => true,
            'publicly_queryable'=> null,
            'hierarchical' => true,
            'rewrite' => true,
            
        )
		);
		for ($i = 1; $i <= 5; $i++) {
			wp_insert_term(
				$i,  
				'importance', 
				array()
			);
		}
	
}

function disallow_insert_term($term, $taxonomy) { 

	if ( $taxonomy === 'importance') { 

		return new WP_Error( 'disallow_insert_term', __('Your role does not have permission to add terms to this taxonomy') ); }

	 }

add_action( 'pre_insert_term', 'disallow_insert_term', 10, 2); 


add_action('add_meta_boxes', 'dmv_add_custom_box');

function dmv_add_custom_box() {
	
	$screens = array( 'old_events' );
	add_meta_box( 'dmv_section_id', 'Date of the event', 'dmv_meta_box_callback', $screens );
 
}




function dmv_meta_box_callback( $post) {

	wp_nonce_field( plugin_basename(__FILE__), 'dmv_security' );

	$value = get_post_meta( $post->ID, 'old_events_meta_key', true );
	
	echo '<input type="date" id="dmv_new_field" name="dmv_new_field" value="'. $value .'" size="1000" />';
}

add_action( 'save_post', 'dmv_save_postdate' );

function dmv_save_postdate( $post_id ) {

	if ( ! wp_verify_nonce( $_POST['dmv_security'], plugin_basename(__FILE__) ) )
		return;

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return;

	if( ! current_user_can( 'edit_post', $post_id ) )
		return;

	$my_data = sanitize_text_field( $_POST['dmv_new_field'] );


	update_post_meta( $post_id, 'old_events_meta_key', $my_data );
}