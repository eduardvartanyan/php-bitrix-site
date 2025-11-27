<?php

namespace Vartek\ScreenKeyboard;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Config\Option;

class EventManager
{
    public static function onBeforeProlog()
    {
        $enabled = Option::get('vartek.screenkeyboard', 'auto_enable', 'Y');
        if ($enabled !== 'Y') return;

        $asset = Asset::getInstance();

        $asset->addJs('/local/js/vartek.screenkeyboard/keyboard.js');
        $asset->addJs('/local/js/vartek.screenkeyboard/layouts.js');
        $asset->addJs('/local/js/vartek.screenkeyboard/state.js');
        $asset->addJs('/local/js/vartek.screenkeyboard/sk.init.js');

        $asset->addCss('/local/components/vartek/screenkeyboard/templates/.default/style.css');
    }
}
