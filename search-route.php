<?php

add_action('rest_api_init', 'dmv_rest_register_route');

 function dmv_rest_register_route() {
    register_rest_route('dmv_old_events/v2', 'search', array(
        'methods'  => 'GET',
        'callback' => 'dmv_search_get_result',
    ));
}

function dmv_search_get_result($data) {
    $decod = json_decode($data['importance'], true);
    
    

    if($data['date_from'] != 0 && $data['date_to'] != 0) {
        $args = array(
            'post_type' => 'old_events',
            's' => sanitize_text_field($data['term']),
            'importance' => $decod['terms'],
            'meta_key' => 'old_events_meta_key',
            'meta_query' => array(
                array(
                    'key' => 'old_events_meta_key',
                    'value' => array($data['date_from'], $data['date_to']),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ),
            ),
        ); 
    }
    
    if($data['date_from'] == 0 && $data['date_to'] == 0 && $data['importance'] == 0) {
        $args = array(
            'post_type' => 'old_events',
            's' => sanitize_text_field($data['term'])
        );
    }
    $old_events = new WP_Query($args);
    $eventsResults = array( );
    
    while($old_events->have_posts()) {
        $old_events->the_post();
        $gg = get_post_meta(get_the_ID(),'dmv_repeater_meta_key', true);
            foreach($old_events as $event) {
                $events = array();
                $dd = get_post_meta(get_the_ID(),'dmv_save_posts_event', true);
                $gg = get_post_meta(get_the_ID(),'dmv_repeater_meta_key', true);
                foreach($dd as $id) {
                    array_push($events, array(
                        'title' => get_the_title($id),
                        'permalink' => get_the_permalink($id),
                    ));  
            }
    }
    array_push($eventsResults, array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'events' => $events,
        'peoples' => $gg
        
    ));
    }
    
    return $eventsResults;
   
}