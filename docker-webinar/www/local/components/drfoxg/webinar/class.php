<?php

namespace Drfoxg\Webinar;

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
            'test' => [
                'prefilters' => [
                    /*
                    new ActionFilter\HttpMethod(array(ActionFilter\HttpMethod::METHOD_POST)),
                    new ActionFilter\Csrf()
                    */
                ],
                'postfilters' => [],
            ],
            'webinar' => [
                'postfilters' => [],
                'prefilters' => []
            ]
        ];
    }

    //public function testAction($data)
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

    public function webinarAction(array $theme, array $month) :array
    {
        try {
            //Debug::writeToFile($data, 'data - webinarAction');

            Debug::writeToFile($theme, 'theme - webinarAction');
            Debug::writeToFile($month, 'month - webinarAction');

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
}