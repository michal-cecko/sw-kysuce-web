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
        document.addEventListener('mousedown', function (event) {
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
        if (allowedFileTypes.length) {
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

    resetForm(form) {
        let fields = form.querySelectorAll(".form-field-container")
        fields.forEach(field => {
            field.classList.remove("filled", "focused")
            if (field.classList.contains("custom-select")) {
                field.classList.remove("opened")
                field.querySelector(".form-field").value = ""
                field.querySelector(".selected-values").innerHTML = ""
                field.querySelector(".option.selected").classList.remove("selected");
            } else if (field.classList.contains("checkboxes-container")) {
                let cbs = field.querySelectorAll(".form-field")
                cbs.forEach(cb => {
                    cb.checked = false;
                })
            } else if (field.classList.contains("is-file-input")) {
                field.querySelector(".form-field").value = ""
                let text = field.querySelector(".drag-drop-text .text");
                text.innerHTML = ""
                text.classList.remove("success", "error")
            } else if(field.classList.contains("is-textarea")) {
                field.querySelector("textarea").height = "auto"
                field.querySelector(".form-field").value = ""
            } else {
                field.querySelector(".form-field").value = ""
            }

        })

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

    objectToFormData(obj, formData = new FormData(), parentKey = '') {
        for (let key in obj) {
            if (obj.hasOwnProperty(key)) {
                const value = obj[key];
                const fullKey = parentKey ? `${parentKey}[${key}]` : key;

                if (value instanceof Date) {
                    formData.append(fullKey, value.toISOString());
                } else if (value instanceof Object && !(value instanceof File)) {
                    this.objectToFormData(value, formData, fullKey);
                } else {
                    formData.append(fullKey, value);
                }
            }
        }

        return formData;
    }

    validateEmail(email) {
        let re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    _prepareFormFields() {
        let _thisClass = this;

        this.waitForElementToDisplay(".form-field-container", function () {
            let fields = document.querySelectorAll(".form-field-container");
            fields.forEach(field => {

                if (field.classList.contains("movable-label")) {
                    let focusedClass = "focused";
                    let filledClass = "filled";
                    let textareaClass = "is-textarea";

                    //Can be input or custom select

                    //Input + Textarea
                    if (field.classList.contains("is-text") || field.classList.contains("is-email")) {
                        let input = field.querySelector(".form-field");
                        input.addEventListener('focusin', function () {
                            field.classList.add(focusedClass)
                        });
                        input.addEventListener('focusout', function () {
                            if (!field.classList.contains(filledClass))
                                field.classList.remove(focusedClass)
                        });
                        input.addEventListener('input', function () {
                            if (input.value === "") {
                                field.classList.remove(filledClass)
                            } else {
                                field.classList.add(filledClass)
                            }

                            //Textarea auto-height
                            if (field.classList.contains(textareaClass)) {
                                // Count the number of line breaks in the textarea content
                                const lineBreaks = (input.value.match(/\n/g) || []).length;

                                // Set the textarea height based on the number of line breaks
                                input.style.height = `${34 * (lineBreaks + 1)}px`;
                            }
                        });
                    }

                    //Select
                    else if (field.classList.contains("custom-select")) {
                        let focusTarget = field.querySelector(".selected-values");
                        let hiddenInputOfSelect = field.querySelector("input");
                        let options = field.querySelectorAll(".options .option");
                        let isMultiple = field.classList.contains("multiple");
                        let openedOptionsClass = "opened";
                        let selectedOptionClass = "selected";

                        const hasValue = function () {
                            return hiddenInputOfSelect.value === "" || hiddenInputOfSelect.value === "[]";
                        }

                        field.addEventListener('click', function () {
                            field.classList.add(focusedClass, openedOptionsClass)
                        });

                        _thisClass.addClickOutsideListener(field, function () {
                            if (!field.classList.contains(filledClass))
                                field.classList.remove(focusedClass)

                            field.classList.remove(openedOptionsClass)
                        })

                        hiddenInputOfSelect.addEventListener('change', function () {
                            if (!hasValue) {
                                field.classList.remove(filledClass)
                            } else {
                                field.classList.add(filledClass)
                            }
                        });

                        options.forEach(option => {
                            option.addEventListener("click", function () {
                                if (isMultiple) {
                                    option.classList.toggle(selectedOptionClass);

                                    let selectedValues = [];
                                    field.querySelectorAll(".option." + selectedOptionClass).forEach(selectedOption => {
                                        selectedValues.push(selectedOption.dataset.value)
                                    })

                                    //Show selected values
                                    let html = ""
                                    selectedValues.forEach(selected => {
                                        html += "<div class='tag small red'>" + selected + "</div>"
                                    })
                                    focusTarget.innerHTML = html
                                    hiddenInputOfSelect.value = JSON.stringify(selectedValues)
                                } else {
                                    field.querySelectorAll(".option." + selectedOptionClass).forEach(selectedOption => {
                                        selectedOption.classList.remove(selectedOptionClass)
                                    })

                                    option.classList.add(selectedOptionClass);

                                    let value = option.dataset.value
                                    focusTarget.innerHTML = value
                                    hiddenInputOfSelect.value = value

                                    field.classList.remove(openedOptionsClass)
                                }

                                const event = new Event('change');
                                hiddenInputOfSelect.dispatchEvent(event);
                            });
                        })
                    }
                } else if (field.classList.contains("checkboxes-container")) {
                    let checkboxes = document.querySelectorAll("input")
                    let multiple = field.classList.contains("multiple")
                    checkboxes.forEach(cb => {
                        cb.addEventListener("change", function () {
                            if (!multiple) {
                                if (!cb.checked) {
                                    cb.checked = true
                                    return;
                                }
                                checkboxes.forEach(cbCheck => {
                                    if (cbCheck !== cb) {
                                        cbCheck.checked = false
                                    }
                                })
                            }
                        })
                    })
                } else {
                    if (field.classList.contains("is-file-input")) {
                        let input = field.querySelector("input")
                        let supports = field.dataset.allowed_types

                        field.addEventListener("dragover", function (e) {
                            e.preventDefault();
                            e.stopPropagation();

                        })

                        let dropHereText = field.querySelector(".drop-here-text")
                        let dragDropText = field.querySelector(".drag-drop-text")
                        let infoText = dragDropText.querySelector(".text")

                        field.addEventListener("dragenter", function (e) {
                            dropHereText.classList.remove("d-none")
                            dragDropText.classList.add("d-none")
                        })

                        field.addEventListener("dragleave", function (e) {
                            let target = e.relatedTarget;
                            if (!target || (target !== this && !this.contains(target))) {
                                dropHereText.classList.add("d-none");
                                dragDropText.classList.remove("d-none");
                            }
                        });

                        field.addEventListener("drop", function (e) {
                            e.stopPropagation();
                            e.preventDefault();

                            let dataTransfer = e.dataTransfer || e.originalEvent.dataTransfer; // Get the dataTransfer object
                            let file = dataTransfer.files[0];

                            updateFieldStatus(file)

                            let reader = new FileReader();
                            reader.onload = function () {
                                console.log(input.value)
                            };
                            reader.readAsDataURL(file);
                        })

                        input.addEventListener("change", function (e) {
                            updateFieldStatus(input.files[0]);
                        })

                        function updateFieldStatus(file) {
                            let filecheck = _thisClass.checkFile(file, supports.replaceAll(" ", "").split(","))
                            if (filecheck === true) {
                                infoText.classList.add("success")
                                infoText.innerHTML = "Nahraný súbor: <div class='uploaded-file-name'>" + file.name + "</div>"
                            } else {
                                if (filecheck === 0) {
                                    infoText.innerHTML = "Chyba! Tento typ súboru <span>nieje povolený</span>."
                                } else {
                                    infoText.innerHTML = "Chyba! Maximálna povolená veľkosť súboru je <span>1MB</span>."
                                }
                                infoText.classList.add("error")
                                infoText.classList.remove("success")
                                input.value = ""
                            }

                            dropHereText.classList.add("d-none")
                            dragDropText.classList.remove("d-none")
                        }
                    }
                }
            });
        }, 10, 10000)
    }
}