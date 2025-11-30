<?php

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;

class vartek_screenkeyboard extends CModule
{
    public $MODULE_ID = 'vartek.screenkeyboard';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;

    public function __construct()
    {
        include __DIR__ . '/version.php';

        /** @var <string> $arModuleVersion */
        $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME         = Loc::getMessage('VSK_MODULE_NAME');
        $this->MODULE_DESCRIPTION  = Loc::getMessage('VSK_MODULE_DESC');
        $this->PARTNER_NAME        = "Vartek";
        $this->PARTNER_URI         = "https://ipvartnyan.ru";
    }

    public function DoInstall(): void
    {
        global $APPLICATION;

        RegisterModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallEvents();

        $APPLICATION->IncludeAdminFile(Loc::getMessage('VSK_INSTALL_TITLE'), __DIR__ . '/step.php');
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentException
     */
    public function DoUninstall(): void
    {
        global $APPLICATION;

        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();

        $request = Application::getInstance()->getContext()->getRequest();
        if ($request->get('savedata') !== 'Y') Option::delete($this->MODULE_ID);

        UnRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(Loc::getMessage('VSK_UNINSTALL_TITLE'), __DIR__ . '/unstep.php');
    }

    public function InstallFiles(): bool
    {
        $root = Application::getDocumentRoot();
        CopyDirFiles(__DIR__ . '/components/', $root . '/local/components/', true, true);
        CopyDirFiles(__DIR__ . '/js/', $root . '/local/js/', true, true);

        return true;
    }

    public function UnInstallFiles(): bool
    {
        DeleteDirFilesEx('/local/components/vartek/screenkeyboard/');
        DeleteDirFilesEx('/local/js/vartek.screenkeyboard/');

        return true;
    }

    public function InstallEvents(): bool
    {
        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            "\\Vartek\\ScreenKeyboard\\EventManager",
            'onBeforeProlog'
        );

        return true;
    }

    public function UnInstallEvents(): bool
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            "\\Vartek\\ScreenKeyboard\\EventManager",
            'onBeforeProlog'
        );

        return true;
    }

    public function InstallDB(): bool
    {
        return true;
    }

    public function UnInstallDB(): bool
    {
        return true;
    }
}
?>