<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'vartek.screenkeyboard',
    [
        "Vartek\\ScreenKeyboard\\EventManager" => 'lib/EventManager.php',
        "Vartek\\ScreenKeyboard\\Config"       => 'lib/Config.php',
        "Vartek\\ScreenKeyboard\\Helpers"      => 'lib/Helpers.php',
    ]
);
