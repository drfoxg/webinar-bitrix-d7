<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// пример
// https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2305&LESSON_PATH=3913.4565.4790.4777.2305
echo 'пример'.'<br/>';
echo $this->getComponent()->arResult['DATE'];


ShowNote('Component "' . $this->getComponent()->getName() . '" connected.');

