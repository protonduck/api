import Vue from 'vue';

export default new Vue({
    methods: {
        edit(item) {
            this.$emit('edit', item);
        },
    },
});
