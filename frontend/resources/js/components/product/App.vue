<template>
    <div>
        <products></products>

        <div>
            <span @click="mode=((mode === 'view' ? 'add' : 'view'))">{{ mode }}</span>
            <keep-alive>
                <component :is="componentName"></component>
            </keep-alive>
        </div>
    </div>
</template>

<script>
    import ProductsList from './ProductsList';
    import ProductDetails from './ProductDetails';
    import ProductAdd from './ProductAdd';

    export default {
        data(){
            return {
                mode: 'view'
            }
        },
        computed: {
            componentName() {
                return this.mode === 'view' ? ProductDetails : ProductAdd;
            }
        },
        components: {
            'products': ProductsList,
            ProductDetails,
            ProductAdd
        },
    }
</script>

<style scoped lang="scss">

</style>
