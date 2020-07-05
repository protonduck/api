<template>
    <div class="row">
        <div v-for="item in items" class="col-md-4">
            <div class="card bg-light">
                <div class="card-header">{{ item.name }} <a href="#" @click.prevent="edit(item.id)">Edit</a></div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" v-for="link in item.links">
                        <a :href="link.url">{{ link.title }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <category-form></category-form>
    </div>
</template>

<script>
    import CategoryForm from "./Form";
    import _ from 'lodash';
    import CategoryService from "../../services/CategoryService";

    export default {
        name: "CategoriesList",
        components: {CategoryForm},
        props: {
            items: {
                required: false,
                type: Array,
            },
        },
        methods: {
            edit(selectedId) {
                let selectedItem = _.find(this.items, {'id': selectedId});
                CategoryService.edit(selectedItem);
            },
        },
        created() {
            CategoryService.$on('categoriesChanged', () => {
                this.items = CategoryService.categories;
            });
        }
    }
</script>

<style scoped>

</style>
