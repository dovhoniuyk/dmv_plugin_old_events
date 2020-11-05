<?php

$del_date = function($args, $assoc_args ) {
    $hh = $assoc_args['date'];
    $zal = $assoc_args['importance'];
    switch ($zal) {
		case 1:
			$zal = ['1'];
			break;
		case 2:
			$zal = ['1'];
			break;
		case 3:
			$zal = ['1','2'];
			break;
		case 4:
			$zal = ['1', '2', '3'];
			break;
		case 5: 
			$zal = ['1','2','3','4'];
		default:	
		$zal = ['1','2','3','4','5'];	

	}
    $arg = array(
        'post_type' => 'old_events',
        'importance' => $zal,
        'meta_query' => array(
            array(
                'key' => 'old_events_meta_key',
                'value' => $hh,
                'compare' => '>'
            ),
        ),
    );

    $query = new WP_Query($arg);
    
    while ( $query->have_posts() ) {
        $query->the_post();
        $post = get_the_ID();
        if($hh == true) {
            wp_delete_post( $post, true );
            WP_CLI::success( "Post deleted by date" );
        }
        if($zal == true) {
            wp_delete_post( $post, true );
            WP_CLI::success( "Post deleted by importance" );
        }
    }
    
};

if ( defined( 'WP_CLI' ) && WP_CLI )
WP_CLI::add_command( 'old_events delete', $del_date, array(
    'shortdesc' => 'Prints a greeting.',
    'synopsis' => array(
        array(
            'type'        => 'assoc',
            'name'        => 'date',
            'description' => 'Whether or not to greet the person with success or error.',
            'optional'    => true,
        ),
        array(
            'type'        => 'assoc',
            'name'        => 'importance',
            'description' => 'Whether or not to greet the person with success or error.',
            'optional'    => true,
        ),
    ),
    'when' => 'after_wp_load',
    'longdesc' =>   '## EXAMPLES' . "\n\n" . 'wp example hello Newman',
) );
