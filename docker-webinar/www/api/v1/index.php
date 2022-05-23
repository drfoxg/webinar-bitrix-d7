<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Configuration;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\Iblock;
//use \Bitrix\Iblock\SectionTable;
use \SomeModule\Webinar\RemDebug;
//use \Bitrix\Iblock\Elements\ElementV1m1p0nWEBINARTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/SomeModule/Webinar/RemDebug.php");

Loader::includeModule("iblock");

$iblockId = 23;

dump($webinars = Iblock::wakeUp($iblockId)->getEntityDataClass());

dump(Configuration::getValue('composer'));

/*
$elements =  $webinars::getList([
    'select' => ['ID', 'NAME', 'DATA', 'THEME_ID.ELEMENT'],
    'filter' => ['=ACTIVE' => 'Y'],
    'order'  => ['ID' => 'ASC'],
])->fetchCollection();
*/
/*
foreach($elements as $element) {

    //dump($element);

    dump('Айди: '.$element->getId());
    dump('Название: '.$element->getName());
    dump('Дата\время: '.$element->getData()->getValue());
    dump('Тема: '.$element->getThemeId()->getElement()->getName());

}
*/

$count = 1;
$themeNumbers = [1, 3, 10];
$monthNumbers = [5,6];
$month = 6;

function getDataFilter(array $months) : array
{
    $count = count($months);

    if ($count == 1) {
        return formatStartEnd($months[0],'>=DATA.VALUE', '<=DATA.VALUE');
    }

    $filter = [
        'LOGIC' => 'OR'
    ];

    foreach ($months as $month) {
        array_push($filter, formatStartEnd($month, '>=DATA.VALUE', '<=DATA.VALUE'));
    }

    return $filter;
}

/**
 * @throws Exception
 */
function formatStartEnd(int $month, string $conditionStar, string $conditionEnd, string $year = '') : array
{
    // валидация входных данных
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

dump(getDataFilter($monthNumbers));
$arFilter = getDataFilter($monthNumbers);

/*
$entityPropsSingle = Bitrix\Main\Entity\Base::compileEntity(
    sprintf('DATA_%s', $iblockId),
    [
        'IBLOCK_ELEMENT_ID' => ['data_type' => 'integer'],
        'VALUE' => ['data_type' => 'string'],
    ],
    [
        'table_name' => sprintf('b_iblock_element_v1m1p0n_we_bi_na_r%s', $iblockId),
        //'table_name' => 'b_iblock_element_v1m1p0n_we_bi_na_r',
    ]
);
*/

//$arFilter['>=DATA.VALUE'] = $dateStart->format('Y-m-d H:i:s');
//$arFilter['<=DATA.VALUE'] = $dateEnd->format('Y-m-d 23:59:59');
$arFilter['=ACTIVE'] = 'Y';

dump($arFilter);

global $DB;
$DB->ShowSqlStat = true;
// и поехали
$debug = new CDebugInfo();
//$debug = new RemDebug();
$debug->Start();

\Bitrix\Main\Application::getConnection()->startTracker(false);

$elements =  $webinars::getList([
    'select' => ['ID', 'NAME', 'DATA', 'THEME_ID.ELEMENT'],
    'filter' => $arFilter,
    'order'  => ['ID' => 'ASC'],
    'runtime' => [
        'DATA.VALUE' => [
            //'data_type' => $entityPropsSingle->getDataClass(),
            'data_type' => 'Bitrix\Iblock\ElementTable',
            'reference' => [
                '=this.ID' => 'ref.IBLOCK_ELEMENT_ID'
            ],
            'join_type' => 'inner'
        ],
    ],
])->fetchCollection();

$debug->Stop();

foreach($elements as $element) {

    //if (in_array($count, $themeNumbers)) {
        dump('Айди: '.$element->getId());
        dump('Название: '.$element->getName());
        dump('Дата\время: '.$element->getData()->getValue());
        //dump('Тема: '.$element->getThemeId()->getElement()->getName());
        foreach($element->getThemeId()->getAll() as $element) {
            dump('Тема: '.$element->getElement()->getName());
        }

    //}

    $count++;
}

echo '<pre>', $debug->Output(), '</pre>';
Application::getConnection()->startTracker(false);

$catalogSectionsIterator = $webinars::getList([
    'select' => ['ID', 'NAME', 'DATA', 'THEME_ID.ELEMENT'],
    'filter' => $arFilter,
    'order'  => ['ID' => 'ASC'],
    'runtime' => [
        'DATA.VALUE' => [
            //'data_type' => $entityPropsSingle->getDataClass(),
            'data_type' => 'Bitrix\Iblock\ElementTable',
            'reference' => [
                '=this.ID' => 'ref.IBLOCK_ELEMENT_ID'
            ],
            'join_type' => 'inner'
        ],
    ],
]);

echo '<pre>', $catalogSectionsIterator->getTrackerQuery()->getSql(), '</pre>';

\Bitrix\Main\Application::getConnection()->stopTracker();
