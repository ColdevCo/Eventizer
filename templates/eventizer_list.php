<?php

get_header(); ?>

    <div id="main-content" class="main-content">

        <div id="primary" class="content-area">

            <div id="content" class="site-content" role="main">

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="entry-content">
                        <?= do_shortcode('[eventlist]'); ?>
                    </div>

                </article>

            </div>

        </div>

        <?php get_sidebar( 'content' ); ?>
        
    </div>

<?php
get_sidebar();
get_footer();
