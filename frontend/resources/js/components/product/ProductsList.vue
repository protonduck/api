<template>
    <div>
        <ProductItem v-for="(product, i) in products" :key="product.id"
             :product="product" :index="(i+1)"></ProductItem>
    </div>
</template>
<script>
    import ProductItem from './ProductItem';
    import ProductService from "../../ProductService";

    export default {
        data() {
            return {
                products: []
            }
        },
        components: {
            ProductItem
        },
        created() {
            this.products = ProductService.products
        }
    }
</script>
