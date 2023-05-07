import Commons from "./commons.js"

class Events extends Commons {
    constructor() {
        super()

        this.init()

        console.log("Events component initializing...")
    }

    init() {
        let _thisClass = this

        new Vue({
            el: '#events',
            components: {},
            data: {
                activeYear: false,
                postsContent: "",
                loading: false,
                years: [],
            },
            created() {
                this.years = document.getElementById("pastEventsData").dataset.years.split(",")
                console.log("Events component created.")
            },
            async mounted() {
                await this.fetchContent(this.years[0])
            },
            methods: {
                async fetchContent(year) {
                    if(this.activeYear === year) return

                    this.activeYear = year
                    let data = await this.loadPosts({
                        year: this.activeYear,
                    })
                    this.postsContent = data.content
                },

                async loadPosts(moreParams = {}) {
                    this.loading = true;

                    let params = {
                        action: "get_events_by_year",
                        nonce: _thisClass.nonce,
                    };

                    if (!_thisClass.empty(moreParams)) {
                        params = {...params, ...moreParams}
                    }

                    let posts = []
                    await fetch(_thisClass.addParamsToUrl(params, _thisClass.ajaxURL))
                        .then(response => response.json())
                        .then(response => {
                            posts = response
                        })

                    this.loading = false;

                    return posts;
                },
            },
        });
    }
}

new Events()

export {}