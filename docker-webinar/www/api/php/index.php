<?php
// Включаем строгую типизацию
declare(strict_types = 1);

//use \Bitrix\Main\Config\Configuration;

//require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//dump(Configuration::getValue('composer'));

$lsRes = `ls -al ../../`;

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <!--script src="https://code.jquery.com/jquery-3.6.0.js"></script-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        console.log('start');
    </script>
</head>
<body>
    <?php


        echo '<pre>';
        print_r($lsRes);
        echo '</pre>';


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

    <p>===</p>
    <p>Конец страницы.</p>
</body>
</html>


