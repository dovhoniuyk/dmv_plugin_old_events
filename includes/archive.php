<?php get_header(); 
 
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $quantity = get_option('quantity');
    $val = get_option('check');
            
    $args = array(
        'post_type' => 'old_events',
        'posts_per_page' => $quantity['input'],
        'paged' => $paged,
    ); 
    $query = new WP_Query( $args );

?>
    <div class="content">
        <div class="posts_container">
            <?php
                while ( $query->have_posts() ) {
                  $query->the_post();
                   
            ?>
                    
                <div data-id="<?php the_ID() ?>"  class="post_content">
                    <div class="thumbnail">
                        <?php the_post_thumbnail('medium', $args ) ?>
                    </div>

                    <div class="title">
                        <a data-url="<?php the_ID() ?>" href="<?php echo get_post_permalink(); ?>"><?php the_title(); ?></a>
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
            <?php
            
                }
               ?>              
                 
                <?php wp_reset_postdata(); ?>
                
                </div>

                <?php
                    if($val['radio'] == 1) {
                        echo paginate_links( array(
                            'total' => $query->max_num_pages,
                            'current' => $paged,
                    ) );
                
                    }else {
	                    echo '<div class="load_more">More posts</div>'; 
                    }               
                ?>
    
    
        </div>
    </div>
   
<?php get_footer(); ?>