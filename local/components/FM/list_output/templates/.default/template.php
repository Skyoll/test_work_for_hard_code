<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div id="container">
    <ul>
        <li><?= Loc::getMessage("LIST_OUTPUT_ID")?>: <?= $arResult['ITEM']['ID'] ?></li>
        <li><?= Loc::getMessage("LIST_OUTPUT_NAME")?>: <?= $arResult['ITEM']['NAME'] ?></li>
        <?php if (!empty($arResult['CACHE_CREATE_DATA'])): ?>
            <li><?= Loc::getMessage("LIST_OUTPUT_CACHE_DATE")?>: <?= $arResult['CACHE_CREATE_DATA'] ?></li>
        <?php endif ?>
        #REQUEST_DATE#
    </ul>
    <a id="update_data"><?= Loc::getMessage("LIST_OUTPUT_UPDATE")?></a>
</div>

<script>
    $(document).ready(function () {

        $('#update_data').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                url: '/ajax/update_data_ajax.php',
                type: 'POST',
                dataType: "json",
                data: {
                    'arParams': '<?= json_encode($arParams)?>',
                },

                success: function (data) {
                    $('#container').html(data.data);
                    console.log(data)
                },
                error: function (errMsg) {
                    console.log('errMsg');
                }
            });
        });
    });
</script>
