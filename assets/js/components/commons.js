export default class Commons {
    constructor() {
        this.ajaxURL = PHPVars.ajaxUrl;
        this.nonce = PHPVars.nonce;
        this.phoneMQ = window.matchMedia('(max-width: 768px)');
    }

    addParamsToUrl(params, baseUrl) {
        const queryString = Object.entries(params)
            .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
            .join('&');

        return `${baseUrl}?${queryString}`;
    }

    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    empty(variable) {
        return ([undefined, null, false, 0, "[]", "", "null", "0000-00-00"].includes(variable)) ||
            (Array.isArray(variable) && !variable.length) ||
            (!Array.isArray(variable) && typeof variable === "object" && !Object.keys(variable).length)
    }

    notify(text, type = "success") {
        let container = document.getElementById("notifications")
        const div = document.createElement('div');
        div.classList.add('notification', type, "shown");
        div.innerHTML = text;
        container.appendChild(div);
        setTimeout(function () {
            div.classList.remove("shown");
            setTimeout(function () {
                div.remove();
            }, 500);
        }, 3000);
    }

    outputErrors(errors) {
        for (const [input, error] of Object.entries(errors)) {
            this.notify(error, "error")
        }
    }

    async WPPostAjax(body) {
        return fetch(this.ajaxURL, {
            method: 'POST',
            credentials: 'same-origin',
            body: body,
        });
    }

    addClickOutsideListener(element, callback) {
        document.addEventListener('mousedown', function(event) {
            if (!element.contains(event.target)) {
                const clickOutsideEvent = new CustomEvent('click-outside');
                element.dispatchEvent(clickOutsideEvent);
            }
        });

        element.addEventListener('click-outside', callback);
    }

    checkFile(file, allowedFileTypes = []) {
        let fileName = file.name;
        let allow = 0;

        //check extension
        if(allowedFileTypes.length) {
            allow = true
        } else {
            allowedFileTypes.forEach((ext) => {
                if (fileName.endsWith(ext.toLowerCase())) allow = true;
            })
        }

        //check max filesize
        const maxFileSize = 1 * 1024 * 1024; // 1 MB in bytes
        if (file && file.size > maxFileSize) {
            allow = 1;
        }

        return allow;
    }

    checkCaptcha() {
        let _this = this
        return new Promise(function (resolve, reject) {
            grecaptcha.ready(function () {
                grecaptcha.execute("***REMOVED***", {action: 'submit'}).then(function (token) {
                    resolve(token);
                }).catch(function (error) {
                    reject(error)
                });
            });
        });
    }
}