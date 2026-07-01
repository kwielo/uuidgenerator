(function () {
    const feedback = document.querySelector('.copy-feedback');
    const lines = document.querySelectorAll('.uuid-line');
    const toastContainer = document.getElementById('toast-container');
    const themeToggle = document.querySelector('.toolbar__theme');
    const bulkInput = document.querySelector('.bulk-input');
    const bulkValue = document.querySelector('.bulk-value');
    const versionGroup = document.querySelector('.type-input');

    const THEME_KEY = 'uuidgen_theme';

    const getActiveVersionButton = function () {
        return document.querySelector('.type-input__button[aria-pressed="true"]');
    };

    const setActiveVersionButton = function (button) {
        const buttons = document.querySelectorAll('.type-input__button');
        buttons.forEach(function (item) {
            const isActive = item === button;
            item.classList.toggle('is-active', isActive);
            item.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    };

    const showToast = function (message) {
        if (!toastContainer) {
            return;
        }

        const toast = document.createElement('div');
        toast.className = 'toast toast--success';
        toast.textContent = message;
        toastContainer.appendChild(toast);

        window.setTimeout(function () {
            toast.classList.add('toast--visible');
        }, 0);

        window.setTimeout(function () {
            toast.classList.remove('toast--visible');
            window.setTimeout(function () {
                toast.remove();
            }, 200);
        }, 2000);
    };

    const initTheme = function () {
        if (localStorage.getItem(THEME_KEY) === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    };

    const toggleTheme = function () {
        const isLight = document.documentElement.getAttribute('data-theme') === 'light';
        if (isLight) {
            document.documentElement.removeAttribute('data-theme');
            localStorage.removeItem(THEME_KEY);
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem(THEME_KEY, 'light');
        }
    };

    initTheme();

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            toggleTheme();
        });
    }

    if (versionGroup) {
        versionGroup.addEventListener('click', function (event) {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            const button = target.closest('.type-input__button');
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            setActiveVersionButton(button);
        });
    }

    lines.forEach(function (line) {
        const input = line.querySelector('.uuid-input');
        const copyButton = line.querySelector('.uuid-copy');

        input.addEventListener('click', function () {
            input.select();
        });

        copyButton.addEventListener('click', function () {
            input.select();

            const onCopySuccess = function () {
                copyButton.classList.add('copied');
                line.classList.add('uuid-line--copied');
                if (feedback) {
                    feedback.textContent = 'Copied ' + input.value + ' to clipboard.';
                }
                showToast('Copied to clipboard');
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(input.value).then(onCopySuccess).catch(function () {
                    document.execCommand('copy');
                    onCopySuccess();
                });
            } else {
                document.execCommand('copy');
                onCopySuccess();
            }
        });
    });

    const submitButton = document.querySelector('.bulk-submit');
    if (submitButton) {
        submitButton.addEventListener('click', function () {
            const bulk = bulkInput ? bulkInput.value : '1';
            const activeVersionButton = getActiveVersionButton();
            const type = activeVersionButton && activeVersionButton.dataset.type ? activeVersionButton.dataset.type : 'uuid4';
            const l = window.location;
            window.location.assign(l.protocol + '//' + l.host + '/' + bulk + '/' + type);
        });
    }

    if (bulkInput) {
        const formatBulkValue = function () {
            const value = bulkInput.value;
            return value === '1' ? '1 ID' : value + ' IDs';
        };

        const updateValueText = function () {
            bulkInput.setAttribute('aria-valuetext', formatBulkValue());
            if (bulkValue) {
                bulkValue.textContent = formatBulkValue();
            }
        };
        bulkInput.addEventListener('input', updateValueText);
        updateValueText();
    }
})();
