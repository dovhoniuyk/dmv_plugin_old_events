<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              domyvi
 * @since             1.0.0
 * @package           Dmv_Events
 *
 * @wordpress-plugin
 * Plugin Name:       EventsDMV
 * Plugin URI:        
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            domyvi
 * Author URI:        
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       eventsdmv
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DMV_EVENTS_VERSION', '1.0.0' );

require __DIR__ . '/functions.php';
require __DIR__ . '/ajax.php';
require __DIR__ . '/class-plugin-cli-command.php';
require __DIR__ . '/widget.php';
require __DIR__ . '/shortcode.php';


add_action('wp_enqueue_scripts', 'eventsdmv_register_assets');
add_action('wp_enqueue_scripts', 'eventsdmv_events_scripts');

add_action('init', 'eventsdmv_events_post');

function eventsdmv_show_nav_item() {
	
	add_menu_page(
		esc_html__('First plugin for WP', 'eventsdmv'),
		esc_html__('EventsDMV', 'eventsdmv'),
		'manage_options',
		'eventsdmv',
		'eventsdmv_show_content',
		'dashicons-editor-code',
		26
	);
}

add_action('admin_menu', 'eventsdmv_show_nav_item');




function eventsdmv_show_content() {
	
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<?php
		if( get_current_screen()->parent_base !== 'options-general' )
			settings_errors('dmv_option');
		?>

		<form action="options.php" method="POST">

			<?php
				settings_fields( 'option_group' );     
				do_settings_sections( 'primer_page' ); 
				submit_button('Save');
			?>

		</form>
	</div>

	<?php
}

add_action('admin_init', 'dmv_plugin_settings');

function dmv_plugin_settings() {
	
	register_setting( 'option_group', 'quantity', 'sanitize_callback' );
	register_setting( 'option_group', 'check', 'sanitize_callback' );
	register_setting( 'option_group', 'custom', 'sanitize_callback' );

	
	add_settings_section( 'section_id', 'Settings', '', 'primer_page' ); 

	
	add_settings_field('primer_field1', 'Quantity of posts', 'dmv_quantity_field', 'primer_page', 'section_id' );
	add_settings_field('primer_field2', 'Pagins or load', 'dmv_check_field', 'primer_page', 'section_id' );
	add_settings_field('primer_field3', 'Custom CSS', 'dmv_custom_field', 'primer_page', 'section_id' );

}


function dmv_quantity_field() {

	$val = get_option('quantity');
	$val = $val ? $val['input'] : null;

	?>
		<input type="number" name="quantity[input]" value="<?php echo esc_attr( $val ) ?>" min="1" />
	<?php

}

function dmv_check_field() {

	$val = get_option('check');
	$val = $val ? $val['radio'] : null;

	?>
		<label><input type="radio" name="check[radio]" value="1" <?php checked( 1, $val ) ?> /> Pagins</label>
		<label><input type="radio" name="check[radio]" value="2" <?php checked( 2, $val ) ?> /> Load more</label>
	<?php
	
}

function dmv_custom_field() {
	?>
		<textarea name="custom" rows="10" cols="40"><?php echo esc_html(get_option('custom'));?></textarea>
	<?php

}


function sanitize_callback( $options ) { 
	
	foreach( $options as $name => & $val ){
		if( $name == 'input' )
			$val = strip_tags( $val );

		if( $name == 'radio' )
			$val = intval( $val );
	}

	return $options;
}


register_activation_hook( __FILE__, 'eventsdmv_register_page' );

function eventsdmv_register_page($updated) {

	$my_post = array(
		'post_title' => 'Events',
		'post_content' => '',
		'post_status' => 'publish',
		'post_type'		=> 'page',
	 );
	 
  
	 wp_insert_post( $my_post );
	 
}


function dmv_single_page_template($single_template) {
		
	if (is_page('events')) {
		$single_template = dirname( __FILE__ ) . '/includes/archive.php' ; 
	}
		
	return $single_template;
}

add_action( 'template_include', 'dmv_single_page_template' );

function dmv_page_template() {
	$only_template = dirname( __FILE__ ) . '/includes/archive-old_events.php' ;

	return $only_template; 
}
add_action( 'single_template', 'dmv_page_template' );











