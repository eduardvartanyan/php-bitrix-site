;(function (window, BX) {

    BX = BX || {};
    BX.ScreenKeyboard = BX.ScreenKeyboard || {};

    // Глобальная информация о состоянии активности клавиатуры
    BX.ScreenKeyboard.State = {
        _active: false,
        _field: null,

        isActive() { return this._active; },

        setActive(state = true, field = null) {
            this._active = !!state;
            this._field = field || null;
        },

        getActiveField() {
            return this._field;
        }
    };

})(window, BX);
