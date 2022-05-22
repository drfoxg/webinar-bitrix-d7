<?php
use \Bitrix\Main\Config\Configuration;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

dd(Configuration::getValue('composer'));
