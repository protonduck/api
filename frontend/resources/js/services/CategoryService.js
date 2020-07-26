import Vue from 'vue';
import axios from "axios";
import _ from "lodash";
import BoardService from "./BoardService";

export default new Vue({
    methods: {
        edit(item) {
            this.$emit('edit', item);
        },
    },
});
