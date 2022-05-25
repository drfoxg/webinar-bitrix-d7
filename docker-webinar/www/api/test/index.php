<?php
//use \Bitrix\Main\Config\Configuration;

//require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//dump(Configuration::getValue('composer'));
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
        $( function() {
            $( "#dialog" ).dialog();
        } );
    </script>
    <script>
        console.log('start');

        var query = {
            c: 'drfoxg:webinar',
            action: 'webinar',
            mode: 'class'
        };

        var data = {
            theme: [1, 2],
            month: [5, 6],
            SITE_ID: 's1',
            //sessid: BX.message('bitrix_sessid')
        };

        var request = $.ajax({
            url: '/bitrix/services/main/ajax.php?' + $.param(query, true),
            method: 'POST',
            data: data
        });

        request.done(function (response) {
            console.log(response);
        });
    </script>
</head>
<body>
    <div id="dialog" title="Basic dialog">
        <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the &apos;x&apos; icon.</p>
    </div>
</body>
</html>


