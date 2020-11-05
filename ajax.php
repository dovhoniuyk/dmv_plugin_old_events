<?php 

// CALENDAR
function calendar() {

	$arg = array(
		'post_type' => 'old_events',
		'meta_key' => 'old_events_meta_key'
    );

    $query = new WP_Query($arg);
    $data = '[';
    while ( $query->have_posts() ) {
		$query->the_post();
		$ff = get_post_permalink();
		$id = get_the_ID();
        $dd =  get_post_meta( $id, 'old_events_meta_key', true);

	$data .= '{ "date": "' . $dd . ' ' . get_the_time(). '", "title": "' . get_the_title() . '",
	"description": "' . get_the_excerpt() . '", "url": "' . $ff . '"},';

}

$data = trim($data, ',');
$data .= ']';

wp_send_json($data);

}

add_action('wp_ajax_calcul', 'calendar'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_calcul', 'calendar'); // wp_ajax_nopriv_{action}\


// LOADMORE
function dmv_loadmore(){

	$quantity = get_option('quantity');
	$next_page = $_POST['page'] + 1;
	$args = array(
		'post_type'    => 'old_events',
		'posts_per_page' => $quantity['input'],
		'paged' => $next_page,
		'post_status' => 'publish'
	);
		
 
	$query = new WP_Query($args);
	while ( $query->have_posts() ) {
	
		$query->the_post();
 			?>

		<div data-id="<?php the_ID() ?>" class="post_content">

		<div class="thumbnail">
				<?php the_post_thumbnail('full', $args ) ?>
		</div>

		<div class="title">
			<a href="<?php echo get_post_permalink(); ?>"><?php the_title(); ?></a>
		</div>

		<div class="excerpt">
			<?php the_excerpt(); ?>
		</div>

		<div class="date">
			<?php echo get_post_meta( $post->ID, 'old_events_meta_key', true ); ?>
		</div>

		<div>
			<?php the_terms( '', 'importance', 'Importance:'); ?>
		</div>
		
	<?php wp_reset_postdata(); ?>
 
	<?php

	}
 
	wp_die();
}

add_action('wp_ajax_loadmore', 'dmv_loadmore'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'dmv_loadmore'); // wp_ajax_nopriv_{action}






// LIKE
function dmv_like_add() {
	if(!wp_verify_nonce($_POST['security'], 'liked') ) {
		wp_die('Ошибка');
	}
	$post_id = $_POST['postID'];
	$user = wp_get_current_user();
	
	if(dmv_is_likes($post_id) ) wp_die();

	if (add_user_meta($user->ID, 'dmv_like', $post_id)) {

		wp_die('Nice post!');
	}
	wp_die('Запрос завершен');
}

function dmv_like_del() {
	if(!wp_verify_nonce($_POST['security'], 'liked') ) {
		wp_die('Ошибка');
	}
	$post_id = $_POST['postID'];
	$user = wp_get_current_user();
	
	if(!dmv_is_likes($post_id) ) wp_die();

	if (delete_user_meta($user->ID, 'dmv_like', $post_id)) {
		wp_die('Bad post');
	}
	wp_die();
}

function dmv_is_likes($post_id) {
	$user = wp_get_current_user();
	$likes = get_user_meta($user->ID, 'dmv_like');

	foreach($likes as $like) {

		if($like == $post_id) return true;
	
	}

	return false;

}
add_action('wp_ajax_dmv_like_add', 'dmv_like_add');
add_action('wp_ajax_dmv_like_del', 'dmv_like_del');