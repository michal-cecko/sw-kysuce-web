<?php if ($phone = get_field("contact_phone", "options")) : ?>
    <div class="contact-info phone">
        <?php
        echo svgIcon(icon_path(false) . "icon-phone.svg");
        echo $phone;
        ?>
    </div>
<?php endif ?>
<?php if ($email = get_field("contact_email", "options")) : ?>
    <div class="contact-info email">
        <?php
        echo svgIcon(icon_path(false) . "icon-email.svg");
        echo $email;
        ?>
    </div>
<?php endif ?>