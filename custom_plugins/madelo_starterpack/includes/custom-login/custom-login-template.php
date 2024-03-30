<?php
if (isset($_GET['logout']) && $_GET['logout'] === 'true') wp_logout();
$err = customLogin::checkLogin($_POST);
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex,nofollow">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" type="text/css"
          href="<?= plugins_url() . '/madelo_starterpack/includes/custom-login/css/style-custom-login.css' ?>">
    <title>Prihlásenie</title>
</head>
<body>
<?php if (is_user_logged_in()) echo '<meta http-equiv="refresh" content="0; url=' . admin_url("options.php") . '">'; ?>
<form action="" method="post" id="customLogin">
    <div class="screen-1">
        <div class="email">
            <div class="sec-2">
                <ion-icon name="person"></ion-icon>
                <input type="text" required id="username"
                       name="username" <?php if (isset($_POST['username']) && !empty($_POST['username'])) echo 'value="' . $_POST['username'] . '"'; ?>
                       placeholder="Uživateľ"/>
            </div>
        </div>
        <div class="password">
            <div class="sec-2">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input class="pas" required id="password" type="password" name="password" placeholder="············"/>
            </div>
        </div>
        <button type="submit" name="btn_submit" class="login">Prihlásiť</button>
        <?php
        if ($err)
        {
            echo '<p class="error">' . $err . '</p>';
        }
        if (isset($_GET['logout'])) echo '<p class="logout">Boli ste úspešne odhlásený.</p>';
        ?>
    </div>
</form>
<?= customLogin::includeCaptcha() ?>
</body>
</html>

