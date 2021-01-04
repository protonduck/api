import Vue from 'vue';
import _ from "lodash";

export default new Vue({
    methods: {
        fetchBoards() {
            this.$http.get('/boards').then(resp => {
                this.setBoards(resp.data);
            }).catch(err => {
                console.log(err);
            });
        },
        setBoards(boards) {
            if (!this.$store.getters.activeBoardId && boards.length) {
                this.$store.commit('change_active_board_id', boards[0]['id']);
            }

            this.$store.commit('update_boards', boards);

            this.$emit('boardsChanged');
        },
        getActiveBoard() {
            return _.find(this.$store.getters.boards, {id: this.$store.getters.activeBoardId});
        },
        edit(item) {
            this.$emit('edit', item);
        },
    },
});
