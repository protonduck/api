<template>
    <div class="board">
        <div class="board_names">
            <board-item
                v-if="showBoardNames"
                v-for="(board, index) in boards"
                :board="board"
                :key="board.id"
                :index="(index + 1)"
                @changeItem="changeItem"
            ></board-item>
            <board-add></board-add>
            <template v-if="boards.length > 0">
                <board-edit :selectedBoard="selectedBoard"></board-edit>
                <div class="board_buttons active" @click.prevent="destroy">
                    <i class="fa fa-trash"></i>
                </div>
            </template>
        </div>

        <div class="board_categories">
            <template v-for="category in categories">
                <category
                    :name="category.name"
                    :bg-color="category.color"
                    :icon="category.icon"
                    :links="category.links"
                ></category>
            </template>
            <div class="category_add">
                +Add category
            </div>
        </div>
    </div>
</template>

<script>

    import category from '../category/Category';
    import boardAdd from '../board/Add';
    import boardEdit from '../board/Edit';
    import boardItem from '../board/Item';
    import axios from 'axios';
    import bus from '../../bus';
    import _ from 'lodash';

    export default {
        name: 'board',
        data() {
            return {
                boards: [],
                selectedBoard: {},
                categories: [],
                showBoardNames: true,
            }
        },
        computed: {},
        props: {},
        components: {
            category,
            boardAdd,
            boardEdit,
            boardItem,
        },
        methods: {
            async loadBoardsData() {

                let boards = await axios({url: 'boards', method: 'GET'});

                this.boards = boards.data;

                if (this.boards[0] !== undefined) {
                    this.changeItem(this.boards[0].id);
                }

            },
            changeItem(id) {
                this.selectedBoard = _.find(this.boards, {id: id});

                this.categories = this.selectedBoard.categories;

                // add bg to body
                document.body.style.backgroundImage = "url('" + this.selectedBoard.image + "')";
                document.body.className = 'body_bg_image';

            },
            async prependBoard(board) {

                this.boards.push(board);

                this.showBoardNames = true;

            },
            editBoard(board) {

                _.assign(_.find(this.boards, {id: board.id}), board);

                this.showBoardNames = true;

            },
            async destroy() {

                if (confirm('Are you sure?')) {
                    await axios({url: 'boards/' + this.selectedBoard.id, method: 'DELETE'});
                    bus.$emit('board:deleted', this.selectedBoard);
                }

            },
            deleteBoard(board) {
                this.boards = this.boards.filter((b) => b.id !== board.id);
            },
        },
        created() {
            this.loadBoardsData();
        },
        mounted() {

            bus.$on('board:stored', this.prependBoard);

            bus.$on('board:edited', this.editBoard);

            bus.$on('board:editing', () => {
                this.showBoardNames = false;
            });

            bus.$on('board:edit-cancelled', () => {
                this.showBoardNames = true
            })

            bus.$on('board:adding', () => {
                this.showBoardNames = false;
            });

            bus.$on('board:add-cancelled', () => {
                this.showBoardNames = true;
            });

            bus.$on('board:deleted', this.deleteBoard);

        }
    }
</script>

<style scoped lang="scss">

    .board_names {
        display: flex;
        flex-wrap: wrap;
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
        color: #fff;
        padding: 10px;
        margin: 20px 10px;
        border-radius: 10px;
    }

    .board_buttons {
        font-weight: bold;
        font-size: larger;
        padding: 3px 20px;
        margin: 3px 5px;
        color: #fff;
    }

    .active,
    .board_buttons {
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
        border-radius: 5px;
        cursor: pointer;
    }

    .board_buttons {
        border-radius: 0;
        padding-left: 7px;
        padding-right: 7px;
    }

    .board_categories {
        display: flex;
        flex-wrap: wrap
    }

    .category_add {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-basis: 450px;
        flex-grow: 1;
        margin: 10px;
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
        color: #fff;
        font-weight: bold;
        font-size: larger;
        padding: 3px 20px;
    }

</style>
