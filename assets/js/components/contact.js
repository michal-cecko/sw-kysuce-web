import Commons from "./commons.min.js";
import Forms from "./forms.min.js";
import { createApp, ref, onMounted } from '../libs/vue/vue.min.js';

class Contact extends Commons {
    constructor() {
        super();

        this.forms = new Forms();
        this.init();

        console.log("Contact component initializing...");
    }

    init() {
        let _thisClass = this;

        const activeForm = ref('contact');
        const contactForm = ref({});
        const playgroundForm = ref({});
        const sending = ref(false);
        const recaptchaResponse = ref('');
        const errors = ref({});

        const toggleActiveForm = (form) => {
            activeForm.value = form === 'contact' ? 'contact' : 'playground';
        };

        const sendForm = async () => {
            sending.value = true;
            errors.value = {};

            await _thisClass.forms.checkCaptcha()
                .then(token => {
                    recaptchaResponse.value = token;
                })
                .catch(error => {
                    errors.value.recaptcha = error;
                    console.log(error);
                });

            const formData = activeForm.value === 'contact' ? contactForm.value : playgroundForm.value;
            const activeFormSelector = activeForm.value === 'contact' ? '#contactForm' : '#playgroundForm';
            const fields = document.querySelectorAll(activeFormSelector + ' .form-field');
            const validator = _thisClass.forms.validateFormFields(fields);
            errors.value = validator.errors;

            if (!_thisClass.empty(errors.value)) {
                _thisClass.forms.outputErrors(errors.value);
                sending.value = false;
                return false;
            }

            const data = _thisClass.forms.objectToFormData({
                action: 'submit_contact_form',
                nonce: _thisClass.nonce,
                recaptcha: recaptchaResponse.value,
                type: activeForm.value === 'contact' ? 'contact' : 'playground',
                data: formData,
            });

            try {
                let response = await _thisClass.forms.WPPostAjax(data);
                response = await response.json();

                if (!response.success) {
                    errors.value.general = response.data;
                    _thisClass.forms.outputErrors(errors.value);
                    sending.value = false;
                    return false;
                }

                if (activeForm.value === 'contact') {
                    contactForm.value = {};
                } else {
                    playgroundForm.value = {};
                }

                _thisClass.forms.resetForm(document.querySelector(activeFormSelector));
                _thisClass.notify(response.data, 'success');
            } catch (error) {
                _thisClass.notify('Nastala neočakávaná chyba, skúste neskôr.', 'error');
                console.error(error);
                return null;
            } finally {
                sending.value = false;
            }
        };

        const app = createApp({
            setup() {

                onMounted(() => {
                    console.log('Contact component created.');
                    _thisClass.forms._prepareFormFields();
                });
                
                return {
                    activeForm,
                    contactForm,
                    playgroundForm,
                    sending,
                    recaptchaResponse,
                    errors,
                    toggleActiveForm,
                    sendForm,
                };
            }
        }).mount('#contact');
    }
}

new Contact();

export {};
