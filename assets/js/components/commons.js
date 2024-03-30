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

    getCurrentURLParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const params = {};

        for (const [key, value] of urlParams) {
            params[key] = value;
        }

        return params;
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

    waitForElementToDisplay(selector, callback, checkFrequencyInMs, timeoutInMs) {
        var startTimeInMs = Date.now();
        (function loopSearch() {
            if (document.querySelector(selector) != null) {
                callback();
                return;
            } else {
                setTimeout(function () {
                    if (timeoutInMs && Date.now() - startTimeInMs > timeoutInMs)
                        return;
                    loopSearch();
                }, checkFrequencyInMs);
            }
        })();
    }
}