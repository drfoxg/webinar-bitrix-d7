<?php

namespace Drfoxg\Webinar\Helpers;

use Bitrix\Iblock\Iblock;
use Protobuf\Exception;

/**
 * Class Helper
 * @package Drfoxg\Webinar\Helpers
 */
class Common
{
    public function __construct()
    {
        return true;
    }

    /**
     * @param array $months
     * @return array|string[]
     * @throws Exception
     */
    public function getDateFiltered(array $months): array
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
     * @param array $arFilter
     * @param array $themes
     */
    public function doThemeFilter(array &$arFilter, array $themes) : void
    {
        $count = count($themes);

        if ($count != 0) {
            $arFilter[] = ['@THEME_ID.ELEMENT.ID' => $themes];
        }
    }

    /**
     * @param $data
     * @param $arFilter
     * @return array
     */
    public function getData($data, $arFilter) : array
    {
        $dataSource = Iblock::wakeUp($data)->getEntityDataClass();

        if (is_null($dataSource)) {
            throw new \Exception(GetMessage('T_NOT_INFOBLOCK'));
        }

        $elements =  $dataSource::getList([
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
        if (!Month::has($month)) {
            throw new \Exception(GetMessage('T_NOT_MONTH'));
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
}