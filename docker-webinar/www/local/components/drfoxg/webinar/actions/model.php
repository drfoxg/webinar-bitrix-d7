<?php

namespace Drfoxg\Webinar\Actions;

use Drfoxg\Webinar\Webinar;

/**
 * Class Model - модель вебинаров
 * @package Drfoxg\Webinar\Actions
 */
class Model extends Webinar
{
    /**
     * Ajax constructor.
     * @param \CBitrixComponent $oComponent
     * @return bool
     */
    public function __construct(\CBitrixComponent $oComponent)
    {
        parent::__construct($oComponent);

        return true;
    }

    /**
     * @return bool
     */
    protected function do()
    {
        return true;
    }
}