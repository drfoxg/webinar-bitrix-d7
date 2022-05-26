<?php

namespace Drfoxg\Webinar\Actions;

use \Drfoxg\Webinar\Webinar;
use \Bitrix\Main\Diag\Debug;
use \Bitrix\Iblock\Iblock;
use \Bitrix\Main\Loader;

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
    private const DATASOURCE = 1;
    //private const DATASOURCE = 23;

    public function __construct(\CBitrixComponent $oComponent)
    {
        parent::__construct($oComponent);
        $this->parent = $oComponent;

        return true;
    }

    /**
     * @return bool
     */
    protected function do()
    {
        Loader::includeModule("iblock");

        $themes = $this->parent->getThemes();
        $month = $this->parent->getMonths();

        $webinars = Iblock::wakeUp(self::DATASOURCE)->getEntityDataClass();

        $arFilter[] = $this->getDataFiltered($month);
        $this->doThemeFilter($arFilter, $themes);
        $arFilter['=ACTIVE'] = 'Y';

        Debug::writeToFile($themes, 'theme - Model->do()');
        Debug::writeToFile($month, 'theme - Model->do()');

        $this->parent->setWebinars($this->getData($webinars, $arFilter));

        return true;
    }

    /**
     * @param array $months
     * @return array|string[]
     * @throws Exception
     */
    private function getDataFiltered(array $months): array
    {
        $count = count($months);

        if ($count == 0) {
            return [];
            //throw new \Exception("No data");
        }

        if ($count == 1) {
            return $this->formatStartEnd($months[0], '>=DATE.VALUE', '<=DATE.VALUE');
        }

        $filter = [
            'LOGIC' => 'OR'
        ];

        foreach ($months as $month) {
            array_push($filter, $this->formatStartEnd($month, '>=DATE.VALUE', '<=DATE.VALUE'));
        }

        return $filter;
    }

    /**
     * @param int $month
     * @param string $conditionStar
     * @param string $conditionEnd
     * @param string $year
     * @return array
     * @throws Exception
     */
    private function formatStartEnd(int $month, string $conditionStar, string $conditionEnd, string $year = '') : array
    {
        // валидация входных данных
        // TODO: добавить константы месяцев
        if (!((1 <= $month) && ($month <= 12))) {
            throw new \Exception("Not month");
        }

        if ($year === '') {
            $year = date('Y');
        }

        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateStart = (new \DateTime($year . '-' . $month . '-1' . ' ' . '00:00:00'));
        $dateEnd   = (new \DateTime($year . '-' . $month . '-' . $number . ' ' . '23:59:59'));

        $result[$conditionStar] = $dateStart->format('Y-m-d H:i:s');
        $result[$conditionEnd]  = $dateEnd->format('Y-m-d H:i:s');

        /*
        dump($dateStart);
        dump($dateEnd);
        */

        return $result;
    }

    /**
     * @param array $arFilter
     * @param array $themes
     */
    private function doThemeFilter(array &$arFilter, array $themes) : void
    {
        $count = count($themes);

        if ($count != 0) {
            $arFilter[] = ['@THEME_ID.ELEMENT.ID' => $themes];
        }
    }

    private function getData($webinars, $arFilter) : array
    {
        $elements =  $webinars::getList([
            'select' => ['ID', 'NAME', 'DATE', 'THEME_ID.ELEMENT'],
            'filter' => $arFilter,
            'order'  => ['ID' => 'ASC'],
        ])->fetchCollection();

        $results = [];

        foreach ($elements as $webinar) {
            foreach ($webinar->getThemeId()->getAll() as $theme) {

                $row['name'] = $webinar->getName();
                $row['date'] = $webinar->getDate()->getValue();
                $row['theme'] = $theme->getElement()->getName();
                /*
                $row[] = $webinar->getName();
                $row[] = $webinar->getDate()->getValue();
                $row[] = $theme->getElement()->getName();
                */
                //$results[]['element'] = $row;
                array_push($results, $row);

                unset($row);
            }
        }

        return $results;
    }
}