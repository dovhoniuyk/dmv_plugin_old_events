<?php get_header(); ?>
    
    <div class="content">

        <?php while( have_posts() ): the_post(); ?>
        
            <div class="post-preview">
                <div class="post_content">
                    <div class="thumbnail">
                        <?php the_post_thumbnail('full', $args ) ?>
                    </div>

                    <div class="title">
                        <?php the_title(); ?>
                    </div>
                    
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                    
                    <div class="date">
                        <?php echo get_post_meta( $post->ID, 'old_events_meta_key', 1 ); ?>
                    </div>
                    
                    <?php 
                        global $post;
                        
                        if(dmv_is_likes($post->ID) ) {

                            ?>
                                <div class="like_btn"><a data-action="del" href="#">Dislike</a></div>
                            <?php

                        }else {
                            
                            ?>
                                <div class="like_btn"><a data-action="add" href="#">Like</a></div>
                            <?php
                        }
                    ?>
                
                   
                    <?php comments_template('', true); ?>
        <?php endwhile; ?>
                    
                    
                </div>
        </div>
    </div>
    
<?php get_footer(); ?>