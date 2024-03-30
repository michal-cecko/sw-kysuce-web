<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex,nofollow">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= plugins_url()?>/madelo_starterpack/includes/under-construction/css/style-under-construction.css">
    <title>Under construction</title>
</head>
<body>
<?php
    $title = get_option('madelo_under_construction_title') ? : '';
    $desc = get_option('madelo_custom_scripts_description') ?  : '';
    $loginUrl = customLogin::getURL(true) ? : admin_url();

?>
    <div class="screen-1">
        <div class="image">
            <img src="<?= plugins_url()?>/madelo_starterpack/includes/under-construction/css/assets/gear.png">
        </div>
        <div class="content">
            <div class="healine">
                <?=$title?>
            </div>
            <div class="description">
                <?=$desc?>
            </div>
            <div class="link">
                <a href="<?=$loginUrl?>">Prihlásenie</a>
            </div>
        </div>
    </div>
</body>
</html>

