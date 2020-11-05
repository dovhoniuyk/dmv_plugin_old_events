<?php get_header(); 
    global $post;
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
        <div class="posts_container">
            <div class="my-search">
                <i class="fa fa-search search-dmv" aria-hidden="true"></i>
            </div>
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
    
    <div class="search-overlay ">
        <div class="search-overlay__top">
            <div class="container">
                   <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                <input type="text" class="search-term" id="search-term">
                <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
            </div>
        </div>
        <form id="ajax-contact-form" method="GET" name="forma[]">
                    <div class="data-range">
                        <input class="datafrom" type="date" name="datarange[]">
                        <input class="datato" type="date" name="datarange2[]">
                    </div>
                    
                        <lable>1<input class="importent-range" type="checkbox" name="radio" value="1"></lable>
                        <lable>2<input class="importent-range" type="checkbox" name="radio" value="2"></lable>
                        <lable>3<input class="importent-range" type="checkbox" name="radio" value="3"></lable>
                        <lable>4<input class="importent-range" type="checkbox" name="radio" value="4"></lable>
                        <lable>5<input class="importent-range"  type="checkbox" name="radio" value="5"></lable>
                    
                    <input class="submit-button" type="submit" value="Отправить"/>
                   
                </form>
        <div class="container-result">
            <div id="search-overlay__results"></div>
            <div class="search-hover"></div>
        </div>
    </div>
    
        </div>
    </div>
   
<?php get_footer(); ?>