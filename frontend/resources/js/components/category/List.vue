<template>
    <div class="row">
        <div v-for="item in items" class="col-md-4">
            <div class="card bg-light mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">{{ item.name }}
                  <a href="#" @click.prevent="edit(item.id)" class="btn btn-outline-primary btn-sm">Edit</a>
                </div>
                <link-list :items="item"></link-list>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white">
                <div class="card-header">
                    <i class="fa fa-plus pr-1"></i>
                    <a href="#" @click.prevent="showModal = true">
                      Add category
                    </a>
                </div>
            </div>
        </div>

        <modal v-if="showModal">
            <div slot="content">
                <category-form></category-form>
            </div>
        </modal>

    </div>
</template>

<script>
    import CategoryForm from "./Form";
    import LinkList from "../link/List"
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
            LinkList,
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
                this.showModal = true;
                this.$nextTick(() => {
                    CategoryService.edit(selectedItem);
                });
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
