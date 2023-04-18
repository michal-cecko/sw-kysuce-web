<?php $posts = $args['posts'] ?? false; ?>
<div class="posts-list row">
    <?php if ($posts && $posts->have_posts()) : ?>
        <?php while ($posts->have_posts()) : $posts->the_post(); ?>
            <div class="col-md-4 col-sm-6">
                <?php get_template_part("template_parts/blog/article-card", "", [
                    'direction' => 'column',
                    'article' => get_post(),
                    'size' => 'small',
                    'show_category' => false
                ]) ?>
            </div>
        <?php endwhile;
        wp_reset_query(); ?>
    <?php else: ?>
        <div class="col-12">
            <div class="empty">Neboli nájdené žiadne články.</div>
        </div>
    <?php endif ?>
</div>

