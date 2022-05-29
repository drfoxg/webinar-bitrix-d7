<?php
// Включаем строгую типизацию
declare(strict_types = 1);

use \Bitrix\Main\Config\Configuration;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetPageProperty("description", "Это точно Пример страницы Special");
$APPLICATION->SetTitle("Title of Example Special");

dump(Configuration::getValue('composer'));
?>Example page
<br/>

<?php

/* команда ОС в ` `
$lsRes = `ls -al ../../`;
echo '<pre>';
print_r($lsRes);
echo '</pre>';
*/

function throwException(int $i) {
    //throw new Exception('Not set!') ;

    if (is_string($i)) {
        echo 'true <br/>';
    } else {
        echo 'false <br/>';
    }
}

$id = $_GET['id'] ?? throwException("123");

$id = isset($_GET['id']) ? $_GET['id'] : throwException("123");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    //throw new Exception('Not set!');
}

?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>