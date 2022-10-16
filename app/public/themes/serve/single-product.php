<?php the_header(); ?>

<div class="card card-full">
    <div class="card-img">
        <img title="<?php echo has_post_thumbnail() ? the_post_thumbnail()->title : the_title(); ?>" alt="<?php echo has_post_thumbnail() ? the_post_thumbnail()->alt : the_title(); ?>" src="<?php echo fallbackImg(the_post_thumbnail_src(null, 'medium')); ?>">
    </div>
    <div class="card-body">
        <div class="card-title">
            <h3><?php echo the_title(); ?></h3>
        </div>
        <time class="card-date" datetime="<?php echo the_time('c'); ?>">
            <?php echo the_time('M d, Y'); ?>
        </time>
        <div class="markdown-body">
            <?php echo the_content(); ?>
        </div>
    </div>
</div>

<?php the_footer(); ?>