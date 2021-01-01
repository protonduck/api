<template>
    <div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center" v-for="item in items.links">
                <a :href="item.url" target="_blank">{{ item.title }}</a>
                <a href="#" @click.prevent="edit(item.id)" class="btn btn-outline-light btn-sm">
                  <i class="fa fa-edit"></i>
                </a>
            </li>
            <li class="list-group-item">
              <i class="fa fa-plus pr-1"></i>
              <a href="#" @click.prevent="add(items.id)">{{ $t('link.add') }}</a>
            </li>
        </ul>

        <modal v-if="$store.getters.showLinkModal">
            <div slot="content">
                <link-form></link-form>
            </div>
        </modal>
    </div>
</template>

<script>
import LinkForm from "./Form";
import _ from 'lodash';
import LinkService from "../../services/LinkService";
import Modal from "../Modal";

export default {
  name: "LinkList",
  data() {
    return {}
  },
  components: {
    LinkForm,
    Modal
  },
  props: {
    items: {
      required: false,
      type: Object,
    },
  },
  methods: {
    add(categoryId) {
      this.$store.commit('toggle_link_modal', true);
      this.$store.commit('change_current_category_id', categoryId);
    },
    edit(selectedId) {

      let selectedItem = _.find(this.items.links, {'id': selectedId});

      this.$store.commit('toggle_link_modal', true);

      this.$nextTick(() => {
        LinkService.edit(selectedItem);
      });

    },
  },
  created() {}
}
</script>

<style scoped>

</style>
