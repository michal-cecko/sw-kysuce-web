import Commons from "./commons.min.js"
import EventBus from "./eventbus.min.js"
import { createApp, ref, onMounted, watch } from '../libs/vue/vue.min.js';

export default class Forms extends Commons {
    constructor() {
        super();
        this.files = {}
    }

    async WPPostAjax(body) {
        return fetch(this.ajaxURL, {
            method: 'POST',
            credentials: 'same-origin',
            body: body,
        });
    }

    addClickOutsideListener(element, callback) {
        if (!(element instanceof Element)) {
            console.error('Invalid element:', element);
            return;
        }

        document.addEventListener('mousedown', function (event) {
            if (!element.contains(event.target)) {
                const clickOutsideEvent = new CustomEvent('click-outside');
                element.dispatchEvent(clickOutsideEvent);
            }
        });

        element.addEventListener('click-outside', callback);
    }

    checkFile(file, field) {
        let allowedFileTypes = field.dataset.allowed_types?.replaceAll(" ", "").split(",").filter(type => type.trim() !== "");
        let fileName = file.name;
        let isValid;

        //check extension
        if (!allowedFileTypes.length) {
            isValid = true
        } else {
            isValid = "Nepovolený typ súboru.";
            allowedFileTypes.forEach((ext) => {
                console.log(fileName.toLowerCase(), ext.toLowerCase())
                if (fileName.toLowerCase().endsWith(ext.toLowerCase())) {
                    isValid = true;
                }
            })
        }
        if (isValid !== true) return isValid;

        //check max filesize
        const maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
        if (file && file.size > maxFileSize) {
            isValid = "Maximálna veľkosť súboru je 2MB.";
        }

        return isValid;
    }

    checkCaptcha() {
        return new Promise(function (resolve, reject) {
            //TODO FIX CAPTCHA
            /*grecaptcha.ready(function () {
                grecaptcha.execute(window.RECAPTCHA_SITE_KEY, {action: 'submit'}).then(function (token) {
                    resolve(token);
                }).catch(function (error) {
                    reject(error)
                });
            });*/
            resolve()
        });
    }

