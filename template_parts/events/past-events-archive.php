<?php $events = $args['events'] ?? false; ?>
<div class="past-events-table">
    <table>
        <thead>
        <tr>
            <th>Dátum</th>
            <th>Názov podujatia</th>
            <th>Miesto</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($events && $events->have_posts()) : ?>
            <?php while ($events->have_posts()) : $events->the_post(); ?>
                <tr>
                    <td>
                        <div class="flex-td">
                            <div class="icon">
                                <?= svgIcon(icon_path(false) . "/icon-calendar_filled.svg") ?>
                            </div>
                            <p><?= getEventDate(get_the_ID()) ?></p>
                        </div>
                    </td>
                    <td><?= get_the_title() ?></td>
                    <td>
                        <div class="flex-td">
                            <div class="icon">
                                <?= svgIcon(icon_path(false) . "/icon-location.svg") ?>
                            </div>
                            <p><?= get_field("event_place") ?></p>
                        </div>
                    </td>
                    <td>
                        <?php if ($pinnedArticle = get_field("event_article_link")) : ?>
                            <a class="link-button" href="<?= get_the_permalink($pinnedArticle->ID) ?>">
                                <?= svgIcon(icon_path(false) . "/icon-arrow_side_top.svg") ?>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile;
            wp_reset_postdata(); ?>
        <?php endif ?>
        </tbody>
    </table>
</div>

