<?php $events = $args['events'] ?? null ?>

<?php if ($events?->have_posts()) : ?>
    <div class="reports-container red-scrollbar">
        <div class="row">
            <?php while ($events->have_posts()) : $events->the_post();
                $article = get_field("event_article_link"); ?>
                <div class="col-3">
                    <?php get_template_part("template_parts/blog/article-card", "", [
                        'direction' => 'column',
                        'article' => $article,
                        'size' => 'small',
                        'show_category' => false
                    ]); ?>
                </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    </div>
<?php endif ?>

