import Commons from "./commons.min.js";
import Forms from "./forms.min.js";
import SkeletonLoader from "./skeleton-loader.min.js";
import EventBus from "./eventbus.min.js"
import { createApp, ref, onMounted, watch, reactive } from '../libs/vue/vue.min.js';

class Event extends Commons {

    constructor() {
        super();

        this.forms = new Forms();
        this.skeleton = new SkeletonLoader();

        this.init();

        this.initCountdown();
    }

    init() {
        let _thisClass = this;

        const fields = ref({});
        const recaptchaResponse = ref("");
        const sending = ref(false);
        const errors = ref({});
        const eventData = ref({});

        const send = async () => {
            errors.value = {};

            let formFields = document.querySelectorAll(".form-field");
            let validator = _thisClass.forms.validateFormFields(formFields);
            errors.value = validator.errors;

            await _thisClass.forms.checkCaptcha().then(function (token) {
                recaptchaResponse.value = token;
            }).catch(function (error) {
                errors.value.recaptcha = error;
                console.error(error);
            });

            if (!_thisClass.empty(errors.value)) {
                _thisClass.forms.outputErrors(errors.value);
                return false;
            }

            sending.value = true;

            let data = _thisClass.forms.objectToFormData({
                fields: fields.value,
                action: "submit_register_form",
                form_id: eventData.value.form_id,
                recaptcha: recaptchaResponse.value,
                id: eventData.value.id,
            });

            try {
                let response = await _thisClass.forms.WPPostAjax(data);
                response = await response.json();

                console.log(response)

                if (!response.success ?? true) {
                    console.log("teeeeest")
                    errors.value.general = response.data;
                    _thisClass.forms.outputErrors(errors.value);
                    return false;
                }

                fields.value = {};
                _thisClass.forms.resetForm(document.querySelector(".form-container"));
                _thisClass.notify(response.data, "success");

            } catch (error) {
                _thisClass.notify("Nastala neočakávaná chyba, skúste neskôr.", "error");
                console.error(error);
                return null;
            } finally {
                sending.value = false;
            }
        };

        EventBus.addEventListener('files-uploaded', (event) => {
            fields.value[event.data.id] = event.data.files
        })

        const updateValue = (prop, value) => {
            fields.value[prop] = value;
        }

        const app = createApp({
            setup() {
                onMounted(() => {
                    console.log("Event Vue component has been mounted.");
                    _thisClass.skeleton.loaded();
                    eventData.value = document.querySelector("#eventData")?.dataset;

                    fields.value = _thisClass.forms._prepareFormFields();
                });

                return {
                    fields,
                    recaptchaResponse,
                    sending,
                    errors,
                    eventData,
                    updateValue,
                    send
                };
            }
        }).mount("#registerForm");
    }

    initCountdown() {
        let countdown = document.querySelector("#flipdown");
        if (!countdown) return;

        let datetimeString = countdown.dataset.start;
        if (!datetimeString) return;

        // Parsing datetime string into JavaScript Date object
        let datetime = new Date(datetimeString);

        // Getting timestamp in seconds
        let timestampInSeconds = Math.floor(datetime.getTime() / 1000);

        // Using the timestampInSeconds in your FlipDown initialization
        const flipdown = new FlipDown(timestampInSeconds, {
            headings: ["Dní", "Hodín", "Minút", "Sekúnd"],
        }).start();
    }

}

new Event();

export {};
