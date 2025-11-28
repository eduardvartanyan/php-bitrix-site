;(function (window, document, BX) {
    BX = BX || {};

    const mode = BX.message('VSK_MODE') || 'kiosk';
    if (mode === 'kiosk' && !kioskDetector()) return;

    BX.ScreenKeyboard = BX.ScreenKeyboard || {};

    if (BX.ScreenKeyboard.__scriptLoaded) return;
    BX.ScreenKeyboard.__scriptLoaded = true;

    BX.ScreenKeyboard.init = function () {
        if (BX.ScreenKeyboard.__initialized) {
            return BX.ScreenKeyboard.instance;
        }

        let container = document.getElementById('sk-keyboard-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'sk-keyboard-container';
            document.body.appendChild(container);
        }

        const keyboard = new BX.ScreenKeyboard.SkKeyboard();
        keyboard.init();
        BX.ScreenKeyboard.instance = keyboard;
        BX.ScreenKeyboard.__initialized = true;
        return keyboard;
    };

    if (document.readyState !== 'loading') {
        BX.ScreenKeyboard.init();
    } else {
        document.addEventListener('DOMContentLoaded', BX.ScreenKeyboard.init, { once: true });
    }

    function kioskDetector() {
        if (!window.matchMedia('(pointer: fine)').matches) return true;
        if (window.matchMedia('(display-mode: fullscreen)').matches) return true;
        if (window.innerHeight === window.screen.availHeight) return true;
        if (!navigator.keyboard) return true;

        return false;
    }
})(window, document, BX);
