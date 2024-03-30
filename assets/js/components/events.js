import { ref, onMounted } from 'vue';
import Commons from "./commons.min.js";
import { createApp } from '../libs/vue/vue.min.js';

class Events extends Commons {
    constructor() {
        super();

        this.init();

        console.log("Events component initializing...");
    }

    init() {
        const activeYear = ref(false);
        const postsContent = ref("");
        const loading = ref(false);
        const years = ref([]);

        const loadPosts = async (moreParams = {}) => {
            loading.value = true;

            let params = {
                action: "get_events_by_year",
                nonce: this.nonce,
            };

            if (!this.empty(moreParams)) {
                params = {...params, ...moreParams};
            }

            let posts = [];
            await fetch(this.addParamsToUrl(params, this.ajaxURL))
                .then(response => response.json())
                .then(response => {
                    posts = response;
                });

            loading.value = false;

            return posts;
        };

        onMounted(async () => {
            years.value = document.getElementById("pastEventsData")?.dataset?.years.split(",");
            await fetchContent(years.value[0]);

            refreshFsLightbox();
        });

        const fetchContent = async (year) => {
            if (activeYear.value === year) return;

            activeYear.value = year;
            const data = await loadPosts({
                year: activeYear.value,
            });
            postsContent.value = data.content;
        };

        const app = createApp({
            setup() {
                onMounted(() => {
                    console.log("Events component created.");
                });

                return {
                    activeYear,
                    postsContent,
                    loading,
                    years,
                    fetchContent
                };
            },
        }).mount("#events");
    }
}

new Events();

export {};
