<?php

use Bitrix\Main\ModuleManager;

class commerce_platform extends CModule
{
    public $MODULE_ID = 'commerce.platform';
    public $MODULE_VERSION = '2.0.0';
    public $MODULE_VERSION_DATE = '2026-10-07';
    public $MODULE_NAME = 'Commerce Platform';
    public $MODULE_DESCRIPTION = 'E-commerce platform for 1C-Bitrix.';

    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall(): void
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
