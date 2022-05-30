<?php

namespace Drfoxg\Webinar\Actions;

use \Drfoxg\Webinar\Webinar;
use \Drfoxg\Webinar\Helpers;
use \Bitrix\Main\Diag\Debug;
use \Bitrix\Iblock\Iblock;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\Json;
//use Protobuf\Exception;

class HtmlModel extends Webinar
{
    /**
     * POST constructor.
     * @param \CBitrixComponent $oComponent
     * @return bool
     */
    private $parent;
    private $common;

    public function __construct(\CBitrixComponent $oComponent)
    {
        parent::__construct($oComponent);
        $this->parent = $oComponent;

        $this->getHelper('month');
        $this->common = $this->getHelper('common');

        return true;
    }

    /**
     * @return bool
     */
    protected function do()
    {
        if(!Loader::includeModule("iblock")) {
            throw new \Exception(GetMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        }

        if ($this->parent->request->getHeaders()->getContentType() != 'application/json') {
            return false;
        }

        $decodedData = Json::decode($this->parent->request->getInput());

        $common = $this->common;

        // подготовка данных
        $themes = $this->parent->convertToInt($decodedData['theme']);
        $month = $this->parent->convertToInt($decodedData['month']);

        $webinars = Iblock::wakeUp(self::DATA_SOURCE)->getEntityDataClass();

        // подготовка фильтров запроса
        $arFilter[] = $common->getDateFiltered($month);
        $common->doThemeFilter($arFilter, $themes);
        $arFilter['=ACTIVE'] = 'Y';


        $this->arResult['WEBINARS'] = $common->getData($webinars, $arFilter);

        $this->includeComponentTemplate();

        return true;
    }
}