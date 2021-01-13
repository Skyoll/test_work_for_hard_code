<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */

global $APPLICATION;
if (isset($arResult['ITEM']) && !empty($arResult['ITEM']) && $arParams['SET_TITLE'] === 'Y') {

    $APPLICATION->SetTitle($arResult['ITEM']['NAME']);
}

if ($arParams['IS_AJAX']) {
    AddEventHandler("main", "OnEndBufferContent", function(&$content) {
        $content = str_replace('#TEST#', sprintf("<li>Дата запроса:%s</li>", date("d/m/Y H:i:s")), $content);
    });
} else {
    AddEventHandler("main", "OnEndBufferContent", function(&$content) {
        $content = str_replace('#TEST#', "", $content);
    });
}