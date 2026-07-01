(function () {
    const feedback = document.querySelector('.copy-feedback');
    const lines = document.querySelectorAll('.uuid-line');
    lines.forEach(function (line) {
        const input = line.querySelector('.uuid-input');
        const copyButton = line.querySelector('.uuid-copy');

        input.addEventListener('click', function () {
            input.select();
        });

        copyButton.addEventListener('click', function () {
            input.select();
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(input.value);
            } else {
                document.execCommand('copy');
            }

            copyButton.classList.add('copied');
            if (feedback) {
                feedback.textContent = 'Copied ' + input.value + ' to clipboard.';
            }

            window.setTimeout(function () {
                copyButton.classList.remove('copied');
            }, 1500);
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

    const bulkInput = document.querySelector('.bulk-input');
    if (bulkInput) {
        const updateValueText = function () {
            bulkInput.setAttribute('aria-valuetext', bulkInput.value + ' UUIDs');
        };
        bulkInput.addEventListener('input', updateValueText);
        updateValueText();
    }
})();
