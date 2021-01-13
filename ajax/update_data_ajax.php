<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Context;

global $APPLICATION;

$request = Context::getCurrent()->getRequest();
$params = $request->getPost("arParams");
$arParams = array_merge(json_decode($params, true), [
    'IS_AJAX' => 'Y'
]);


ob_start();
$APPLICATION->IncludeComponent(
	"FM:list_output",
	"",
    $arParams,
	false
);
$out = ob_get_clean();
$res = ['data' => $out];
echo json_encode(['data' => $out]);
