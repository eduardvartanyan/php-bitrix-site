<?php

namespace Vartek\ScreenKeyboard;

use Bitrix\Main\Page\Asset;

class EventManager
{
    public static function onBeforeProlog()
    {
        $mode = Config::get('mode');
        if ($mode === 'off') return;

        Asset::getInstance()->addString("
            <script>
                BX.message({
                    VSK_MODE: '" . \CUtil::JSEscape($mode) . "'
                });
            </script>
        ");

        $asset = Asset::getInstance();

        $asset->addJs('/local/js/vartek.screenkeyboard/keyboard.js');
        $asset->addJs('/local/js/vartek.screenkeyboard/layouts.js');
        $asset->addJs('/local/js/vartek.screenkeyboard/state.js');
        $asset->addJs('/local/js/vartek.screenkeyboard/sk.init.js');

        $asset->addCss('/local/js/vartek.screenkeyboard/style.css');
    }
}
