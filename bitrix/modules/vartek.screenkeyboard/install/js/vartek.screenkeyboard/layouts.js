;(function (window, BX) {
    BX = BX || {};
    BX.ScreenKeyboard = BX.ScreenKeyboard || {};

    BX.ScreenKeyboard.LAYOUTS = {
        ru: [
            ['й','ц','у','к','е','н','г','ш','щ','з','х','ъ'],
            ['ф','ы','в','а','п','р','о','л','д','ж','э'],
            ['shift','я','ч','с','м','и','т','ь','б','ю','.','backspace'],
            ['dig','lang','space','-','clear','done']
        ],
        en: [
            ['q','w','e','r','t','y','u','i','o','p'],
            ['a','s','d','f','g','h','j','k','l'],
            ['shift','z','x','c','v','b','n','m','.','backspace'],
            ['dig','lang','space','-','clear','done']
        ],
        email: [
            ['q','w','e','r','t','y','u','i','o','p'],
            ['a','s','d','f','g','h','j','k','l'],
            ['z','x','c','v','b','n','m','.','backspace'],
            ['dig','space','-','_','@','clear','done']
        ],
        num: [
            ['1','2','3'],
            ['4','5','6'],
            ['7','8','9'],
            ['.','0','backspace'],
            ['abc','done']
        ]
    };
})(window, BX);
