<?php
    $data = $args['data'] ?? [];
    $payBySquare = generate_pay_by_square($data['amount'], $data['pay_by_square_note']);
?>
<table>
    <tr>
        <?php if (!empty($payBySquare)) : ?>
        <td style="width: 50%">
                <img border="0" vspace="0" hspace="0" width="50%"
                     src="<?= $payBySquare ?>"
                     alt="PAY BY SQUARE QR"
                     style="width: 100%; max-width: 150px; color: #000000; font-size: 13px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;"/>
        </td>
        <?php endif ?>
        <td width="<?= !empty($payBySquare) ? "50%" : "100%" ?>">
            IBAN: <b><?= $data['iban'] ?></b><br>
            Suma: <b><?= $data['amount'] ?>€</b><br>
            Poznámka: <b><?= $data['manual_note'] ?></b>
        </td>
    </tr>
</table>
<?php if (!empty($data['support_email'])) : ?>
    <p>V prípade akýchkoľvek problémov s registráciou alebo platbou nás neváhajte <a href="mailto:<?= $data['support_email'] ?>">kontaktovať</a></p>
<?php endif ?>