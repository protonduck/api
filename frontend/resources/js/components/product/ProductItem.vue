<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{index}} {{product.title}}</h4>
            <p class="card-text">{{product.qt}} items for ${{product.price}}</p>
            <a href="#" class="btn btn-primary" @click="viewDetailsClicked">View Details</a>
        </div>
    </div>
</template>

<script>
    import ProductService from "../../ProductService";

    export default {
        props: {
            product: {
                type: Object,
                required: true,
            },
            index: {
                type: Number,
            },
        },
        data(){
            return{}
        },
        methods: {
            viewDetailsClicked(){
                ProductService.viewDetails(this.product.id);
            }
        }
    }
</script>

<style scoped>
    div.card {
        width: 500px;
        border: 1px solid red;
        border-radius: 10px;
        margin: 10px;
        padding: 10px;
    }
</style>
