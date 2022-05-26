<?php

namespace Drfoxg\Webinar;

use \Bitrix\Main\DB\Exception;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\Errorable;
use \Bitrix\Main\ErrorCollection;
use \Bitrix\Main\Engine\ActionFilter;
use \Bitrix\Main\Engine\Contract\Controllerable;

use \Bitrix\Main\Diag\Debug;

/**
 * Class Webinar
 * @package Drfoxg\Webinar
 */
class Webinar extends \CBitrixComponent implements Controllerable, Errorable
{
    /**
     * @var ErrorCollection
     */
    protected ErrorCollection $errorCollection;

    /**
     * Подключаемые модули
     * @var array
     */
    private $arModules = [
        "iblock",
    ];

    private array $themes;
    private array $months;
    private array $webinars;

    /**
     * @return array
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * @param array $themes
     */
    public function setThemes(array $themes): void
    {
        $this->themes = $themes;
    }

    /**
     * @return array
     */
    public function getMonths(): array
    {
        return $this->months;
    }

    /**
     * @param array $months
     */
    public function setMonths(array $months): void
    {
        $this->months = $months;
    }

    /**
     * @param array $themes
     */
    public function getWebinars(): array
    {
        return $this->webinars;
    }

    /**
     * @param array $themes
     */
    public function setWebinars(array $webinars): void
    {
        $this->webinars = $webinars;
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function convertToInt(array $data):array
    {
        /*
        Debug::dumpToFile($data, '$data - convertToInt');
        $data = explode(',', $data);
        */
        $size = count($data);
        for ($i = 0; $i < $size; $i++) {
            $data[$i] = (int)$data[$i];

            if ($data[$i] === 0) {
                throw new Exception('Wrong input data');
            }
        }

        Debug::dumpToFile($data, '$data - convertToInt');

        return $data;
    }
    /**
     * Обработка событий компонента
     * @return string
     */
    private function prepareAction()
    {
        if ($this->request->isAjaxRequest()) {
            Debug::writeToFile($this->request->isAjaxRequest(), 'on prepareAction - ret ajax');
            return 'ajax';
        }

        Debug::writeToFile($this->request->isAjaxRequest(), 'on prepareAction - ret init');
        return 'init';
    }

    /**
     * Получаем параметры из вне
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams = [])
    {
        $this->errorCollection = new ErrorCollection();
        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * Проверяем права доступа
     * @return bool
     */
    private function haveAccess()
    {
        return true;
    }

    /**
     * Webinar constructor.
     * @param \CBitrixComponent|null $oComponent
     * @return bool
     */
    public function __construct(\CBitrixComponent $oComponent = null)
    {
        parent::__construct($oComponent);

        return true;
    }

    /**
     * Точка входа в компонент
     * @return string
     */
    public function executeComponent()
    {
        Debug::writeToFile(null, 'executeComponent');

        try {
            $this->loadModules();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
            die;
        }

        if (!$this->haveAccess()) {
            return false;
        }

        $sAction = $this->prepareAction();

        if (!$sAction) {
            return false;
        }

        return $this->doAction($sAction);
    }

    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }

    public function getErrorByCode($code): Error
    {
        return $this->errorCollection->getErrorByCode($code);
    }

    public function configureActions()
    {
        // TODO: Implement configureActions() method.
        return [
            /*
            'test' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
            */
            'webinar' => [
                'postfilters' => [],
                'prefilters' => []
            ]
        ];
    }

    //public function testAction($data)
    /*
    public function testAction(string $theme, string $month) :array
    {
        try {
            //Debug::writeToFile($data, 'data - webinarAction');

            Debug::writeToFile($theme, 'theme - testAction');
            Debug::writeToFile($month, 'month - testAction');

            return [
                'asd' => $theme,
                'dsa' => $month,
                'count' => 200
            ];
        } catch (Exceptions $e) {
            $this->errorCollection[] = new Error($e->getMessage());
            return [
                "result" => "Произошла ошибка",
            ];
        }

    }
    */

    public function webinarAction(array $theme = [], array $month = []) :array
    {
        try {
            //Debug::writeToFile($data, 'data - webinarAction');

            Debug::dumpToFile($theme, 'theme - webinarAction');
            Debug::dumpToFile($month, 'month - webinarAction');

            $theme = $this->convertToInt($theme);
            $month = $this->convertToInt($month);

            $this->setThemes($theme);
            $this->setMonths($month);

            Debug::dumpToFile($this->getThemes(), 'theme - webinarAction');
            Debug::dumpToFile($this->getMonths(), 'month - webinarAction');

            $this->doAction('model');
            /*
            return [

                'themeRet' => $this->getThemes(),
                'monthRet' => $this->getMonths(),
                'webinarsRet' => $this->getWebinars()

            ];
            */

            return $this->getWebinars();

        } catch (Exceptions $e) {
            $this->errorCollection[] = new Error($e->getMessage());
            return [
                "result" => "Произошла ошибка",
            ];
        }

    }


    /**
     * Формируем метод обработки события
     * @param string $sAction
     * @return string
     */
    private function doAction($sAction)
    {
        $sFileName = $sAction . '.php';

        if (file_exists(dirname(__FILE__) . '/actions/' . $sFileName)
            && include_once 'actions/' . $sFileName) {
            $sClassNameSpace = __NAMESPACE__ . '\Actions\\' . ucfirst($sAction);

            $oObject = new $sClassNameSpace($this);

            if (is_callable([$oObject, 'do'])) {
                return call_user_func([$oObject, 'do']);
            }
        }

        return '';
    }

    /**
     * Подключаем модули
     * @return bool
     * @throws LoaderException
     */
    private function loadModules()
    {
        if ($this->arModules) {
            foreach ($this->arModules as $sModule) {
                if (!Loader::includeModule($sModule)) {
                    throw new LoaderException('Module "' . $sModule . '" was not initialized.');
                }
            }
        }

        return true;
    }

    /**
     * Получаем доступ к помощникам компонента
     * @param string $sName
     * @return object|null
     */
    protected function getHelper($sName = '')
    {
        try {
            $this->loadModules();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
            die;
        }

        $sFileName = lcfirst($sName) . '.php';

        if (file_exists(dirname(__FILE__) . '/helpers/' . $sFileName)
            && include_once 'helpers/' . $sFileName) {
            $sClassNameSpace = __NAMESPACE__ . '\Helpers\\' . ucfirst($sName);

            return new $sClassNameSpace;
        }

        return null;
    }
}