import Commons from "./commons.js"
import Forms from "./forms.js"
import SkeletonLoader from "./skeleton-loader.js"

class Event extends Commons {

    constructor() {
        super();

        this.forms = new Forms();
        this.skeleton = new SkeletonLoader();

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
                _thisClass.skeleton.loaded()
                this.eventData = document.querySelector("#eventData")?.dataset
                this.fields = _thisClass.forms._prepareFormFields()
            },
            watch: {
                fields:{
                    deep: true,
                    handler() {
                        console.log(this.fields);
                    }
                }
            },
            methods: {
                async send() {
                    this.errors = {}

                    let _this = this

                    let fields = document.querySelectorAll(".form-field")
                    let validator = _thisClass.forms.validateFormFields(fields);
                    this.errors = validator.errors;
                    this.fields = _thisClass.overwriteProps(validator.fields, this.fields);

                    await _thisClass.forms.checkCaptcha().then(function (token) {
                        _this.recaptchaResponse = token
                    }).catch(function (error) {
                        _this.errors.recaptcha = error
                        console.log(error)
                    });

                    if(!_thisClass.empty(this.errors)) {
                        _thisClass.forms.outputErrors(this.errors)
                        return false;
                    }

                    this.sending = true;

                    let data = _thisClass.forms.objectToFormData({
                        fields: this.fields,
                        action: "submit_register_form",
                        form_id: this.eventData.form_id,
                        recaptcha: this.recaptchaResponse,
                        id: this.eventData.id,
                    })

                    try {
                        let response = await _thisClass.forms.WPPostAjax(data);
                        response = await response.json();

                        if (!response.success) {
                            this.errors.general = response.data
                            _thisClass.forms.outputErrors(this.errors)
                            return false;
                        }

                        //TODO UNCOMMENT LATER
                        //this.fields = {}
                        //_thisClass.forms.resetForm(document.querySelector(".form-container"))
                        _thisClass.notify(response.data, "success")

                    } catch (error) {
                        _thisClass.notify("Nastala neočakávaná chyba, skúste neskôr.", "error")
                        console.error(error);
                        return null;
                    } finally {
                        this.sending = false;
                    }
                },
            },
        });
    }
}

new Event()

export {}
