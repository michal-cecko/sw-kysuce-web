import Commons from "./commons.min.js";
import { createApp, ref, computed } from "../libs/vue/vue.min.js";

class Header extends Commons {
    constructor() {
        super();
        this.init();
    }

    init() {

        let initialScrollPosition = window.scrollY;
        const scrollPosition = ref(initialScrollPosition);
        const isOpened = ref(false);

        const handleScroll = () => {
            scrollPosition.value = window.scrollY;
        };

        const hasStickyHeader = computed(() => {
            return scrollPosition.value > 50;
        });

        window.addEventListener("scroll", () => {
            handleScroll();
        });

        createApp({
            setup() {
                return {
                    isOpened,
                    scrollPosition,
                    hasStickyHeader
                };
            },
            created() {
                console.log("Header Vue component has been created.");
            }
        }).mount("#header");
    }
}

new Header();