<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("iblock"))
    return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-"=>" "));

$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
    $arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];
//формирование массива параметров
$arComponentParameters = array(
    "GROUPS" => array(
        "LIST"    =>  array(
            "NAME"  =>  "Списки",
            "SORT"  =>  "300",
        ),
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arTypesEx,
            "DEFAULT" => "news",
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "DEFAULT" => '={$_REQUEST["ID"]}',
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y",
        ),
        "SET_TITLE"  =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  "Установка Title",
            "TYPE"      =>  "CHECKBOX",
        ),
        "RULE_SHOW"   =>  array(
            "PARENT"    =>  "LIST",
            "NAME"      =>  "Правило показа",
            "TYPE"      =>  "LIST",
            "VALUES"    =>  array(
                "rand" =>  "Случайно",
                "last" =>  "Последний",
            ),
            "MULTIPLE"  =>  "N",
        ),
        "ELEMENT_FIELDS"    =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  "Поля элемента",
            "TYPE"      =>  "STRING",
            "DEFAULT"   =>  "NAME, ID"
        ),

    ),
);