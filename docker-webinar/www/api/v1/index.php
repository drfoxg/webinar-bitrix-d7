<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Configuration;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\Iblock;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
    "drfoxg:webinar",
    "",
    Array(
        "INFOBLOCKID" => "2",
        "MONTHS" => [],
        "THEMES" => ""
    )
);


