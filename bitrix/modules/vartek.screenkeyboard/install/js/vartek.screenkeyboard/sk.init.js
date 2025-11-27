;(function (window, document, BX) {
    BX = BX || {};
    BX.ScreenKeyboard = BX.ScreenKeyboard || {};

    BX.ScreenKeyboard.init = function () {
        let container = document.getElementById('sk-keyboard-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'sk-keyboard-container';
            document.body.appendChild(container);
        }

        try {
            const keyboard = new BX.ScreenKeyboard.SkKeyboard();
            keyboard.init();
        } catch (e) {
            console.error('[ScreenKeyboard] Init error:', e);
        }
    };

    if (document.readyState !== 'loading') {
        BX.ScreenKeyboard.init();
    } else {
        document.addEventListener('DOMContentLoaded', BX.ScreenKeyboard.init);
    }
})(window, document, BX);
