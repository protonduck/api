import Vue from 'vue';
import _ from "lodash";

export default new Vue({
    data: {
        products: [
            {
                id: 1,
                title: 'Product #1',
                price: 50,
                qt: 1000
            },
            {
                id: 2,
                title: 'Product #2',
                price: 100,
                qt: 456
            },
            {
                id: 3,
                title: 'Product #3',
                price: 23,
                qt: 2345
            },
            {
                id: 4,
                title: 'Product #4',
                price: 23,
                qt: 2345
            }
        ]
    },
    methods:{
        viewDetails(id){
            let productToView = _.find(this.products, {id:id});
            this.$emit("viewDetails", productToView);
        }
    }
});