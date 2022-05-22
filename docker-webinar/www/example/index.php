<?php
use \Bitrix\Main\Config\Configuration;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetPageProperty("description", "Это точно Пример страницы Special");
$APPLICATION->SetTitle("Title of Example Special");

dd(Configuration::getValue('composer'));


?>Hello world<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>