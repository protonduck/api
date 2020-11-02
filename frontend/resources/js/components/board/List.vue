<template>
    <div>
        <div class="boards mb-3">
            <nav class="nav nav-pills">
                <a class="nav-item nav-link" href="#"
                   v-bind:class="{active: board.id === $store.getters.activeBoardId}"
                   v-for="(board, i) in boards" @click.prevent="switchBoard(board.id)">{{ board.name }}</a>

                <a href="#" class="nav-item nav-link" @click.prevent="$store.commit('toggle_board_modal', true)">
                  <i class="fa fa-plus"></i>
                </a>

                <a href="#" class="nav-item nav-link" @click.prevent="edit">
                  <i class="fa fa-edit"></i>
                </a>
            </nav>
        </div>

      <modal v-if="$store.getters.showBoardModal">
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
    import _ from "lodash";

    export default {
        name: "BoardsList",
        data() {
            return {
                boards: [],
                categories: [],
            }
        },
        components: {
            CategoriesList,
            Modal,
            BoardForm,
        },
        methods: {
            edit() {

              let selectedItem = _.find(this.boards, {'id': this.$store.getters.activeBoardId});

              this.$store.commit('toggle_board_modal', true);

              this.$nextTick(() => {
                  BoardService.edit(selectedItem);
              });

            },
            switchBoard(id) {
                this.$store.commit('change_active_board_id', id);
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

            });

            BoardService.fetchBoards();

        },
    }
</script>
