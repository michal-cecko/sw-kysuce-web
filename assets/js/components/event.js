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
                this.eventData = document.querySelector("#eventIntro")?.dataset
            },
            mounted() {
            },
            methods: {
                async send() {
                    let _this = this

                    await _thisClass.checkCaptcha().then(function (token) {
                        _this.recaptchaResponse = token
                    }).catch(function (error) {
                        _this.errors.recaptcha = error
                    });

                    this.sending = true;

                    try {
                        await fetch(_thisClass.ajaxURL, {
                            method: 'POST',
                            credentials: 'same-origin',
                            body: data,
                        })
                            .then(response => {
                                return response.json()
                            })
                            .then(data => {
                                console.log(data)
                            }).catch(error => {
                                console.error('There was an error fetching the image:', error);
                                _thisClass.notify("Nastala neočakávaná chyba, skúste neskôr.", "error")
                            })
                    } catch (error) {
                        console.error(error)
                        _thisClass.notify("Nastala neočakávaná chyba, skúste neskôr.", "error")
                    } finally {
                        this.sending = false
                    }
                }
            },
        });
    }
}

new Event()

export {}
