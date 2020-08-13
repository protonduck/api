<template>
    <div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center" v-for="item in items.links">
                <a :href="item.url">{{ item.title }}</a>
                <a href="#" @click.prevent="edit(item.id)" class="btn btn-outline-primary btn-sm">Edit</a>
            </li>
            <li class="list-group-item">
              <i class="fa fa-plus pr-1"></i>
              <a href="#" @click.prevent="add">Add link</a>
            </li>
        </ul>

        <modal v-if="showModal">
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
    import Bus from "../../bus";

    export default {
        name: "LinkList",
        data() {
            return {
                showModal: false
            }
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
            add() {
                this.showModal = true;
            },
            edit(selectedId) {

                let selectedItem = _.find(this.items.links, {'id': selectedId});

                this.showModal = true;

                this.$nextTick(() => {
                    LinkService.edit(selectedItem);
                });

            },
        },
        created() {
            Bus.$on('closeModal', () => {
                this.showModal = false
            });
        }
    }
</script>

<style scoped>

</style>
