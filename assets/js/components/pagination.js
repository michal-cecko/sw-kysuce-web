import PaginationNumber from "./paginationNumber.js";

export default Vue.component('Pagination', {
    template: `
    <div class="pagination d-flex justify-content-center align-items-center gap-2"
         v-if="totalPages > 1">
      <div class="arrow-prev arrow" :class="currentPage > 1 ? '' : 'disabled'" @click="changePage(currentPage - 1)">
        <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1.48712 0.999878L6.07291 5.58566C6.85396 6.36671 6.85396 7.63304 6.07291 8.41409L1.48712 12.9999" stroke="white" stroke-width="1.5"/>
        </svg>
      </div>
      <div class="numbers d-flex justify-content-center align-items-center gap-2">
        <template v-for="(number, index) in numbers">
          <PaginationNumber
            :key="index"
            :number="number.number"
            :is-active="number.isActive"
            @page-change="changePage"
          />
        </template>
      </div>
      <div class="arrow-next arrow" :class="currentPage < totalPages ? '' : 'disabled'" @click="changePage(currentPage + 1)">
        <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1.48712 0.999878L6.07291 5.58566C6.85396 6.36671 6.85396 7.63304 6.07291 8.41409L1.48712 12.9999" stroke="white" stroke-width="1.5"/>
        </svg>
      </div>
    </div>
  `,
    components: { PaginationNumber },
    props: {
        currentPage: {
            type: Number,
            required: true,
        },
        totalPages: {
            type: Number,
            required: true,
        },
        displayPages: {
            type: Number,
            default: 5,
        },
        arrowHTML: {
            type: String,
            default: '',
        },
    },
    methods: {
        printNumbers(from, to) {
            let numbers = [];
            for (let i = from; i <= to; i++) {
                numbers.push({ number: i, isActive: this.currentPage === i });
            }
            return numbers;
        },
        changePage(number) {
            if (number < 1 || number > this.totalPages || number === this.currentPage) {
                return;
            }
            this.$emit('page-changed', number);
        },
    },
    computed: {
        numbers() {
            const total_pages = this.totalPages;
            const current_page = this.currentPage;
            const display_pages = this.displayPages;

            if (total_pages <= display_pages) {
                return this.printNumbers(1, total_pages);
            } else {
                const half_display = Math.floor(display_pages / 2);
                let from, to;
                if (current_page - half_display <= 0) {
                    from = 1;
                    to = display_pages;
                } else if (current_page + half_display >= total_pages) {
                    from = total_pages - display_pages + 1;
                    to = total_pages;
                } else {
                    from = current_page - half_display;
                    to = current_page + half_display;
                }
                return this.printNumbers(from, to);
            }
        },
    },
});