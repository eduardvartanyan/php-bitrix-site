;(function (window, document, BX) {

    BX = BX || {};
    BX.ScreenKeyboard = BX.ScreenKeyboard || {};

    BX.ScreenKeyboard.init = function () {

        // контейнер создаётся компонентом
        const container = document.getElementById("sk--keyboard-container");

        if (!container) {
            console.warn("[ScreenKeyboard] Container not found");
            return;
        }

        try {
            const keyboard = new BX.ScreenKeyboard.SkKeyboard();
            keyboard.init();
        } catch (e) {
            console.error("[ScreenKeyboard] Init error:", e);
        }
    };

    if (document.readyState !== "loading") {
        BX.ScreenKeyboard.init();
    } else {
        document.addEventListener("DOMContentLoaded", BX.ScreenKeyboard.init);
    }

})(window, document, BX);
