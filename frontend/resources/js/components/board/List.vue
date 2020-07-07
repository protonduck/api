<template>
    <div>
        <div class="boards mb-3">
            <nav class="nav nav-pills">
                <a class="nav-item nav-link" href="#"
                   @click.prevent="switchBoard"
                   v-bind:class="{active: board.id === activeBoardId}"
                   v-for="(board, i) in boards">{{ board.name }}</a>

                <a href="#" class="nav-item nav-link" @click.prevent="addBoard"><i class="fa fa-plus"></i></a>
            </nav>
        </div>

        <categories-list :items="categories"></categories-list>
    </div>
</template>

<script>
    import BoardService from "../../services/BoardService";
    import CategoriesList from "../category/List";
    import Modal from "../Modal";

    export default {
        name: "BoardsList",
        data() {
            return {
                boards: [],
                categories: [],
                activeBoardId: null,
            }
        },
        components: {
            CategoriesList,
            Modal
        },
        methods: {
            addBoard() {
                //boardsChanged
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
                    this.categories = BoardService.getActiveBoard().categories;
                }

                this.activeBoardId = BoardService.activeBoardId;
            });

            BoardService.fetchBoards();

        },
    }
</script>

<style scoped>

</style>
