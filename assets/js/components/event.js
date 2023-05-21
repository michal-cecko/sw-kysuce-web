import Commons from "./commons.js"

class Event extends Commons {

    constructor() {
        super();
        this.init();
    }

    init() {
        let _thisClass = this
        let _Vue = Vue

        new _Vue({
            el: '#event',
            data: {
                fields: {},
                recaptchaResponse: "",
                sending: false,
                errors: {},
                eventData: {},
            },
            created() {
                console.log("Event Vue component has been created.")
            },
            mounted() {
                this.eventData = document.querySelector("#eventData")?.dataset
                _thisClass._prepareFormFields()
            },
            methods: {
                async send() {
                    this.errors = {}

                    let _this = this

                    let fields = document.querySelectorAll(".form-field")
                    fields.forEach(field => {
                        let container = field.closest(".form-field-container")
                        let fieldName = container.dataset.name
                        let isRequired = container.classList.contains("required");
                        let isFile = container.classList.contains("is-file-input");

                        if(!isFile && isRequired && _thisClass.empty(field.value)) {
                            this.errors[field.id] = "Pole " + fieldName + " musíte vyplniť."
                        } else if(field.type === "email" && !_thisClass.validateEmail(field.value)) {
                            this.errors[field.id] = "Pole " + fieldName + " musí mať správny tvar emailu."
                        } else if(isFile) {
                            if(field.files.length) {
                                this.fields[field.id] = field.files[0]
                            } else if(isRequired) {
                                this.errors[field.id] = "Nahrajte prosím súbor do poľa " + fieldName + "."
                            }
                        }
                    })

                    await _thisClass.checkCaptcha().then(function (token) {
                        _this.recaptchaResponse = token
                        console.log(_this.recaptchaResponse)
                    }).catch(function (error) {
                        _this.errors.recaptcha = error
                        console.log(error)
                    });

                    if(!_thisClass.empty(this.errors)) {
                        _thisClass.outputErrors(this.errors)
                        return false;
                    }

                    this.sending = true;

                    let data = _thisClass.objectToFormData({
                        fields: this.fields,
                        action: "submit_register_form",
                        form_id: this.eventData.form_id,
                        recaptcha: this.recaptchaResponse,
                        id: this.eventData.id,
                    })

                    try {
                        let response = await _thisClass.WPPostAjax(data);
                        response = await response.json();

                        if (!response.success) {
                            this.errors.general = response.data
                            _thisClass.outputErrors(this.errors)
                            return false;
                        }

                        this.fields = {}
                        _thisClass.resetForm(document.querySelector(".form-container"))
                        _thisClass.notify(response.data, "success")

                    } catch (error) {
                        _thisClass.notify("Nastala neočakávaná chyba, skúste neskôr.", "error")
                        console.error(error);
                        return null;
                    } finally {
                        this.sending = false;
                    }
                }
            },
        });
    }
}

new Event()

export {}
