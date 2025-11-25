<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Page\Asset;
use Vartek\ScreenKeyboard\Config;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class VartekScreenkeyboardComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params): array
    {
        return $params;
    }

    public function executeComponent(): void
    {
        try {
            Loader::includeModule('vartek.screenkeyboard');
        } catch (LoaderException $e) {
            ShowError('Module vartek.screenkeyboard is not installed');
            return;
        }

        if (Config::get('auto_enable') !== 'Y') {
            Asset::getInstance()->addJs('/local/js/vartek.screenkeyboard/layouts.js');
            Asset::getInstance()->addJs('/local/js/vartek.screenkeyboard/keyboard.js');
            Asset::getInstance()->addJs('/local/js/vartek.screenkeyboard/init.js');
            Asset::getInstance()->addString("<script>if (BX && BX.ScreenKeyboard){ BX.ScreenKeyboard.init(); }</script>");
        }

        $this->includeComponentTemplate();
    }
}