<?php get_header(); ?>

<div class="container content-area">
    <div class="row">
        <main class="col-12">
            <?php while (have_posts()) : the_post(); ?>
                <article class="card">
                    <div class="card-body">
                        <h1 class="card-title"><?php the_title(); ?></h1>
                        <div class="card-text">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>