<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
          integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <?php wp_head(); ?>
</head>
<body>

<?php $whiteRightSide = is_front_page(); ?>

<header id="header" class="<?= $whiteRightSide ? 'white' : '' ?>" :class="[isOpened ? 'openedNavigation' : '', hasStickyHeader ? 'sticky' : '']">
    <div class="container">
        <div class="header-wrapper d-flex align-items-center">
            <a href="<?= home_url() ?>" class="logo-container">
                <?= svgIcon(icon_path(false) . "logo.svg", ['class' => ['logo d-md-block d-none']]) ?>
                <?= svgIcon(icon_path(false) . "logo-without-slovakia.svg", ['class' => ['logo d-md-none d-block']]) ?>
            </a>
            <nav>
                <ul class="d-flex justify-content-between align-items-center">
                    <?php printMenu("header-links"); ?>
                    <a href="<?= get_site_url() ?>/podujatia-a-sutaze" class="events-link ml-auto d-flex align-items-center d-md-none d-flex">
                        <?= svgIcon(icon_path(false) . "icon-calendar.svg") ?>
                        <span class="text"><?= __("Kalendár podujatí", "swslovakia") ?></span>
                    </a>
                    <a href="<?= get_site_url() ?>" class="contact-button  d-md-none d-flex justify-content-center align-items-center">
                        <lord-icon
                                src="<?= icon_path() ?>/icon-phone-animated.json"
                                trigger="loop-on-hover"
                                stroke="90"
                                target="a.contact-button"
                                colors="primary:#1C1B2B,secondary:#F03834">
                        </lord-icon>
                    </a>
                </ul>
            </nav>
            <div class="toggler-container d-md-none d-flex">
                <span>menu</span>
                <div class="toggler" :class="isOpened ? 'active' : ''" @click="isOpened = !isOpened">
                    <span class="one"></span>
                    <span class="two"></span>
                    <span class="three"></span>
                </div>
            </div>
            <a href="<?= get_site_url() ?>/podujatia-a-sutaze" class="ml-auto <?= is_front_page() ? '' : 'all-black' ?> events-link d-md-flex d-none align-items-center">
                <?= svgIcon(icon_path(false) . "icon-calendar.svg") ?>
                <span class="text d-lg-block d-none"><?= __("Kalendár podujatí", "swslovakia") ?></span>
                <span class="text d-lg-none d-block"><?= __("Podujatia", "swslovakia") ?></span>
            </a>
            <a href="<?= get_site_url() ?>" class="contact-button ml-auto ml-md-0 d-md-flex d-none justify-content-center align-items-center">
                <lord-icon
                        src="<?= icon_path() ?>/icon-phone-animated.json"
                        trigger="loop-on-hover"
                        stroke="90"
                        target="a.contact-button"
                        colors="primary:#1C1B2B,secondary:#F03834">
                </lord-icon>
            </a>
        </div>
    </div>
</header>



<div id="notifications"></div>
