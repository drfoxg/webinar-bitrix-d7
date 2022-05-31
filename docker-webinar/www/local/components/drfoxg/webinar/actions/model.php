<?php

namespace Drfoxg\Webinar\Actions;

use \Drfoxg\Webinar\Webinar;
use \Drfoxg\Webinar\Helpers;
use \Bitrix\Main\Diag\Debug;
use \Bitrix\Iblock\Iblock;
use \Bitrix\Main\Loader;
use Protobuf\Exception;

/**
 * Class Model - модель вебинаров
 * @package Drfoxg\Webinar\Actions
 */
class Model extends Webinar
{
    /**
     * Ajax constructor.
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
            throw new Exception(GetMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        }

        $common = $this->common;

        // подготовка данных
        $themes = $this->parent->getThemes();
        $month = $this->parent->getMonths();

        // подготовка фильтров запроса
        $arFilter[] = $common->getDateFiltered($month);
        $common->doThemeFilter($arFilter, $themes);
        $arFilter['=ACTIVE'] = 'Y';

        $this->parent->setWebinars($common->getData(self::DATA_SOURCE, $arFilter));

        return true;
    }

}