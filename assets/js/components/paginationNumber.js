import { defineComponent, ref } from '../libs/vue/vue.min.js'

export default defineComponent({
    name: 'PaginationNumber',
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
    setup(props, { emit }) {
        const changePage = () => {
            emit('page-change', props.number);
        }

        return { changePage };
    },
    template: `
      <div class="number" :class="{ active: isActive }" @click="changePage">{{ number }}</div>
    `
});
