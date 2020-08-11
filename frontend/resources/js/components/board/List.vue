<template>
    <div>
        <div class="boards mb-3">
            <nav class="nav nav-pills">
                <a class="nav-item nav-link" href="#"
                   v-bind:class="{active: board.id === activeBoardId}"
                   v-for="(board, i) in boards" @click.prevent="switchBoard(board.id)">{{ board.name }}</a>

                <a href="#" class="nav-item nav-link" @click.prevent="add">
                  <i class="fa fa-plus"></i>
                </a>

                <a href="#" class="nav-item nav-link" @click.prevent="edit(activeBoardId)">
                  <i class="fa fa-edit"></i>
                </a>
            </nav>
        </div>

      <modal v-if="showModal">
        <div slot="content">
          <board-form></board-form>
        </div>
      </modal>

        <categories-list :items="categories"></categories-list>
    </div>
</template>

<script>
    import BoardService from "../../services/BoardService";
    import CategoriesList from "../category/List";
    import Modal from "../Modal";
    import BoardForm from "./Form";
    import Bus from "../../bus";
    import _ from "lodash";

    export default {
        name: "BoardsList",
        data() {
            return {
                boards: [],
                categories: [],
                activeBoardId: null,
                showModal: false,
            }
        },
        components: {
            CategoriesList,
            Modal,
            BoardForm,
        },
        methods: {
            add() {
                this.showModal = true
            },
            edit(selectedId) {

              let selectedItem = _.find(this.boards, {'id': selectedId});

              this.showModal = true;

              this.$nextTick(() => {
                  BoardService.edit(selectedItem);
              });

            },
            switchBoard(id) {
                BoardService.activeBoardId = id;
                BoardService.$emit('boardsChanged');
            }
        },
        created() {

            BoardService.$on('boardsChanged', () => {

                this.boards = BoardService.boards;

                if (BoardService.getActiveBoard() !== undefined) {

                    let activeBoard = BoardService.getActiveBoard();

                    this.categories = activeBoard.categories;

                    document.body.style.backgroundImage = "url('" + activeBoard.image + "')";
                    document.body.className = 'body_bg_image';

                }

                this.activeBoardId = BoardService.activeBoardId;

            });

            Bus.$on('closeModal', () => {
                this.showModal = false
            });

            BoardService.fetchBoards();

        },
    }
</script>
