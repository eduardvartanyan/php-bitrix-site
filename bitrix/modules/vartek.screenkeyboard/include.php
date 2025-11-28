<?php

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Vartek\ScreenKeyboard\EventManager as SKEventManager;

Loader::registerAutoLoadClasses(
    'vartek.screenkeyboard',
    [
        "Vartek\\ScreenKeyboard\\EventManager" => 'lib/EventManager.php',
        "Vartek\\ScreenKeyboard\\Config"       => 'lib/Config.php',
        "Vartek\\ScreenKeyboard\\Helpers"      => 'lib/Helpers.php',
    ]
);

if (Loader::includeModule('vartek.screenkeyboard')) {
    EventManager::getInstance()->addEventHandler('main', 'OnBeforeProlog', [SKEventManager::class, 'onBeforeProlog']);
}
?>