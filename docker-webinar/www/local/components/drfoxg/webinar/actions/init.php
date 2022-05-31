<?php

namespace Drfoxg\Webinar\Actions;

use Bitrix\Iblock\Iblock;
use Bitrix\Main\Diag\Debug;
use CModule;
use CIBlockElementRights;
use CIBlockElement;
use Drfoxg\Webinar\Webinar;

/**
 * Class Init
 * @package Drfoxg\Webinar\Actions
 */
class Init extends Webinar
{
    private $parent;
    private $common;

    /**
     * Init constructor.
     * @param \CBitrixComponent $oComponent
     * @return bool
     */
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
        $common = $this->common;

        $themesAndMonths = $this->getIntParams($this->arParams['MONTHS'], $this->arParams['THEMES']);

        $this->arResult['MONTHS'] = $themesAndMonths['months'];
        $this->arResult['THEMES'] = $themesAndMonths['themes'];

        // подготовка фильтров запроса
        $arFilter[] = $common->getDateFiltered($themesAndMonths['months']);
        $common->doThemeFilter($arFilter, $themesAndMonths['themes']);
        $arFilter['=ACTIVE'] = 'Y';

        $this->arResult['WEBINARS'] = $common->getData($this->arParams['INFOBLOCKID'], $arFilter);

        $this->includeComponentTemplate();

        return true;
    }

    /**
     * Получить из входных параметров компонента массивы из целых чисел
     * @param array $month
     * @param string $themesAsString
     * @return array
     */
    private function getIntParams(array $month, string $themesAsString) : array
    {
        $result['months'] = $this->parent->convertToInt($month);

        if (empty($themesAsString)) {
            $result['themes'] = [];
        } else {
            $result['themes'] = $this->parent->convertToInt(explode(',', $themesAsString));
        }

        return $result;
    }
}