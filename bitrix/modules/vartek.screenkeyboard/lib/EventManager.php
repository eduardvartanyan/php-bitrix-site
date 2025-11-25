<?php

namespace Vartek\ScreenKeyboard;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;

class EventManager
{
    public static function injectScript(&$content)
    {
        $enabled = Option::get('vartek.screenkeyboard', 'auto_enable', 'Y');
        if ($enabled !== 'Y') return;

        Asset::getInstance()->addJs('/local/js/vartek.screenkeyboard/keyboard.js');
        Asset::getInstance()->addJs('/local/js/vartek.screenkeyboard/layouts.js');
        Asset::getInstance()->addJs('/local/js/vartek.screenkeyboard/state.js');
        Asset::getInstance()->addJs('/local/js/vartek.screenkeyboard/sk.init.js');

        $content = str_replace(
            "</body>",
            "<script>if (window.BX && BX.ScreenKeyboard) { BX.ScreenKeyboard.init(); }</script></body>",
            $content
        );
    }
}
