import { createApp, ref, onMounted } from '../libs/vue/vue.min.js'
import Pagination from "./pagination.min.js"
import Commons from "./commons.min.js"

class Blog extends Commons {
    constructor() {
        super()
        this.init()
        console.log("Blog component initializing...")
    }

    init() {
        let activeCategory = ref(false)
        let postsContent = ref("")
        let loading = ref(false)
        let currentPage = ref(1)
        let postsPerPage = ref(6)
        let pagination = ref({})
        let loadMoreVisible = ref(false)
        let blogData = ref({})

        blogData.value = document.getElementById("blogdata").dataset

        const fetchContent = async (catID) => {
            activeCategory.value = catID
            loading.value = true

            const data = await loadPosts({
                posts_per_page: postsPerPage.value,
                category: activeCategory.value,
                page: currentPage.value
            })

            postsContent.value = data.content
            pagination.value = data.pagination
            checkLoadMoreVisibility()
            loading.value = false
        }

        const changePage = async (page) => {
            if (currentPage.value === page || (!this.empty(pagination.value.total_pages) && pagination.value.total_pages < page) || page < 1) return
            currentPage.value = page
            await fetchContent(activeCategory.value)
        }

        const loadPosts = async (moreParams = {}) => {
            const params = {
                post_type: blogData.value.post_type,
                action: "get_posts",
                nonce: this.nonce,
                ...moreParams
            }

            let posts = []
            await fetch(this.addParamsToUrl(params, this.ajaxURL))
                .then(response => response.json())
                .then(response => {
                    posts = response
                })

            return posts
        }

        const checkLoadMoreVisibility = () => {
            loadMoreVisible.value = pagination.value.total_pages > currentPage.value
        }

        const printNumber = (number) => {
            number = parseInt(number)
            return `<div class="number ${currentPage.value === number ? 'active' : ''}" data-number="${number}">${number}</div>`
        }

        const printNumbers = (from, to) => {
            let html = ""
            for (let i = from; i <= to; i++) {
                html += printNumber(i)
            }
            return html
        }

        const numbersHTML = () => {
            if (this.empty(pagination.value)) return

            let pages = pagination.value.total_pages
            let page = currentPage.value

            if (pages < 6) {
                return printNumbers(1, pages)
            } else {
                if (page < 3) {
                    return printNumbers(1, pages)
                } else if (page > pages - 2) {
                    return printNumbers(pages - 4, pages)
                } else {
                    return printNumbers(page - 2, page + 2)
                }
            }
        }

        createApp({
            el: '#blog',
            components: { Pagination },
            setup() {

                onMounted(() => {
                    let el = document.querySelector('.pagination');
                    el?.addEventListener('page-change', async (event) => {
                        await changePage(event.detail.page);

                        refreshFsLightbox();
                    });
                })

                fetchContent(-1)

                return {
                    activeCategory,
                    postsContent,
                    loading,
                    currentPage,
                    postsPerPage,
                    pagination,
                    loadMoreVisible,
                    blogData,
                    fetchContent,
                    changePage,
                    loadPosts,
                    checkLoadMoreVisibility,
                    printNumber,
                    printNumbers,
                    numbersHTML
                }
            }
        }).mount("#blog")
    }
}

new Blog()

export {}