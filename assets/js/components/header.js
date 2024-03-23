import Commons from "./commons.min.js"
import {createApp, ref} from '../libs/vue/vue.min.js'

class Header extends Commons {

    constructor() {
        super();
        this.init();
    }

    init() {
        const app = createApp({
            data: {
                isOpened: false,
                scrollPosition: 0,
            },
            created() {
                this.scrollPosition = window.scrollY
                console.log(`Header Vue component has been created.`)
            },
            mounted() {
                window.addEventListener('scroll', this.handleScroll)
            },
            methods: {
                handleScroll() {
                    this.scrollPosition = window.scrollY
                }
            },
            computed: {
                hasStickyHeader() {
                    return this.scrollPosition > 50
                }
            },
        }).mount("#header");
    }
}

new Header()

export {}
