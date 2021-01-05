<template>
  <div>
    <div class="boards mb-3">
      <nav>
        <div class="d-flex">
          <draggable v-model="boards" group="boards" @change="update()" class="nav nav-pills">
            <a class="nav-item nav-link" href="#"
               v-bind:class="{active: board.id === $store.getters.activeBoardId}"
               v-for="(board, i) in boards" @click.prevent="switchBoard(board.id)"
               :key="board.id">
              {{ board.name }}
            </a>
          </draggable>

          <div class="nav nav-pills">
            <a href="#" class="nav-item nav-link" @click.prevent="$store.commit('toggle_board_modal', true)">
              <i class="fa fa-plus"></i>
            </a>

            <a href="#" class="nav-item nav-link" @click.prevent="edit">
              <i class="fa fa-edit"></i>
            </a>
          </div>
        </div>
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
import draggable from 'vuedraggable'

export default {
  name: "BoardsList",
  data() {
    return {
      categories: [],
    }
  },
  components: {
    CategoriesList,
    Modal,
    BoardForm,
    draggable
  },
  computed: {
    boards: {
      get() {
        return this.$store.getters.boards;
      },
      set(value) {
        this.$store.commit('update_boards', value);
      }
    }
  },
  methods: {
    edit() {
      let selectedItem = _.find(this.$store.getters.boards, {'id': this.$store.getters.activeBoardId});

      this.$store.commit('toggle_board_modal', true);

      this.$nextTick(() => {
        BoardService.edit(selectedItem);
      });
    },
    switchBoard(id) {
      this.$store.commit('change_active_board_id', id);

      localStorage.setItem('active_board_id', id);

      BoardService.$emit('boardsChanged');
    },
    update() {
      this.boards.map((board, index) => {
        this.$store.dispatch('board_save', {
          url: '/boards/' + board.id,
          method: 'put',
          sort: board.sort = index + 1,
        });
      });
    },
  },
  created() {
    BoardService.$on('boardsChanged', () => {
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
