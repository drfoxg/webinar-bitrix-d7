<?php

namespace Drfoxg\Webinar\Actions;

use CModule;
use CIBlockElementRights;
use CIBlockElement;
use Drfoxg\Webinar\Webinar;

/**
 * Class Init
 * @package Drfoxg\Webinar\Actions
 */
class Init extends Webinar
{

    //public array $arResult;

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

        //$this->arResult['DATE'] = date('Y-m-d');
        //$this->arResult = [];

        return true;
    }

    /**
     * @return bool
     */
    protected function do()
    {
        $this->arResult['DATE'] = date('Y-m-d');

        $themesAsString = $this->arParams['THEMES'];
        $this->arResult['THEMES'] = array_map('intval', explode(',', $themesAsString));
        $this->arResult['MONTHS'] = array_map('intval', $this->arParams['MONTHS']);

        $this->includeComponentTemplate();


        return true;
    }
}