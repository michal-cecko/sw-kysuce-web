<?php
$data = $args['data'] ?? [];
$payBySquare = $data['pay_by_square'] ?? null;
?>
    <table>
        <tr>
            <?php if (!empty($payBySquare)) : ?>
                <td style="width: 50%" valign="top">
                    <img border="0" vspace="0" hspace="0"
                         src="<?= $payBySquare ?>"
                         alt="PAY BY SQUARE QR"
                         style="width: 90%; max-width: 370px; margin: 0; padding: 0; outline: none; border: none; display: block;"/>
                </td>
            <?php endif ?>
            <td width="<?= !empty($payBySquare) ? "50%" : "100%" ?>" valign="top">
                IBAN: <br><b><?= $data['iban'] ?></b><br><br>
                Suma: <br><b><?= $data['amount'] ?><?= $data['currency'] ?? "€" ?></b><br><br>
                Názov príjemcu: <br><b>SW Slovakia</b><br><br>
                Poznámka: <br><b><?= $data['note'] ?></b><br>
            </td>
        </tr>
    </table>
<?php if (!empty($data['support_email'])) : ?>
    <p>V prípade akýchkoľvek problémov s registráciou alebo platbou nás neváhajte <a
                href="mailto:<?= $data['support_email'] ?>">kontaktovať</a></p>
<?php endif ?>