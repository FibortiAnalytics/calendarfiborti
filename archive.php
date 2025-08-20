<?php get_header(); ?>

<div class="container content-area">
    <div class="row">
        <main class="col-lg-8">
            <header class="mb-4">
                <h1 class="archive-title">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        the_author();
                    } elseif (is_day()) {
                        echo get_the_date();
                    } elseif (is_month()) {
                        echo get_the_date('F Y');
                    } elseif (is_year()) {
                        echo get_the_date('Y');
                    } else {
                        echo 'Archivo';
                    }
                    ?>
                </h1>
            </header>
            
            <?php if (have_posts()) : ?>
                <div class="row">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-md-6 mb-4">
                            <article class="card h-100">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h5>
                                    <p class="card-text"><?php the_excerpt(); ?></p>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">Leer más</a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p>No hay contenido en este archivo.</p>
            <?php endif; ?>
        </main>
        
        <aside class="col-lg-4">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>