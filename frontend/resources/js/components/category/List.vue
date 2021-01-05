<template>
  <div class="row">
    <div v-for="category in categories" class="col-md-3">
      <div class="card mb-3" :style="{borderColor: '#' + category.color}">
        <div
            class="card-header d-flex justify-content-between align-items-center"
            :title="category.description"
            :style="{backgroundColor: '#' + category.color, opacity: 0.5}"
        >
          <i :class="category.icon" v-show="category.icon"></i>
          {{ category.name }}
          <a href="#" @click.prevent="edit(category.id)" class="btn btn-outline-light btn-sm">
            <i class="fa fa-edit"></i>
          </a>
        </div>
        <link-list :items="category"></link-list>
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
  components: {
    CategoryForm,
    LinkList,
    Modal
  },
  computed: {
    categories: {
      get() {
        return this.$store.getters.categories;
      },
      set(value) {
        this.$store.commit('update_categories', value);
      }
    }
  },
  methods: {
    edit(selectedId) {
      let selectedCategory = _.find(this.categories, {'id': selectedId});
      this.$store.commit('toggle_category_modal', true);
      this.$nextTick(() => {
        CategoryService.edit(selectedCategory);
      });
    },
  },
  created() {
    CategoryService.$on('categoriesChanged', () => {
      this.categories = CategoryService.categories;
    });
  }
}
</script>

<style scoped>

</style>
