
<?php $data = $args['data'] ?? []; ?>

<h3>Nová správa z webu streetworkoutslovakia.eu</h3>
<br><br>
<div>
    Odosielateľ: <?= $data['meno'] ?? '???' ?><br>
    Email: <?= $data['email'] ?? '???' ?><br><br>
    Správa: <?= $data['sprava'] ?? '???' ?>
</div>