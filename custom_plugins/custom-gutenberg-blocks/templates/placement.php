<div class="placement">
    <div class="place first row align-items-center">
        <div class="col-md-2">
            <span class="tag red small">
                <?= svgIcon(icon_path(false) . "icon-award.svg", ['class' => ['mr-1']]) ?>
                <?= __("1.&nbsp;miesto", "swslovakia") ?>
            </span>
        </div>
        <div class="col-md-5">
            <div class="name"><?= $firstPlaceName ?></div>
        </div>
        <div class="col-md-5">
            <div class="desc"><?= $firstPlaceDesc ?></div>
        </div>
    </div>
    <div class="place second row align-items-center">
        <div class="col-md-2">
            <span class="tag red small">
                <?= svgIcon(icon_path(false) . "icon-award.svg", ['class' => ['mr-1']]) ?>
                <?= __("2.&nbsp;miesto", "swslovakia") ?>
            </span>
        </div>
        <div class="col-md-5">
            <div class="name"><?= $secondPlaceName ?></div>
        </div>
        <div class="col-md-5">
            <div class="desc"><?= $secondPlaceDesc ?></div>
        </div>
    </div>
    <div class="place third row align-items-center">
        <div class="col-md-2">
            <span class="tag red small">
                <?= svgIcon(icon_path(false) . "icon-award.svg", ['class' => ['mr-1']]) ?>
                <?= __("3.&nbsp;miesto", "swslovakia") ?>
            </span>
        </div>
        <div class="col-md-5">
            <div class="name"><?= $thirdPlaceName ?></div>
        </div>
        <div class="col-md-5">
            <div class="desc"><?= $thirdPlaceDesc ?></div>
        </div>
    </div>
</div>