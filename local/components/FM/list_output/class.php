<?php

class ListOutput extends \CBitrixComponent
{
    const CACHE_TIME = 3600;

    public $componentParams = [];
    private $iblockType;
    private $iblockId;
    private $setTitle;
    private $ruleShow;
    private $arFieldsElement;
    private $isAjax;

    public function onPrepareComponentParams($params)
    {
        !$params['CACHE_TIME'] && $params['CACHE_TIME'] = self::CACHE_TIME;
        !in_array($params['CACHE_TYPE'], ['A', 'N', 'Y']) && $params['CACHE_TYPE'] = 'A';

        $this->iblockType = (string)$params['IBLOCK_TYPE'];
        $this->iblockId = (int)$params['IBLOCK_ID'];
        $this->setTitle = $params['SET_TITLE'] === 'Y';
        $this->ruleShow = (string)$params['RULE_SHOW'];
        $this->arFieldsElement = array_diff(explode(',', $params['ELEMENT_FIELDS']), ['']);
        $this->isAjax = $params['IS_AJAX'] === 'Y';

        return $params;
    }

    public function executeComponent()
    {
        CModule::IncludeModule('iblock');

        try {
            if ($this->ruleShow === 'rand') {
                $this->arResult['ITEM'] = $this->getRandomElement();
                $this->setResultCacheKeys(['ITEM']);
                $this->includeComponentTemplate();
                return;
            }

            if ($this->isAjax) {
                $this->arResult['CACHE_CREATE_DATA'] = $this->getCacheCreateData();
            }

            if ($this->startResultCache($this->arParams['CACHE_TIME'])) {
                $this->arResult['ITEM'] = $this->getLastElement();
                $this->setResultCacheKeys(['ITEM']);
                $this->includeComponentTemplate();
            }


        } catch (\Exception $e) {
            $this->abortResultCache();
        }
    }

    private function getRandomElement()
    {
        $dbElement = CIBlockElement::GetList(
            [
                'RAND' => 'rand'
            ],
            [
                'IBLOCK_ID' => $this->iblockId,
                'IBLOCK_TYPE' => $this->iblockType,
                'ACTIVE' => 'Y',
                'CHECK_PERMISSIONS' => 'Y'
            ],
            false,
            [
                'nTopCount' => 1
            ],
            $this->arFieldsElement
        );

        if ($res = $dbElement->Fetch()) {
            return $res;
        }

        return [];
    }

    private function getLastElement()
    {
        $dbElement = CIBlockElement::GetList(
            [
                'ID' => 'desc'
            ],
            [
                "IBLOCK" => $this->iblockId,
                'IBLOCK_TYPE' => $this->iblockType,
                'ACTIVE' => 'Y',
                'CHECK_PERMISSIONS' => 'Y'
            ],
            false,
            [
                'nTopCount' => 1
            ],

            $this->arFieldsElement
        );

        if ($res = $dbElement->Fetch()) {
            return $res;
        }

        return [];
    }

    private function getCacheCreateData()
    {
        try {
            $relativeCachePath = $this->getCachePath();
            $folderCachePath = '/var/www/bitrix/bitrix/cache' . $relativeCachePath;
            $cacheFilePath = $this->getCacheFile($folderCachePath);
            $fileHandler = fopen($cacheFilePath, "rb");
            if ($fileHandler) {
                $header = fread($fileHandler, 150);
                fclose($fileHandler);
            }

            preg_match("/datecreate\\s*=\\s*'([\\d]+)'/im", $header, $match);

            return date("d/m/Y H:i:s", $match[1]);
        } catch (\Exception $exception) {
            return '';
        }
    }

    private function getCacheFile($cacheFolderPath)
    {
        $di = new RecursiveDirectoryIterator($cacheFolderPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $it = new RecursiveIteratorIterator($di);

        foreach ($it as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
                return $file;
            }
        }
    }
}