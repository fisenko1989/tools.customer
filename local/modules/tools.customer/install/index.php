<?php

declare(strict_types=1);

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class tools_customer extends CModule
{
    var $MODULE_ID = 'tools.customer';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    protected $module_path;

    protected static $events = [
        [
            'sale',
            'OnSaleOrderSaved',
            'tools.customer',
            '\Tools\Customer\Events\Sale',
            'OnSaleOrderSaved',
        ],
        [
            'main',
            'OnAdminSaleOrderView',
            'tools.customer',
            '\Tools\Customer\Events\Main',
            'OnAdminSaleOrderView',
        ]
    ];

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage("MODULE_DESCRIPTION");
        $this->module_path = dirname(__DIR__);
    }

    /**
     * Установка модуля
     * @return void
     */
    public function DoInstall(): void
    {
        global $APPLICATION;

        $this->InstallDB();

        if (count(self::$events) > 0) {
            $this->InstallEvents();
        }
        ModuleManager::registerModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(
            'Установка модуля ' . $this->MODULE_ID,
            $this->module_path . "/install/step.php"
        );
    }

    /**
     * Удаление модуля
     * @return void
     */
    public function DoUninstall(): void
    {
        global $APPLICATION;

        $this->UnInstallDB();

        if (count(self::$events) > 0) {
            $this->UnInstallEvents();
        }
        ModuleManager::unRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(
            'Установка модуля ' . $this->MODULE_ID,
            $this->module_path . "/install/unstep.php"
        );
    }

    /**
     * Добавляем события
     * @return bool
     */
    public function InstallEvents(): bool
    {
        $event_manager = EventManager::getInstance();

        foreach (self::$events as $event) {
            $event_manager->registerEventHandlerCompatible(
                $event[0],
                $event[1],
                $event[2],
                $event[3],
                $event[4],
                $event[5]
            );
        }

        return true;
    }

    /**
     * Удаляем события
     * @return bool
     */
    public function UnInstallEvents(): bool
    {
        $event_manager = EventManager::getInstance();

        foreach (self::$events as $event) {
            $event_manager->unRegisterEventHandler($event[0], $event[1], $event[2], $event[3], $event[4], $event[5]);
        }

        return true;
    }

    /**
     * Добавляем таблицу в бд
     * @return bool
     */
    function InstallDB(): bool
    {
        global $DB, $DBType;
        $DB->RunSQLBatch(
            Application::getDocumentRoot(
            ) . "/local/modules/" . $this->MODULE_ID . "/install/db/" . $DBType . "/install.sql"
        );
        return true;
    }

    /**
     * Удаляем Таблицу из бд
     * @return bool
     */
    function UnInstallDB(): bool
    {
        global $DB, $DBType;
        $DB->RunSQLBatch(
            Application::getDocumentRoot(
            ) . "/local/modules/" . $this->MODULE_ID . "/install/db/" . $DBType . "/uninstall.sql"
        );
        return true;
    }
}