<?php get_header(); ?>

<div class="container content-area">
    <div class="row">
        <main class="col-lg-8">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article class="card mb-4">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-img-top">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h2 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="card-text">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Leer más</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No hay contenido disponible.</p>
            <?php endif; ?>
        </main>
        
        <aside class="col-lg-4">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>