<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\Application;

$moduleId = 'vartek.screenkeyboard';

try {
    Loader::includeModule($moduleId);
} catch (LoaderException $e) { return; }

$request = Application::getInstance()->getContext()->getRequest();

if ($request->get('mid') !== $moduleId) {
    LocalRedirect('/bitrix/admin/settings.php?mid=' . $moduleId . '&lang=' . LANGUAGE_ID);
}
