export default Vue.component('PaginationNumber', {
    template: `
      <div class="number" :class="{ active: isActive }" @click="changePage(number)">{{ number }}</div>
    `,
    props: {
        number: {
            type: Number,
            required: true
        },
        isActive: {
            type: Boolean,
            required: true
        }
    },
    methods: {
        changePage(number) {
            this.$emit('page-change', number);
        }
    }
})