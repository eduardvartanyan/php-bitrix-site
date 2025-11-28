;(function (window, document, BX) {
    BX = BX || {};
    BX.ScreenKeyboard = BX.ScreenKeyboard || {};

    const getLayouts = () => BX.ScreenKeyboard.LAYOUTS || {};
    const KeyboardState = BX.ScreenKeyboard.State;

    class SkKeyboard {
        constructor() {
            this.activeInput = null;
            this.activeCard = null;
            this.cardObserver = null;
            this.layout = 'ru';
            this.previousLayout = 'ru';
            this.shift = false;
            this.visible = false;
            this.container = null;
            this.focusLock = false;
            this.keyLock = false;
            this.initialized = false;
        }

        init() {
            if (this.initialized) {
                return;
            }
            this.initialized = true;

            this.container = document.getElementById('sk-keyboard-container');
            if (!this.container) return;

            document.addEventListener('focusin', (e) => this.handleFocus(e));
            document.addEventListener('mousedown', (e) => {
                if (this.container?.contains(e.target)) {
                    e.preventDefault();
                }
                this.handleGlobalClick(e);
            });
            document.addEventListener('touchstart', (e) => {
                if (this.container?.contains(e.target)) {
                    e.preventDefault();
                }
                this.handleGlobalClick(e);
            }, { passive: false });

            const keyboardMouseDown = (e) => {
                this.focusLock = true;
                setTimeout(() => {
                    this.focusLock = false;
                }, 0);
            };

            this.container.addEventListener('mousedown', keyboardMouseDown);
            this.container.addEventListener('touchstart', keyboardMouseDown, { passive: false });

            this.container.addEventListener('click', (e) => {
                const key = e.target.closest('button[data-key]')?.dataset.key;
                if (key) this.handleKeyClick(key);
            });
        }

        getLayoutForInput(el) {
            const type = el.type?.toLowerCase() || '';
            const mode = el.getAttribute('inputmode')?.toLowerCase() || '';

            const numericModes = ['numeric', 'tel', 'number'];
            const emailModes   = ['email'];

            if ([type, mode].some(v => numericModes.includes(v))) return 'num';
            if ([type, mode].some(v => emailModes.includes(v))) return 'email';

            return this.previousLayout;
        }

        isInput(el) {
            return ['number', 'text', 'email'].includes(el.type)
                || ['TEXTAREA'].includes(el.tagName);
        }

        getProductCard(element) {
            return element?.closest?.('.product-item-container') || null;
        }

        setActiveCard(card) {
            if (!card) {
                this.clearActiveCard();
                return;
            }

            if (this.activeCard === card) {
                this.ensureActiveCardHover();
                return;
            }

            this.clearActiveCard();
            this.activeCard = card;
            this.ensureActiveCardHover();
            this.observeActiveCard();
        }

        clearActiveCard() {
            this.disconnectCardObserver();
            if (!this.activeCard) return;
            this.activeCard.classList.remove('hover');
            this.activeCard = null;
        }

        ensureActiveCardHover() {
            if (this.activeCard && !this.activeCard.classList.contains('hover')) {
                this.activeCard.classList.add('hover');
            }
        }

        disconnectCardObserver() {
            if (this.cardObserver) {
                this.cardObserver.disconnect();
                this.cardObserver = null;
            }
        }

        observeActiveCard() {
            if (typeof MutationObserver === 'undefined' || !this.activeCard) return;

            this.disconnectCardObserver();
            this.cardObserver = new MutationObserver(() => {
                if (!this.activeCard) return;
                if (!this.activeCard.classList.contains('hover')) {
                    this.activeCard.classList.add('hover');
                }
            });

            this.cardObserver.observe(this.activeCard, {
                attributes: true,
                attributeFilter: ['class']
            });
        }



        show() {
            if (this.visible) return;

            this.visible = true;
            KeyboardState?.setActive(true, this.activeInput);
            this.container.classList.add('sk-visible');
            this.container.classList.remove('sk-hiding');

            this.render();
        }

        hide() {
            if (!this.visible) return;

            this.visible = false;
            KeyboardState?.setActive(false);
            this.container.classList.remove('sk-visible');
            this.container.classList.add('sk-hiding');
            setTimeout(() => {
                this.container.classList.remove('sk-hiding');
                this.container.innerHTML = '';
            }, 250);

            if (!this.activeInput) return;

            this.triggerValidation();

            this.activeInput.classList.remove('sk-active-input');
            this.activeInput = null;
        }

        render() {
            this.container.classList.toggle('numeric', this.layout === 'num');

            const layouts = getLayouts();
            if (!Object.keys(layouts).length) {
                console.error('[SkKeyboard] Layouts not ready');
                return;
            }

            const layout = layouts[this.layout];
            if (!layout) {
                console.error(`[SkKeyboard] Layout "${this.layout}" not found`);
                return;
            }

            this.container.innerHTML = layout
                .map(row => `<div class="keyboard-row">
                ${row.map(key => this.createKey(key)).join('')}
            </div>`)
                .join('');
        }

        createKey(key) {
            const labels = {
                backspace: '⌫',
                space: 'Пробел',
                done: 'Готово',
                shift: '⇧',
                dig: '123',
                abc: 'ABC',
                clear: 'Очистить',
                lang: this.layout === 'ru' ? 'EN' : 'RU',
            };

            if (this.shift && this.layout !== 'num') {
                if (key === '.') key = ',';
                if (key === '-') key = '_';
            }

            const isDigit = /^[0-9]$/.test(key);
            const isEmail = key === '@';
            const label = labels[key] || (this.shift ? key.toUpperCase() : key);
            const width = key === '.' && this.layout === 'num' ? 'style="width:95px"' : '';
            const classes = [
                'key',
                key,
                isDigit ? 'digit' : '',
                isEmail ? 'email-key' : '',
                key === 'shift' && this.shift ? 'active' : ''
            ].join(' ').trim();

            return `<button type="button" class="${classes}" data-key="${key}" ${width}>${label}</button>`;
        }



        handleGlobalClick(e) {
            const { target } = e;
            const targetCard = this.getProductCard(target);
            const insideActiveCard = this.activeCard && targetCard === this.activeCard;

            if (this.container?.contains(target)) {
                this.ensureActiveCardHover();
                return;
            }

            if (this.isInput(target)) {
                if (target !== this.activeInput) {
                    this.activeInput = target;
                    this.layout = this.getLayoutForInput(target);
                    this.render();
                }
                this.setActiveCard(targetCard);
                this.show();
                return;
            }

            if (insideActiveCard) {
                this.ensureActiveCardHover();
                return;
            }

            const path = e.composedPath?.() || [];
            if (path.includes(this.activeInput)) return;

            this.clearActiveCard();
            if (this.visible) this.hide();
        }

        handleFocus(e) {
            if (!this.isInput(e.target)) return;
            if (this.focusLock) return;

            if (this.activeInput === e.target) {
                this.ensureActiveCardHover();
                return;
            }

            document.querySelectorAll('.sk-active-input').forEach(el => {
                el.classList.remove('sk-active-input');
            });

            this.activeInput = e.target;
            this.activeInput.classList.add('sk-active-input');
            this.layout = this.getLayoutForInput(e.target);
            this.setActiveCard(this.getProductCard(e.target));
            this.show();
        }

        handleKeyClick(key) {
            if (this.keyLock) return;
            this.keyLock = true;
            setTimeout(() => {
                this.keyLock = false;
            }, 0);

            let im = this.activeInput?.inputmask || BX?.MaskedInput?.instances?.find(i => i.el === this.activeInput);
            if (im && im.opts) {
                im.opts.clearIncomplete = false;
                im.opts.clearMaskOnLostFocus = false;
            } else {
                const originalInput = this.activeInput?.oninput;
                if (this.activeInput.oninput) {
                    this.activeInput.oninput = null;
                    setTimeout(() => this.activeInput.oninput = originalInput, 50);
                }
            }

            switch (key) {
                case 'backspace':
                    this.handleBackspace();
                    break;
                case 'space':
                    this.insertCharacter(' ');
                    break;
                case 'done':
                    if (this.activeInput) {
                        KeyboardState?.setActive(false);
                        this.activeInput.blur();
                    }
                    this.hide();
                    break;
                case 'lang':
                    this.toggleLanguage();
                    break;
                case 'shift':
                    this.toggleShift();
                    break;
                case 'dig':
                    this.toggleLayout('num');
                    break;
                case 'abc':
                    this.toggleLayout(this.previousLayout !== 'num' ? this.previousLayout : 'ru');
                    break;
                case 'clear':
                    if (this.activeInput) {
                        this.activeInput.value = '';
                        this.dispatchInputEvent(this.activeInput);
                    }
                    break;
                default:
                    const value = this.shift ? key.toUpperCase() : key;
                    this.insertCharacter(value);
                    break;
            }
        }

        supportsSelection(input) {
            const type = input?.type?.toLowerCase();
            return ['text', 'search', 'password', 'url', 'tel'].includes(type);
        }

        debugInputState() {
            if (!this.activeInput) return null;
            return {
                id: this.activeInput.id,
                type: this.activeInput.type,
                value: this.activeInput.value,
                selectionStart: this.activeInput.selectionStart,
                selectionEnd: this.activeInput.selectionEnd
            };
        }

        handleBackspace() {
            if (!this.activeInput) return;

            this.focusActiveInput();

            const start = this.activeInput.selectionStart ?? this.activeInput.value.length;
            const end = this.activeInput.selectionEnd ?? this.activeInput.value.length;

            if (!this.supportsSelection(this.activeInput)) {
                const value = this.activeInput.value;
                this.activeInput.value = value.slice(0, -1);
                this.dispatchInputEvent(this.activeInput);
                return;
            }

            if (start === end && start > 0) {
                try {
                    this.activeInput.setSelectionRange(start - 1, start);
                } catch (error) {
                    return;
                }
            }

            try {
                document.execCommand('delete');
            } catch (e) {
                const val = this.activeInput.value;
                this.activeInput.value = val.slice(0, Math.max(0, start - 1)) + val.slice(end);
                this.activeInput.selectionStart = this.activeInput.selectionEnd = Math.max(0, start - 1);
            }

            this.dispatchInputEvent(this.activeInput);
        }

        insertCharacter(char) {
            if (!this.activeInput) return;

            this.focusActiveInput();

            const { type } = this.activeInput;
            const value = this.activeInput.value;
            const canSelect = this.supportsSelection(this.activeInput);

            try {
                if (canSelect) {
                    const start = this.activeInput.selectionStart ?? value.length;
                    const end = this.activeInput.selectionEnd ?? value.length;
                    try {
                        this.activeInput.setSelectionRange(start, end);
                        document.execCommand('insertText', false, char);
                    } catch (err) {
                        document.execCommand('insertText', false, char);
                    }
                } else {
                    this.activeInput.value = `${value}${char}`;
                }
            } catch {
                this.activeInput.value = value + char;
            }

            this.dispatchInputEvent(this.activeInput);

            if (this.shift) {
                this.shift = false;
                this.render();
            }
        }



        toggleLanguage() {
            this.layout = this.layout === 'ru' ? 'en' : 'ru';
            this.previousLayout = this.layout;
            this.render();
        }

        toggleLayout(layout) {
            if (!layout) return;

            if (this.layout !== 'num' && this.layout !== 'email') {
                this.previousLayout = this.layout;
            }
            this.layout = layout;

            this.render();
        }

        toggleShift() {
            this.shift = !this.shift;
            this.render();
        }



        dispatchInputEvent(element) {
            if (!element) return;

            const type = element.type?.toLowerCase();
            if (type === 'number') {
                // Для числовых полей событие input/ change шлем только при закрытии клавиатуры (triggerValidation),
                // чтобы нативная логика Bitrix не дублировала символы.
                return;
            }

            const event = new Event('input', { bubbles: true });
            element.dispatchEvent(event);

            // change генерируется вручную в triggerValidation, чтобы не срабатывала
            // автоматическая коррекция количества на каждой букве
        }

        triggerValidation() {
            const input = this.activeInput;
            if (!input) return;

            input.value = String(input.value ?? '');

            const im = input.inputmask ||
                (window.Inputmask?.get ? window.Inputmask.get(input) : null) ||
                BX?.MaskedInput?.instances?.find(i => i.el === input);

            if (im?.isComplete && !im.isComplete()) {
                input.value = '';
                im.mask?.(input);
            }

            setTimeout(() => {
                ['input', 'change', 'blur'].forEach(eventName => {
                    input.dispatchEvent(new Event(eventName, { bubbles: true }));
                });
            }, 50);
        }

        focusActiveInput() {
            if (!this.activeInput) return;
            if (document.activeElement === this.activeInput) return;

            this.focusLock = true;
            this.activeInput.focus();
            setTimeout(() => {
                this.focusLock = false;
            }, 0);
        }
    }

    BX.ScreenKeyboard.SkKeyboard = SkKeyboard;
})(window, document, BX);
