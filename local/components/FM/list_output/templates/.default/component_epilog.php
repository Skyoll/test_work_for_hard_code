<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

/** @var array $arParams */

global $APPLICATION;
if (isset($arResult['ITEM']) && !empty($arResult['ITEM']) && $arParams['SET_TITLE'] === 'Y') {

    $APPLICATION->SetTitle($arResult['ITEM']['NAME']);
}

if ($arParams['IS_AJAX']) {
    AddEventHandler("main", "OnEndBufferContent", function(&$content) {
        $content = str_replace('#REQUEST_DATE#', sprintf("<li>%s:%s</li>", Loc::getMessage("LIST_OUTPUT_REQUEST_DATE") ,date("d/m/Y H:i:s")), $content);
    });
} else {
    AddEventHandler("main", "OnEndBufferContent", function(&$content) {
        $content = str_replace('#REQUEST_DATE#', "", $content);
    });
}