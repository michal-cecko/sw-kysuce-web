import Commons from "./commons.js"

class Forms extends Commons {

    constructor() {
        super();

        this._prepareFormFields()

        console.log("Forms component has been initialized.")
    }

    _prepareFormFields() {
        let _thisClass = this;
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
                        console.log(field.classList.contains(textareaClass))
                        if (field.classList.contains(textareaClass)) {
                            // Count the number of line breaks in the textarea content
                            const lineBreaks = (this.value.match(/\n/g) || []).length;

                            // Set the textarea height based on the number of line breaks
                            this.style.height = `${34 * (lineBreaks + 1)}px`;
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

                    this.addClickOutsideListener(field, function () {
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
                                infoText.innerHTML = "Tento typ súboru <span>nieje povolený</span>."
                            } else {
                                infoText.innerHTML = "Maximálna povolená veľkosť súboru je <span>1MB</span>."
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
    }
}

new Forms()

export {}
