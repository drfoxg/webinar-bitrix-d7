<?php
use \Bitrix\Main\Config\Configuration;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\Iblock;
//use \Bitrix\Iblock\Elements\ElementV1m1p0nWEBINARTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

Loader::includeModule("iblock");

$iblockId = 23;

dump($webinars = Iblock::wakeUp($iblockId)->getEntityDataClass());



dump(Configuration::getValue('composer'));

$elements =  $webinars::getList([
    'select' => ['ID', 'NAME', 'DATA', 'THEME_ID.ELEMENT'],
    'filter' => ['=ACTIVE' => 'Y'],
    'order'  => ['ID' => 'ASC'],
])->fetchCollection();

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

foreach($elements as $element) {

    if (in_array($count, $themeNumbers)) {
        dump('Айди: '.$element->getId());
        dump('Название: '.$element->getName());
        dump('Дата\время: '.$element->getData()->getValue());
        dump('Тема: '.$element->getThemeId()->getElement()->getName());
    }

    $count++;
}