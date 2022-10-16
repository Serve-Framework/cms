<?php the_header(); ?>

<h1>Products</h1>

<!-- POSTS LOOP -->
<?php if (have_posts()) : ?>
    <div class="cards-flex">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="card">
                <a class="card-img" href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>">
                    <img title="<?php echo has_post_thumbnail() ? the_post_thumbnail()->title : the_title(); ?>" alt="<?php echo has_post_thumbnail() ? the_post_thumbnail()->alt : the_title(); ?>" src="<?php echo fallbackImg(the_post_thumbnail_src(null, 'medium')); ?>">
                </a>
                <div class="card-body">
                    <a class="card-title" href="<?php echo the_permalink(); ?>">
                        <h3><?php echo the_title(); ?></h3>
                    </a>
                    <time class="card-date" datetime="<?php echo the_time('c'); ?>">
                        <?php echo the_time('M d, Y'); ?>
                    </time>
                    <p class="card-text"><?php echo trimExcerpt(the_excerpt(), 180, '...', true); ?>    </p>
                    <a href="<?php echo the_permalink(); ?>" class="button">Read More</a>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
    <div class="row text-center floor-sm roof-sm">
        <ul class="pagination pagination-btns">
           <?php echo pagination_links(); ?>
        </ul>
    </div>
<?php else : ?>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">No Posts to Display!</h3>
            <p>We couldn't find any posts.</p>
        </div>
    </div>
<?php endif; ?>

<?php the_footer(); ?>