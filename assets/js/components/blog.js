import Commons from "./commons.js"
import Pagination from "./pagination.js"

class Blog extends Commons {
    constructor() {
        super()

        this.init()

        console.log("Blog component initializing...")
    }

    init() {
        let _thisClass = this

        new Vue({
            el: '#blog',
            components: { Pagination },
            data: {
                activeCategory: false,
                postsContent: "",
                loading: false,
                currentPage: 1,
                postsPerPage: 6,
                pagination: {},
                loadMoreVisible: false,
                blogData: {},
            },
            created() {
                console.log("Blog component created.")
            },
            async mounted() {
                this.blogData = document.getElementById("blogdata").dataset

                await this.fetchContent(-1)

                let _this = this;
                this.$nextTick(() => {
                    let el = this.$el.querySelector('.pagination');
                    el.addEventListener('page-change', function (event) {
                        _this.changePage(event.detail.page);
                    });
                });
            },
            methods: {
                async fetchContent(catID) {
                    this.activeCategory = catID
                    let data = await this.loadPosts({
                        posts_per_page: this.postsPerPage,
                        category: this.activeCategory,
                        page: this.currentPage
                    })
                    console.log(data)
                    this.postsContent = data.content
                    this.pagination = data.pagination
                    this.checkLoadMoreVisibility()
                },

                async changePage(page) {
                    if (this.currentPage === page || (!_thisClass.empty(this.pagination.total_pages) && this.pagination.total_pages < page) || page < 1) return;
                    this.currentPage = page;
                    await this.fetchContent(this.activeCategory)
                },

                async loadPosts(moreParams = {}) {
                    this.loading = true;

                    let params = {
                        action: "get_posts",
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

                checkLoadMoreVisibility() {
                    this.loadMoreVisible = this.pagination.total_pages > this.currentPage;
                },

                printNumber(number) {
                    number = parseInt(number)
                    return `<div class="number ${this.currentPage === number ? 'active' : ''}" data-number="${number}">${number}</div>`;
                },

                printNumbers(from, to) {
                    let html = ""
                    for (let i = from; i <= to; i++) {
                        html += this.printNumber(i)
                    }
                    return html
                },

                numbersHTML() {
                    if(_thisClass.empty(this.pagination)) return;

                    let pages = this.pagination.total_pages;
                    let page = this.currentPage;

                    if(pages < 6) {
                        return this.printNumbers(1, pages);
                    } else {
                        if(page < 3) {
                            return this.printNumbers(1, pages);
                        } else if(page > pages - 2) {
                            return this.printNumbers(pages - 4, pages);
                        } else {
                            return this.printNumbers(page - 2, page + 2);
                        }
                    }
                }
            },
        });
    }
}

new Blog()

export {}