import Commons from "./commons.js"
import Forms from "./forms.js"

class Contact extends Commons {
    constructor() {
        super()

        this.forms = new Forms();
        this.init()

        console.log("Contact component initializing...")
    }

    init() {
        let _thisClass = this

        new Vue({
            el: '#contact',
            components: {},
            data: {
                activeForm: 'contact',
                contactForm: {},
                playgroundForm: {},
                sending: false,
                recaptchaResponse: "",
                errors: {},
            },
            created() {
                console.log("Contact component created.")
            },
            mounted() {
                _thisClass.forms._prepareFormFields()
            },
            methods: {
                toggleActiveForm(form) {
                    this.activeForm = form === 'contact' ? 'contact' : 'playground';
                },
                async sendForm() {
                    let _this = this;
                    this.sending = true;

                    await _thisClass.forms.checkCaptcha().then(function (token) {
                        _this.recaptchaResponse = token
                    }).catch(function (error) {
                        _this.errors.recaptcha = error
                        console.log(error)
                    });

                    let formData = this.activeForm === 'contact' ? this.contactForm : this.playgroundForm;

                    let activeFormSelector = this.activeForm === 'contact' ? "#contactForm" : "#playgroundForm"
                    let fields = document.querySelectorAll(activeFormSelector + " .form-field")
                    let validator = _thisClass.forms.validateFormFields(fields);
                    this.errors = validator.errors;

                    if(!_thisClass.empty(this.errors)) {
                        _thisClass.forms.outputErrors(this.errors)
                        return false;
                    }

                    let data = _thisClass.forms.objectToFormData({
                        action: "submit_contact_form",
                        nonce: _thisClass.nonce,
                        recaptcha: this.recaptchaResponse,
                        type: this.activeForm === 'contact' ? 'contact' : 'playground',
                        data: formData,
                    });

                    try {
                        let response = await _thisClass.forms.WPPostAjax(data);
                        response = await response.json();

                        if (!response.success) {
                            this.errors.general = response.data
                            _thisClass.forms.outputErrors(this.errors)
                            return false;
                        }

                        if(this.activeForm === 'contact') {
                            this.contactForm = {}
                        } else {
                            this.playgroundForm = {}
                        }
                        _thisClass.forms.resetForm(document.querySelector(activeFormSelector))
                        _thisClass.notify(response.data, "success")
                    }
                    catch (error) {
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

new Contact()

export {}