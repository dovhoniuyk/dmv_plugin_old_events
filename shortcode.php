<?php 
add_shortcode('importance', 'show_taxonomy');

function show_taxonomy($atts) {
	
    $atts = shortcode_atts(
		array(
			'count' => 5,
			'import' => '',
			'from' => '',
			'to' => ''
	), $atts);

	switch ($atts['import']) {
		case 1:
			$vas = ['2','3','4','5'];
			break;
		case 2:
			$vas = ['3','4','5'];
			break;
		case 3:
			$vas = ['4','5'];
			break;
		case 4:
			$vas = ['5'];
			break;
		case 5: 
			$vas = ['5'];
		default:	
		$vas = ['1','2','3','4','5'];	

	}
	if($atts['from'] && $atts['to']){
		$args = array(
			'post_type' => 'old_events',
			'importance' => $vas,
			'paged' => $paged,
			'meta_key' => 'old_events_meta_key',
			'meta_query' => array(
				array(
					'key' => 'old_events_meta_key',
					'value' => array($atts['from'], $atts['to']),
					'compare' => 'BETWEEN',
					'type' => 'DATE'
				),
			),
			
			
	);

}else

	$args = array(
		'post_type' => 'old_events',
		'importance' => $vas,
		'posts_per_page' => $atts['count'],
		'paged' => $paged,
	);

	$query = new WP_Query( $args);
					
	while ( $query->have_posts() ) {
		$query->the_post();
			?>
		<div class="post_content">

			<div class="thumbnail">
				<?php the_post_thumbnail('full', $args ) ?>
			</div>

			<div class="title">
				<a href="<?php echo get_post_permalink(); ?>"><?php the_title(); ?></a>
			</div>

			<div class="excerpt">
				<?php the_excerpt(); ?>
			</div>
	
			<?php echo get_post_meta( get_the_ID(), 'old_events_meta_key', true ); ?>
	
			<div>
				<?php the_terms( '', 'importance', 'Importance:'); ?>
            </div>
		
			<?php
			
	}
?>		
		</div>
<?php
    
}