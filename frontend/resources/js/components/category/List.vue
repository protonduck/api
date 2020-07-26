<template>
    <div class="row">
        <div v-for="item in items" class="col-md-4">
            <div class="card bg-light mb-3">
                <div class="card-header">{{ item.name }} <a href="#" @click.prevent="edit(item.id)">Edit</a></div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" v-for="link in item.links">
                        <a :href="link.url">{{ link.title }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white">
                <div class="card-header">
                    <a href="#" @click.prevent="showModal = true">Add category</a>
                </div>
            </div>
        </div>

        <modal v-show="showModal">
            <div slot="content">
                <category-form></category-form>
            </div>
        </modal>

    </div>
</template>

<script>
    import CategoryForm from "./Form";
    import _ from 'lodash';
    import CategoryService from "../../services/CategoryService";
    import Modal from "../Modal";
    import Bus from "../../bus";

    export default {
        name: "CategoriesList",
        data() {
            return {
                showModal: false
            }
        },
        components: {
            CategoryForm,
            Modal
        },
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
                this.showModal = true
            },
        },
        created() {
            CategoryService.$on('categoriesChanged', () => {
                this.items = CategoryService.categories;
            });


            Bus.$on('closeModal', () => {
                this.showModal = false
            });
        }
    }
</script>

<style scoped>

</style>
