<?php get_header(); ?>

<div class="container content-area">
    <div class="row">
        <main class="col-lg-8">
            <?php while (have_posts()) : the_post(); ?>
                <article class="card">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="card-img-top">
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h1 class="card-title"><?php the_title(); ?></h1>
                        <div class="card-text">
                            <?php the_content(); ?>
                        </div>
                        <div class="mt-3 text-muted">
                            <small>
                                Publicado el <?php echo get_the_date(); ?> por <?php the_author(); ?>
                            </small>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </main>
        
        <aside class="col-lg-4">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>