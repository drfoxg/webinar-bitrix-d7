<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

foreach ($this->getComponent()->arResult['WEBINARS'] as $key => $webinars) {
    echo '<div>'.$webinars['name'].'</div>'.' '.'<div>'.$webinars['date'].'</div>'.' '.'<div>'.$webinars['theme'].'</div>'.'<br/>';
}
echo '<br/>';

//ShowNote('Component "' . $this->getComponent()->getName() . '" connected.');

