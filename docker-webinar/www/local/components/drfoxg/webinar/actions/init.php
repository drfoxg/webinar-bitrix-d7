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

        $webinars = Iblock::wakeUp($this->arParams['INFOBLOCKID'])->getEntityDataClass();

        // подготовка фильтров запроса
        $arFilter[] = $common->getDateFiltered($themesAndMonths['months']);
        $common->doThemeFilter($arFilter, $themesAndMonths['themes']);
        $arFilter['=ACTIVE'] = 'Y';

        $this->arResult['WEBINARS'] = $common->getData($webinars, $arFilter);

        $this->includeComponentTemplate();

        return true;
    }

    /**
     * @param array $month
     * @param string $themesAsString
     * @return array
     */
    private function getIntParams(array $month, string $themesAsString) : array
    {
        $result['months'] = array_map('intval', $month);

        if (empty($themesAsString)) {
            $result['themes'] = [];
        } else {
            $result['themes'] = array_map('intval', explode(',', $themesAsString));
        }

        return $result;
    }
}