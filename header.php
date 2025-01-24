<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
          integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= favicon_path() ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= favicon_path() ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= favicon_path() ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= favicon_path() ?>/site.webmanifest">
    <?php wp_head(); ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-98GXJNGW7H"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-98GXJNGW7H');
    </script>
</head>
<body>

<?php $whiteRightSide = is_front_page(); ?>

<div id="header">
    <header class="<?= $whiteRightSide ? 'white' : '' ?>" :class="[isOpened ? 'openedNavigation' : '', hasStickyHeader ? 'sticky' : '']">
        <div class="container">
            <div class="header-wrapper d-flex align-items-center">
                <a href="<?= home_url() ?>" class="logo-container">
                    <?= svgIcon(icon_path(false) . "logo-swk.svg", ['class' => ['logo d-md-block d-none']]) ?>
                    <?= svgIcon(icon_path(false) . "logo-swk.svg", ['class' => ['logo d-md-none d-block']]) ?>
                </a>
                <nav>
                    <ul class="d-flex justify-content-between align-items-center">
                        <?php printMenu("header-links"); ?>
                        <li>
                            <a href="<?= get_site_url() ?>/podujatia-a-sutaze" class="events-link ml-auto d-flex align-items-center d-md-none d-flex">
                                <?= svgIcon(icon_path(false) . "icon-calendar.svg") ?>
                                <span class="text"><?= __("Kalendár podujatí", "swslovakia") ?></span>
                            </a>
                        </li>
                        <li class="d-md-none d-block">
                            <a href="<?= get_site_url() ?>/kontakt"><?= __("Kontakt", "swslovakia") ?></a>
                        </li>
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
                <a href="<?= get_site_url() ?>/kontakt" class="contact-button ml-auto ml-md-0 d-md-flex d-none justify-content-center align-items-center">
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
</div>


<div id="notifications"></div>
