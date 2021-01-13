<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

global $APPLICATION;

$APPLICATION->IncludeComponent(
	"FM:list_output", 
	".default", 
	array(
		"IBLOCK_TYPE" => "news",
		"IBLOCK_ID" => "1",
		"RULE_SHOW" => "rand",
		"SET_TITLE" => "N",
		"ELEMENT_FIELDS" => "NAME,ID",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>