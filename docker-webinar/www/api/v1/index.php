<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Configuration;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\Iblock;
//use \Bitrix\Iblock\SectionTable;
use \SomeModule\Webinar\RemDebug;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$APPLICATION->IncludeComponent(
    "drfoxg:webinar",
    "",
    Array(
        "INFOBLOCKID" => "23",
        "MONTHS" => array("1","10","11","12"),
        "THEMES" => "1,2"
    )
);

//require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/SomeModule/Webinar/RemDebug.php");

Loader::includeModule("iblock");

$iblockId = 1;
//$iblockId = 23;

dump($webinars = Iblock::wakeUp($iblockId)->getEntityDataClass());

dump(bitrix_sessid());

dump(Configuration::getValue('composer'));

$count = 1;
//$themeNumbers = [1, 2, 1000];
$themeNumbers = [3, 9];
//$themeNumbers = [1];
//$themeNumbers = [];
$monthNumbers = [5, 6, 7];
//$monthNumbers = [];
$month = 6;

/**
 * @param array $months
 * @return array|string[]
 * @throws Exception
 */
function getDateFiltered(array $months): array
{
    $count = count($months);

    if ($count == 0) {
        throw new Exception("No data");
    }

    if ($count == 1) {
        return formatStartEnd($months[0], '>=DATE.VALUE', '<=DATE.VALUE');
    }

    $filter = [
        'LOGIC' => 'OR'
    ];

    foreach ($months as $month) {
        array_push($filter, formatStartEnd($month, '>=DATE.VALUE', '<=DATE.VALUE'));
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
function formatStartEnd(int $month, string $conditionStar, string $conditionEnd, string $year = '') : array
{
    // валидация входных данных
    // TODO: добавить константы месяцев
    if (!((1 <= $month) && ($month <= 12))) {
        throw new Exception("Not month");
    }

    if ($year === '') {
        $year = date('Y');
    }

    $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $dateStart = (new DateTime($year . '-' . $month . '-1' . ' ' . '00:00:00'));
    $dateEnd   = (new DateTime($year . '-' . $month . '-' . $number . ' ' . '23:59:59'));

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
 * @throws Exception
 */
function doThemeFilter(array &$arFilter, array $themes) : void
{
    $count = count($themes);

    if ($count != 0) {
        $arFilter[] = ['@THEME_ID.ELEMENT.ID' => $themes];
    }
}

dump(getDateFiltered($monthNumbers));

$arFilter[] = getDateFiltered($monthNumbers);
doThemeFilter($arFilter, $themeNumbers);
$arFilter['=ACTIVE'] = 'Y';

dump($arFilter);


\Bitrix\Main\Application::getConnection()->startTracker(false);

Application::getConnection()->startTracker(false);

$catalogSectionsIterator = $webinars::getList([
    'select' => ['ID', 'NAME', 'DATE', 'THEME_ID.ELEMENT'],
    'filter' => $arFilter,
    'order'  => ['ID' => 'ASC'],
]);

echo '<pre>', $catalogSectionsIterator->getTrackerQuery()->getSql(), '</pre>';

\Bitrix\Main\Application::getConnection()->stopTracker();

global $DB;
$DB->ShowSqlStat = true;
// и поехали
$debug = new CDebugInfo();
//$debug1 = new RemDebug();
$debug->Start();

$elements =  $webinars::getList([
    'select' => ['ID', 'NAME', 'DATE', 'THEME_ID.ELEMENT'],
    'filter' => $arFilter,
    'order'  => ['ID' => 'ASC'],
])->fetchCollection();

echo '<pre>', $debug->Output(), '</pre>';

$debug->Stop();

foreach($elements as $element) {

    //if (in_array($count, $themeNumbers)) {
        dump('Айди Вебинара: '.$element->getId());
        dump('Название: '.$element->getName());
        dump('Дата\время: '.$element->getData()->getValue());
        //dump('Тема: '.$element->getThemeId()->getElement()->getName());
        foreach ($element->getThemeId()->getAll() as $theme) {
            dump('Тема: ' . $theme->getElement()->getName());
            dump('Айди Темы: '.$theme->getElement()->getId());
        }

    //}

    $count++;
}