    resetForm(form) {
        let fields = form.querySelectorAll(".form-field-container")
        fields.forEach(field => {
            field.classList.remove("filled", "focused")
            if (field.classList?.contains("custom-select")) {
                field.classList?.remove("opened")
                field.querySelector(".form-field").value = ""
                field.querySelector(".selected-values").innerHTML = ""
                field.querySelector(".option.selected")?.classList?.remove("selected");
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

                        if (defaultValue?.length) {
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
                        let focusTargetClass = 'selected-values';
                        let focusTarget = field.querySelector(`.${focusTargetClass}`);
                        let hiddenInputOfSelect = field.querySelector("input");
                        let optionsContainerClass = 'options';
                        let optionsContainer = field.querySelector(`.${optionsContainerClass}`);
                        let options = optionsContainer.querySelectorAll('.option');
                        let isMultiple = field.classList.contains("multiple");
                        let openedOptionsClass = "opened";
                        let selectedOptionClass = "selected";
                        let defaults = hiddenInputOfSelect.dataset?.default?.split("###")
                        defaults = defaults.length ? defaults : [];
                        if (!isMultiple && defaults.length > 1) defaults = defaults[0]
                        if (!isMultiple && Array.isArray(defaults) && defaults.length) defaults = defaults[0]
                        defaultValues[hiddenInputOfSelect.id] = defaults

                        const isEmpty = function () {
                            return hiddenInputOfSelect.value === "" || hiddenInputOfSelect.value === "[]";
                        }

                        const hideFieldOptions = (fieldToHide) => {
                            if (!fieldToHide.classList.contains(filledClass))
                                fieldToHide.classList.remove(focusedClass)

                            fieldToHide.classList.remove(openedOptionsClass)
                        }

                        focusTarget.addEventListener('click', function (e) {
                            if(field.classList.contains(openedOptionsClass)) {
                                hideFieldOptions(field)
                            } else {
                                field.classList.add(focusedClass, openedOptionsClass)
                            }
                        });

                        _thisClass.addClickOutsideListener(optionsContainer, function (e) {
                            if(field.classList.contains(openedOptionsClass)) {
                                console.log("cl2")
                                hideFieldOptions(field)
                            }
                        })

                        hiddenInputOfSelect.addEventListener('change', function () {
                            if (isEmpty()) {
                                field.classList.remove(filledClass)
                            } else {
                                field.classList.add(filledClass)
                            }
                        });

                        if (defaults.length) {
                            field.classList.add(filledClass)
                            if (isMultiple) {
                                _thisClass.setMultipleSelectOptionsHTML(defaults, focusTarget, hiddenInputOfSelect);
                            } else {
                                _thisClass.setSingularSelectOptionHTML(defaults, focusTarget, hiddenInputOfSelect, field, openedOptionsClass);
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
                        if (cb.checked) {
                            if (multiple) {
                                defaults.push(cb.value)
                            } else {
                                defaults = cb.value
                            }
                        }

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

                    defaultValues[fieldID] = defaults
                }


                // File
                else {
                    if (field.classList.contains("is-file-input")) {
                        let input = field.querySelector("input")

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

                        // Add event listener for drop and change events
                        field.addEventListener("drop", handleDrop);
                        input.addEventListener("change", handleFileInputChange);

// Function to handle drop event
                        function handleDrop(e) {
                            e.stopPropagation();
                            e.preventDefault();

                            let dataTransfer = e.dataTransfer || e.originalEvent.dataTransfer;
                            let file = dataTransfer.files[0];

                            processFile(file);
                        }

// Function to handle file input change event
                        function handleFileInputChange(e) {
                            let files = e.target.files;
                            processFile(files[0]);
                        }

// Function to process the file
                        function processFile(file) {
                            let reader = new FileReader();
                            reader.readAsDataURL(file);

                            handleFileUpload(file);
                        }

// Function to check files
                        function handleFileUpload(file) {
                            let {validFiles, invalidFiles, isValid} = _thisClass.validateFiles([file], field);

                            if (isValid) {
                                EventBus.emit("files-uploaded", {files: file, id: input.id});
                                if(!_thisClass.files[input.id]) _thisClass.files[input.id] = []
                                _thisClass.files[input.id]?.push(file)
                                updateFieldStatus(validFiles);
                                return;
                            }

                            let text = "";
                            invalidFiles.forEach(error => {
                                text += "<span>" + error.name + "</span>: " + error.error + "<br>";
                            });
                            infoText.innerHTML = text;
                            _thisClass.notify(text, "error");
                            infoText.classList.add("error");
                            infoText.classList.remove("success");
                            input.value = ""; // Clear the input field on error
                            dropHereText.classList.add("d-none");
                            dragDropText.classList.remove("d-none");
                        }

                        let uploadedFilesCount = 0;

                        function updateFieldStatus(files) {
                            _thisClass.notify("Súbor bol úspešne nahraný.")

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

    validateFormFields(inputNodes) {
        let thisClass = this;
        let fields = {}
        let errors = {}

        inputNodes.forEach(input => {
            let field = input.closest(".form-field-container")
            let fieldName = field.dataset.name
            let isRequired = field.classList.contains("required");
            let isFile = field.classList.contains("is-file-input");

            if (!isFile && isRequired && this.empty(input.value)) {
                errors[input.id] = "Pole " + fieldName + " musíte vyplniť."
            } else if (input.type === "email" && !this.validateEmail(input.value)) {
                errors[input.id] = "Pole " + fieldName + " musí mať správny tvar emailu."
            } else if (isFile) {
                let files = input.files
                if(!files.length) {
                    files = thisClass.files[input.id] || []
                }
                console.log(files, thisClass.files, input.id)
                if (isRequired && !files.length) {
                    errors[input.id] = "Nahrajte prosím súbor do poľa " + fieldName + "."
                } else if (files.length && thisClass.validateFiles(files, field)?.isValid !== true) {
                    errors[input.id] = "Pole " + fieldName + " obsahuje nesprávny súbor."
                } else {
                    fields[input.id] = input.files[0]
                }
            }
        })

        return {fields: fields, errors: errors}
    }

    validateFiles(files, field) {
        let thisClass = this;
        let validFiles = [];
        let invalidFiles = [];
        let isValid = true;

        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            console.log(file);
            let fileCheck = thisClass.checkFile(file, field);
            if (fileCheck === true) {
                validFiles.push(file);
            } else {
                isValid = false;
                invalidFiles.push({
                    name: file.name,
                    error: fileCheck
                })
            }
        }

        if (!validFiles.length) {
            isValid = false;
        }

        return {validFiles, invalidFiles, isValid}
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