import Commons from "./commons.js"

export default class Forms extends Commons {
    constructor() {
        super();
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
        const maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
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
            } else if (field.classList.contains("is-textarea")) {
                field.querySelector("textarea").height = "auto"
                field.querySelector(".form-field").value = ""
            } else {
                field.querySelector(".form-field").value = ""
            }

        })

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

    _prepareFormFields() {
        let _thisClass = this;

        let defaultValues = {};

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
                        let defaultValue = input.dataset?.default;

                        if(defaultValue?.length) {
                            field.classList.add(filledClass, focusedClass);
                            defaultValues[input.name] = defaultValue
                        }

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
                        let defaults = hiddenInputOfSelect.dataset?.default?.split("###")
                        defaults = defaults.length ? defaults : [];
                        if(!isMultiple && defaults.length > 1) defaults = defaults[0]
                        defaultValues[hiddenInputOfSelect.id] = defaults

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

                        if(defaults.length) {
                            field.classList.add(filledClass)
                            if(isMultiple){
                                _thisClass.setMultipleSelectOptionsHTML(defaults, focusTarget, hiddenInputOfSelect);
                            } else {
                                let value = defaults[0]
                                _thisClass.setSingularSelectOptionHTML(value, focusTarget, hiddenInputOfSelect, field, openedOptionsClass);
                            }
                        }
                        
                        options.forEach(option => {

                            if (defaults?.indexOf(option.dataset.value) !== -1) {
                                option.classList.add(selectedOptionClass)
                            }

                            option.addEventListener("click", function () {
                                if (isMultiple) {
                                    option.classList.toggle(selectedOptionClass);

                                    let selectedValues = [];
                                    field.querySelectorAll(".option." + selectedOptionClass).forEach(selectedOption => {
                                        selectedValues.push(selectedOption.dataset.value)
                                    })

                                    //Show selected values
                                    _thisClass.setMultipleSelectOptionsHTML(selectedValues, focusTarget, hiddenInputOfSelect);
                                } else {
                                    field.querySelectorAll(".option." + selectedOptionClass).forEach(selectedOption => {
                                        selectedOption.classList.remove(selectedOptionClass)
                                    })

                                    option.classList.add(selectedOptionClass);
                                    let value = option.dataset.value
                                    _thisClass.setSingularSelectOptionHTML(value, focusTarget, hiddenInputOfSelect, field, openedOptionsClass);
                                }

                                const event = new Event('change');
                                hiddenInputOfSelect.dispatchEvent(event);
                            });
                        })
                    }
                }

                // Checkboxes
                else if (field.classList.contains("checkboxes-container")) {
                    let checkboxes = field.querySelectorAll("input")
                    let multiple = field.classList.contains("multiple")
                    let defaults = [];
                    let fieldID = field.dataset.id;

                    checkboxes.forEach(cb => {
                        if(cb.checked) defaults.push(cb.value)

                        cb.addEventListener("change", function () {
                            let parent = cb.closest(".checkboxes")
                            let cbsInGroup = parent.querySelectorAll("input");
                            if (!multiple) {
                                if (!cb.checked) {
                                    cb.checked = true
                                    return;
                                }
                                cbsInGroup.forEach(cbCheck => {
                                    if (cbCheck !== cb) {
                                        cbCheck.checked = false
                                    }
                                })
                            }
                        })
                    })

                    defaultValues[fieldID] = defaults
                }


                // File
                else {
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
                            let files = e.target.files;
                            let validFiles = [];

                            for (let i = 0; i < files.length; i++) {
                                let file = files[i];
                                let filecheck = _thisClass.checkFile(file, supports.replaceAll(" ", "").split(","));
                                if (filecheck === true) {
                                    validFiles.push(file);
                                }
                            }

                            if (validFiles.length > 0) {
                                updateFieldStatus(validFiles);
                            } else {
                                infoText.innerHTML = "Chyba! Niektorý z týchto typov súborov <span>nie je povolený</span> alebo žiadne súbory nespĺňajú požiadavky.";
                                infoText.classList.add("error");
                                infoText.classList.remove("success");
                                input.value = ""; // Clear the input field on error
                                dropHereText.classList.add("d-none");
                                dragDropText.classList.remove("d-none");
                            }
                        });

                        let uploadedFilesCount = 0; // To store the count of uploaded files

                        function updateFieldStatus(files) {
                            infoText.classList.add("success");
                            uploadedFilesCount = files.length; // Set the count of uploaded files
                            updateUploadedFilesCount(); // Update the displayed count

                            // You can further process each file in the 'files' array if needed
                            for (let i = 0; i < files.length; i++) {
                                let file = files[i];
                                // Process each file here if necessary
                            }

                            dropHereText.classList.add("d-none");
                            dragDropText.classList.remove("d-none");
                        }

                        function updateUploadedFilesCount() {
                            infoText.innerHTML = "Počet nahraných súborov: " + uploadedFilesCount;
                        }

                    }
                }
            });
        }, 10, 10000)

        return defaultValues;
    }

    setSingularSelectOptionHTML(value, focusTarget, hiddenInputOfSelect, field, openedOptionsClass) {
        focusTarget.innerHTML = value
        hiddenInputOfSelect.value = value

        field.classList.remove(openedOptionsClass)
    }

    setMultipleSelectOptionsHTML(selectedValues, focusTarget, hiddenInputOfSelect) {
        let html = ""
        selectedValues.forEach(selected => {
            html += "<div class='tag small red'>" + selected + "</div>"
        })
        focusTarget.innerHTML = html
        hiddenInputOfSelect.value = JSON.stringify(selectedValues)
    }

    validateFormFields(fieldNodes) {
        let fields = {}
        let errors = {}

        fieldNodes.forEach(field => {
            let container = field.closest(".form-field-container")
            let fieldName = container.dataset.name
            let isRequired = container.classList.contains("required");
            let isFile = container.classList.contains("is-file-input");

            if (!isFile && isRequired && this.empty(field.value)) {
                errors[field.id] = "Pole " + fieldName + " musíte vyplniť."
            } else if (field.type === "email" && !this.validateEmail(field.value)) {
                errors[field.id] = "Pole " + fieldName + " musí mať správny tvar emailu."
            } else if (isFile) {
                if (field.files.length) {
                    fields[field.id] = field.files[0]
                } else if (isRequired) {
                    errors[field.id] = "Nahrajte prosím súbor do poľa " + fieldName + "."
                }
            }
        })

        return {fields: fields, errors: errors}
    }

    validateEmail(email) {
        let re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    outputErrors(errors) {
        for (const [input, error] of Object.entries(errors)) {
            this.notify(error, "error")
        }
    }
}