(function () {
    const feedback = document.querySelector('.copy-feedback');
    const lines = document.querySelectorAll('.uuid-line');
    const toastContainer = document.getElementById('toast-container');
    const themeToggle = document.querySelector('.toolbar__theme');
    const bulkInput = document.querySelector('.bulk-input');
    const bulkValue = document.querySelector('.bulk-value');

    const THEME_KEY = 'uuidgen_theme';

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
                if (feedback) {
                    feedback.textContent = 'Copied ' + input.value + ' to clipboard.';
                }
                showToast('Copied to clipboard');
                window.setTimeout(function () {
                    copyButton.classList.remove('copied');
                }, 1500);
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
            const bulk = document.querySelector('.bulk-input').value;
            const type = document.querySelector('.type-input').value;
            const l = window.location;
            window.location.assign(l.protocol + '//' + l.host + '/' + bulk + '/' + type);
        });
    }

    if (bulkInput) {
        const updateValueText = function () {
            bulkInput.setAttribute('aria-valuetext', bulkInput.value + ' UUIDs');
            if (bulkValue) {
                bulkValue.textContent = bulkInput.value;
            }
        };
        bulkInput.addEventListener('input', updateValueText);
        updateValueText();
    }
})();
