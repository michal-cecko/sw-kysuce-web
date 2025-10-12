import Commons from "./commons.min.js";
import { createApp, ref, onMounted } from '../libs/vue/vue.min.js'

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

        console.log("initting...")

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

        const fetchContent = async (year) => {
            //if (activeYear.value === year) return;

            console.log("fetching...")

            activeYear.value = year;
            const data = await loadPosts({
                year: activeYear.value,
            });
            postsContent.value = data.content;
        };

        const app = createApp({
            setup() {
                onMounted(async () => {
                    console.log("Events component created.");
                    years.value = document.getElementById("pastEventsData")?.dataset?.years.split(",");
                    await fetchContent(years.value[0]);
                    refreshFsLightbox();
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