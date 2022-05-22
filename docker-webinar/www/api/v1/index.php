<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Configuration;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\Iblock;
use \Bitrix\Iblock\SectionTable;
//use \Bitrix\Iblock\Elements\ElementV1m1p0nWEBINARTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

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
$month = 6;


$currentYear = date('Y');
$number = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
$dateStart = new DateTime($currentYear.'-' . $month . '-01');
$dateEnd = new DateTime($currentYear.'-' . $month . '-' . $number);

dump($dateStart->format('d-m-Y H:i:s'));
dump($dateEnd->format('d-m-Y 23:59:59'));

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

dump($entityPropsSingle);

//dump($entityPropsSingle->getDataClass());

$arFilter['>=DATA.VALUE'] = $dateStart->format('Y-m-d H:i:s');
$arFilter['<=DATA.VALUE'] = $dateEnd->format('Y-m-d 23:59:59');
$arFilter['=ACTIVE'] = 'Y';

dump($arFilter);

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

foreach($elements as $element) {

    //if (in_array($count, $themeNumbers)) {
        dump('Айди: '.$element->getId());
        dump('Название: '.$element->getName());
        dump('Дата\время: '.$element->getData()->getValue());
        dump('Тема: '.$element->getThemeId()->getElement()->getName());
    //}

    $count++;
}

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

//echo '<pre>', $sql = $elements->getTrackerQuery()->getSql(), '</pre>';
