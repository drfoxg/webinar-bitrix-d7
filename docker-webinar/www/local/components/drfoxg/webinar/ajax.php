<?php
define('NO_AGENT_CHECK', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$APPLICATION->IncludeComponent(
    'drfoxg:webinar',
    '.default',
    array(
        "IBLOCK_ID"	=> "1"
    ),
    false
);