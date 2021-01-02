<template>
    <div class="row">
        <div v-for="item in items" class="col-md-3">
            <div class="card mb-3" :style="{borderColor: '#' + item.color}">
                <div
                    class="card-header d-flex justify-content-between align-items-center"
                    :title="item.description"
                    :style="{backgroundColor: '#' + item.color, opacity: 0.5}"
                >
                  <i :class="item.icon"></i>
                  {{ item.name }}
                  <a href="#" @click.prevent="edit(item.id)" class="btn btn-outline-light btn-sm">
                    <i class="fa fa-edit"></i>
                  </a>
                </div>
                <link-list :items="item"></link-list>
            </div>
        </div>

        <div class="col-md-3" v-if="$store.getters.activeBoardId !== 0">
            <div class="card bg-white">
                <div class="card-header">
                    <i class="fa fa-plus pr-1"></i>
                    <a href="#" @click.prevent="$store.commit('toggle_category_modal', true)">
                      {{ $t('category.add') }}
                    </a>
                </div>
            </div>
        </div>

        <modal v-if="$store.getters.showCategoryModal">
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

    export default {
        name: "CategoriesList",
        data() {
            return {}
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
                this.$store.commit('toggle_category_modal', true);
                this.$nextTick(() => {
                    CategoryService.edit(selectedItem);
                });
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
