<?php

namespace Drfoxg\Webinar\Actions;

use Drfoxg\Webinar\Webinar;

/**
 * Class Init
 * @package Drfoxg\Webinar\Actions
 */
class Init extends Webinar
{

    public array $arResult;

    /**
     * Init constructor.
     * @param \CBitrixComponent $oComponent
     * @return bool
     */
    public function __construct(\CBitrixComponent $oComponent)
    {
        parent::__construct($oComponent);

        // пример
        // https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2305&LESSON_PATH=3913.4565.4790.4777.2305

        $this->arResult['DATE'] = date('Y-m-d');

        return true;
    }

    /**
     * @return bool
     */
    protected function do()
    {
        $this->includeComponentTemplate();

        $this->arResult['DATE'] = date('Y-m-d');

        return true;
    }
}